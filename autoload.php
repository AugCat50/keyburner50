<?php

/**
 * Функция автозагрузки классов 
 * Надо посмотреть как сделать Exception
 */
$myAutoload = function (string $classname) {
    $vendor = __DIR__ . "/vendor/autoload.php";
    if (file_exists($vendor)) {
        require_once(__DIR__ . "/vendor/autoload.php");
    }

    $myClass = __DIR__ .  "/$classname.php";
    if (file_exists($vendor)) {
        require_once(__DIR__ .  "/$classname.php");
    }
};

spl_autoload_register($myAutoload);
