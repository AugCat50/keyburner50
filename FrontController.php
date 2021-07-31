<?php
use app\Registry\Registry;
use app\CommandResolver\CommandResolver;
use app\Response\Response;

class FrontController
{
    /**
     * Экземпляр объекта Реестр
     * 
     * @var app\Registry\Registry
     */
    private $reg;

    /**
     * Получение экземпляра класса Реестр
     */
    private function __construct()
    {
        $this->reg = Registry::getInstance();
    }

    /**
     * Метод обёртка для инициализации системы и обработки запроса
     * 
     * @return void
     */
    public static function run()
    {
        $frontController = new FrontController();
        $frontController->init();
        // d($frontController->reg);
        $frontController->handleRequest();
        // d($frontController);
    }

    /**
     * Получаем из реестра объект Application Helper'а и делегируем ему инициализацию приложения
     * 
     * ApplicationHelper::init() парсит и устанавливает настройки и роуты и создаёт объект Request
     * Далее обращаться к параметрам HTTP-запроса исключительно с помощью вызова метода Request::getProperty()
     * 
     * @return void
     */
    private function init()
    {
        $this->reg->getApplicationHelper()->init();
    }

    /**
     * Обработка запроса вызывается здесь.
     * 
     * Контроллер, обращается к логике приложения, выполняя команду из объекта типа Command.
     * Этот объект выбирается в соответствии со структурой запрошенного URL.
     * 
     * @return void
     */
    private function handleRequest()
    {
        $request  = $this->reg->getRequest();
        $resolver = new CommandResolver();
        $cmd      = $resolver->getCommand($request);
        $response = $cmd->execute($request);

        $this->print($response);
    }

    /**
     * Вывод данных ответа. 
     * 
     * Если получен объект Response и у него заполнено свойство view, то создаём объект View и передаём ему Response на отрисовку. 
     * Если свойство view не заполнено, выводим feedback как строку. 
     * Если получен не объект Response, то просто выводим в поток вывода.
     * 
     * @param  string|app\Response\Response
     * @return void
     */
    private function print($response)
    {
        
        if($response instanceof Response){

            $viewName = $response->getView();

            if(! is_null($viewName)){
                $className = 'resources\views\\' . $viewName .'View';
                $class     = new \ReflectionClass($className);
                $view      = $class->newInstance();
                $view->print($response);
            } else {
                echo $response->getFeedbackString('<br>');
            }

        } else{
            //Если возвращён не Response
            // d($response);
            echo $response;
        }
    }
}
