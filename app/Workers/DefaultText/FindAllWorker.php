<?php

/**
 * Получить из БД все тексты и вернуть в виде коллекции
 */

namespace app\Workers\DefaultText;

use app\Commands\Command;
use app\Workers\Worker;
use DomainObjectAssembler\DomainObjectAssembler;

class FindAllWorker extends Worker
{
    public function doUpdate(Command $command): void
    {
        $this->find($command);
    }

    /**
     * Получить коллекцию всех default текстов из базы данных
     * Создаётся ассемблер, настраивается объект идентификации (WHERE hidden = false), производится запрос.
     * 
     * @return DomainObjectAssembler\Collections\DefaultTextCollection
     */
    public function find(Command $command): void
    {
        $assembler      = new DomainObjectAssembler('DefaultText');
        $identityObject = $assembler->getIdentityObject()->field('hidden')->eq(false);
        $collection     = $assembler->find($identityObject);

        //Суррогат return. Жертва меньшей связанности, я так понимаю, ноль идей как иначе возвращать информацию.
        $command->setAnswer($collection);

        // return $collection;
    }
}
