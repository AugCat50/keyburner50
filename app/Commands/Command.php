<?php
/**
 * Суперкласс для всех команд. 
 * 
 * Команда - это управляющий класс, служащий для вызова логики приложения исходя из условий запроса.
 * Зависимости запрос - команда обозваны в системе роутами (routes/routes.ini)
 * 
 * Команда может хранить в параметрах своё состояние, для принятия решения о переходе к другим командам или операциям.
 */
namespace app\Commands;

use app\Requests\Request;
use app\Response\Response;

abstract class Command
{
    /**
     * Объект Response
     * 
     * @var app\Response\Response
     */
    protected Response $response;

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
    }
    
    /**
     * Перенаправление на другую команду, если такое потребуется
     */
    private function forvard(Request $request)
    {
        //
    }

    /**
     * Вызывыть логику приложения тут.
     */
    abstract public function execute(Request $request);

    // abstract public function doExecute(Request $request);
}
