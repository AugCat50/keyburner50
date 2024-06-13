<?php
/**
 * Комманда для работы со статистикой. Получение и запись статистики по id 
 */
namespace app\Commands\Http;

use app\Requests\Request;
use app\Response\Response;
use app\Workers\StatisticsWorker;

class StatisticsHttpCommand extends HttpCommand
{
    public function __construct(Response $response)
    {
        //Запрос приходит из ajax, проверяем сессию
        session_start();
        if (! isset($_SESSION["auth_subsystem"]["user_id"])) throw new \Exception('StatisticsHttpCommand(17): ID пользователя отсутствует в сессии');

        parent::__construct($response);
    }
    
    /**
     * GET id
     * Получить и вернуть статистику для текста по id
     * 
     * @param  app\Requests\Request $request
     * @return app\Response\Response
     */
    protected function show(Request $request)
    {
        $worker = new StatisticsWorker();
        $data   = $worker->getStatistics($request);

        $this->response->addFeedback($data);
        return $this->response;
    }

    /**
     * POST
     * Записать статистику в БД
     *
     * @param  app\Requests\Request  $request
     * @return app\Response\Response
     */
    protected function store(Request $request)
    {
        $worker = new StatisticsWorker();
        $data   = $worker->main($request);

        $this->response->addFeedback($data);
        return $this->response;
    }
}
