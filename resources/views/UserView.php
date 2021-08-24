<?php
/**
 * Класс для отображения страницы '/user'
 */
namespace resources\views;

use app\Response\Response;

class UserView extends View
{
    public function print(Response $response)
    {
        $err = $this->debug($response);

        //Выводим html страницы user
        require('templates/user.php');
    }
}
