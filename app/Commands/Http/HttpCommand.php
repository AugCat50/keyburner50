<?php 
/**
 * Суперкласс - Комманда для обработки http запросов. 
 * Выясняет метод http запроса и направляет на соответствующий метод реализующей команды, REST
 */
namespace app\Commands\Http;

use app\Commands\Command;
use app\Requests\Http\HttpRequest;
use app\Requests\Request;

abstract class HttpCommand extends Command
{
    /**
     * Общий для всеx команд метод, запускающий выполнение.
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

        //Request сохраняется в свойство, потому что данные из него пригодятся в воркерах
        //Передать напрямую в конструкторе команды воркеру не получится, потому что request не попадает в конустроктор команды
        $this->request = $request;
        $httpMethod    = $request->getHttpMethod();
        $id            = $request->getProperty('id');
        
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

    /**
     * GET all (no id)
     * 
     * @param  app\Requests\Request $request
     * @return app\Response\Response
     */
    protected function index(Request $request)
    {
        $this->voidHttpMethodResponce('GET (index)');
        return $this->response;
    }

    /**
     * GET id
     * 
     * @param  app\Requests\Request $request
     * @return app\Response\Response
     */
    protected function show(Request $request)
    {
        $this->voidHttpMethodResponce('GET (id)');
        return $this->response;
    }

    /**
     * POST
     * Store a newly created resource in storage.
     *
     * @param  app\Requests\Request  $request
     * @return app\Response\Response
     */
    protected function store(Request $request)
    {
        $this->voidHttpMethodResponce('POST (store)');
        return $this->response;
    }

    /**
     * PUT
     * Update the specified resource in storage.
     *
     * @param  app\Requests\Request  $request
     * @return app\Response\Response
     */
    protected function update(Request $request)
    {
        $this->voidHttpMethodResponce('PUT (update)');
        return $this->response;
    }

    /**
     * DELETE
     * Remove the specified resource from storage.
     *
     * @param  app\Requests\Request $request
     * @return app\Response\Response
     */
    protected function destroy(Request $request)
    {
        $this->voidHttpMethodResponce('_DELETE (destroy)');
        return $this->response;
    }

    /**
     * Просто добавляет в объект Response сообщение, что вызванный метод не переопределён в дочернем классе
     * 
     * @param  string $name
     * @return void
     */
    private function voidHttpMethodResponce($name): void
    {
        $refClass = new \ReflectionClass($this);
        $command  = $refClass->getName();
        $this->response->addFeedback('HTTP method ... ' . $name . ' ... в комманде ... ' . $command . ' ... не определён.');
    }
}
