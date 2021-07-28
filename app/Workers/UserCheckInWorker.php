<?php 
namespace app\Workers;

// use DomainObjectAssembler\Collections\Collection;
use app\Requests\Request;
use DomainObjectAssembler\DomainModel\NullModel;
use DomainObjectAssembler\DomainModel\UserModel;
use DomainObjectAssembler\DomainObjectAssembler;

class UserCheckInWorker
{
    public function addNewUser(Request $request): string
    {
        // define('CHECKIN','true');
        $name   = $request->getProperty('name');
        $pass_1 = $request->getProperty('pass_1');
        $pass_2 = $request->getProperty('pass_2');
        $mail   = $request->getProperty('mail');

        if (! isset($name, $pass_1, $pass_2, $mail) ) {
            return 'UserCheckInWorker(22): Набор данных не полон: name ='. $name. ' pass_1 = '. $pass_1. ' pass_2 = '. $pass_2. ' mail = '. $mail;
        }

        if ($pass_1 !== $pass_2) {
            return 'UserCheckInWorker(25): Пароли не совпадают!';
        }

        //Смотрим есть ли в базе такой логин или почта
        // $h_name = hash("sha512", $_POST["name"]);
        // $h_mail = hash("sha512", $_POST["mail"]);

        //Для тестов:
        // $h_name = 'testUser1';
        // $h_mail = 'test2@mail.test';
        $h_name = 'testUser11';
        $h_mail = 'test2@mail.test1';

        //Делаем выборку из БД, чтобы выячнить есть ли уже такой пользователь
        $assembler   = new DomainObjectAssembler('User');
        $identityObj = $assembler->getIdentityObject()
                                                ->field('name')
                                                ->eq($h_name)
                                                ->or()
                                                ->field('mail')
                                                ->eq($h_mail)
                                                ;
        $collection = $assembler->find($identityObj);

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
        
        //Если сюда дошло исполнение, значит данные годятся для записи
        //Стандарное исполнение не подходит, сначала надо записать в 

        //Делаем 2 записи. Одна в temp, вторая в user
        $tempAssembler = new DomainObjectAssembler('Temp');
        $assembler->createNewModel();
        $tempAssembler->createNewModel();
        // int $id, int $user_id, string $key_act, string $mail

        //Солим пароль, хешируем. Логин и почта без соли
        $length_solt = rand(0,100);
        $solt        = random_bytes($length_solt);
        $hashed_name = hash("sha512", $name);
        $hashed_pass = hash("sha512", $password.$solt);
        $hashed_mail = hash("sha512", $mail);

                //Генерируем ключ для активации
                $length_key = rand(0,100);
                $key        = random_bytes($length_key);
                $hashed_key = hash("sha512", $key);
                
                //Отправляем письмо
                // $title = "Активация учётной записи на keyburner.com";
                // $message_html = "Активируйте учётную запись, пройдя по ссылке: <a href='http://94.244.191.245/keyburner/activation.php?key=$hashed_key'>Активировать</a><br>Если вы не создавали учётную запись, проигнорируйте это письмо.";
                // $message_nohtml = "Активируйте учётную запись пройдя по ссылке: http://94.244.191.245/keyburner/activation.php?key=$hashed_key   Если вы не создавали учётную запись, проигнорируйте это письмо.";
                // $and_mail = send_mail($mail, $title, $message_html, $message_nohtml);

        // int $id, string $name, string $password, string $solt, string $mail  
    }
}
