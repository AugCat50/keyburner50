<?php 
/**
 * Комманда для обработки http запросов
 */
namespace app\Commands\Http;

use app\Commands\Command;
use app\Requests\Request;

class DefaultTextHttpCommand extends Command
{
    /**
     * Вызывыть логику приложения тут.
     * 
     * @return void|mixed
     */
    public function execute(Request $request)
    {
        echo "Default Text";
        return false;
    }
}
