<?php 
/**
 * Класс запроса для Http
 */
namespace app\Requests\Http;

use app\Requests\Request;

class HttpRequest extends Request
{
    /**
     * Тип http запроса, строка 'GET', 'POST', 'PUT', 'DELETE'
     * Можно заполнить при ajax запросе, чтобы определить действие
     * 
     * @var string
     */
    private $method;

    /**
     * Проверку типа запроса можно было сделать здесь, но я решил передавать информацию в HttpCommand
     * и принимать решение по обработке там.
     * 
     * @see app\Commands\Command\HttpCommand::execute()
     * 
     * @return void
     */
    public function init()
    {
        $this->properties = $_REQUEST;
        
        if (isset($_SERVER['PATH_INFO'])) {
            $this->path = $_SERVER['PATH_INFO'];
        } else {
            $this->path = '/';
        }

        $this->method = $_SERVER['REQUEST_METHOD'];        
        $this->path   = (empty ($this->path) ) ? : $this->path;

        //Если заполнено свойство ajax_path, значит запрос из ajaxQuery, перезаписываем путь на переданный
        $ajaxPath = $this->getProperty('ajax_path'); 
        if(isset($ajaxPath)){
            $this->path = $ajaxPath;
        }

        //Если заполнено свойство method, значит переопределяем http метод (PUT, DELETE т.п.)
        //Дело в том, что ajax работает только с GET, POST
        $method = $this->getProperty('method');
        if(isset($method)){
            $this->method = $method;
        }
    }

    /**
     * Получить строку http метода
     * 
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->method;
    }

    /**
     * Determine if the user is authorized to make this request.
     * Пока не зайдествовано. Но можно поместить сюда проверку сессии
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * Пока не реализовано
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
