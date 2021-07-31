<?php 
/**
 * Комманда для обработки http запросов для пользователей (регистрация, авторизация, подтверждение)
 */
namespace app\Commands\Http;

use app\Requests\Request;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Зарегистрировать нового пользователя, проверить, сохранить в БД
     *
     * @param  app\Requests\Request  $request
     * @return app\Response\Response
     */
    public function store(Request $request)
    {
        $worker = new UserCheckInWorker();
        $msg    = $worker->addNewUser($request);

        $this->response->setFeedback($msg);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return 
     */
    public function destroy(Request $request)
    {
        //
    }
}
