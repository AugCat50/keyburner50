<?php 
namespace DomainObjectAssembler\Factories\DomainObjectFactory;

use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\DomainModel\UserTextModel;

class UserTextObjectFactory extends DomainObjectFactory
{
    /**
     * Получить имя обрабатываемой этим маппером модели для проверки
     * Проверка в суперклассе Mapper
     * 
     * @return string
     */
    protected function targetClass(): string
    {
        return UserTextModel::class;
    }

    /**
     * Создать объект модели соответствующей мапперу
     * 
     * Поскольку Ивент находится в самом низу иерархии, коллекцию для него создвать пока не будем
     * 
     * @return DomainModel\EventModel
     */
    protected function doCreateObject(array $raw): DomainModel
    {
        if(! array_key_exists('id', $raw))          $raw['id'] = -1;
        if(! array_key_exists('user_id', $raw))     $raw['user_id'] = -1;
        if(! array_key_exists('user_themes', $raw)) $raw['user_themes'] = -1;
        if(! array_key_exists('name', $raw))        $raw['name'] = '';
        if(! array_key_exists('text', $raw))        $raw['text'] = '';

        $model = new UserTextModel( 
            (int)    $raw['id'],
            (int)    $raw['user_id'],
            (int)    $raw['user_themes'],
            (string) $raw['name'],
            (string) $raw['text']
        );

        return $model;
    }
}
