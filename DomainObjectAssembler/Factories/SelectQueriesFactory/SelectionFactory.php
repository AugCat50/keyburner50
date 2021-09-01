<?php
/**
 * Фабрика подготовки SQL оператора SELECT
 * ["SELECT id, name FROM default_text WHERE hidden = ?", $values]
 */
namespace DomainObjectAssembler\Factories\SelectQueriesFactory;

use DomainObjectAssembler\IdentityObject\IdentityObject;

// abstract class SelectionFactory
class SelectionFactory
{
    /**
     * На случай, если SELECT будут как-то сильно отличаться: 
     * 
     * В классе определен общедоступный интерфейс в форме абстрактного
     * класса. В методе newSelection() ожидается объект типа IdentityObject,
     * который требуется также во вспомогательном методе buildWhere(), но он
     * должен быть локальным по отношению к текущему типу
     */
    // abstract public function newSelection(IdentityObject $obj): array;

    /**
     * В этом классе создается основа оператора SQL, а затем вызывается метод
     * buildWhere(), чтобы ввести в этот оператор условное предложение.
     * 
     * @param DomainObjectAssembler\IdentityObject\IdentityObject $obj
     * 
     * @return array
     */
    public function newSelection(IdentityObject $obj): array
    {
        //Обращаемся к IdentityObject\Field чтобы узнать какие поля надо и можно получить
        $fields    = implode(", ", $obj->getObjectFields());
        $tableName = $obj->getTableName();
        $core      = "SELECT $fields FROM $tableName";

        list($where, $values) = $this->buildWhere($obj);

        //Получается массив готовый для prepare, типа:
        //[0] => "SELECT id, name, text, hidden FROM default_texts WHERE name = ? AND id = ?"
        //[1] => [0] => 'имя', [1] => int(4)
        return [$core . " " . $where, $values];
    }

    /**
     * В самом методе buildWhere() вызывается метод IdentityObject::getComps() с целью получить данные, 
     * требующиеся для создания предложения WHERE, а также составить список значений, причем и то и другое возвращается в двухэлементном массиве.
     * 
     * Для более сложных запросов логика требует доработки. NOT возможно работает не до конца верно, надо контролировать, если применяется
     * 
     * @param DomainObjectAssembler\IdentityObject\IdentityObject $obj
     * 
     * @return array
     */
    public function buildWhere(IdentityObject $obj): array
    {
        if ($obj->isVoid()) {
            return [ '"', [] ] ;
        }

        $compstrings = [];
        $values      = [] ;

        $array = $obj->getComps();
        $count = count($array);

        //Посмотреть массив полей. Для тестирования
        // d($obj->showFieldsArray());

        for($i=0, $not = false; $i<$count; $i++){
            $comp = $array[$i];

            //Анализ наличия у поля Field логического оператора, и добавление его в строку запроса
            if (
                $comp['connective'] == null && $count==1 
                || 
                $comp['connective'] !== 'NOT' && $i==($count-1)
                ) {

                //Если логический оператор null и массив из одного условия 
                //или 
                //если логический оператор не NOT и последний элемент массива
                $compstrings[] = $comp['name']. $comp['operator']. '?';
                $values     [] = $comp['value'];

            } else if ($comp['connective'] === 'NOT') {

                //Если логический оператор NOT, добавляем в начало строки
                $compstrings[] = ' NOT '. $comp['name']. $comp['operator']. '?';
                $values     [] = $comp['value'];

            } else if ($comp['connective'] === 'OR' && $i!=($count-1)) {

                //Если логический оператор OR и не последний элемент массива
                $compstrings[] = $comp['name']. $comp['operator']. '?'. ' OR ';
                $values     [] = $comp['value'];

            } else if ($comp['connective'] === 'AND' && $i!=($count-1)) {

                //Если логический оператор AND и не последний элемент массива
                $compstrings[] = $comp['name']. $comp['operator']. '?'. ' AND ';
                $values     [] = $comp['value'];

            }
        }

        $where = "WHERE " . implode(' ', $compstrings);
        //Как было в простом варианте:
        // $where = "WHERE " . implode(" AND ", $compstrings);
        return [$where, $values];
    }
}
