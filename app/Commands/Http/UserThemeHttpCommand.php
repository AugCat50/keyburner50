<?php
/**
 * Комманда для работы с пользовательской темой, обновление, удаление.
 * За исключением создания и сохранения новой, это находится в UserTextHttpCommand::getThemeId
 */
namespace app\Commands\Http;

use app\Requests\Request;
use app\Response\Response;
use DomainObjectAssembler\DomainObjectAssembler;
use DomainObjectAssembler\IdentityMap\ObjectWatcher;

class UserThemeHttpCommand extends HttpCommand
{
    private $themeAssembler = null;
    private $objWatcher     = null;

    public function __construct(Response $response)
    {
        //Запрос приходит из ajax, проверяем сессию
        session_start();
        if (! isset($_SESSION["auth_subsystem"]["user_id"])) throw new \Exception('UserThemeHttpCommand(19): ID пользователя отсутствует в сессии');

        $this->themeAssembler = new DomainObjectAssembler('UserTheme');
        $this->objWatcher     = ObjectWatcher::getInstance();

        parent::__construct($response);
    }

    /**
     * GET
     * 
     * @return app\Response\Response
     */
    public function index(Request $request)
    {
        
    }

    /**
     * GET
     * 
     * @param  app\Requests\Request $request
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * POST
     * Сохранение новой темы находится в UserTextHttpCommand::getThemeId , поскольку отденой от тестов функции "создать тему" нет
     * Но можно переделать и перенести создание темы сюда.
     *
     * @param  app\Requests\Request  $request
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * PUT
     * Обновить тему в БД (переименовать)
     *
     * @param  app\Requests\Request  $request
     * @return app\Response\Response
     */
    public function update(Request $request)
    {
        //Подготовить ассоциативный массив для создания модели
        $data['id']      = $request->getProperty('id');
        $data['name']    = $request->getProperty('name');
        $data['user_id'] = $_SESSION["auth_subsystem"]["user_id"];

        //Создать модель, id>0 - модель добавится в objWatcher как существующая. Помечаем её как Dirty
        $model = $this->themeAssembler->createNewModel($data);
        $model->markDirty();

        //Запускаем очереди на выполнение
        $answer = $this->objWatcher->performOperations();

        $this->response->addFeedback($answer);
        $this->response->addFeedback('Тема '. $request->getProperty('name'). ' успешно переименована!');
        
        return $this->response;
    }

    /**
     * DELETE
     * Удаление пользовательской темы, вместе со всеми её текстами из БД
     * 
     * Сначала получается коллекция текстов, затем они помечаются как удаляемые,
     * запускается работа с БД и всё удаляется
     *
     * @param  app\Requests\Request  $request
     * @return app\Response\Response
     */
    public function destroy(Request $request)
    {
        //Получаем коллекцию текстов этой темы
        $themeId           = $request->getProperty('id');
        $userTextAssembler = new DomainObjectAssembler('UserText');
        $identityObj       = $userTextAssembler->getIdentityObject()->field('user_themes')->eq($themeId);
        $collection        = $userTextAssembler->find($identityObj);

        //Ставим тексты в очередь на удаление
        foreach ($collection as $model) {
            $model->markDeleted();
        }

        //Создаём фейковую модель темы с настоящим id и ставим её в очередь на удаление
        $themeModel = $this->themeAssembler->createNewModel(['id' => $themeId, 'user_id' => -1, 'name' => '']);
        $themeModel->markDeleted();

        //Запускаем очереди БД на выполнение
        $answer = $this->objWatcher->performOperations();

        $this->response->addFeedback($answer);
        $this->response->addFeedback('Тема '. $request->getProperty('name'). 'успешно удалена!');
        return $this->response;
    }
}
