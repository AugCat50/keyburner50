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
    public function __construct(Response $response)
    {
        //Запрос приходит из ajax, проверяем сессию
        session_start();
        if (! isset($_SESSION["auth_subsystem"]["user_id"])) throw new \Exception('UserThemeHttpCommand(19): ID пользователя отсутствует в сессии');

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
        $id   = $request->getProperty('id');
        $name = $request->getProperty('name');

        $assembler   = new DomainObjectAssembler('UserTheme');
        $identityObj = $assembler->getIdentityObject()->field('id')->eq($id);
        $model       = $assembler->findOne($identityObj);
        $model->setName($name);

        $answer = $this->performDB();

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
            $userTextAssembler->delete($model);
        }

        //Создаём фейковую модель темы с настоящим id и ставим её в очередь на удаление
        $userThemeAssembler = new DomainObjectAssembler('UserTheme');
        $themeModel         = $userThemeAssembler->createNewModel(['id' => $themeId, 'user_id' => -1, 'name' => '']);
        $userThemeAssembler->delete($themeModel);

        //Запускаем очереди БД на выполнение
        $answer = $this->performDB();

        $this->response->addFeedback($answer);
        $this->response->addFeedback('Тема '. $request->getProperty('name'). 'успешно удалена!');
        return $this->response;
    }

    /**
     * Получение объекта ObjectWatcher и запуск на выполнение его очередей
     * Выполнение работы с БД
     * 
     * @return array
     */
    private function performDB(){
        $objWather = ObjectWatcher::getInstance();
        $answer    = $objWather->performOperations();

        return $answer;
    }
}
