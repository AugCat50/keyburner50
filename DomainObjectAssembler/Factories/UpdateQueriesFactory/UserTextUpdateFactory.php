<?php
/**
 * Класс для подготовки объекта модели UserTextModel к обновлению в БД
 * Формирует SQL строку запроса и массив переменных для запроса
 */
namespace DomainObjectAssembler\Factories\UpdateQueriesFactory;

use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\DomainModel\UserTextModel;

class UserTextUpdateFactory extends UpdateFactory
{
    /**
     * В методе newUpdate() извлекаются данные, необходимые для формирования запроса. 
     * Это процесс, посредством которого данные объекта преобразуются в информацию для базы данных.
     * Затем они передаются в protected метод buildStatement(), см суперкласс UpdateFactory.
     * 
     * @param DomainObjectAssembler\DomainModel\UserTextModel $obj
     * 
     * @return array
     */
    public function newUpdate(DomainModel $obj): array
    {
        //проверка типов
        if(! $obj instanceof UserTextModel){
            throw new \Exception('>>>>> UserTextUpdateFactory(26): Oбъект должен быть типа: '. UserTextModel::class . ' ---- Получен: '. get_class($obj) . ' <<<<<');
        }

        $id = $obj->getId();

        //Массив грязных полей (статистика)
        $dirty = $obj->getDirtyFields();

        //Если массив грязных полей для обновления пуст, то обновляем все поля (кроме статистики)
        //Это данные для INSERT
        if(empty($dirty)){
            $fields['user_id']     = $obj->getUserId();
            $fields['user_themes'] = $obj->getUserThemes();
            $fields['name']        = $obj->getName();
            $fields['text']        = $obj->getText();
        } else {
            //Если есть массив грязных полей, то заполняем их на обработку
            foreach ($dirty as $field) {
                $name   = $field[0];
                $method = $field[1];

                $fields[$name] = $obj->{$method}();;
            }
        }

        $cond = null;

        //Если id не отрицательный, сохраняем его в условия (UPDATE), иначе условия null (INSERT)
        if ($id > -1) {
            $cond['id'] = $id;
        }

        return $this->buildStatement("user_texts", $fields, $cond);
    }
}
