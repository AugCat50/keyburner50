<?php

namespace app\Workers\User;

use app\Commands\Command;
use app\Requests\Request;
use app\Workers\Worker;

/**
 * Можно расшить Worker, если потребуется проверка ещё где-то, в формате обсервера
 */

class UserExists
{
    /**
     * Для получения доступа к данным из методов
     */
    private $name         = null;
    private $pass_1       = null;
    private $mail         = null;

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

        //Не обязательная начальная проверка. Если доверять фронтэнду, можно убрать сравнение паролей и оставить один пароль
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
        $identityObj = $this->userAssembler->getIdentityObject()
                                                ->field('name')
                                                ->eq($h_name)
                                                ->or()
                                                ->field('mail')
                                                ->eq($h_mail)
                                                ;
        $collection = $this->userAssembler->find($identityObj);
        
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
                return $msg;
            }
        }

        return false;
    }
}
