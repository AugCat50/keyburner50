<?php
/**
 * Команда, вызываемая в случае не обнаружения в роутах соотвествующей комманды
 */
namespace app\Commands;

use app\Requests\Request;

class NotFoundCommand extends Command
{
    public function execute(Request $request){
        return $this->response;
    }
}