<?php

namespace app\Commands\Http\User;

use app\Commands\Http\HttpCommand;
use app\Requests\Request;

class StoreUser extends HttpCommand
{
    public function __construct(Response $response)
    {
        parent::__construct($response);

        $observer = new FindAllWorker($this);
        $this->attach($observer);
    }

    /**
     * POST
     * Зарегистрировать нового пользователя, проверить, сохранить в БД
     *
     * @param  app\Requests\Request  $request
     * @return app\Response\Response
     */
    protected function store(Request $request)
    {
        $worker = new UserCheckInWorker();
        $msg    = $worker->addNewUser($request);

        $this->response->setFeedback($msg);
        return $this->response;
    }
}
