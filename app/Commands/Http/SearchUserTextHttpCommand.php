<?php 
/**
 * Комманда для обработки запросов поиска пользовательских текстов
 */
namespace app\Commands\Http;

use app\Requests\Request;
use app\Response\Response;
use app\Workers\UserThemesWorker;
use DomainObjectAssembler\DomainObjectAssembler;

class SearchUserTextHttpCommand extends HttpCommand
{
    public function __construct(Response $response)
    {
        //Запрос приходит из ajax, проверяем сессию
        session_start();
        if (! isset($_SESSION["auth_subsystem"]["user_id"])) throw new \Exception('UserTextHttpCommand(49): ID пользователя отсутствует в сессии');

        parent::__construct($response);
    }

    /**
     * GET no id
     * Поиск текстов по поисковой фразе
     * 
     * Обратите внимание шо оно творит:
     *   $query = "SELECT id, area, name 
     *             FROM texts 
     *             WHERE id_user = :id AND name LIKE :search 
    *              OR id_user = :id AND text LIKE :search";
     *
     * @param  app\Requests\Request $request
     * 
     * Возвращает коллекцию тектов в Response
     * @return app\Response\Response
     */
    public function index(Request $request)
    {
        $searchPhrase = $request->getProperty('text');
        $assembler    = new DomainObjectAssembler('UserText');
        $identityObj  = $assembler->getIdentityObject()
                            ->field('user_id')->eq( $_SESSION["auth_subsystem"]["user_id"] )
                            ->and()
                            ->field('name')->like($searchPhrase)
                            ->or()
                            ->field('user_id')->eq( $_SESSION["auth_subsystem"]["user_id"] )
                            ->and()
                            ->field('text')->like($searchPhrase)
                            ;

        $collection = $assembler->find($identityObj);
        $count      = $collection->getTotal();
        if($count == 0) {
            $this->response->addFeedback('Ничего не найдено!');
        } else {

            //Получить имена тем из БД по id, заодно генерируются все модели текстов и сохраняются в коллекции
            $usThWorker = new UserThemesWorker();
            foreach ($collection as $model) {
                $themeId    = $model->getUserThemes();
                $themeModel = $usThWorker->findThemeWhereId($themeId);
                $themeName  = $themeModel->getName();
                $model->setThemeName($themeName);
            }

            $this->response->setFeedback($collection, 'SearchTextList');
        }

        return $this->response;
    }

    /**
     *GET id
     * @param  app\Requests\Request $request
     * @return app\Response\Response
     */
    public function show(Request $request)
    {
        d('show');
        d($request);
        // return $this->response;
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
     * @return 
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param app\Requests\Request $request
     * @return 
     */
    public function destroy(Request $request)
    {

    }
}



