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
    private $assembler = null;

    public function __construct()
    {
        $this->assembler = new DomainObjectAssembler('UserTheme');
    }

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
        $userId      = $_SESSION["auth_subsystem"]["user_id"];
        $identityObj = $this->assembler->getIdentityObject()
                                        ->field('user_id')
                                        ->eq($userId);
        
        $collection  = $this->assembler->find($identityObj);

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
        $identityObj = $this->assembler->getIdentityObject()
                                        ->field('id')
                                        ->eq($id);
        
        $model  = $this->assembler->findOne($identityObj);

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
            //Установить список полей, которые требуется получить
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
        
        $identityObj = $this->assembler->getIdentityObject()
                                        ->field('name')
                                        ->eq($themeName);
        $modelUsTheme = $this->assembler->findOne($identityObj);

        //Если тема в БД есть, получаем её id
        if ($modelUsTheme instanceof UserThemeModel) {
            $themeId = $modelUsTheme->getId();
        } else if ($modelUsTheme instanceof NullModel) {
        
            //Если темы нет, записываем как новую в БД
            $data['id']      = -1;
            $data['user_id'] =  $_SESSION["auth_subsystem"]["user_id"];
            $data['name']    = $themeName;

            $this->assembler->createNewModel($data);
            $objWatcher->performOperations();
        
            //После записи в БД получаем id
            $themeId = $pdo->lastInsertId();
        }

        return $themeId;
    }
}
