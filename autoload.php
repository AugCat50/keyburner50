<?php
/**
 * Функция автозагрузки классов 
 */
spl_autoload_register(function($classname){
    require_once(__DIR__. "/vendor/autoload.php");
    require_once(__DIR__.  "/$classname.php");
});
