<?php

/**
 * Комманда для получения текстов по умолчанию (Default Text)
 */

namespace app\Commands\Http\DefaultText;

use app\Commands\Http\HttpCommand;
use app\Requests\Request;
use app\Response\Response;
use app\Workers\DefaultText\FindAllWorker;
// use app\Workers\GetDefaultTextWorker;
use DomainObjectAssembler\Collections\Collection;

class IndexCommand extends HttpCommand
{
    public function __construct(Response $response)
    {
        parent::__construct($response);

        $observer = new FindAllWorker($this);
        $this->attach($observer);
    }
    
    /**
     * GET no id
     * Получение списка дефолтных текстов, сохранение в response и передача в фронт контроллер
     *
     * @param  app\Requests\Request $request
     * @return app\Response\Response
     */
    protected function index(Request $request)
    {
        //Вызов на выполнение обсервера и получение от него данных в ответе, проверка типа ответа
        $this->notify();
        $collection = $this->answer;

        if(! $collection instanceof Collection) {
            throw new \Exception('В методе index класса DefaultTextHttpCommand ожидается коллеция, получено >>> '. $collection . '<br>');
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
}
