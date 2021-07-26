<?php 
namespace app\Workers;

use DomainObjectAssembler\Collections\Collection;
use DomainObjectAssembler\DomainObjectAssembler;

class GetDefaultTextWorker
{
    public function findOne(int $id)
    {
        $assembler      = new DomainObjectAssembler('DefaultText');
        $identityObject = $assembler->getIdentityObject()->field('id')->eq($id);
        $model          = $assembler->findOne($identityObject);

        return $model;
    }

    /**
     * Сделать выборку всех текстов из базы данных
     * Создаётся ассемблер, настраивается объект идентификации (WHERE hidden = false), производится запрос.
     * 
     * @return DomainObjectAssembler\Collections\DefaultTextCollection
     */
    public function find(): Collection
    {
        $assembler      = new DomainObjectAssembler('DefaultText');
        $identityObject = $assembler->getIdentityObject()->field('hidden')->eq(false);
        $collection     = $assembler->find($identityObject);

        return $collection;
    }
}
