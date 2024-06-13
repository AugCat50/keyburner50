<?php
/**
 * Суперкласс View
 * 
 * Использование классов View:
 * @see FrontController::print()
 */
namespace resources\views;

use app\Registry\Registry;
use app\Response\Response;

abstract class View 
{
    /**
     * Отрисовка или получить html для отрисовки
     */
    abstract public function print(Response $response);

    /**
     * Показывать сообщения об ошибках, если константа окружения debug = true и имеется сообщение об ощибке в Response
     * При закомментированном debug = true в $env = null
     */
    protected function debug(Response $response){
        $reg = Registry::getInstance();
        $env = $reg->getEnviroment()->get('debug');
        $err = $response->getError();

        if($err && $env){
            return "<p class='error pink-neon'>$err</p>";
        }
    } 
}
