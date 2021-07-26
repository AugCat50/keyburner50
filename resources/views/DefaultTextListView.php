<?php 
namespace resources\views;

use app\Response\Response;

class DefaultTextListView extends View
{
    public function print(Response $response)
    {
        $array = $response->getFeedback();

        $str = null;

        foreach ($array as $value) {
            $str .= "<li class='default-text-list__name blue-neon js_default-text-list__name' data-id='". $value['id']. "' name='". $value['name']. "'>
                        <span class='pointer'>&#187; </span><span class='js_value'>". 
                            $value['name']. 
                        "</span>
                    </li>";

        }
        echo $str;
    }
}
