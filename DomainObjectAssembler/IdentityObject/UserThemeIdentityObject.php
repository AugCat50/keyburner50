<?php
/**
 * Объект идентичности для пользовательских тем
 */
namespace DomainObjectAssembler\IdentityObject;

class UserThemeIdentityObject extends IdentityObject
{
    /**
     * Таблица 'user_themes'
     * Разрешённые поля ['id', 'user_id',  'name']
     */
    public function __construct(string $field = null)
    {
        parent::__construct(
            $field,
            ['id', 'user_id',  'name'],
            'user_themes'
        );
    }
}