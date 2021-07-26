<?php 
namespace resources\views;

use app\Response\Response;

abstract class View 
{
    abstract function print(Response $response);
}
