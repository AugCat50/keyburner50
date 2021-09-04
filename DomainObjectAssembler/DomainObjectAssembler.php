<?php
/**
 * Центральный класс предметной области. 
 * 
 * Получает фабрики для работы с заданным видом модели с помощью абстрактной фабрики PersistanceFactory,
 * выполняет работу по выполнению операций с БД: 
 * - Получение коллекции объектов                         - find(), 
 * - Получение модели объекта из БД                       - findOne(),
 * - Получение нового объекта модели по переданным данным - createNewModel(),
 * - Так же >>> Сохранение, Обновление, Удаление строки БД для сообтветствующей модели
 */
namespace DomainObjectAssembler;

use app\Registry\Registry;
use DomainObjectAssembler\Collections\Collection;
use DomainObjectAssembler\DomainModel\DomainModel;
use DomainObjectAssembler\IdentityMap\ObjectWatcher;
use DomainObjectAssembler\IdentityObject\IdentityObject;

class DomainObjectAssembler
{
    private $factory        = null;
    private $statements     = [];
    private $pdo            = null;
    private $identityObject = null;

    /**
     * В конструктор передаётся строка "имя модели", оно должно быть одним из списка разрешённых,
     * из свойства $enforce класса PersistanceFactory >>> ['DefaultText', 'User', 'UserText', 'UserTheme', 'Temp']
     */
    public function __construct(string $modelName)
    {
        $this->factory = new PersistanceFactory($modelName);
        $reg           = Registry::getInstance();
        $this->pdo     = $reg->getPdo();
        
        if(is_null($this->pdo)) {
            throw new \Exception('>>>> DomainObjectAssembler(__construct): получил NULL вместо объекта PDO. <<<<<');
        }
        // $this->factory->getModelFactory();
    }

    /**
     * Получить объект идентичности
     * 
     * Делегируем получение PersistanceFactory, если объекта ещё нет в свойстве identityObject
     */
    public function getIdentityObject(): IdentityObject
    {
        if(is_null($this->identityObject)){
            $this->identityObject = $this->factory->getIdentityObject();
        }
        
        return $this->identityObject;
    }

    /**
     * Создать новый объект модели с данными $raw
     * 
     * @param array $raw
     * 
     * @return DomainObjectAssembler\DomainModel\DomainModel
     */
    public function createNewModel(array $raw = []): DomainModel
    {
        $domainFactory = $this->factory->getModelFactory();
        $model         = $domainFactory->createObject($raw);
        return $model;
    }
    
    /**
     * Получает объект модели по текущему указателю коллекции и увеличивает казатель на 1
     * 
     * По идее, у объекта коллекции уже будет увеличен указатель и в другом месте можно сразу получить модель
     * по следующей строке.
     * 
     * @param  DomainObjectAssembler\IdentityObject\IdentityObject $idObj
     * 
     * @return DomainObjectAssembler\DomainModel\DomainModel
     */
    public function findOne(IdentityObject $idObj): DomainModel
    {
        $collection = $this->find($idObj);
        $model      = $collection->next();
        return $model;
    }

    /**
     * Получает коллекцию по запросу, сконфигурированному объектом идентичности и сгенерированному фабрикой запросов SelectionFactory.
     * 
     * @param  DomainObjectAssembler\IdentityObject\IdentityObject $idobj
     * 
     * @return DomainObjectAssembler\Collections\Collection
     */
    public function find(IdentityObject $idobj): Collection
    {
        try{
            //Получить объект SelectionFactory
            $selfact = $this->factory->getSelectionFactory();

            //Полоучить массив {[0] => 'запрос', [1] => переменные} в объекте SelectionFactory
            //Получается массив готовый для prepare, типа:
            //[0] => "SELECT id, name, text, hidden FROM default_texts WHERE name = ? AND id = ?"
            //[1] => [0] => 'имя', [1] => int(4)
            list ($selection, $values) = $selfact->newSelection($idobj);

            //Для тестирования - Посмотреть запрос и переменные
            // d($selection);
            // d($values);

            //подготовить запрос prepare
            $stmt = $this->getStatement($selection);

            //Выполнить запрос
            $status = $stmt->execute($values);

            //Извлечь массив данных
            $raw = $stmt->fetchAll();
            return $this->factory->getCollection($raw);

        } catch(\Exception $e){
            return '>>>>> DomainObjectAssembler(122): Не удалось получить данные. Текст ошибки: '. $e->getMessage(). ' <<<<<';
        }
    }

    /**
     * Закинуть строку запроса, полученную из фабрики запросов и выполнить pdo::prepare()
     * Кешируем подготовленный запрос в массив statements['строка'] = подготовленный запрос.
     * 
     * @param string $str
     * 
     * @return \PDOStatement
     */
    public function getStatement(string $str): \PDOStatement
    {
        if (! isset($this->statements[$str])) {
            $this->statements[$str] = $this->pdo->prepare($str);
        }
        return $this->statements[$str];
    }

    /**
     * Unit of Work. Добавить модель в очередь ObjectWatcher на сохранение
     * 
     * По сути метод не нужен, поскольку при создании модели new , она автоматически попадает на сохраниенние в конструкторе. 
     * Если такое поведение не устраивает, необходимо в конструкторе DomainModel удалить markNew() и раскомментировать этот метод
     */
    // public function insert(DomainModel $model)
    // {
    //     ObjectWatcher::addNew($model);
    // }

    /**
     * Unit of Work. Добавить модель в очередь ObjectWatcher на обновление
     * Дублирование DomainModel::markDirty() Будет логично удалить этот метод совсем, проверить что он нигде не используется.
     * 
     * @param  DomainObjectAssembler\DomainModel\DomainModel $model
     * 
     * @return void
     */
    public function update(DomainModel $model)
    {
        ObjectWatcher::addDirty($model);
    }

    /**
     * Unit of Work. Добавить модель в очередь ObjectWatcher на удаление
     * Дублирование DomainModel::markDeleted() Будет логично удалить этот метод совсем, проверить что он нигде не используется.
     * 
     * @param  DomainObjectAssembler\DomainModel\DomainModel $model
     * 
     * @return void
     */
    public function delete(DomainModel $model)
    {
        ObjectWatcher::addDelete($model);
    }

    /**
     * Делегирует полномочия методу Update, поскольку оператор INSERT генерирует фабрика Updete в случае, если id модели пуст или -1.
     * Если id модели пуст или -1, значит это новый объект, которого нет в БД.
     * 
     * @param  DomainObjectAssembler\DomainModel\DomainModel $model
     * 
     * @return void|string
     */
    public function doInsert(DomainModel $model)
    {
        return $this->doUpdate($model);
    }

    /**
     * Обновление соответствующей модели строки в БД
     * 
     * @param  DomainObjectAssembler\DomainModel\DomainModel $model
     * 
     * @return void|string
     */
    public function doUpdate(DomainModel $model)
    {
        try{
            $updFactory = $this->factory->getUpdateFactory();

            //Сгенерировать массив ['запрос', [данные]] в фабрике запросов
            $query = $updFactory->newUpdate($model);

            //подготовить запрос prepare
            $stmt = $this->getStatement($query[0]);
    
            //Выполнить запрос
            $stmt->execute($query[1]);
        } catch(\Exception $e){
            return '>>>>> DomainObjectAssembler(211): Не удалось сохранить '. $model->getModelName(). '--- ID: '. $model->getId(). ' Текст ошибки: '. $e->getMessage(). ' <<<<<';
        }
    }

    /**
     * Удаление соответствующей модели строки в БД
     * 
     * id извлекается из модели, после удаления строки из БД, сам объект модели остаётся в системе в Identity Map. 
     * В теории, это может вызвать проблемы, поэтому объект можно попытаться удалить или пометить как удалённый.
     * 
     * @param  DomainObjectAssembler\DomainModel\DomainModel $model
     * 
     * @return void|string
     */
    public function doDelete(DomainModel $model)
    {
        try{
            $id = $model->getId();

            //Чтобы не грузить клиента созданием Identity Object, когда это можно сделать автоматически
            $idObj = $this->getIdentityObject();
            $idObj->field('id')->eq($id);
    
            $delFactory = $this->factory->getDeletionFactory();
            $queryStr   = $delFactory->newDeletion($idObj);
    
            //подготовить запрос prepare
            $stmt = $this->getStatement($queryStr);
    
            //Выполнить запрос
            $stmt->execute();
        }catch(\Exception $e){
            return '>>>>> DomainObjectAssembler(191): Не удалось удалить '. $model->getModelName(). '--- ID: '. $model->getId(). ' Текст ошибки: '. $e->getMessage(). ' <<<<<';
        }
    }

    /**
     * PDO::lastInsertId — Возвращает ID последней вставленной строки или значение последовательности
     * 
     * Замечание:
     * В зависимости от драйвера PDO этот метод может вообще не выдать осмысленного результата, 
     * так как база данных может не поддерживать автоматического инкремента полей или последовательностей.
     * 
     * @return string|false
     */
    public function getLastInsertId(): string|false
    {
        $id = $this->pdo->lastInsertId();
        return $id;
    }
}
