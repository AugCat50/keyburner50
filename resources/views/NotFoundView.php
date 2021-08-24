<?php
/**
 * Класс для отображения страницы 404, в случае, если не обнаружено соотвествие роут - комманда
 */
namespace resources\views;

use app\Response\Response;

class NotFoundView extends View
{
    public function print(Response $response)
    {
        $err = $this->debug($response);
        $msg = '<section class="not-found-section"><h1 class="not-found pink-neon">404</h1>';
        $msg .= $response->getFeedbackString('<br>');
        $msg .= '<a class="error green-neon" href="http://94.244.191.245/keyburner50/index.php">На главную</a>';
        echo $msg. $err. '</section>';
    }
}
