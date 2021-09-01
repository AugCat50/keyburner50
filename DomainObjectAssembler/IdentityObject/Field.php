<?php
/**
 * Класс поля таблицы БД, хранит имя поля и настройки для добавления поля в SQL запрос. 
 * Объекты полей сохраняются в массив в IdentityObject
 */
namespace DomainObjectAssembler\IdentityObject;

class Field
{
    protected $name  = null;
    protected $comps = [];

    // protected $incomplete = false;

    /**
     * Задать имя поля (например, 'age')
     * 
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Ввести операцию и значение в массив настроек объекта поля (для составления запроса в будущем)
     * 
     * Пример заполнения массива: ['id', ' = ', 12, null]
     * 'connective' - заранее подготовленная ячейка для логического оператора. null - логического оператора для поля нет
     * 
     * @param string $operator
     * @param mixed  $value
     * 
     * @return void
     */
    public function addTest(string $operator, $value)
    {
        $this->comps[] = [
            'name'       => $this->name,
            'operator'   => $operator,
            'value'      => $value,
            'connective' => null
        ];
    }

    /**
     * Сохраняет в массив настроек строку логического оператора
     * 
     * Строка логического оператора (например 'NOT')
     * @param  string
     * 
     * @return void
     */
    public function addLogicalConnective(string $logicalConnective)
    {
        $i = count($this->comps) - 1;
        $this->comps[$i]['connective'] = $logicalConnective;
    }

    /**
     * Получить массив настроек поля
     * 
     * @return array
     */
    public function getComps(): array
    {
        return $this->comps;
    }

    /**
     * Проверить, заполнены ли настройки поля
     * 
     * Если массив $comps не содержит элементы, 
     * это означает, что данные сравнения с полем и
     * само поле не готовы для применения в запросе
     * 
     * @return bool
     */
    public function isIncomplete(): bool
    {
        return empty($this->comps);
    }
}
