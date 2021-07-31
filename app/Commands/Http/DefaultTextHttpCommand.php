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
     * Получение списка дефолтных текстов, сохранение в response и передача в фронт контроллер
     *
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
     * Получение одного дефолтного текста, сохранение в response и передача в фронт контроллер. 
     * 
     * Возвращается только текст, возможно есть смысл возвращать модель.
     *
     * @param  int  $id
     * @return app\Response\Response
     */
    public function show($id)
    {
        $worker = new GetDefaultTextWorker();
        $model  = $worker->findOne($id);
        $text   = $model->getText();

        $this->response->setFeedback($text, 'Simple');
        return $this->response;
    }


    
    /**
     * Store a newly created resource in storage.
     *
     * @param  app\Requests\Request  $request
     * @return 
     */
    public function store(Request $request)
    {

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
