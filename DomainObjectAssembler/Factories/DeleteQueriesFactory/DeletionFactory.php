<?php
/**
 * Фабрика для подготовки SQL строки запроса на удаление 
 */
namespace DomainObjectAssembler\Factories\DeleteQueriesFactory;

use DomainObjectAssembler\IdentityObject\IdentityObject;

class DeletionFactory
{
    /**
     * @param DomainObjectAssembler\IdentityObject\IdentityObject $obj
     * 
     * @return string
     */
    public function newDeletion(IdentityObject $obj)
    {
        $compArray = $obj->getComps();
        $condition = implode('', $compArray[0]);
        $tableName = $obj->getTableName();
        $queryStr  = "DELETE FROM $tableName WHERE $condition";
        
        return $queryStr;
    }
}
