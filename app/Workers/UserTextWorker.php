<?php
/**
 * Класс для логики работы с пользовательскими темами
 */
namespace app\Workers;

use app\Requests\Request;
use app\Registry\Registry;
use DomainObjectAssembler\DomainModel\UserTextModel;
use DomainObjectAssembler\IdentityMap\ObjectWatcher;

class UserTextWorker
{
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

    public function delete(int $id)
    {
        $model      = new UserTextModel($id, 0, 0, '', '');
        $objWatcher = ObjectWatcher::getInstance();
        $objWatcher::addDelete($model);
        $objWatcher->performOperations();
    }
}
