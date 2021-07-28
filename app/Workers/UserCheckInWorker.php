<?php 
namespace app\Workers;

// use DomainObjectAssembler\Collections\Collection;
use app\Requests\Request;
use DomainObjectAssembler\DomainModel\NullModel;
use DomainObjectAssembler\DomainModel\UserModel;
use DomainObjectAssembler\DomainObjectAssembler;

class UserCheckInWorker
{
    public function addNewUser(Request $request)
    {
        define('CHECKIN','true');    

        $name   = $request->getProperty('name');
        $pass_1 = $request->getProperty('pass_1');
        $pass_2 = $request->getProperty('pass_2');
        $mail   = $request->getProperty('mail');

        if ( isset($name, $pass_1, $pass_2, $mail) ) {

            //Смотрим есть ли в базе такой логин или почта
            // $h_name = hash("sha512", $_POST["name"]);
            // $h_mail = hash("sha512", $_POST["mail"]);

            //Для тестов:
            $h_name = $name;
            $h_mail = $mail;

            //Можно, разумеется, проверить всё одним запросом. Но ценой дополнительного запроса
            $assembler    = new DomainObjectAssembler('User');
            // $identityObjN = $assembler->getIdentityObject()->field('name')->eq($h_name);
            // $identityObjM = $assembler->getIdentityObject()->field('mail')->eq($h_mail);

            // $modelN = $assembler->findOne($identityObjN);
            // $modelM = $assembler->findOne($identityObjM);

            $identityObj = $assembler->getIdentityObject()->field('name')->eq($h_name)->and()->field('mail')->eq($h_mail);
            $model       = $assembler->findOne($identityObj);
            d($identityObj, 1);

            if ($model instanceof NullModel) {
                
            } else if ($model instanceof UserModel) {
                return '';
            } else {
                throw new \Exception('UserCheckInWorker(44): Неизвестная ошибка. Вместо объекта NullModel или UserModel получен'. get_class($model));
            }

            d($result,1);
            
            $is_name_user = get_user($pdo, $h_name);
            $is_mail_user = get_user($pdo, false, $h_mail);
            
            //Проверки
            if(is_array($is_mail_user)){
                $data = "На этот почтовый адрес уже зарегистрирован аккаунт!";
            }else if(is_array($is_name_user)){
                $data = "Login уже занят!";
            }else if($_POST["pass_1"] != $_POST["pass_2"]){
                $data = "Пароли не совпадают!";
            }else{
                $data = check_in_user($pdo, $_POST["name"], $_POST["pass_1"], $_POST["mail"]);
            }
            
        }
    }
}
