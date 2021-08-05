<?php 
/**
 * Класс запроса для Http
 */
namespace app\Requests\Http;

use app\Requests\Request;

class HttpRequest extends Request
{
    private $method;
    public  $path;

    /**
     * Проверку типа запроса можно было сделать здесь, но я решил передавать информацию в HttpCommand
     * и принимать решение по обработке там.
     * 
     * @see app\Commands\Command\HttpCommand::execute()
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

        $method = $this->getProperty('method');
        if(isset($method)){
            $this->method = $method;
        }

        // d($this);
    }

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->method;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
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
}
