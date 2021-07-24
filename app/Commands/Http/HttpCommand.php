<?php 
/**
 * Комманда для обработки http запросов
 */
namespace app\Commands\Http;

use app\Commands\Command;
use app\Requests\Request;

abstract class HttpCommand extends Command
{
    abstract public function index  (Request $request);
    abstract public function store  (Request $request);
    abstract public function update (Request $request);
    abstract public function destroy(Request $request);

    public function execute(Request $request)
    {
        $httpMethod = $request->getHttpMethod();

        switch ($httpMethod) {
            case 'GET':
                $this->index($request);
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