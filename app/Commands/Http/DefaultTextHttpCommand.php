<?php 
/**
 * Комманда для обработки http запросов для текстов по умолчанию (Default Text)
 */
namespace app\Commands\Http;

use app\Requests\Request;
use app\Workers\GetDefaultTextWorker;

class DefaultTextHttpCommand extends HttpCommand
{
    /**
     * GET no id
     * Получение списка дефолтных текстов, сохранение в response и передача в фронт контроллер
     *
     * @param  app\Requests\Request $request
     * @return app\Response\Response
     */
    public function index(Request $request)
    {
        $worker     = new GetDefaultTextWorker();
        $collection = $worker->find();
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
    public function show(Request $request)
    {
        $id     = $request->getProperty('id');
        $worker = new GetDefaultTextWorker();
        $model  = $worker->findOne($id);
        $text   = $model->getText();

        $this->response->setFeedback($text, 'Simple');
        return $this->response;
    }

    /**
     * POST
     * Store a newly created resource in storage.
     *
     * @param  app\Requests\Request  $request
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * PUT
     * Update the specified resource in storage.
     *
     * @param  app\Requests\Request  $request
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * DELETE
     * Remove the specified resource from storage.
     *
     * @param app\Requests\Request $request
     */
    public function destroy(Request $request)
    {
        //
    }
}
