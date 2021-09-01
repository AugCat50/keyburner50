<?php
/**
 * Объект идентичности для пользователей
 */
namespace DomainObjectAssembler\IdentityObject;

class UserIdentityObject extends IdentityObject
{
    /**
     * Таблица 'users'
     * Разрешённые поля ['id', 'name',  'password', 'solt', 'mail']
     */
    public function __construct(string $field = null)
    {
        parent::__construct(
            $field,
            ['id', 'name',  'password', 'solt', 'mail'],
            'users'
        );
    }
}