<?php 
namespace resources\views;

use app\Response\Response;

class SimpleView extends View
{
    public function print(Response $response)
    {
        $array = $response->getFeedback();

        $str = null;

        foreach ($array as $value) {
            echo $value;

        }
    }
}