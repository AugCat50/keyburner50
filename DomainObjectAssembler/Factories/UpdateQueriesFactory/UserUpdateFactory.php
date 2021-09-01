<?php
/**
 * Класс для подготовки объекта модели UserModel к обновлению в БД
 * Формирует SQL строку запроса и массив переменных для запроса
 */
namespace DomainObjectAssembler\Factories\UpdateQueriesFactory;

use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\DomainModel\UserModel;

class UserUpdateFactory extends UpdateFactory
{
    /**
     * В методе newUpdate() извлекаются данные, необходимые для формирования запроса. 
     * Это процесс, посредством которого данные объекта преобразуются в информацию для базы данных.
     * Затем они передаются в protected метод buildStatement(), см суперкласс UpdateFactory.
     * 
     * @param DomainObjectAssembler\DomainModel\UserModel $obj
     * 
     * @return array
     */
    public function newUpdate(DomainModel $obj): array
    {
        //проверка типов
        if(! $obj instanceof UserModel){
            throw new \Exception('>>>>> UserUpdateFactory(26): Oбъект должен быть типа: '. UserModel::class . ' ---- Получен: '. get_class($obj) . ' <<<<<');
        }
        
        $id                 = $obj->getId();
        $values['name']     = $obj->getName();
        $values['password'] = $obj->getPassword();
        $values['solt']     = $obj->getSolt();
        $values['mail']     = $obj->getMail();

        $cond = null;

        //Если id не отрицательный, сохраняем его в условия (UPDATE), иначе условия null (INSERT)
        if ($id > -1) {
            $cond['id'] = $id;
        }

        return $this->buildStatement("users", $values, $cond);
    }
}
