<?php 
namespace app\Workers;

use DomainObjectAssembler\DomainObjectAssembler;
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
     * Возвращает массив моделей пользовательских тем
     */
    public function getUserTexts(UserThemeCollection $collection){
        $assembler = new DomainObjectAssembler('UserText');
        $themes    = [];

        foreach ($collection as $theme) {
            $id = $theme->getId();

            $identityObj        = $assembler->getIdentityObject()
                                                ->field('user_themes')
                                                ->eq($id);
            $userTextCollection = $assembler->find($identityObj);
            $theme->setUserTextCollection($userTextCollection);
            $themes[] = $theme;
        }
        
        return $themes;
    }
}
