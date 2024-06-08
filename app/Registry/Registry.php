<?php

/**
 * Реестр
 * 
 * Шаблон предназначен для того, чтобы предоставлять глобальтный доступ к данным, 
 * позволяет избавиться от передачи данных через все слои системы, где они не используются, снижая связанность.
 * Клиенты зависят от реестра, что не отражено в их интерфейсе, что является минусом шаблона.
 * 
 * Данные конфигурации есть смысл кешировать.
 */

namespace app\Registry;

use app\Traits\SingletonTrait;

use app\Conf\Conf;
use app\ApplicationHelper\ApplicationHelper;
use app\Requests\Request;

class Registry
{
    use SingletonTrait;

    /**
     * Объект Application Helper
     * 
     * @var app\ApplicationHelper\ApplicationHelper
     */
    private ?ApplicationHelper $applicationHelper = null;

    /**
     * Объект Conf с переменными окружения
     * 
     * @var app\Conf\Conf
     */
    private Conf $enviroment;

    /**
     * Объект Conf с настройками роутов
     * 
     * @var app\Conf\Conf
     */
    private Conf $routes;

    /**
     * Объект реквест. app\Requests\Http\ или app\Requests\Cli\ ,
     * в зависимости от типа запроса, поступившего в систему
     * 
     * @var app\Requests\Request $request
     */
    private Request $request;

    /**
     * Объект \PDO

     * @var \PDO
     */
    private \PDO $pdo;


    /**
     * Создаёт, сохраняет и возвращает объект Application Helper
     * 
     * @return app\ApplicationHelper\ApplicationHelper
     */
    public function getApplicationHelper(): ApplicationHelper
    {
        if (is_null($this->applicationHelper)) {
            $this->applicationHelper = new ApplicationHelper();
        }
        return $this->applicationHelper;
    }

    /**
     * Установить объект, содержащий массив настроек окружения
     * 
     * @param app\Conf\Conf $enviroment
     * 
     * @return void
     */
    public function setEnviroment(Conf $enviroment): void
    {
        $this->enviroment = $enviroment;
    }

    /**
     * Получить объект, содержащий массив настроек окружения
     * 
     * @return app\Conf\Conf
     */
    public function getEnviroment(): Conf
    {
        if (is_null($this->enviroment)) {
            throw new \Exception("Registry _error_: Массив ностроек окружения не установлен");
        }
        return $this->enviroment;
    }

    /**
     * Установить объект, содержащий массив зависимостей запрос - комманда
     * 
     * @param app\Conf\Conf $routes
     * 
     * @return void
     */
    public function setRoutes(Conf $routes): void
    {
        $this->routes = $routes;
    }

    /**
     * Получить объект, содержащий массив зависимостей запрос - комманда
     * 
     * @return app\Conf\Conf
     */
    public function getRoutes(): Conf
    {
        if (is_null($this->routes)) {
            throw new \Exception("Registry _error_: Массив роутов не установлен");
        }
        return $this->routes;
    }

    /**
     * Установить объект Request
     * 
     * @param app\Requests\Request $request
     * 
     * @return void
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * Получить объект Request
     * 
     * @return app\Requests\Request
     */
    public function getRequest(): Request
    {
        if (is_null($this->request)) {
            throw new \Exception("Registry _error_: Request не установлен");
        }
        return $this->request;
    }

    /**
     * Сохранить объект PDO
     * 
     * @return void
     */
    public function setPdo(\PDO $pdo): void
    {
        $this->pdo = $pdo;
    }


    /**
     * Получить объект PDO
     * 
     * @return \PDO
     */
    public function getPdo(): \PDO
    {
        return $this->pdo;
    }
}
