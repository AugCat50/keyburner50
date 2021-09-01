<?php
/**
 * Фабрика DefaultTextModel
 */
namespace DomainObjectAssembler\Factories\DomainObjectFactory;

use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\DomainModel\DefaultTextModel;

class DefaultTextObjectFactory extends DomainObjectFactory
{
    /**
     * Получить имя класса обрабатываемой этой фабрикой модели
     * 
     * @return string
     */
    protected function targetClass(): string
    {
        return DefaultTextModel::class;
    }

    /**
     * Создать объект модели соответствующей фабрике
     * 
     * Ассоциативный массив, где ключи - это имена полей
     * @param  array $raw
     * 
     * @return DomainObjectAssembler\DomainModel\DefaultTextModel
     */
    protected function doCreateObject(array $raw): DomainModel
    {
        $model = new DefaultTextModel( 
            (int)    $raw['id'],
            (string) $raw['name'],
            (string) $raw['text'],
            (bool)   $raw['hidden']
        );

        return $model;
    }
}
