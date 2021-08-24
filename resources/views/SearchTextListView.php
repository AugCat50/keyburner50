<?php
/**
 * Класс для отображения результатов поиска
 */
namespace resources\views;

use app\Response\Response;

class SearchTextListView extends View
{
    public function print(Response $response)
    {
        $err = $this->debug($response);

        //feedback - массив
        $array      = $response->getFeedback();
        $collection = $array[0];

        $count = $collection->getTotal();

        $str = "<h4 class='user-text-list__head bright-blue-neon'>Результаты поиска: [$count]</h4>
                <button class='saerch-close js_saerch-close'>
                    <img class='user-text-list__ico' src='http://94.244.191.245/keyburner50/resources/img/x.png' title='Закрыть результаты поиска'>
                </button>
                <div class='select__wrapper blue-neon-box'>
                    <span class='select__arrow'>&#9660;</span>
                    <select class='select js_select' data-area='Search'>";
        
        $i = 1;
        foreach ($collection as $textModel) {
            $id        = $textModel->getId();
            $themeName = $textModel->getThemeName();

            //Чтобы сделать имя гарантированно уникальным, добавлена нумирация $i)
            //Имя обязательно должно быть уникальным, на этом завязано получение текста и темы в user_text.js
            $name      = $textModel->getName();

            $str .= "<option class='user-text-list__name select__option blue-neon js_user-text-name' data-id=". $id." data-area='Search' name='". $name."'>" . $name. " -- ". $themeName. "</option>";
            $i++;
        }

        $str .= "</select></div>";
        $str  = $err. $str;

        echo $str;
    }
}
