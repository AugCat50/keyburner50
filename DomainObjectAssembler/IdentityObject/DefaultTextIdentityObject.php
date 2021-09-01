<?php
/**
 * Объект идентичности для тектов по умолчанию
 */
namespace DomainObjectAssembler\IdentityObject;

class DefaultTextIdentityObject extends IdentityObject
{
    /**
     * Таблица 'default_texts'
     * Разрешённые поля ['id', 'name',  'text', 'hidden']
     */
    public function __construct(string $field = null)
    {
        parent::__construct(
            $field,
            ['id', 'name',  'text', 'hidden'],
            'default_texts'
        );
    }
}