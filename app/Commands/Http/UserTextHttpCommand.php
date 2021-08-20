<?php 
namespace app\Commands\Http;

use app\Requests\Request;
use app\Response\Response;
use app\Workers\UserTextWorker;
use app\Workers\UserThemesWorker;
use DomainObjectAssembler\DomainObjectAssembler;

class UserTextHttpCommand extends HttpCommand
{
    private $userTextWorker;

    public function __construct(Response $response)
    {
        //Запрос приходит из ajax, проверяем сессию
        session_start();
        if (! isset($_SESSION["auth_subsystem"]["user_id"])) throw new \Exception('UserTextHttpCommand(49): ID пользователя отсутствует в сессии');

        $this->userTextWorker = new UserTextWorker();
        parent::__construct($response);
    }

    /**
     * GET no id
     * 
     * @return app\Response\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * GET id
     * Получить один текст
     * 
     * @param  app\Requests\Request $request
     * @return app\Response\Response
     */
    public function show(Request $request)
    {
        $id          = $request->getProperty('id');
        $assembler   = new DomainObjectAssembler('UserText');
        $identityObj = $assembler->getIdentityObject()
                            ->field('id')
                            ->eq($id);
        $model       = $assembler->findOne($identityObj);
        $text        = $model->getText();
        $this->response->setFeedback($text);
        
        return $this->response;
    }

    /**
     * POST
     * Метод для сохранения пользовательского текста. Если тема новая, сохраняется так же тема
     *
     * @param  app\Requests\Request  $request
     * @return 
     */
    public function store(Request $request)
    {
        //Получить id темы
        $request = $this->getThemeId($request);
        // $userThemeWorker = new UserThemesWorker();
        // $themeName       = $request->getProperty('theme');
        // $themeId         = $userThemeWorker->getThemeIdWhereName($themeName);
        // $request->setProperty('themeId', $themeId);

        //Запись самого нового текста, получение id записанного текста
        $textId = $this->userTextWorker->insert($request);

        //Получаем обновлённый стек пользовательских тем и текстов, чтобы обновить меню
        $this->updateUserTextList();
        
        //Заполняем response. span должен быть обязательно, на нём завязано разбитие строки в js
        $this->response->addKeyFeedback('textId', $textId);
        $this->response->addKeyFeedback('message', '<span>Текст сохранён!</span>');
        
        return $this->response;
    }

    /**
     * PUT
     * Обновить текст в БД
     *
     * @param  app\Requests\Request  $request
     * @param  int  $id
     * @return 
     */
    public function update(Request $request)
    {
        //Получить id темы
        $request = $this->getThemeId($request);

        $textId = $this->userTextWorker->update($request);

        // $this->response->addKeyFeedback('textId', $textId);
        $this->response->addKeyFeedback('message', "<span>Текст ID: $textId обновлён!</span>");

        //Получаем обновлённый стек пользовательских тем и текстов, чтобы обновить меню
        $this->updateUserTextList();

        return $this->response;
    }

    /**
     * DELETE
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return 
     */
    public function destroy(Request $request)
    {
        $id     = $request->getProperty('id');
        $this->userTextWorker->delete($id);

        $this->response->addKeyFeedback('message', "<span>Текст ID: $id успешно удалён!</span>");

        $this->updateUserTextList();

        return $this->response;
    }

    /**
     * Получаем обновлённый стек пользовательских тем и текстов, чтобы обновить меню
     * 
     * Обратите внимание, устанавливается вью
     */
    public function updateUserTextList()
    {
        $userThemeWorker = new UserThemesWorker();
        $userThemesArray = $userThemeWorker->find();
        $this->response->addKeyFeedback('userThemesArray', $userThemesArray);
        $this->response->setView('UserTextList');
    }

    public function getThemeId(Request $request): Request
    {
        //Получить id темы
        $userThemeWorker = new UserThemesWorker();
        $themeName       = $request->getProperty('theme');
        $themeId         = $userThemeWorker->getThemeIdWhereName($themeName);
        $request->setProperty('themeId', $themeId);

        return $request;
    }
}
