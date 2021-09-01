<?php
/**
 * Фабрика TempModel
 */
namespace DomainObjectAssembler\Factories\DomainObjectFactory;

use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\DomainModel\TempModel;

class TempObjectFactory extends DomainObjectFactory
{
    /**
     * Получить имя класса обрабатываемой этой фабрикой модели
     * 
     * @return string
     */
    protected function targetClass(): string
    {
        return TempModel::class;
    }

    /**
     * Создать объект модели соответствующей фабрике
     * 
     * Ассоциативный массив, где ключи - это имена полей
     * @param  array $raw
     * 
     * @return DomainObjectAssembler\DomainModel\TempModel
     */
    protected function doCreateObject(array $raw): DomainModel
    {
        $model = new TempModel( 
            (int)    $raw['id'],
            (int)    $raw['user_id'],
            (string) $raw['key_act'],
            (string) $raw['mail']
        );

        return $model;
    }
}
