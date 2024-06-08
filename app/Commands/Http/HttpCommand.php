<?php 
/**
 * Суперкласс - Комманда для обработки http запросов. 
 * Выясняет метод http запроса и направляет на соответствующий метод реализующей команды, дескать rest.
 */
namespace app\Commands\Http;

use app\Commands\Command;
use app\Requests\Http\HttpRequest;
use app\Requests\Request;

abstract class HttpCommand extends Command
{
    abstract public function index  (Request $request);
    abstract public function show   (Request $request);
    abstract public function store  (Request $request);
    abstract public function update (Request $request);
    abstract public function destroy(Request $request);

    /**
     * Общий для все команд метод, запускающий выполнение.
     * Делегирует выполнение методу, в зависимости от параметров запроса
     * 
     * @param app\Requests\Http\HttpRequest $request
     * 
     * Чаще всего будет возвращать Response
     * @return void|Response
     */
    public function execute(Request $request)
    {
        if (! $request instanceof HttpRequest) {
            throw new \Exception('HttpCommand(32): HTTP команде должен быть передан объект HttpRequest. Получен - '. get_class($request));
        }

        $httpMethod = $request->getHttpMethod();
        $id         = $request->getProperty('id');
        
        switch ($httpMethod) {
            case 'GET':
                //Если есть id, получаем одну запись
                if(! is_null($id)){
                    return $this->show($request);
                }

                //Если нет id, получаем все записи
                return $this->index($request);
                break;
            case 'POST':
                return $this->store($request);
                break;
            case 'PUT':
                return $this->update($request);
                break;
            case 'DELETE':
                return $this->destroy($request);
                break;
        }
        //Вероятно, надо реализовать класс NullResponse, и убрать void. И установить тип возвращаемого значения
    }
}
