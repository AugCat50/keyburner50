<?php 
/**
 * Комманда для авторизации пользователя
 * 
 * Не мешало бы добавить автовход при успешной активации аккаунта
 */
namespace app\Commands\Http;

use app\Requests\Request;
use DomainObjectAssembler\DomainModel\NullModel;
use DomainObjectAssembler\DomainModel\UserModel;
use DomainObjectAssembler\DomainObjectAssembler;

class LogInHttpCommand extends HttpCommand
{
    /**
     * GET id
     * Метод для проверки авторизации пользвателя
     * 
     * При неудаче заполняем feedback response. Если фидбек пуст, значит акторизация удачна, js фронт редиректит на роут /user
     * Сохраняем сообщение не в response->error потому, что отображение ошибок может быть отключено в env.ini
     * 
     * @param  app\Requests\Request $request
     * @return app\Response\Response
     */
    protected function show(Request $request)
    {
        session_start();

        $name     = $request->getProperty('name');
        $mail     = $request->getProperty('mail');
        $password = $request->getProperty('password');

        //Если нет ни логина ни почты и нет пароля - редирект на главную.
        //Проверкой занимается фронтэнд, это на всякий случай.
        if(! (($name || $mail) && $password) ){
            $location = 'Location: http://' . $_SERVER['SERVER_ADDR'] . '/keyburner50/index.php';
            header ($location);
            exit;
        }

        $assembler   = new DomainObjectAssembler('User');
        $identityObj = $assembler->getIdentityObject();

        if($name && $password){

            //Если пользователь ввёл логин, запрашиваем из БД name
            $name_hash = hash("sha512", $name);
            $identityObj->field('name')->eq($name_hash);

        } else if($mail && $password){

            //Если пользователь ввёл mail, запрашиваем из БД mail
            $mail_hash = hash("sha512", $mail);
            $identityObj->field('mail')->eq($mail_hash);

        }
        
        $userModel = $assembler->findOne($identityObj);

        if($userModel instanceof NullModel){

            $this->response->addFeedback('Пользователь с таким логином или почтой не найден');

        } else if($userModel instanceof UserModel){
            $samplePass = $userModel->getPassword();
            $solt       = $userModel->getSolt();
            $pass_hesh  = hash("sha512", $password. $solt);

            if($samplePass === $pass_hesh){
                $_SESSION['auth_subsystem']['user_id']   = $userModel->getId();
                //Или имя или почта сохранится в user_name. Намудрил с хешировнаием логина, взять логин из модели не выйдет.
                $_SESSION['auth_subsystem']['user_name'] = $name.$mail;
            } else {
                $this->response->addFeedback('Пароль не совпал!');
            }
        }

        return $this->response;
    }

    /**
     * DELETE
     * Удаление сессии
     *
     * @param app\Requests\Request $request
     * @return void
     */
    protected function destroy(Request $request)
    {
        session_start();
        //Затереть массив сессии
        $_SESSION = [];

        //Удалить идентификатор сессии из куки
        unset($_COOKIE[session_name()]);

        session_destroy();

        //редирект в js, в обработчике нажатия кнопки
        // header("Location: index.php");
        exit;
    }
}
