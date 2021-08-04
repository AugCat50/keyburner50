<?php 
/**
 * Комманда для обработки http запросов для пользователей (регистрация, авторизация, подтверждение)
 */
namespace app\Commands\Http;

use app\Registry\Registry;
use app\Requests\Request;
use app\Workers\GetDefaultTextWorker;
use app\Workers\UserCheckInWorker;
use app\Workers\UserThemesWorker;

class UserHttpCommand extends HttpCommand
{
    /**
     * Display a listing of the resource.
     *
     * @return 
     */
    public function index(Request $request)
    {
        //Костыль, товарищи. Чтобы не попадать сюда из check_in.
        $path = $request->getPath();
        if($path === '/check_in'){
            throw new \Exception('"/check_in" доступен только с id = -1 и из ajax');
        }

        session_start();

        //Если нет id пользователя - редирект на главную. Маркер авторизации
        if(! isset($_SESSION["auth_subsystem"]["user_id"])) {
            //Получить переменную окружения url из реестра
            $reg = Registry::getInstance();
            $url = $reg->getEnviroment()->get('url');
            header('Location: '. $url. 'index.php');
        }

        //Получаем данные DefaultText
        $defWorker  = new GetDefaultTextWorker();
        $defTextCol = $defWorker->find();
        $this->response->addKeyFeedback('defaultTextCollection', $defTextCol);

        //Получаем стек пользовательских тем и текстов
        $userThemeWorker = new UserThemesWorker();
        $userThemesArray = $userThemeWorker->find();

        $this->response->addKeyFeedback('userThemesArray', $userThemesArray);

        $this->response->setView('User');
        return $this->response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //$id = $request->getProperty('id');
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
