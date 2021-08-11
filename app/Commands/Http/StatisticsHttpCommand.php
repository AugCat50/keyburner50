<?php 
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
        if (! isset($_SESSION["auth_subsystem"]["user_id"])) throw new \Exception('UserTextHttpCommand(49): ID пользователя отсутствует в сессии');

        parent::__construct($response);
    }
    
    /**
     * @return app\Response\Response
     */
    public function index(Request $request)
    {
        d('index');
        d($request);

        // return $this->response;
    }

    /**
     * 
     * @param  app\Requests\Request $request
     * @return app\Response\Response
     */
    public function show(Request $request)
    {
        d('show');
        d($request);

        // return $this->response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  app\Requests\Request  $request
     * @return 
     */
    public function store(Request $request)
    {
        // d('store');
        // d($request);
        $worker = new StatisticsWorker();
        $data   = $worker->main($request);
        // $this->response->addKeyFeedback('statistics', $data);
        // $this->response->addFeedback('999');
        $this->response->addFeedback($data);

        return $this->response;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  app\Requests\Request  $request
     * @param  int  $id
     * @return 
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return 
     */
    public function destroy(Request $request)
    {

    }
}
