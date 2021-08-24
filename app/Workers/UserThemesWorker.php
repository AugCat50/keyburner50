<?php
/**
 * Класс для логики работы с пользовательскими темами
 */
namespace app\Workers;

use app\Registry\Registry;
use DomainObjectAssembler\DomainModel\NullModel;
use DomainObjectAssembler\DomainObjectAssembler;
use DomainObjectAssembler\IdentityMap\ObjectWatcher;
use DomainObjectAssembler\DomainModel\UserThemeModel;
use DomainObjectAssembler\Collections\UserThemeCollection;

class UserThemesWorker
{
    public function find()
    {
        $collection = $this->findThemes();
        $count      = $collection->getTotal();

        //Если коллекция пустая - возвлащаем её во view, иначе получаем тексты
        if(! $count){
            return array();
        } else {
            $arr = $this->getUserTexts($collection);
            return $arr;
        }
    }

    /**
     * Получить коллекцию пользовательских тем по id пользователя
     * 
     * @return DomainObjectAssembler\Collections\UserThemeCollection
     */
    public function findThemes()
    {
        $assembler   = new DomainObjectAssembler('UserTheme');
        $userId      = $_SESSION["auth_subsystem"]["user_id"];
        $identityObj = $assembler->getIdentityObject()
                                    ->field('user_id')
                                    ->eq($userId);
        
        $collection  = $assembler->find($identityObj);

        return $collection;
    }

    /**
     * Получить модель темы по id
     * 
     * @param  int $id
     * @return DomainObjectAssembler\DomainModel\UserThemeModel
     */
    public function findThemeWhereId(int $id)
    {
        $assembler   = new DomainObjectAssembler('UserTheme');
        $identityObj = $assembler->getIdentityObject()
                                    ->field('id')
                                    ->eq($id);
        
        $model  = $assembler->findOne($identityObj);

        return $model;
    }

    /**
     * Возвращает массив моделей пользовательских тем c сохранёнными списками имён текстов, но без самих текстов
     * 
     * @param  DomainObjectAssembler\Collections\UserThemeCollection $collection
     * @return array
     */
    public function getUserTexts(UserThemeCollection $collection){
        $assembler = new DomainObjectAssembler('UserText');
        $themes    = [];

        foreach ($collection as $theme) {
            $id = $theme->getId();

            $identityObj        = $assembler->getIdentityObject()
                                                ->field('user_themes')
                                                ->eq($id);
            $identityObj->setEnforrceFields(['id', 'user_themes', 'name']);
            $userTextCollection = $assembler->find($identityObj);

            $theme->setUserTextCollection($userTextCollection);
            $themes[] = $theme;
        }
        
        return $themes;
    }

    /**
     * Выяснить id модели по имени, если не найден - создать и записать в БД новую тему
     * 
     * @param  string $themeName
     * @return int
     */
    public function getThemeIdWhereName(string $themeName): int
    {
        $reg         = Registry::getInstance();
        $pdo         = $reg->getPdo();
        $objWatcher  = ObjectWatcher::getInstance();
        
        $assembler   = new DomainObjectAssembler('UserTheme');
        $identityObj = $assembler->getIdentityObject()
                                        ->field('name')
                                        ->eq($themeName);
        $modelUsTheme = $assembler->findOne($identityObj);

        //Если тема в БД есть, получаем её id
        if ($modelUsTheme instanceof UserThemeModel) {
            $themeId = $modelUsTheme->getId();
        } else if ($modelUsTheme instanceof NullModel) {
        
            //Если темы нет, записываем как новую в БД
            new UserThemeModel(-1, $_SESSION["auth_subsystem"]["user_id"], $themeName);
            $objWatcher->performOperations();
        
            //После записи в БД получаем id. Или так или делать запрос, как закомменировано ниже.
            $themeId = $pdo->lastInsertId();
        }

        return $themeId;
    }
}
