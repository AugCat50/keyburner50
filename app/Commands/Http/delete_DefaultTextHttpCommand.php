<?php

/**
 * Комманда для обработки http запросов для текстов по умолчанию (Default Text)
 */

namespace app\Commands\Http;

use app\Requests\Request;
use app\Response\Response;
use app\Workers\GetDefaultTextWorker;
use DomainObjectAssembler\Collections\Collection;
use DomainObjectAssembler\DomainModel\DefaultTextModel;
use DomainObjectAssembler\DomainModel\DomainModel;

class DefaultTextHttpCommand extends HttpCommand
{
    public function __construct(Response $response)
    {
        parent::__construct($response);

        $observer = new GetDefaultTextWorker($this);
        $this->attach($observer);
    }

    /**
     * GET no id
     * Получение списка дефолтных текстов, сохранение в response и передача в фронт контроллер
     *
     * @param  app\Requests\Request $request
     * @return app\Response\Response
     */
    protected function index()
    {
        //Вызов на выполнение обсервера и получение от него данных в ответе, проверка типа ответа
        $this->status = 'find';
        $this->notify();
        $collection = $this->answer;

        if(! $collection instanceof Collection) {
            throw new \Exception('В методе index класса DefaultTextHttpCommand ожидается коллеция, получено >>> '. $collection);
        }

        //Логика команды, переформатирование в массив, удобный для view, задание вью строкой
        $data       = [];
        $i          = 0;

        foreach ($collection as $model) {
            $data[$i]['id']   = $model->getId();
            $data[$i]['name'] = $model->getName();
            $i++;
        }
        
        $this->response->setFeedback($data, 'DefaultTextList');
        return $this->response;
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
    protected function show()
    {
        //Вызов на выполнение обсервера и получение от него данных в ответе, проверка типа ответа
        $this->status = 'findOne';
        $this->notify();
        $model = $this->answer;

        if(! $model instanceof DefaultTextModel) {
            throw new \Exception('В методе index класса DefaultTextHttpCommand ожидается DefaultTextModel, получено >>> '. $model);
        }

        //Логика команды, получение текста, задание вью строкой
        $text = $model->getText();

        $this->response->setFeedback($text, 'StringPrint');
        return $this->response;
    }
}
