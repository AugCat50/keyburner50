<?php

/**
 * Шаблон Front Controller (Фронтальный контроллер) предоставляет центральную точку доступа для обработки всех
 * входящих запросов и в конечном итоге поручает уровню представления вывод
 * полученных результатов для просмотра пользователем.
 */

use app\Registry\Registry;
use app\AppController\CommandResolver;
use app\AppController\ViewController;

class FrontController
{
    /**
     * Экземпляр объекта Реестр
     * 
     * @var app\Registry\Registry
     */
    private Registry $reg;

    /**
     * Получение экземпляра класса Реестр
     * Закрытый конструктор. Старт возможет только через метод run()
     */
    private function __construct()
    {
        $this->reg = Registry::getInstance();
    }

    /**
     * Стартовая точка системы. Метод обёртка для инициализации системы и обработки запроса
     * 
     * @return void
     */
    public static function run(): void
    {
        $frontController = new self();
        $frontController->init();
        $frontController->handleRequest();
    }

    /**
     * Получаем из реестра объект Application Helper'а и делегируем ему инициализацию приложения
     * 
     * ApplicationHelper::init() парсит и устанавливает настройки и роуты и создаёт объект Request
     * Далее обращаться к параметрам HTTP-запроса исключительно с помощью вызова метода Request::getProperty()
     * 
     * @return void
     */
    private function init(): void
    {
        //Изначально, в шаблоне подразумевается широкий доступ к хелперу
        //Здесь же хелпер больше нигде не используется, можно его создание вынести из реестра сюда. Но уже не буду трогать
        $this->reg->getApplicationHelper()->init();
    }

    /**
     * Обработка запроса вызывается здесь.
     * 
     * Контроллер, обращается к логике приложения, запуская на выполнение команду (Command).
     * Этот объект выбирается и создаётся в соответствии со структурой запрошенного URL (в соответствии роутам) в фабрике CommandResolver.
     * Логика выбора http метода в случае http запроса - в суперкласce Command.
     * 
     * Получает контроллер View и передаёт Response на обработку и рендер
     * 
     * @return void
     */
    private function handleRequest(): void
    {
        $request  = $this->reg->getRequest();
        $resolver = new CommandResolver();
        $cmd      = $resolver->getCommand($request);
        $response = $cmd->execute($request);

        $viewController = new ViewController();
        $viewController->render($response);
    }
}
