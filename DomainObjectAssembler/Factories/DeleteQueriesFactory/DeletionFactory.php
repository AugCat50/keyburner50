<?php
/**
 * Фабрика для подготовки SQL строки запроса на удаление 
 */
namespace DomainObjectAssembler\Factories\DeleteQueriesFactory;

use DomainObjectAssembler\IdentityObject\IdentityObject;

class DeletionFactory
{
    /**
     * Получает объект идентичности, извлекает из него имя обслуживаемой таблицы,
     * имя поля, по которому производится выборка, и условие для него, 
     * извлечённые данные подставляются в строку SQL, строка запроса возвращается на выполнение.
     * 
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
