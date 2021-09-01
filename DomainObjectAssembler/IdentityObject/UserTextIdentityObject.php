<?php
/**
 * Объект идентичности для пользовательских тектов
 */
namespace DomainObjectAssembler\IdentityObject;

class UserTextIdentityObject extends IdentityObject
{
    /**
     * Таблица 'user_texts'
     * Разрешённые поля ['id', 'user_id',  'user_themes', 'name', 'text']
     */
    public function __construct(string $field = null)
    {
        parent::__construct(
            $field,
            ['id', 'user_id',  'user_themes', 'name', 'text'],
            'user_texts'
        );
    }
}