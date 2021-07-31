<?php 
/**
 * Комманда для активации пользователя
 */
namespace app\Commands\Http;

use app\Requests\Request;
use app\Workers\UserActivationWorker;
use DomainObjectAssembler\DomainModel\NullModel;
use DomainObjectAssembler\DomainObjectAssembler;

class UserActivationHttpCommand extends HttpCommand
{
    /**
     * @return app\Response\Response
     */
    public function index(Request $request)
    {
        d($request);
    }

    /**
     * @param  int  $id
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

        //Если NullModel, значит запись не была найдена
        if($tempModel instanceof NullModel){
            $this->response->setError('Пользователь с таким id и ключём активации не обнаружен! Обратитесь к администратору за помощью: <a href="mailto:draackul2@gmail.com">draackul2@gmail.com</a>');
        } else{
            $this->response->addFeedback('Пользователь успешно активирован!');
        }

        return $this->response;

        function activation($pdo, $key){
            try{
                $query = "SELECT 1 FROM temp WHERE key_act = :key";
                $stmt  = $pdo->prepare($query);
                $stmt->bindParam(':key', $key);
                $stmt->execute();
                $user = $stmt->fetch();
            }catch(\PDOException $e){
                $data = "Ошибка в model -- activation:" . $e->getMessage() . "<br>";
            }
            
            if($user){
                try{
                    $query = "DELETE FROM temp WHERE key_act = :key";
                    $stmt  = $pdo->prepare($query);
                    $stmt->bindParam(":key", $key);
                    $stmt->execute();
                    $data = "Аккаунт активирован!";
                }catch(\PDOException $e){
                    $data = "Ошибка в model -- activation при удалении из temp:" . $e->getMessage() . "<br>";
                }
            }else{
                $data = "Ключ активации не подошёл!";
            }
            return $data;
        }
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
     * @param  int  $id
     * @return 
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return 
     */
    public function destroy(Request $request)
    {

    }
}