<?php
/**
 * Класс логики работы с пользовательскими текстами. Сохранить, обновить, удалить
 */
namespace app\Workers;

use app\Requests\Request;
use app\Registry\Registry;
use DomainObjectAssembler\DomainModel\UserTextModel;
use DomainObjectAssembler\IdentityMap\ObjectWatcher;

class UserTextWorker
{
    /**
     * Записать в БД новый пользовательский текст
     * 
     * @param  app\Requests\Request $request
     * @return int
     */
    public function insert(Request $request): int
    {
        $reg        = Registry::getInstance();
        $pdo        = $reg->getPdo();
        $objWatcher = ObjectWatcher::getInstance();
        
        $name    = $request->getProperty('name');
        $text    = $request->getProperty('text');
        $themeId = $request->getProperty('themeId');
        new UserTextModel(-1, $_SESSION["auth_subsystem"]["user_id"], $themeId, $name, $text);
        $objWatcher->performOperations();

        $textId = $pdo->lastInsertId();

        return $textId;
    }

    /**
     * Обновить в БД уже существующий пользовательский текст
     * 
     * @param  app\Requests\Request $request
     * @return int
     */
    public function update(Request $request)
    {
        $id      = $request->getProperty('id');
        $themeId = $request->getProperty('themeId');
        $name    = $request->getProperty('name');
        $text    = $request->getProperty('text');

        // UserTextModel(int $id, int $user_id, int $user_themes, string $name, string $text)
        $model = new UserTextModel(
                        $id, 
                        $_SESSION["auth_subsystem"]["user_id"], 
                        $themeId, 
                        $name, 
                        $text
                    );
        $model->markDirty();

        $objWatcher = ObjectWatcher::getInstance();
        $objWatcher->performOperations();

        return $id;
    }

    /**
     * Удалить текст по id, для этого создаётся модель-пустышка с id и добавляется в очередь на удаление
     * 
     * Может при необходимости вернуть строку ответ из ObjectWatcher.
     * 
     * @param  int $id
     * @return void|string
     */
    public function delete(int $id)
    {
        $model      = new UserTextModel($id, 0, 0, '', '');
        $objWatcher = ObjectWatcher::getInstance();
        $objWatcher::addDelete($model);
        $message    = $objWatcher->performOperations();

        return $message;
    }
}
