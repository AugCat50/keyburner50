<?php
/**
 * Фабрика UserModel
 */
namespace DomainObjectAssembler\Factories\DomainObjectFactory;

use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\DomainModel\UserModel;

class UserObjectFactory extends DomainObjectFactory
{
    /**
     * Получить имя класса обрабатываемой этой фабрикой модели
     * 
     * @return string
     */
    protected function targetClass(): string
    {
        return UserModel::class;
    }

    /**
     * Создать объект модели соответствующей фабрике
     * 
     * Ассоциативный массив, где ключи - это имена полей
     * @param  array $raw
     * 
     * @return DomainObjectAssembler\DomainModel\UserModel
     */
    protected function doCreateObject(array $raw): DomainModel
    {
        $model = new UserModel( 
            (int)    $raw['id'],
            (string) $raw['name'],
            (string) $raw['password'],
                     $raw['solt'],
            (string) $raw['mail']
        );

        return $model;
    }
}
