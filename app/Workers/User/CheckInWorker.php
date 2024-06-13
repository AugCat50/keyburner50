<?php
/**
 * Класс выполняющий регистрацию и отправку письма активации пользователю
 */
namespace app\Workers\User;

use app\Commands\Command;
use app\Requests\Request;
use DomainObjectAssembler\DomainObjectAssembler;
use DomainObjectAssembler\IdentityMap\ObjectWatcher;

class UserCheckInWorker
{
    private $objectWather = null;

    private $userAssembler;

    /**
     * Получть объект ObjectWatcher для вызова выполнения записи в БД
     */
    public function __construct()
    {
        $this->objectWather  = ObjectWatcher::getInstance();
        $this->userAssembler = new DomainObjectAssembler('User');
    }

    /**
     * Входня точка
     */
    public function doUpdate(Command $command): void
    {
        $request = $command->getRequest();
        $this->addNewUser($request);
    }
    
    /**
     * Управляющий метод
     * 
     * @return string
     */
    public function addNewUser(Request $request): string
    {
        $userCheker = new UserExistsWorker();
        // define('CHECKIN','true');
        $status = $userCheker->verification($request);

        //если не false, значит есть сообщение об ошибке
        if($status){
            return $status;
        }

        $id_mail    = $this->insertUser();
        $hashed_key = $this->getActivationKey();

        //Сначала пытаемся отправить письмо, чтобы в случае неудачи не удалять ключ активации из базы
        $result     = $this->sendMail($this->mail, $id_mail['id'], $hashed_key);

        //Если false, значит письмо не отправилось, ключ активации не сохраняем
        if($result[1]){
            $this->insertKeyAct($id_mail, $hashed_key);
        }
        
        return 'Пользователь зарегистрирован! <br>'. $result[0];
    }



    /**
     * Записать пользователя в БД в users
     * 
     * @return array
     */
    private function insertUser(): array
    {
        //Солим пароль, хешируем. Логин и почта без соли
        $length_solt = rand(1,100);
        $solt        = random_bytes($length_solt);
        $hashed_name = hash("sha512", $this->name);
        $hashed_pass = hash("sha512", $this->pass_1 . $solt);
        $hashed_mail = hash("sha512", $this->mail);
        
        $userData = [
            'id'       => -1,
            'name'     => $hashed_name,
            'password' => $hashed_pass,
            'solt'     => $solt,
            'mail'     => $hashed_mail
        ];
        
        //При создании модели, она автоматически попадает в очередь на запись
        $this->userAssembler->createNewModel($userData);
        
        //Выполнить очередь, сначала записать user, чтобы получит его id
        $this->objectWather->performOperations();
        
        //Узнать id только что записанного пользователя
        $newUserId = $this->userAssembler->getLastInsertId();

        return ['id' => $newUserId, 'mail' => $hashed_mail];
    }

    /**
     * Пользователь уже создан, генерируем и записываем для него ключ активации в temps
     * 
     * @param array $id_mail
     * 
     * @return string
     */
    private function insertKeyAct(array $id_mail, string $hashed_key)
    {
        $tempAssembler = new DomainObjectAssembler('Temp');
        
        $tempData = [
            'id'      => -1,
            'user_id' => $id_mail['id'],
            'key_act' => $hashed_key,
            'mail'    => $id_mail['mail']
        ];
        
        $tempAssembler->createNewModel($tempData);
        $this->objectWather->performOperations();
    }

    /**
     * Генерируем ключ для активации
     * 
     * @return string
     */
    private function getActivationKey(): string
    {
        $length_key = rand(1,100);
        $key        = random_bytes($length_key);
        $hashed_key = hash("sha512", $key);

        return $hashed_key;
    }

    /**
     * Отправляем пользователю письмо на почту
     * 
     * @param string $to
     * 
     * @return string
     */
    private function sendMail(string $to, int $id, string $hashed_key)
    {
        $title          = "Активация учётной записи на keyburner.com";
        $message_html   = "Активируйте учётную запись, пройдя по ссылке: <a href='http://94.244.191.245/keyburner50/index.php/activation?id=$id&key=$hashed_key'>Активировать</a><br>Если вы не создавали учётную запись, проигнорируйте это письмо.";
        $message_nohtml = "Активируйте учётную запись пройдя по ссылке: http://94.244.191.245/keyburner50/index.php/activation?id=$id&key=$hashed_key   Если вы не создавали учётную запись, проигнорируйте это письмо.";

        $sender = new SendMailWorker();
        $msg    = $sender->send($to, $title, $message_html, $message_nohtml);

        return $msg;
    }
}
