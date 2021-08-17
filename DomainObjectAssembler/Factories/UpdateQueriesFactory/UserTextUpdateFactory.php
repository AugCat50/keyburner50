<?php
namespace DomainObjectAssembler\Factories\UpdateQueriesFactory;

use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\DomainModel\UserTextModel;

class UserTextUpdateFactory extends UpdateFactory
{
    /**
     * В методе newUpdate() извлекаются данные, необходимые для формирования запроса. 
     * Это процесс, посредством которого данные объекта преобразуются в информацию для базы данных.
     * 
     * @param DomainObjectAssembler\DomainModel\UserTextModel $obj
     * 
     * @return array
     */
    public function newUpdate(DomainModel $obj): array
    {
        //проверка типов
        if(! $obj instanceof UserTextModel){
            throw new \Exception('UserTextUpdateFactory(21): Oбъект должен быть типа: '. UserTextModel::class . ' ---- Получен: '. get_class($obj));
        }

        $id = $obj->getId();

        $dirty = $obj->getDirtyFields();
        
        //Если массив полей для обновления пуст, то обновляем все поля (кроме статистики)
        //Это данные для INSERT
        if( isset($dirty)){
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

        if ($id > -1) {
            $cond['id'] = $id;
        }

        return $this->buildStatement("user_texts", $fields, $cond);
    }
}
