<?php 
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
        if (! isset($_SESSION["auth_subsystem"]["user_id"])) throw new \Exception('UserTextHttpCommand(49): ID пользователя отсутствует в сессии');

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
     * Получить один текст
     * 
     * @param  app\Requests\Request $request
     * @return app\Response\Response
     */
    public function show(Request $request)
    {
        //
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
        //
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
        //
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
        $themeId           = $request->getProperty('id');
        $userTextAssembler = new DomainObjectAssembler('UserText');
        $identityObj       = $userTextAssembler->getIdentityObject()->field('user_themes')->eq($themeId);
        $collection        = $userTextAssembler->find($identityObj);

        foreach ($collection as $model) {
            $userTextAssembler->delete($model);
        }

        $userThemeAssembler = new DomainObjectAssembler('UserTheme');
        $themeModel         = $userThemeAssembler->createNewModel(['id' => $themeId, 'user_id' => -1, 'name' => '']);
        $userThemeAssembler->delete($themeModel);

        $objWather = ObjectWatcher::getInstance();
        $objWather->performOperations();

        $this->response->addFeedback('Тема '. $request->getProperty('name'). 'успешно удалена!');
        return $this->response;
    }
}
