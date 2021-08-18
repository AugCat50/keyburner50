<?php 
/**
 * Абстрактный класс запроса. Реализации находятся в соответствующих папках Http и Cli, в зависимости от типа запроса.
 * 
 * Централизация операции обработки запросов в одном месте.
 * Объект типа Request может также служить удобным хранилищем данных, которые требуется передать на уровень представления, 
 * хотя для этого обычно применяется класс Response
 */
namespace app\Requests;

abstract class Request
{
    /**
     * Для сохранения параметров запроса, массива $_REQUEST в http
     */
    protected $properties;

    /**
     * Неполный url, часть запроса, являющаяся ключом к команде. Роут, в общем.
     * 
     * @var string
     */
    protected $path = '/';

    public function __construct()
    {
        $this->init();
    }

    /**
     * Метод init() отвечает за наполнение закрытого массива $properties для обработки в дочерних классах
     */
    abstract public function init();

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
    * Установить строку URL запроса, для нахождения соответствующей команды в роутах.
    * Саму строку получают реализующие классы.
    *
    * @param string $key
    *
    * @return string
    */
    protected function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
    * Получить строку URL запроса, для нахождения соответствующей команды в роутах.
    *
    * @return void
    */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Как только будет получен объект типа Request, вы должны обращаться к параметрам HTTP-запроса исключительно с помощью вызова метода 
     * getProperty(). Этому методу передается ключ в виде символьной строки, а 
     * он возвращает соответствующее значение, хранящееся в массиве $properties
     * 
     * @param string $key
     * 
     * @return mixed
     */
    public function getProperty(string $key)
    {
        if (isset($this->properties[$key]) && $this->properties[$key] != '') {
            return $this->properties[$key];
        }

        return null;
    }

    /**
     * Можно ввести дополнительные данные к данным запроса
     * 
     * @param string $key
     * @param mixed  $val
     * 
     * @return void
     */
    public function setProperty(string $key, $val)
    {
        $this->properties[$key] = $val;
    }

}
