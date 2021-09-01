<?php
/**
 * Фабрика UserThemeModel
 */
namespace DomainObjectAssembler\Factories\DomainObjectFactory;

use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\DomainModel\UserThemeModel;

class UserThemeObjectFactory extends DomainObjectFactory
{
    /**
     * Получить имя класса обрабатываемой этой фабрикой модели
     * 
     * @return string
     */
    protected function targetClass(): string
    {
        return UserThemeModel::class;
    }

    /**
     * Создать объект модели соответствующей фабрике
     * 
     * Ассоциативный массив, где ключи - это имена полей
     * @param  array $raw
     * 
     * @return DomainObjectAssembler\DomainModel\UserThemeModel
     */
    protected function doCreateObject(array $raw): DomainModel
    {
        $model = new UserThemeModel( 
            (int)    $raw['id'],
            (int)    $raw['user_id'],
            (string) $raw['name']
        );

        return $model;
    }
}
