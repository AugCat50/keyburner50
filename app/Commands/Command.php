<?php
/**
 * Суперкласс для всех команд. 
 * 
 * Команда - это управляющий класс, служащий для вызова логики приложения исходя из условий запроса.
 * обрабатывать входные данные, вызывать логику приложения, обрабатывать полученный реузльтат
 * Зависимости запрос - команда обозваны в системе роутами (routes/routes.ini)
 * 
 * Команда может хранить в параметрах своё состояние, для принятия решения о переходе к другим командам или операциям.
 */
namespace app\Commands;

use app\Requests\Request;
use app\Response\Response;
use app\Workers\Worker;

abstract class Command implements \SplSubject
{
    protected \SplObjectStorage $storage;
    /**
     * Объект Response
     * 
     * @var app\Response\Response
     */
    protected Response $response;

    protected Request  $request;

    protected string $status = '';
    protected mixed $answer;

    /**
     * Объект Worker, который будет использовать команда
     * Воркеры в каждой команде жёстко закодированы в конутрукторе.
     * Конечно, можно сделать гибкую логику генерации вокеров, согласно файлу конфигурации,
     * разбить воркеры на один метод (операция) - один воркер
     * 
     * Но сложность системы этого не требует. Я решил оставить мультиоперационные воркеры как есть.
     * 
     * @var app\Workers\Worker
     */
    protected ? Worker $worker = null;

    /**
     * В приложении feedback и имя класса View возвращается в response, 
     * поэтому сохраним объект сразу в свойстве, доступном всем дочерним реализациям.
     * 
     * Объект Response создаётся в CommandResolver при создании объекта комманды
     * 
     * @param app\Response\Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
        $this->storage  = new \SplObjectStorage();
    }
    
    /**
     * Методы библиотеки Spl, реализация шаблона Observer
     */
    public function attach(\SplObserver $observer): void
    {
        $this->storage->attach($observer);
    }

    public function detach(\SplObserver $observer): void
    {
        $this->storage->detach($observer);
    }

    public function notify(): void
    {
        foreach ($this->storage as $obs)
        {
            $obs->update($this);
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setAnswer(mixed $answer)
    {
        $this->answer = $answer;
    }

    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Перенаправление на другую команду, если такое потребуется
     */
    protected function forvard(Request $request): void
    {
        //
    }

    /**
     * Вызывыть логику приложения тут.
     */
    abstract public function execute(Request $request);

    // abstract public function doExecute(Request $request);
}
