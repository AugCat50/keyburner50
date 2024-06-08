<?php
/**
 * Класс для отображения данных Response в строковом виде,
 * простых данных по умолчанию, данных, для которых не задан класс View.
 * 
 * На данный момент так же ответчает за вывод DefaultText в textarea. Т.е. отправляет этот текст в js в виде строки.
 * 
 * Скорее всего, это будет текст ошибки, раз дошло до этого вью. Но можно вывести любую строку
 */
namespace resources\views;

use app\Response\Response;

class StringPrintView extends View
{
    /**
     * Сепаратор для функции implode
     * 
     * @var string $separator
     */
    private $separator = '<br>';

    public function print(Response $response): void
    {
        $err = $this->debug($response);

        $msg = $response->getFeedbackString($this->separator);
        echo $err. $msg;
    }

    public function setSeparator(string $s): void
    {
        $this->separator = $s;
    }
}
