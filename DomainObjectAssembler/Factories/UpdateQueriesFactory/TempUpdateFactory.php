<?php
/**
 * Класс для подготовки объекта модели TempModel к обновлению в БД
 * Формирует SQL строку запроса и массив переменных для запроса
 */
namespace DomainObjectAssembler\Factories\UpdateQueriesFactory;

use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\DomainModel\TempModel;

class TempUpdateFactory extends UpdateFactory
{
    /**
     * В методе newUpdate() извлекаются данные, необходимые для формирования запроса. 
     * Это процесс, посредством которого данные объекта преобразуются в информацию для базы данных.
     * Затем они передаются в protected метод buildStatement(), см суперкласс UpdateFactory.
     * 
     * @param DomainObjectAssembler\DomainModel\TempModel $obj
     * 
     * @return array
     */
    public function newUpdate(DomainModel $obj): array
    {
        //проверка типов
        if (!$obj instanceof TempModel) {
            throw new \Exception('>>>>> TempUpdateFactory(26): Oбъект должен быть типа: ' . TempModel::class . ' ---- Получен: ' . get_class($obj) . ' <<<<<');
        }

        $id                = $obj->getId();
        $values['user_id'] = $obj->getUserId();
        $values['key_act'] = $obj->getKeyAct();
        $values['mail']    = $obj->getMail();

        $cond = null;

        //Если id не отрицательный, сохраняем его в условия (UPDATE), иначе условия null (INSERT)
        if ($id > -1) {
            $cond['id'] = $id;
        }

        return $this->buildStatement("temps", $values, $cond);
    }
}
