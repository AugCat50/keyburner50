<?php
/**
 * Точка входа для ajax запросов
 */
//Подключение дебаг функции
require_once('functions/d.php');
//Подключение автозагрузчика
require_once('autoload.php');

FrontController::run();
