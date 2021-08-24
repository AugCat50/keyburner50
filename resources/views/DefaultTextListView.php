<?php
/**
 * Класс для отображения страницы index.php/
 * Выводит в шаблон список дефолтных текстов
 */
namespace resources\views;

use app\Response\Response;

class DefaultTextListView extends View
{
    public function print(Response $response)
    {
        $err = $this->debug($response);

        $array = $response->getFeedback();
        $str = null;

        foreach ($array as $value) {
            $str .= "<li class='default-text-list__name blue-neon js_default-text-list__name' data-id='". $value['id']. "' name='". $value['name']. "'>
                        <span class='pointer'>&#187; </span><span class='js_value'>". 
                            $value['name']. 
                        "</span>
                    </li>";

        }
        
        $str = $err. $str;

        //Выводим html страницы index и переменную $str в нём
        require('templates/index.php');
    }
}
