<?php

/**
 * Комманда для получения текста по умолчанию по его id (Default Text)
 */

namespace app\Commands\Http\DefaultText;

use app\Commands\Http\HttpCommand;
use app\Requests\Request;
use app\Response\Response;
use app\Workers\DefaultText\FindByIdWorker;
// use app\Workers\GetDefaultTextWorker;
use DomainObjectAssembler\DomainModel\DefaultTextModel;

class ShowCommand extends HttpCommand
{
    public function __construct(Response $response)
    {
        parent::__construct($response);

        $observer = new FindByIdWorker($this);
        $this->attach($observer);
    }

    /**
     * GET id
     * Получение одного дефолтного текста, сохранение в response и передача в фронт контроллер. 
     * 
     * Возвращается только текст, возможно есть смысл возвращать модель.
     *
     * @param  app\Requests\Request $request
     * @return app\Response\Response
     */
    protected function show(Request $request)
    {
        //Вызов на выполнение обсервера и получение от него данных в ответе, проверка типа ответа
        $this->notify();
        $model = $this->answer;

        if(! $model instanceof DefaultTextModel) {
            throw new \Exception('В методе index класса DefaultTextHttpCommand ожидается DefaultTextModel, получено >>> '. $model);
        }

        //Логика команды, получение текста, задание вью строкой
        $text   = $model->getText();

        $this->response->setFeedback($text, 'StringPrint');
        return $this->response;
    }
}