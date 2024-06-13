<?php

/**
 * Команда, вызываемая в случае отсутствия роута url - command в списке роутов
 */

namespace app\Commands;

use app\Requests\Request;

class NotFoundCommand extends Command
{
    public function execute(Request $request)
    {
        $this->response->setView('NotFound');
        return $this->response;
    }
}
