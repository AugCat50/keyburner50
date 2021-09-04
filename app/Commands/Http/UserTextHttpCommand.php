<?php
/**
 * Работа с текстами пользователей. Сохранить, обновить, удалить.
 * Если при сохранении текста тема новая, сохраняется и тема.
 */
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
        if (! isset($_SESSION["auth_subsystem"]["user_id"])) throw new \Exception('UserTextHttpCommand(22): ID пользователя отсутствует в сессии');

        $this->userTextWorker = new UserTextWorker();
        
        parent::__construct($response);
    }

    /**
     * GET no id
     * 
     * @param  app\Requests\Request $request
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
     * Сохранить новый пользовательский текст. Если тема новая, сохраняется так же тема
     *
     * @param  app\Requests\Request  $request
     * @return app\Response\Response
     */
    public function store(Request $request)
    {
        //Получить id темы
        $request = $this->getThemeId($request);

        //Запись самого нового текста, получение id записанного текста
        $textId = $this->userTextWorker->insert($request);

        //Получаем обновлённый стек пользовательских тем и текстов в response, чтобы обновить меню
        $this->updateUserTextList();
        
        //Заполняем response. span должен быть обязательно, на нём завязано разбитие строки в js
        $this->response->addKeyFeedback('textId', $textId);
        $this->response->addKeyFeedback('message', '<span>Текст сохранён!</span>');
        
        return $this->response;
    }

    /**
     * PUT
     * Обновить пользовательский текст
     *
     * @param  app\Requests\Request  $request
     * @return app\Response\Response
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
     * Удалить пользовательский текст
     *
     * @param  app\Requests\Request  $request
     * @return app\Response\Response 
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
     * Обратите внимание, устанавливается View
     */
    private function updateUserTextList()
    {
        $userThemeWorker = new UserThemesWorker();
        $userThemesArray = $userThemeWorker->find();
        $this->response->addKeyFeedback('userThemesArray', $userThemesArray);
        $this->response->setView('UserTextList');
    }

    /**
     * Выяснить id темы по имени, если не найден - создать и записать в БД новую тему
     * и сохранить в $request
     * 
     * @param  app\Requests\Request  $request
     * @return app\Requests\Request 
     */
    private function getThemeId(Request $request): Request
    {
        $userThemeWorker = new UserThemesWorker();
        $themeName       = $request->getProperty('theme');
        $themeId         = $userThemeWorker->getThemeIdWhereName($themeName);
        $request->setProperty('themeId', $themeId);

        return $request;
    }
}
