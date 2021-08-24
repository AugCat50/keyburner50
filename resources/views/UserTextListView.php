<?php
/**
 * Класс для подготовки html для отображения меню с списками пользовательских текстов
 * Подключается напрямую в user.php
 */
namespace resources\views;

use app\Response\Response;

class UserTextListView extends View
{
    public function print(Response $response)
    {
        $err = $this->debug($response);

        //массив
        $userThemes = $response->getKeyFeedback('userThemesArray');
        $count      = count($userThemes);
        
        if(! $count) {
            echo '<p>У вас ещё нет текстов.</p>';
        } else {
            $result = '';
            foreach($userThemes as $themeModel){
                $collection = $themeModel->getUserTextCollection();
                $count      = $collection->getTotal();
                $themeName  = $themeModel->getName();

                $result .= "<ul class='user-text-list js_user-text-list'>
                                <h4 class='user-text-list__head bright-blue-neon'><span class='js_theme-name'>".
                                    $themeModel->getName(). "</span><span> [$count]</span>
                                </h4>
                                <div class='select__wrapper blue-neon-box'>
                                    <span class='select__arrow'>&#9660;</span>
                                    <select class='select js_select' data-area='".$themeName."'>";
                

                foreach ($collection as $textModel) {
                    $id   = $textModel->getId();
                    $name = $textModel->getName();
    
                    $result .= "<option class='user-text-list__name select__option blue-neon js_user-text-name' data-id=".$id." data-area='".$themeName."' name='".$name."'>" . $name . "</option>";
                }
                
                $result .= "</select></div>
                            <button class='user-text-list__button js_copy-theme' theme-name='". $themeModel->getName()."'>
                                <img class='user-text-list__ico' src='http://94.244.191.245/keyburner50/resources/img/copy-icon.png' title='Копировать в поле текста'>
                            </button>
                            <button class='user-text-list__button js_edit-theme' theme-id='". $themeModel->getId()."' theme-name='". $themeModel->getName()."'>
                                <img class='user-text-list__ico' src='http://94.244.191.245/keyburner50/resources/img/edit.png' title='Переименовать тему'>
                            </button>
                            <button class='user-text-list__button js_delete-theme' theme-id='". $themeModel->getId()."' theme-name='". $themeModel->getName()."'>
                                <img class='user-text-list__ico' src='http://94.244.191.245/keyburner50/resources/img/del.png' title='Удалить тему'>
                            </button>
            </ul>";
            }

            $id      = $response->getKeyFeedback('textId');
            $message = $response->getKeyFeedback('message');
            if ($id && $message) {
                //Если есть и id и сообщение
                $result = $id. $message. $result;
            } else if ($message) {
                //При удалении id нет
                $result = $message. $result;
            }

            // echo $result;
            echo $result. $err;
        }
    }
}
