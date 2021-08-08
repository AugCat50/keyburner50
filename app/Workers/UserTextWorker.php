<?php
/**
 * Класс для логики работы с пользовательскими темами
 */
namespace app\Workers;

use app\Requests\Request;
use app\Registry\Registry;
use app\Response\Response;
use DomainObjectAssembler\DomainModel\UserTextModel;
use DomainObjectAssembler\IdentityMap\ObjectWatcher;

class UserTextWorker
{
    /**
     * Записать в БД новый текст
     */
    public function insert(Request $request): int
    {
        $reg         = Registry::getInstance();
        $pdo         = $reg->getPdo();
        $objWatcher  = ObjectWatcher::getInstance();
        
        $name    = $request->getProperty('name');
        $text    = $request->getProperty('text');
        $themeId = $request->getProperty('themeId');
        new UserTextModel(-1, $_SESSION["auth_subsystem"]["user_id"], $themeId, $name, $text);
        $objWatcher->performOperations();

        $textId = $pdo->lastInsertId();

        return $textId;
    }

    /**
     * Обновить в БД уже существующий текст
     */
    public function update(Request $request)
    {
        $id      = $request->getProperty('id');
        $themeId = $request->getProperty('themeId');
        $name    = $request->getProperty('name');
        $text    = $request->getProperty('text');

        // int $id, int $user_id, int $user_themes, string $name, string $text
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

    public function delete(int $id)
    {
        $model      = new UserTextModel($id, 0, 0, '', '');
        $objWatcher = ObjectWatcher::getInstance();
        $objWatcher::addDelete($model);
        $objWatcher->performOperations();
    }
}
