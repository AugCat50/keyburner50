<?php
/**
 * Хранилище данных, которые требуется передать на уровень представления
 * 
 * Возвращается в FrontController, который вызывает view
 */
namespace app\Response;

class Response
{
    /**
     * Имя класса шаблона без слова View. По умолчанию 'StringPrint' (StringPrintView.php)
     * Тот же эффект, если свойство пусто, null
     * 
     * @var string
     */
    private $view = 'StringPrint';

    /**
     * Массив, содержащий feedback на запрос
     * 
     * @var array
     */
    private $feedback = [];

    private $error = null;

    /**
     * Можно передать текст ошибки при создании объекта
     */
    public function __construct(string $error = null){
        $this->error = $error;
    }

    /**
     * Добавить $feedback и установить View
     */
    public function setFeedback($feedback = null, string $view = 'Simple'){
        if(! is_null($feedback)){

            if(is_array($feedback)){
                $this->feedback   = $feedback;
            } else {
                $this->feedback[] = $feedback;
            }

        }
        $this->view = $view;
    }

    /**
     * Установить Feedback на запрос, метод нужен чтобы получить одномерный, а не многомерный массив feedback
     * 
     * Если получен массив, присоединяет его к уже существующему массиву feedback
     * Иначе просто сохраняет полученное в ячейку массива feedback
     * 
     * @param string|array $msg
     * 
     * @return void
     */
    public function addFeedback($msg)
    {
        if(is_array($msg)){
            $this->feedback = array_merge($this->feedback, $msg);
        } else{
            $this->feedback[] = $msg;
        }
        
        // array_push($this->feedback, $msg);
    }

    /**
     * Получить массив Feedback без изменений и обработки
     * 
     * @return array
     */
    public function getFeedback(): array
    {
        return $this->feedback;
    }

    /**
     * Добавить feedback по ключу 'key' => 'data'
     */
    public function addKeyFeedback($key, $msg)
    {
        $this->feedback[$key] = $msg;
    }

    /**
     * Получить feedback по ключу 'key'
     * 
     * @return mixed
     */
    public function getKeyFeedback($key)
    {
        if (isset($this->feedback[$key])){
            return $this->feedback[$key];
        }
        return null;
    }

    /**
     * Получить Feedback на запрос в виде строки
     * 
     * @var string $separator
     * 
     * @return string
     */
    public function getFeedbackString(string $separator = "\n", array $array = []): string
    {
        //Если обращение не рекурсивное, устанавливаем $this->feedback
        if(empty($array)) $array = $this->feedback;
        //Если $this->feedback пуст
        if(empty($array)) return implode($separator, $array);

        foreach($array as $val)
            $_array[] = is_array($val)? $this->getFeedbackString($separator, $val) : $val;
        return implode($separator, $_array);
    }

    /**
     * Очистить массив feedback
     * 
     * @return void
     */
    public function clearFeedback()
    {
        $this->feedback = [];
    }

    /**
     * Получить имя шаблона
     * По умолчанию 'Simple' (SimpleView.php)
     * 
     * @return string
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Установить имя шаблона, строка без слова View
     */
    public function setView(string $view)
    {
        $this->view = $view;
    }

    /**
     * Получить сообщение ошибки
     * 
     * @return mixed|string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Записать сообщение ошибки
     * Ограничение на строку можно убрать
     */
    public function setError(string $error)
    {
        $this->error = $error;
    }
}
