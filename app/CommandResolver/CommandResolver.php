<?php
/**
 * Класс, отвечающий за поиск и получение команды соотвествующий url запроса.
 * Фабрика команд.
 */
namespace app\CommandResolver;

use app\Requests\Request;
use app\Commands\Command;
use app\Commands\DefaultCommand;
use app\Commands\Http\DefaultTextHttpCommand;
use app\Registry\Registry;
use app\Response\Response;

class CommandResolver{
    private static $refcmd     = null;
    // private static $defaultcmd = DefaultCommand::class;
    private static $defaultcmd = DefaultTextHttpCommand::class;

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

        $response = new Response();

        if (is_null($class)) {
            $response->addFeedback("Соответствие пути ". $path. " не обнаружено!");
            return new self::$defaultcmd();
        }

        if (! class_exists($class)) {
            $response->addFeedback("Класс ". $class. " не найден!");
            return new self::$defaultcmd();
        }

        $refclass = new \ReflectionClass ($class);

        if (! $refclass->isSubClassOf(self::$refcmd)) {
            $response->addFeedback("Команда ". $refclass. " не относится к классу Command!");
            return new self::$defaultcmd();
        }

        return $refclass->newInstance();
    }
}
