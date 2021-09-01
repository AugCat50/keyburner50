<?php
/**
 * Фабрика UserTextModel
 */
namespace DomainObjectAssembler\Factories\DomainObjectFactory;

use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\DomainModel\UserTextModel;

class UserTextObjectFactory extends DomainObjectFactory
{
    /**
     * Получить имя класса обрабатываемой этой фабрикой модели
     * 
     * @return string
     */
    protected function targetClass(): string
    {
        return UserTextModel::class;
    }

    /**
     * Создать объект модели соответствующей фабрике
     * 
     * Ассоциативный массив, где ключи - это имена полей
     * @param  array $raw
     * 
     * @return DomainObjectAssembler\DomainModel\UserTextModel
     */
    protected function doCreateObject(array $raw): DomainModel
    {
        //При отсутствии каких-либо значений, заполняем их значениями-затычками.
        if(! array_key_exists('id', $raw))              $raw['id']              = -1;
        if(! array_key_exists('user_id', $raw))         $raw['user_id']         = -1;
        if(! array_key_exists('user_themes', $raw))     $raw['user_themes']     = -1;
        if(! array_key_exists('name', $raw))            $raw['name']            = '';
        if(! array_key_exists('text', $raw))            $raw['text']            = '';
        if(! array_key_exists('statistics', $raw))      $raw['statistics']      = '';
        if(! array_key_exists('statistics_best', $raw)) $raw['statistics_best'] = '';

        $model = new UserTextModel( 
            (int)    $raw['id'],
            (int)    $raw['user_id'],
            (int)    $raw['user_themes'],
            (string) $raw['name'],
            (string) $raw['text'],
            (string) $raw['statistics'],
            (string) $raw['statistics_best']
        );

        return $model;
    }
}
