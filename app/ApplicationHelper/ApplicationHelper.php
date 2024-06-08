<?php
/**
 * ApplicationHelper
 * Не обязательный класс для шаблона FrontController, 
 * реализует стратегию инициализации переменных окружения и routes зависимостей Url - комманда.
 * 
 * Создаёт объект Request.
 * Парсит конфигурационные ini файлы, создаёт и заполняет объекты Conf данными из ini.
 * Сохраняет это всё в реестр.
 */
namespace app\ApplicationHelper;

use app\Conf\Conf;
use app\Registry\Registry;
use app\Requests\Http\HttpRequest;
use app\Requests\Cli\CliRequest;

class ApplicationHelper
{
    /**
     * Путь к файлу - Настройки окружения
     * 
     * @var string
     */
    private string $enviroment;

    /**
     * Путь к файлу - routes зависимостей Url - комманда
     * 
     * @var string
     */
    private string $routes;

    /**
     * Реестр
     * 
     * @var app\Registry\Registry
     */
    private Registry $reg;
    

    /**
     * Конструктор.
     * Установка путей к файлам настроек.
     */
    public function __construct()
    {
        $this->enviroment = dirname(dirname(__DIR__)) . "/env.ini";
        $this->routes     = dirname(dirname(__DIR__)) . "/routes/routes.ini";
        $this->reg        = Registry::getInstance();
    }
    
    /**
     * Инициализация приложения. Создание объекта Request, в зависимости от того 
     * выполняется ли приложение в контексте веб или запущено из командной строки
     * 
     * -- Функционал метода похож на шаблон PageController --
     * 
     * @return void
     */
    public function init(): void
    {
        //чтение конфигурационных файлов
        $this->setupOptions();

        if (isset($_SERVER['REQUEST_METHOD'])) {
            $request = new HttpRequest();
        } else {
            $request = new CliRequest();
        }

        //Возможна и такая проверка
        // if (defined('STDIN')) {
        //     $request = new CliRequest();
        // }
        $this->reg->setRequest($request);
    }
    
    /**
     * Парсинг файлов настроек в объекты-обёртки Conf
     * Настройки окружения и роуты.
     * 
     * @return void
     */
    private function setupOptions(): void
    {
        if(!file_exists($this->enviroment)) {
            throw new \Exception ("ApplicationHelper(89) _error_: Файл конфигурации окружения не найден!\n");
        }
        if(!file_exists($this->routes)) {
            throw new \Exception ("ApplicationHelper(92) _error_:Файл конфигурации роутов не найден!\n");
        }
        
        //массив типа Conf для хранения общих значений конfигурации
        $env   = parse_ini_file($this->enviroment, true);
        $eConf = new Conf($env['config']);
        $this->reg->setEnviroment($eConf);
        
        //массив типа Conf для преобразования путей URL в классы типа Command
        $rts   = parse_ini_file($this->routes, true);
        $rConf = new Conf($rts['commands']);
        $this->reg->setRoutes($rConf);

        //Установить подключение с БД
        $this->setDataBaseConnection($eConf);
    }

    /**
     * Создать PDO, установить соединение с БД, объект PDO сохраняется в реестр.
     * Конфигурационные данные из "/env.ini"
     * 
     * @return void;
     */
    private function setDataBaseConnection(Conf $eConf): void
    {
        $dbType = $eConf->get('dbType');
        $host   = $eConf->get('host');
        $dbName = $eConf->get('dbName');
        $dbUser = $eConf->get('dbUser');
        $dpPass = $eConf->get('dbPass');
        $dsn    = $dbType. ':host='. $host. ';dbname='. $dbName;

        try {
            $pdo = new \PDO( $dsn, $dbUser, $dpPass);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e){
            echo "ApplicationHelper(128) _error_: Соединение с БД не удалось: " . $e->getMessage() . "\n<br>";
            exit;
        }

        $this->reg->setPdo($pdo);
    }
}
