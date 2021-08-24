<?php 
/**
 * Комманда для активации пользователя
 */
namespace app\Commands\Http;

use app\Requests\Request;
use DomainObjectAssembler\DomainModel\NullModel;
use DomainObjectAssembler\DomainObjectAssembler;
use DomainObjectAssembler\IdentityMap\ObjectWatcher;

class UserActivationHttpCommand extends HttpCommand
{
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
     * Активация аккаунта по ссылке в письме. 
     * 
     * Получает запись по user_id и key_act, и если такая запись найдена, удаляет её.
     * Не стал выносить в воркер, т.к. кода мало, он простой и больше нигде точно не будет применён. 
     * 
     * @param  app\Requests\Request $request
     * @return app\Response\Response
     */
    public function show(Request $request)
    {
        $id  = $request->getProperty('id');
        $key = $request->getProperty('key');

        $tempAssembler = new DomainObjectAssembler('Temp');
        $identityObj   = $tempAssembler->getIdentityObject()
                                            ->field('key_act')
                                            ->eq($key)
                                            ->and()
                                            ->field('user_id')
                                            ->eq($id);
        
        $tempModel = $tempAssembler->findOne($identityObj);

        $this->response->setView('Activation');
        
        //Если NullModel, значит запись не была найдена
        if($tempModel instanceof NullModel){
            $this->response->setError('Пользователь с таким id и ключём активации не обнаружен! Обратитесь к администратору за помощью: <a href="mailto:draackul2@gmail.com">draackul2@gmail.com</a>');
        } else{
            $tempAssembler->delete($tempModel);
            $objectWather = ObjectWatcher::getInstance();
            $objectWather->performOperations();
            $this->response->addFeedback('Пользователь успешно активирован!');
        }

        return $this->response;
    }

    /**
     * POST
     * Store a newly created resource in storage.
     *
     * @param app\Requests\Request $request
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * PUT
     * Update the specified resource in storage.
     *
     * @param app\Requests\Request $request
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * DELETE
     * Remove the specified resource from storage.
     *
     * @param app\Requests\Request $request
     */
    public function destroy(Request $request)
    {
        //
    }
}
