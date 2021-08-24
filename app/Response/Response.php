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
     * Имя класса шаблона без слова View. По умолчанию 'Simple' (SimpleView.php)
     * 
     * @var string
     */
    private $view = 'Simple';

    /**
     * Массив, содержащий feedback на запрос
     * 
     * @var array
     */
    protected $feedback = [];

    protected $error = null;

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
     * Получить массив Feedback без изменений и обработки
     * 
     * @return array
     */
    public function getFeedback(): array
    {
        return $this->feedback;
    }

    /**
     * Получить Feedback на запрос в виде строки
     * 
     * @return string
     */
    public function getFeedbackString($separator = "\n"): string
    {
        return implode($separator, $this->feedback);
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
