<?php

/**
 * Контроллер представлений.
 * Часть шаблона ApplicationController, отвечающая за выбор представления.
 * 
 * Можно расширить, получая соответсвие команда-вью из файла конфигурации, для реализации более сложной логики.
 */

namespace app\AppController;

use app\Response\Response;
use resources\views\StringPrintView;
use resources\views\View;

class ViewController
{
    /**
     * Делегирует получение View другому методу этого же класса
     * Запускает рендер полученного View, передавая ему $response
     * 
     * @var app\Response\Response $response
     * 
     * @return void
     */
    public function render(Response $response): void
    {
        if($response instanceof Response){
            $view = $this->getView($response);
            $view->print($response);
        } else{
            //Если возвращён не app\Response\Response
            //Вообще, такого не должно случаться.
            echo 'ViewController(31): вместо Response получено это: ' . $response;
        }
    }

    /** 
     * Логика получения нужного объекта View
     * 
     * @var app\Response\Response Response
     * 
     * @return resources\views\View
    */
    private function getView(Response $response): View
    {
        $viewName = $response->getView();

        if(! is_null($viewName)){
            //Получать папку с фронтом из env.ini. И вообще, возможно лучше генерировать вью фабрикой по запросу команды, а не здесь
            $className = 'resources\views\\' . $viewName .'View';
            $class     = new \ReflectionClass($className);
            $view      = $class->newInstance();
            
        } else {
            //Если $response->view не заполнено. Вообще, оно будет заполнено, там по умолчанию 'StringPrint', т.е. StringPrintView
            $view = new StringPrintView($response);
        }

        return $view;
    }

    private function getStringPrintView()
    {
        
    }
}
