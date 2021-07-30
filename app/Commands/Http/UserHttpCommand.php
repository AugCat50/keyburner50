<?php 
/**
 * Комманда для обработки http запросов для пользователей (регистрация, авторизация, подтверждение)
 */
namespace app\Commands\Http;

use app\Requests\Request;
use app\Response\Response;
use app\Workers\UserCheckInWorker;

class UserHttpCommand extends HttpCommand
{
    /**
     * Display a listing of the resource.
     *
     * @return 
     */
    public function index(Request $request)
    {
        // $worker     = new UserCheckInWorker();

        // $collection = $worker->find();
        // $data       = [];
        // $i          = 0;

        // foreach ($collection as $model) {
        //     $data[$i]['id']   = $model->getId();
        //     $data[$i]['name'] = $model->getName();
        //     $i++;
        // }

        // $result = [ 'view' => 'DefaultTextList', 'response' => new Response($data)];
        // return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $worker = new GetDefaultTextWorker();
        // $model  = $worker->findOne($id);
        // $text   = $model->getText();

        // $result = [ 'view' => 'Simple', 'response' => new Response($text)];
        // return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  app\Requests\Request  $request
     * @return 
     */
    public function store(Request $request)
    {
        $worker = new UserCheckInWorker();
        $msg    = $worker->addNewUser($request);

        d($msg);
        d($request);
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
