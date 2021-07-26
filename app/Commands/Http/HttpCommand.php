<?php 
/**
 * Комманда для обработки http запросов
 */
namespace app\Commands\Http;

use app\Commands\Command;
use app\Requests\Http\HttpRequest;
use app\Requests\Request;

abstract class HttpCommand extends Command
{
    abstract public function index  (Request $request);
    abstract public function store  (Request $request);
    abstract public function update (Request $request);
    abstract public function destroy(Request $request);

    public function execute(Request $request)
    {
        if (! $request instanceof HttpRequest) {
            throw new \Exception('HttpCommand(21): HTTP команде должен быть передан объект HttpRequest. Получен - '. get_class($request));
        }

        $httpMethod = $request->getHttpMethod();

        switch ($httpMethod) {
            case 'GET':
                return $this->index($request);
                break;
            case 'POST':
                $this->store($request);
                break;
            case 'PUT':
                $this->update($request);
                break;
            case 'DELETE':
                $this->destroy($request);
                break;
        }
    }
}