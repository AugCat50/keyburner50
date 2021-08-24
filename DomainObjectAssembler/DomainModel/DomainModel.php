<?php
/**
 * Суперкласс моделей
 * Реализует методы get set для работы с ID,
 * так же реализует делегирующие методы шаблона Unit of Work класса ObjectWatcher,
 * для постановки задач на работу с БД
 */
namespace DomainObjectAssembler\DomainModel;

use DomainObjectAssembler\DomainObjectAssembler;
use DomainObjectAssembler\IdentityMap\ObjectWatcher;

abstract class DomainModel
{
    private $id;
    private $dirtyFieldsArray = null;

    // abstract public function getFinder(): Mapper; --- перемещено в DomainObjectAssembler
    abstract public function getModelName(): string;

    /**
     * Конструктор
     * 
     * В случае создания модели для несуществующей строки в БД,
     * Заданный id игнорируется маппером и создаётся автоматически при выполнении doInsert()
     * 
     * @param int $id
     */
    public function __construct(int $id = -1)
    {
        $this->id = $id;

        //Объект модели регистрируется в ObjectWatcher, если не передан id > 0 - как новый
        //WARNING:: новые объекты могут автоматически попадать на сохранение в базу данных
        if ($id < 0) {
            $this->markNew();
        } else {
            $this->addToMap();
        }
    }

    /**
     * Установить id
     * 
     * @param  int $id
     * @return void
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Получить id
     * 
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Получить объект DomainObjectAssembler с фабриками для соотвествующей модели
     * 
     * @return DomainObjectAssembler\DomainObjectAssembler
     */
    public function getAssembler()
    {
        $modelName = $this->getModelName();
        return new DomainObjectAssembler($modelName);
    }


    //Unit of Work methods
    /**
     * Отметить модель как новую, добавить в очередь на запись в БД
     */
    public function markNew()
    {
        ObjectWatcher::addNew($this);
    }

    /**
     * Отметить модель как удалённую, добавить в очередь на удаление из БД
     */
    public function markDeleted()
    {
        ObjectWatcher::addDelete($this);
    }

    /**
     * Отметить модель как обновлённую, добавить в очередь на UPDATE в БД
     * 
     * Список полей поддерживается только в UserTextUpdateFactory. 
     * Но вообще, при необходимости можно добавить для фабрики любой модели
     * @param array|string $fields
     */
    public function markDirty($fields = null)
    {
        ObjectWatcher::addDirty($this);

        if (isset($fields)){
            if(is_null($this->dirtyFieldsArray)) $this->dirtyFieldsArray = [];
            array_push($this->dirtyFieldsArray, $fields);
        }
    }

    /**
     * Удалить модель из всех очередей
     */
    public function markClean()
    {
        ObjectWatcher::addClean($this) ;
    }

    /**
     * Добавить объект в карту объектов, получив для него уникальный идентификатор.
     * Это может помочь избежать дублирования объектов в системе.
     */
    public function addToMap()
    {
        ObjectWatcher::add($this);
    }

    /**
     * Получить массив грязных полей, сохранённых в модели
     * 
     * Обратите внимание, возвнащаться должен массив или null, но не пустой массив
     * @return null|array
     */
    public function getDirtyFields(){
        if( empty($this->dirtyFieldsArray) ) return null;
        return $this->dirtyFieldsArray;
    }
}
