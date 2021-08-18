<?php
/**
 * Шаблон Identity Object дает программистам клиентского кода возможность определять критерии поиска без ссылки на запрос к базе данных. Он
 * также избавляет от необходимости создавать специальные методы запросов для
 * различных видов операций поиска, которые могут понадобиться пользователю.
 * Одна из целей шаблона Identity Object — оградить пользователей от подробностей реализации базы данных. 
 */
namespace DomainObjectAssembler\IdentityObject;

class IdentityObject
{
    /**
     * currentfield - объект класса Field в текущем namespace
     */
    protected $currentfield = null;
    protected $fields       = [];
    private   $enforce      = [];
    private   $tableName    = null;
    private   $or           = false;

    /**
     * Объект идентичности может быть создан пустым или же с отдельным полем
     * В данном случае, конструктору необходимо передать массив допустимых полей $enforce 
     * и строку с именем таблицы $tableName, для построения запросов в фабриках
     */
    public function __construct(string $field = null, array $enforce = null, string $tableName = null)
    {
        if (! is_null($enforce) ) {
            $this->enforce = $enforce;
        }

        if ( ! is_null($field) ) {
            $this->field($field);
        }

        if ( ! is_null($tableName) ) {
            $this->tableName = $tableName;
        }
    }

    // Имена полей, на которые наложено данное ограничение
    public function getObjectFields()
    {
        return $this->enforce;
    }

    /**
     * На случай, если захочется выбирать не все поля из БД, этим методом можно изменить список полей,
     * захардкоженный в дочерних реализациях в конструкторе. Поля условий тоже должны быть в списке
     * 
     * Передать массив строк имён полей.
     * @param array $enforce
     */
    public function setEnforrceFields(array $enforce)
    {
        $this->enforce = [];
        $this->enforce = $enforce;
    }

    /**
     * Вводит новое поле, генерирует ошибку, если текущее поле ещё неполное. (перед вводимым, предыдущее)
     * Например, если было задано имя, но не был задан оператор сравнения.   (т.е. age, а не age > 40)
     * 
     * Возвращяет ссылку на текущий объект для поддержки текучего интерфейса
     * 
     * @return DomainObjectAssembler\IdentityObject
     */
    public function field(string $fieldname): self
    {
        if (! $this->isVoid() && $this->currentfield->isIncomplete()) {
            throw new \Exception (">>>>> IdentityObject(71): Попытка ввести новое поле до того, как текущее было заполнено! <<<<<");
        }

        //Проверка на допустимость использования поля
        $this->enforceField($fieldname);

        //Пока я пришёл к выводу, что мне могут потребоваться два разных поля с одним именем. Проверку уберу
        // if (isset($this->fields[$fieldname])) {
        //     $this->currentfield=$this->fields[$fieldname];
        // } else {
        //     $this->currentfield       = new Field($fieldname);
        //     $this->fields[$fieldname] = $this->currentfield;
        // }

        $this->currentfield = new Field($fieldname);
        $this->fields[]     = $this->currentfield;

        return $this;
    }

    /**
     * Для целей тестирования, чтобы удобнее было смотреть массив полей
     */
    public function showFieldsArray()
    {
        return $this->fields;
    }

    /**
     * Проверка, имеются ли уже какие-нибудь поля у объекта идентичности?
     * 
     * @return bool
     */
    public function isVoid(): bool
    {
        return empty($this->fields);
    }

    /**
     * Допустимо ли заданное имя поля?
     */
    public function enforceField(string $fieldname)
    {
        if (! in_array($fieldname, $this->enforce) && ! empty($this->enforce) ) {
            $forcelist = implode(', ', $this->enforce);
            throw new \Exception(">>>>> IdentityObject(64): {$fieldname} не является корректным полем ($forcelist) <<<<<");
        }
    }

    // Вводит операцию равенства в текущее поле,
    // например, значение 'age' превращается
    // в значение 'age=40'. Возвращает ссылку на
    // текущий объект через метод operator()
    public function eq($value): self
    {
        return $this->operator(" = ", $value);
    }

    // Операция сравнения "меньше"
    public function lt($value): self
    {
        return $this->operator(" < ", $value);
    }

    // Операция сравнения "больше"
    public function gt($value): self
    {
        return $this->operator(" > ", $value);
    }

    /**
     * Ввести логический оператор LIKE для ТЕКУЩЕГО объекта поля
     * 
     * Поскольку он используется для сравнения с переменными, добавлен как обыный оператор
     */
    public function like($value) : self
    {
        // $this->setLogicalConnective('LIKE');
        // return $this;
        return $this->operator(" LIKE ", $value);
    }

    /**
     * Вводит оператор и сравниваемое значение в текущий объект Field
     */
    private function operator(string $symbol, $value): self
    {
        if ($this->isVoid()) {
            throw new \Exception (">>>>> IdentityObject(118): Не задано ни одного поля (массив объектов полей пуст)  <<<<<");
        }
        $this->currentfield->addTest($symbol, $value);
        return $this;
    }

    /**
     * Ввести логический оператор OR для ТЕКУЩЕГО объекта поля
     */
    public function or()
    {
        $this->setLogicalConnective('OR');
        return $this;
    }

    /**
     * Ввести логический оператор AND для ТЕКУЩЕГО объекта поля
     */
    public function and()
    {
        $this->setLogicalConnective('AND');
        return $this;
    }

    /**
     * Ввести логический оператор NOT для ТЕКУЩЕГО объекта поля
     * 
     * На данный момент возможно не до конца адекватно будет работать в сложных запросах. Надо следить, если применяется.
     * В простых должен работать нормально
     */
    public function not()
    {
        $this->setLogicalConnective('NOT');
        return $this;
    }

    //Это на будущее
    // public function in()
    // {
    //     $this->setLogicalConnective('IN');
    //     return $this;
    // }

    /**
     * Сохраняет установленный логический оператор в текущий объект Field
     */
    public function setLogicalConnective(string $str): self
    {
        if ($this->isVoid()) {
            throw new \Exception (">>>>> IdentityObject(127): Не задано ни одного поля (массив объектов полей пуст)  <<<<<");
        }
        $this->currentfield->addLogicalConnective($str);
        return $this;
    }

    // Возвращает все полученные до сих пор выражения сравнения из ассоциативного массива
    public function getComps(): array
    {
        $ret = [];

        foreach ($this->fields as $field) {
            //Сливает элементы одного или большего количества массивов таким образом, что значения одного массива присоединяются к концу предыдущего.
            //Результатом работы функции является новый массив
            $ret = array_merge($ret, $field->getComps());
        }

        return $ret;
    }

    public function getTableName(): string
    {
        if(! is_null($this->tableName)) {
            return $this->tableName;
        }
        throw new \Exception(">>>>> IdentityObject(132): Имя таблицы, которую обслуживает объект идентификации, не установлено!  <<<<<");    
    }
}
