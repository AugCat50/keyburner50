<?php
/**
 * Класс, отвечающий за поиск и получение команды соотвествующий url запроса.
 * Фабрика команд.
 */
namespace app\CommandResolver;

use app\Commands\Command;
use app\Requests\Request;
use app\Registry\Registry;
use app\Response\Response;
use app\Commands\NotFoundCommand;

class CommandResolver{
    private static $refcmd = null;

    /**
     * Дефолтная команда, при пустом роуте. Вывод списка дефолтных текстов
     */
    private static $defaultcmd = NotFoundCommand::class;

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

        //Ищем в массиве роутов соответствие url запроса, получаем полное имя класса
        $class = $commands->get($path);

        if (is_null($class)) {
            $response = new Response("CommandResolver(46): Соответствие пути ". $path. " не обнаружено!");
            return new self::$defaultcmd($response);
        }

        if (! class_exists($class)) {
            $response = new Response("CommandResolver(51): Класс ". $class. " не найден!");
            return new self::$defaultcmd($response);
        }

        $refclass = new \ReflectionClass ($class);

        if (! $refclass->isSubClassOf(self::$refcmd)) {
            $response = new Response("CommandResolver(58): Команда ". $refclass. " не относится к классу Command!");
            return new self::$defaultcmd($response);
        }

        $response = new Response();
        return $refclass->newInstance($response);
    }
}
