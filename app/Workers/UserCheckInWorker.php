<?php 
namespace app\Workers;

use app\Requests\Request;
use DomainObjectAssembler\DomainObjectAssembler;
use DomainObjectAssembler\IdentityMap\ObjectWatcher;

class UserCheckInWorker
{
    /**
     * Для получения доступа к данным из методов
     */
    private $name         = null;
    private $pass_1       = null;
    private $mail         = null;
    private $objectWather = null;

    /**
     * Получть объект ObjectWatcher для вызова выполнения записи в БД
     */
    public function __construct()
    {
        $this->objectWather  = ObjectWatcher::getInstance();
    }

    /**
     * Управляющий метод
     * 
     * @return string
     */
    public function addNewUser(Request $request): string
    {
        // define('CHECKIN','true');
        $this->verification($request);
        $id_mail    = $this->insertUser();
        $hashed_key = $this->insertKeyAct($id_mail);
        $message    = $this->sendMail($this->mail, $hashed_key);

        return 'Пользователь зарегистрирован! <br>'. $message;
    }

    /**
     * Делаем запрос в БД и выясняем не зарегистрирован ли уже пользователь на этот логин и почту
     * 
     * @return string|void
     */
    public function verification(Request $request)
    {
        $this->name   = $name   = $request->getProperty('name');
        $this->pass_1 = $pass_1 = $request->getProperty('pass_1');
                        $pass_2 = $request->getProperty('pass_2');
        $this->mail   = $mail   = $request->getProperty('mail');

        //Не обяхательная начальная проверка. Если доверять фронтэнду, можно убрать сравнение паролей и оставить один пароль
        if (! isset($name, $pass_1, $pass_2, $mail) ) {
            return 'UserCheckInWorker(22): Набор данных не полон: name ='. $name. ' pass_1 = '. $pass_1. ' pass_2 = '. $pass_2. ' mail = '. $mail;
        }

        if ($pass_1 !== $pass_2) {
            return 'UserCheckInWorker(25): Пароли не совпадают!';
        }

        //Смотрим есть ли в базе такой логин или почта
        $h_name = hash("sha512", $name);
        $h_mail = hash("sha512", $mail);

        //Для тестов:
        // $h_name = 'testUser1';
        // $h_mail = 'test2@mail.test';
        // $h_name = 'testUser11';
        // $h_mail = 'test2@mail.test1';

        //Делаем выборку из БД, чтобы выячнить есть ли уже такой пользователь
        $this->userAssembler = $userAssembler = new DomainObjectAssembler('User');

        $identityObj = $userAssembler->getIdentityObject()
                                                ->field('name')
                                                ->eq($h_name)
                                                ->or()
                                                ->field('mail')
                                                ->eq($h_mail)
                                                ;
        $collection = $userAssembler->find($identityObj);

        //Если коллекция не пустая, проверяем на какие из введённых данных уже существуют пользователи
        //Или так или два запроса. Решил сделать один запрос и проверку тут
        if ( $collection->getTotal()) {
            $msg = '';

            foreach ($collection as $model) {
                $exName = $model->getName();
                $exMail = $model->getMail();

                if($h_name === $exName) {
                    $msg .= 'Login уже занят!<br>';
                }

                if($h_mail === $exMail) {
                    $msg .= 'На этот mail уже зарегистрирован пользователь!<br>';
                }
            }
            return $msg;
        }
    }

    /**
     * Записать пользователя в БД в users
     * 
     * @return array
     */
    private function insertUser()
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
    private function insertKeyAct(array $id_mail){
        //Генерируем ключ для активации
        $length_key = rand(1,100);
        $key        = random_bytes($length_key);
        $hashed_key = hash("sha512", $key);

        $tempAssembler = new DomainObjectAssembler('Temp');
        
        $tempData = [
            'id'      => -1,
            'user_id' => $id_mail['id'],
            'key_act' => $hashed_key,
            'mail'    => $id_mail['mail']
        ];
        
        $tempAssembler->createNewModel($tempData);
        $this->objectWather->performOperations();
        
        return $hashed_key;
    }

    /**
     * Отправляем пользователю письмо на почту
     * 
     * @param string $to
     * 
     * @return string
     */
    private function sendMail(string $to, string $hashed_key): string
    {
        $title          = "Активация учётной записи на keyburner.com";
        $message_html   = "Активируйте учётную запись, пройдя по ссылке: <a href='http://94.244.191.245/keyburner50/activation.php?key=$hashed_key'>Активировать</a><br>Если вы не создавали учётную запись, проигнорируйте это письмо.";
        $message_nohtml = "Активируйте учётную запись пройдя по ссылке: http://94.244.191.245/keyburner50/activation.php?key=$hashed_key   Если вы не создавали учётную запись, проигнорируйте это письмо.";

        $sender = new SendMailWorker();
        $msg    = $sender->send($to, $title, $message_html, $message_nohtml);

        return $msg;
    }
}
