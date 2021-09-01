<?php
/**
 * Объект идентичности для аккаунтов, ожидающих активации
 */
namespace DomainObjectAssembler\IdentityObject;

class TempIdentityObject extends IdentityObject
{
    /**
     * Таблица 'temps'
     * Разрешённые поля ['id', 'user_id',  'key_act', 'mail']
     */
    public function __construct(string $field = null)
    {
        parent::__construct(
            $field,
            ['id', 'user_id',  'key_act', 'mail'],
            'temps'
        );
    }
}