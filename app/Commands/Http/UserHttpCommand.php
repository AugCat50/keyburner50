<?php 
/**
 * Комманда для получения базовых данных пользователя на странице /user
 * а так же для регистрации нового пользовтеля. Может надо сделать отдельный контроллер или наоборот всё собрать в один 
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
     * GET no id
     * Получение данных пользователя, когда он попадает на роут /user
     * Получение списка стандартных текстов, получение списков тем с текстами пользователя
     * 
     * id пользователя в сессии - $_SESSION["auth_subsystem"]["user_id"]
     *
     * @param  app\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Костыль, чтобы запретить попадать сюда из check_in.
        $path = $request->getPath();
        if($path === '/check_in'){
            throw new \Exception('"/check_in" доступен только с id = -1 и из ajax');
        }

        //Старт стессии в index.php
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
     * GET id
     * Display the specified resource.
     *
     * @param  app\Requests\Request  $request
     */
    public function show(Request $request)
    {
        // return $this->response;
    }

    /**
     * POST
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
     * PUT
     * Возможности переименовать пользователя пока нет, но можно добавить
     *
     * @param app\Requests\Request $request
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * DELETE
     * Возможности удаления пользователя пока нет, но можно добавить
     *
     * @param app\Requests\Request $request 
     */
    public function destroy(Request $request)
    {
        //
    }
}
