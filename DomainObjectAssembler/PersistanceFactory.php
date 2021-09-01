<?php
/**
 * Абстрактная фабрика, создаёт группу фабрик по имени класса модели. 
 * Предоставляет: 
 * - Фабрику модели DomainObjectFactory
 * - Фабрики запросов (insert & update, select, delete)
 * - Объект идентичности
 * - Возможность получить объект коллекции, с данными или без
 */
namespace DomainObjectAssembler;

use DomainObjectAssembler\Collections\Collection;
use DomainObjectAssembler\IdentityObject\IdentityObject;
use DomainObjectAssembler\Factories\DeleteQueriesFactory\DeletionFactory;
use DomainObjectAssembler\Factories\SelectQueriesFactory\SelectionFactory;

class PersistanceFactory
{
    /**
     * Список существующих в системе моделей, без приставки Model
     * 
     * @var array
     */
    private $enforce = ['DefaultText', 'User', 'UserText', 'UserTheme', 'Temp'];

    /**
     * Имя модели, без присавки Model. См $enforce
     * 
     * @var string
     */
    private $modelClass;

    /**
     * В конструктор передаётся строка имя модели, без присавки Model. См $enforce
     * 
     * @param string $modelName
     */
    public function __construct(string $modelName)
    {
        if(! in_array($modelName, $this->enforce)){
            $str = implode(', ', $this->enforce);
            throw new \Exception(">>>>> PersistanceFactory(42): имя модели $modelName отсутствует в списке разрешённых: $str <<<<<");
        }
        
        $this->modelClass = $modelName;
    }

    /**
     * Получить объект идентичности соответствующий заданному классу модели
     * 
     * @return DomainObjectAssembler\IdentityObject\IdentityObject
     */
    public function getIdentityObject(): IdentityObject
    {
        $className = 'DomainObjectAssembler\IdentityObject\\' . $this->modelClass .'IdentityObject';
        return $this->reflection($className);
    }

    /**
     * Получить объект фабрики модели
     * 
     * @return PersistanceFactory\DomainObjectFactory\DomainObjectFactory
     */
    public function getModelFactory()
    {
        $className = 'DomainObjectAssembler\Factories\DomainObjectFactory\\' . $this->modelClass .'ObjectFactory';
        return $this->reflection($className);
    }

    /**
     * Получить объект фабрики SELECT запросов
     * 
     * @return PersistanceFactory\SelectQueriesFactory\SelectQueriesFactory
     */
    public function getSelectionFactory()
    {
        return new SelectionFactory();
    }

    /**
     * Получить объект фабрики UPDATE и INSERT запросов
     * 
     * @return PersistanceFactory\UpdateQueriesFactory\UpdateQueriesFactory
     */
    public function getUpdateFactory()
    {
        $className = 'DomainObjectAssembler\Factories\UpdateQueriesFactory\\' . $this->modelClass .'UpdateFactory';
        return $this->reflection($className);
    }

    /**
     * Получить объект фабрики DELETE запросов
     * 
     * @return PersistanceFactory\DeleteQueriesFactory\DeleteQueriesFactory
     */
    public function getDeletionFactory()
    {
        return new DeletionFactory();
    }

    /**
     * Получить коллекцию, сооттветствующую заданной модели
     * 
     * Данные для сбора объектов модели в коллекции. Может вызываться без данных, будет пустая коллекция
     * @param  array $raw
     * 
     * @return DomainObjectAssembler\Collections\Collection
     */
    public function getCollection(array $raw =[]): Collection
    {
        $className = 'DomainObjectAssembler\Collections\\' . $this->modelClass .'Collection';
        
        $class        = new \ReflectionClass($className);
        $modelFactory = $this->getModelFactory();
        $collection   = $class->newInstance($raw, $modelFactory);
        return $collection;
    }

    /**
     * Получает полное имя класса, возвращает его объект
     * 
     * @param  string $className
     * 
     * @return object
     */
    private function reflection(string $className)
    {
        $class   = new \ReflectionClass($className);
        $factory = $class->newInstance();

        return $factory;
    }
}
