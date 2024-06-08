<?php
    //Подключение дебаг функции
    require_once('functions/d.php');
    //Подключение автозагрузчика
    require_once('autoload.php');
    //Старт стессии в index.php, потому что иконка загружается http
    session_set_cookie_params([
        'lifetime' => time() + 86400,
        'path'     => '/',
        'domain'   => $_SERVER['HTTP_HOST'],
        'secure'   => true,
        'httponly' => true,
        'samesite' => 'None'
    ]);

    session_start();
    ob_start();
?>
<!DOCTYPE html>
<html lang="ru" class="html">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Тренажёр слепого набора" >
        <meta name="keywords"    content="Keyburner, Тренажёр слепого набора">
        <meta name="autor"       content="draackul2@gmail.com">
        <title>Keyburner - Тренажёр слепого набора</title>
        <link rel="shortcut icon" type="image/jpeg" href="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/img/favicon.jpg"/>
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap&subset=cyrillic" rel="stylesheet">
        <link rel="stylesheet" href="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/css/normalize/normalize.css">
        <link rel="stylesheet" href="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/css/common.blocks/body/body.css">
        <link rel="stylesheet" href="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/css/common.blocks/main-wrapper/main-wrapper.css">
        <link rel="stylesheet" href="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/css/common.blocks/main-header/main-header.css">
        <link rel="stylesheet" href="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/css/common.blocks/main/main.css">
        <link rel="stylesheet" href="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/css/common.blocks/statistics-section/statistics-section.css">
        <link rel="stylesheet" href="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/css/common.blocks/default-text-list/default-text-list.css">
        <link rel="stylesheet" href="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/css/common.blocks/user-text-list/user-text-list.css">
        <link rel="stylesheet" href="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/css/common.blocks/select/select.css">
        <link rel="stylesheet" href="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/css/common.blocks/footer/footer.css">
        <link rel="stylesheet" href="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/css/common.blocks/html/html.css">
        <link rel="stylesheet" href="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/css/common.blocks/neon/neon.css">
        <link rel="stylesheet" href="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/css/common.blocks/button/button.css">
        <link rel="stylesheet" href="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/css/common.blocks/dialog/dialog.css">
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/jquery-3.5.1.min.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/cookie.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/search.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/const_and_null_var.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/text_replace.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/ajaxQuery.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/ajaxQuery_stat.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/ajaxUser.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/default_text.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/user_text.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/user_text_show.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/user_theme.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/template_textarea.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/work_textarea.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/edit_button.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/page_reload.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/random_default_text.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/random_user_text.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/new_text_button.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/log_in.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/check_in.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/statistics.js"></script>
        <script src="http://<?= $_SERVER['SERVER_ADDR']?>/keyburner50/resources/js/textarea_autosize.js"></script>
    </head>
    <body class="body">
        <div class="body__size">
            <?php
                //Точка входа
                FrontController::run();
            ?>
        </div>
    </body>
</html>