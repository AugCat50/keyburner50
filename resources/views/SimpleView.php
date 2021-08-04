<?php 
namespace resources\views;

use app\Response\Response;

class SimpleView extends View
{
    public function print(Response $response)
    {
        $err = $this->debug($response);
        $msg = $response->getFeedbackString('<br>');
        echo $err. $msg;
    }
}