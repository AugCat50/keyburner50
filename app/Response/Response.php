<?php
/**
 * Хранилище данных, которые требуется передать на уровень представления
 * 
 * Возвращается в FrontController, который вызывает view
 */
namespace app\Response;

class Response
{
    private $view = 'Simple';
    /**
     * Массив, содержащий feedback на запрос
     * 
     * @var array
     */
    protected $feedback = [];

    protected $error = null;

    public function __construct(string $error = null){
        $this->error = $error;
    }

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
     * Установить Feedback на запрос. Есть смысл перенести в отдельный класс Response
     * 
     * @param string $msg
     * 
     * @return void
     */
    public function addFeedback($msg)
    {
        array_push($this->feedback, $msg);
    }

    /**
     * Получить Feedback на запрос. Есть смысл перенести в отдельный класс Response
     * 
     * @return array
     */
    public function getFeedback(): array
    {
        return $this->feedback;
    }

    /**
     * Получить Feedback на запрос в виде строки. Есть смысл перенести в отдельный класс Response
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

    public function getView()
    {
        return $this->view;
    }

    public function setView(string $view)
    {
        $this->view = $view;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setError(string $error)
    {
        $this->error = $error;
    }
}
