<?php
/**
 * Класс для подготовки объекта модели UserThemeModel к обновлению в БД
 * Формирует SQL строку запроса и массив переменных для запроса
 */
namespace DomainObjectAssembler\Factories\UpdateQueriesFactory;

use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\DomainModel\UserThemeModel;

class UserThemeUpdateFactory extends UpdateFactory
{
    /**
     * В методе newUpdate() извлекаются данные, необходимые для формирования запроса. 
     * Это процесс, посредством которого данные объекта преобразуются в информацию для базы данных.
     * Затем они передаются в protected метод buildStatement(), см суперкласс UpdateFactory.
     * 
     * @param DomainObjectAssembler\DomainModel\UserThemeModel $obj
     * 
     * @return array
     */
    public function newUpdate(DomainModel $obj): array
    {
        //проверка типов
        if(! $obj instanceof UserThemeModel){
            throw new \Exception('>>>>> UserThemeUpdateFactory(26): Oбъект должен быть типа: '. UserThemeModel::class . ' ---- Получен: '. get_class($obj) . ' <<<<<');
        }
        
        $id                = $obj->getId();
        $values['user_id'] = $obj->getUserId();
        $values['name']    = $obj->getName();

        $cond = null;

        //Если id не отрицательный, сохраняем его в условия (UPDATE), иначе условия null (INSERT)
        if ($id > -1) {
            $cond['id'] = $id;
        }

        return $this->buildStatement("user_themes", $values, $cond);
    }
}
