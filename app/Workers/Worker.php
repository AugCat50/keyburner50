<?php

/**
 * Суперкласс Worker, гарантирующий наличие метода doExecute
 */

namespace app\Workers;

use app\Commands\Command;
use app\Observers\CommandObserver;

abstract class Worker extends CommandObserver
{
  abstract public function doUpdate(Command $command): void;
  // {
  //   //Получить состояние, чтобы выяснить какой метод воркера запускать. Состояние просто строка имя метода, в данном случае
  //   $methodName = $command->getStatus();

  //   //Если задано имя конкретного метода воркера - запоускается он. Инчае метод index()
  //   if (method_exists($this, $methodName)) {
  //     $answer = $this->$methodName($command);
  //     $command->setAnswer($answer);
  //   } else {
  //     $answer = $this->index($command);
  //   }
  // }
}
