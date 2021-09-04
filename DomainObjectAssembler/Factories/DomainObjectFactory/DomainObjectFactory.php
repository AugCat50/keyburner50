<?php
/**
 * Класс занимается созданием объектов модели из сырых данных. Так же взаимодействует с ObjectWatcher
 * 
 * В класс можно также переместить методы addToMap() и getFromMap() или установить взаимосвязь класса
 * ObjectWatcher с методами createObject() по типу наблюдателя. Но не допускайте создание клонов
 * объектов предметной области (моделей), чтобы система не стала неуправляемой!
 * 
 * Шаблон Domain Object Factory отделяет исходные данные, полученные
 * из базы данных, от данных из полей объектов. В теле метода createObject()
 * можно произвести любое количество правок. Этот процесс прозрачен для клиента, обязанность которого — предоставлять исходные данные. 
 * Если убрать эти функциональные возможности из класса Mapper, они станут доступными для других компонентов.
 * 
 * Объекты типа DomainObjectFactory отделены от базы данных, поэтому 
 * их можно более эффективно использовать для тестирования. Например, можно
 * создать имитирующий объект типа DomainObjectFactory, чтобы протестировать исходный код класса Collection. 
 * Сделать это намного проще, чем имитировать целый объект типа Mapper
 */
namespace DomainObjectAssembler\Factories\DomainObjectFactory;

use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\IdentityMap\ObjectWatcher;

abstract class DomainObjectFactory
{
    /**
     * Получить строку название класса модели, которую обслуживает фабрика
     */
    abstract protected function targetClass(): string;

    /**
     * Созадть объект модели из массива сырых данных
     * 
     * Метод просто раскладывает ассоциативный массив с данными в правильм порядке для конструктора модели.
     * Можно проверять наличие и полноту данных, как в UserTextObjectFactory
     */
    abstract protected function doCreateObject(array $raw): DomainModel;

    /**
     * Получить объект модели из массива сырых данных.
     * Возвращает объект из IdentityMap или делегирует создание методу дочерней реализации
     * 
     * Работу с IdentityMap можно разместить здесь или в модели.
     * Пока принял решение, что модель сама будет себя маркировать (addNew, addToMap)
     */
    public function createObject(array $raw): DomainModel
    {
        if(! array_key_exists('id', $raw)) {
            $raw['id'] = -1;
        }

        //Сначала проверить наличие ссылки на объект в ObjectWatcher (IdentityMap)
        //Если таковой уже имеется, возвращаем, если нет, идём дальше
        if ($raw['id'] > 0) {
            $old = $this->getFromMap($raw['id']);
            if (! is_null($old)) {
                return $old;
            }
        }

        //Создать объект
        $obj = $this->doCreateObject($raw);

        //Сохранить ссылку на объект в ObjectWatcher (IdentityMap), если id реальный. Иначе добавить в список на запись в БД
        //В данный момент это делает суперкласс DomainModel
        // if ($raw['id'] > 0) {
        //     $this->addToMap($obj);
        // }
        //  else {
        //     $this->addToNew($obj);
        // }
        
        return $obj;
    }

    /**
     * Получить объект из карты IdentityMap по id
     * 
     * В данном классе предусмотрены два удобных метода — addToMap() и getFromMap(). 
     * Это дает возможность не запоминать полный синтаксис статического обращения к классу ObjectWatcher
     * 
     * @param int $id
     * 
     * Если объект есть, вернёт его, иначе вернёт null
     * @return null|object
     */
    public function getFromMap(int $id)
    {
        return ObjectWatcher::exists( $this->targetClass(), $id );
    }

    /**
     * @see getFromMap()
     * 
     * @param DomainModel $model
     * 
     * @return null
     */
    // public function addToMap(DomainModel $model)
    // {
    //     ObjectWatcher::add($model);
    // }

    // public function addToNew(DomainModel $model)
    // {
    //     ObjectWatcher::addNew($model);
    // }
}
