<?php 
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

        // $array = $response->getFeedback();
        // $str = '<h2 class="default-text-list__head main-header_h2 pink-neon">Результаты поиска:</h2>';

        // foreach ($array as $value) {
        //     $str .= "<li class='default-text-list__name blue-neon js_default-text-list__name' data-id='". $value['id']. "' name='". $value['name']. "'>
        //                 <span class='pointer'>&#187; </span><span class='js_value'>". 
        //                     $value['name']. 
        //                 "</span>
        //             </li>";

        // }

        $count = $collection->getTotal();

        $str = "<h4 class='user-text-list__head bright-blue-neon'>Результаты поиска: [$count]</h4>
                <div class='select__wrapper blue-neon-box'>
                    <span class='select__arrow'>&#9660;</span>
                    <select class='select js_select' data-area='Search'>";
        
        // foreach ($array as $value) {
        //     $str .= "<option class='user-text-list__name select__option blue-neon js_user-text-name' data-id=". $value['id']." data-area='".$themeName."' name='". $value['name']."'>" . $value['name'] . "</option>";
        // }
        
        $i = 1;
        foreach ($collection as $textModel) {
            $id        = $textModel->getId();
            // $themeName = $themeModel->getName();
            $themeName = $textModel->getThemeName();

            //Чтобы сделать имя гарантированно уникальным, добавлена нумирация $i)
            //Имя обязательно должно быть уникальным, на этом завязано получение текста и темы в user_text.js
            // $name      = $i. ') '. $textModel->getName();
            $name      = $textModel->getName();

            $str .= "<option class='user-text-list__name select__option blue-neon js_user-text-name' data-id=". $id." data-area='Search' name='". $name."'>" . $name. " -- ". $themeName. "</option>";
            $i++;
        }

        $str .= "</select></div>";
        
        $str = $err. $str;

        echo $str;
    }
}
