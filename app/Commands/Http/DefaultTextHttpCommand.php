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
     * Display a listing of the resource.
     *
     * @return 
     */
    public function index(Request $request)
    {
        $worker     = new GetDefaultTextWorker();
        $collection = $worker->find();
        $data       = [];

        // foreach ($collection as $model) {
        //     $name = $model->getName();
        //     $result = '';
        //     <li class='default-text-list__name blue-neon js_default-text-list__name' data-id='".$val['id']."' name='".$val['name']."'><span class='pointer'>&#187; </span><span class='js_value'>" . $val['name'] . "</span></li>
        //     echo "$name <br>";
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $worker = new GetDefaultTextWorker();
        $model  = $worker->findOne($id);
        // return $model;
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
