<?php
/**
 * Класс логики работы с пользовательскими текстами. Сохранить, обновить, удалить
 */
namespace app\Workers;

use app\Requests\Request;
use app\Registry\Registry;
use DomainObjectAssembler\DomainModel\UserTextModel;
use DomainObjectAssembler\DomainObjectAssembler;
use DomainObjectAssembler\IdentityMap\ObjectWatcher;

class UserTextWorker
{
    private $assembler  = null;
    private $objWatcher = null;

    public function __construct()
    {
        $this->assembler  = new DomainObjectAssembler('UserText');
        $this->objWatcher = ObjectWatcher::getInstance();
    }

    /**
     * Записать в БД новый пользовательский текст
     * 
     * @param  app\Requests\Request $request
     * 
     * Можно при необходимости вернуть строку ответ из ObjectWatcher
     * @return int
     */
    public function insert(Request $request): int
    {
        //Получить реестр и объект PDO, чтобы выконце узнать id добавленной записи
        $reg = Registry::getInstance();
        $pdo = $reg->getPdo();

        //Подготовить ассоциативный массив для создания модели
        $data['name']        = $request->getProperty('name');
        $data['text']        = $request->getProperty('text');
        $data['user_themes'] = $request->getProperty('themeId');
        $data['user_id']     = $_SESSION["auth_subsystem"]["user_id"];
        
        //Создание объекта модели, сохранение его в архив ObjectWatcher
        $this->assembler->createNewModel($data);

        //Поскольку id нет, модель была помечена как addNew при создании. Запуск очередей на выполнение
        $message = $this->objWatcher->performOperations();
        $textId  = $pdo->lastInsertId();

        return $textId;
    }

    /**
     * Обновить в БД уже существующий пользовательский текст
     * 
     * @param  app\Requests\Request $request
     * 
     * Можно при необходимости вернуть строку ответ из ObjectWatcher
     * @return int
     */
    public function update(Request $request)
    {
        //Подготовить ассоциативный массив для создания модели
        $data['id']          = $request->getProperty('id');
        $data['name']        = $request->getProperty('name');
        $data['text']        = $request->getProperty('text');
        $data['user_themes'] = $request->getProperty('themeId');
        $data['user_id']     = $_SESSION["auth_subsystem"]["user_id"];

        // Создание объекта модели, сохранение его в архив ObjectWatcher, пометить как Dirty
        $model = $this->assembler->createNewModel($data);
        $model->markDirty();

        //Запуск очередей на выполнение
        $message = $this->objWatcher->performOperations();

        return $data['id'];
    }

    /**
     * Удалить текст по id, для этого создаётся модель-пустышка с id и добавляется в очередь на удаление
     * 
     * @param  int $id
     * 
     * Может при необходимости вернуть строку ответ из ObjectWatcher
     * @return void|string
     */
    public function delete(int $id)
    {
        //Подготовить ассоциативный массив для создания модели
        $data['id']      = $id;
        $data['user_id'] = $_SESSION["auth_subsystem"]["user_id"];

        //Создать модель, пометить как Deleted
        $model = $this->assembler->createNewModel($data);
        $model->markDeleted();

        //Запуск очередей на выполнение
        $message = $this->objWatcher->performOperations();

        return $message;
    }
}
