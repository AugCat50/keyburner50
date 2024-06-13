<?php

/**
 * 
 */

namespace app\Workers\DefaultText;

use app\Commands\Command;
use app\Workers\Worker;
use DomainObjectAssembler\DomainObjectAssembler;

class FindByIdWorker extends Worker
{
    public function doUpdate(Command $command): void
    {
        $this->findById($command);
    }

    /**
     * Получить модель текста по id
     * 
     * @param  int $id
     * @return DomainObjectAssembler\DomainModel\DefaultTextModel
     */
    public function findById(Command $command): void
    {
        $request = $command->getRequest();
        $id      = $request->getProperty('id');
        
        $assembler      = new DomainObjectAssembler('DefaultText');
        $identityObject = $assembler->getIdentityObject()->field('id')->eq($id);
        $model          = $assembler->findOne($identityObject);

        //Суррогат return. Жертва меньшей связанности, я так понимаю, ноль идей как иначе возвращать информацию.
        $command->setAnswer($model);

        // return $model;
    }
}
