<?php
/**
 * Класс для отображения страницы активации
 */
namespace resources\views;

use app\Response\Response;

class ActivationView extends View
{
    public function print(Response $response)
    {
        $err = $this->debug($response);
        $msg = $response->getFeedbackString('<br>');
        $msg = $err. $msg;
        
        require('templates/activation.php');
    }
}
