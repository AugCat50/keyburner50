<?php 
namespace resources\views;

use app\Registry\Registry;
use app\Response\Response;

abstract class View 
{
    abstract function print(Response $response);

    /**
     * Показывать сообщения об ошибках, если константа окружения debug = true и имеется сообщение об ощибке в Response
     */
    protected function debug(Response $response){
        $reg = Registry::getInstance();
        $env = $reg->getEnviroment()->get('debug');
        $err = $response->getError();

        if($err && $env){
            echo "<div class='error' style='border: 1px solid red'>$err</div>";
        }
    } 
}
