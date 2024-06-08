<!-- Страница для обработки запроса активации из письма -->
<!DOCTYPE html>
<html lang="ru" class="html">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Тренажёр слепого набора">
        <meta name="keywords" content="Keyburner, Тренажёр слепого набора">
        <meta name="autor" content="draackul2@gmail.com">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Keyburner - Тренажёр слепого набора</title>
        
        <link rel="shortcut icon" type="image/jpeg" href="resources/img/favicon.jpg"/>
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap&subset=cyrillic" rel="stylesheet"> 
        <link rel="stylesheet" type="text/css" href="http://176.36.150.88/keyburner50/resources/css/normalize/normalize.css">
        <link rel="stylesheet" type="text/css" href="http://176.36.150.88/keyburner50/resources/css/common.blocks/body/body.css">
        <link rel="stylesheet" type="text/css" href="http://176.36.150.88/keyburner50/resources/css/common.blocks/main-wrapper/main-wrapper.css">
        <link rel="stylesheet" type="text/css" href="http://176.36.150.88/keyburner50/resources/css/common.blocks/main-header/main-header.css">
        <link rel="stylesheet" type="text/css" href="http://176.36.150.88/keyburner50/resources/css/common.blocks/main/main.css">
        <link rel="stylesheet" type="text/css" href="http://176.36.150.88/keyburner50/resources/css/common.blocks/statistics-section/statistics-section.css">
        <link rel="stylesheet" type="text/css" href="http://176.36.150.88/keyburner50/resources/css/common.blocks/default-text-list/default-text-list.css">
        <link rel="stylesheet" type="text/css" href="http://176.36.150.88/keyburner50/resources/css/common.blocks/footer/footer.css">
        <link rel="stylesheet" type="text/css" href="http://176.36.150.88/keyburner50/resources/css/common.blocks/html/html.css">
        <link rel="stylesheet" type="text/css" href="http://176.36.150.88/keyburner50/resources/css/common.blocks/neon/neon.css">
        <link rel="stylesheet" type="text/css" href="http://176.36.150.88/keyburner50/resources/css/common.blocks/button/button.css">
        <link rel="stylesheet" type="text/css" href="http://176.36.150.88/keyburner50/resources/css/common.blocks/dialog/dialog.css">
    </head>
    
    <body class="body">
        <div class="body__size">
            <div class="main-wrapper activation-main">
                <p class="activation-answer bright-blue-neon">
                    <?php
                        //Подключение дебаг функции
                        require_once('functions/d.php');
                        //Подключение автозагрузчика
                        require_once('autoload.php');

                        //Точка входа
                        FrontController::run();
                    ?>
                </p>
                <a class="activation-main__button button pink-neon pink-neon-box" href="user.php">OK</a>
            </div>
            
            <footer class="footer">
                <details class="details">
                    <summary>&copy; AugCat50</summary>
                    <p><a href="mailto:draackul2@gmail.com">draackul2@gmail.com</a></p>
                </details>
                <a target="_blank" href="https://www.artstation.com/antoinecollignon">Art by Antoine Collignon</a>
            </footer>
        </div>
    </body>
</html>