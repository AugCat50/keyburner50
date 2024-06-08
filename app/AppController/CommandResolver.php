<?php

/**
 * Часть шаблона ApplicationController, отвечающая за выбор команды.
 * 
 * ФронтКонтроллеру требуется какой-нибудь способ, позволяющий решить, как интерпретировать HTTP-запрос, 
 * чтобы в результате можно было вызвать нужный код для обработки этого запроса.
 * 
 * Класс, отвечает за поиск и получение команды соотвествующий url запроса.
 * Фабрика команд.
 */

namespace app\AppController;

use app\Commands\Command;
use app\Requests\Request;
use app\Registry\Registry;
use app\Response\Response;
use app\Commands\NotFoundCommand;
// use resources\views\View;

class CommandResolver
{
    private static ? \ReflectionClass $refcmd = null;

    /**
     * Дефолтная команда, при пустом роуте. Вывод списка дефолтных текстов ♥
     * 
     * @var string
     */
    private static string $defaultcmd = NotFoundCommand::class;

    public function __construct()
    {
        // этот объект можно сделать конфигурируемым
        self::$refcmd = new \ReflectionClass(Command::class);
    }

    /**
     * Метод, получающий объект команды соотвествующий url запроса
     * 
     * @param app\Requests\Request $request
     * 
     * @return app\Commands\Command
     */
    public function getCommand(Request $request): Command
    {
        //Получить объект реестр
        $reg = Registry::getInstance();

        //Получаем массив роутов
        $commands = $reg->getRoutes();

        //Получить url из объекта реквест
        $path = $request->getPath();

        //Получаем полное имя класса команды из массива роутов по url
        $class = $commands->get($path);

        if (is_null($class)) {
            $response = new Response("CommandResolver(58): Соответствие пути " . $path . " не обнаружено!");
            return new self::$defaultcmd($response);
        }

        if (!class_exists($class)) {
            $response = new Response("CommandResolver(63): Класс " . $class . " не найден!");
            return new self::$defaultcmd($response);
        }

        //Получение ReflectionClass для команды, для дальнейшего получения его объекта
        $refclass = new \ReflectionClass($class);

        if (!$refclass->isSubClassOf(self::$refcmd)) {
            $response = new Response("CommandResolver(70): Команда " . $refclass . " не относится к классу Command!");
            return new self::$defaultcmd($response);
        }

        $response = new Response();
        return $refclass->newInstance($response);
    }

    //для расширения до AppController
    // public function getView(Request $request): View
}
