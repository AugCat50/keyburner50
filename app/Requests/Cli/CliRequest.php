<?php 
/**
 * В классе CliRequest аргументы командной строки интерпретируются в
 * виде пары “ключ-значение” и разделяются на отдельные свойства. Кроме того,
 * в этом классе выполняется поиск аргумента с префиксом path:, а его значение
 * присваивается свойству $path текущего объекта
 */
namespace app\Requests\Cli;

use app\Requests\Request;

class CliRequest extends Request
{ 
    /**
     * Обработка ввода из командной строки
     * 
     * @return void
     */
    public function init()
    {
        $args = $_SERVER ['argv'] ;
        foreach ($args as $arg) {
            if (preg_match("/^path: (\S+)/", $arg, $matches)) {
                $this->path = $matches[1];
            } else {
                if (strpos($arg, '=')) {
                    list($key, $val) = explode("=", $arg);
                    $this->setProperty($key, $val);
                }
            }
        }

        $this->path = (empty($this->path)) ? "/" : $this->path;
    }

    /**
     * Determine if the user is authorized to make this request.
     * Пока не реализовано
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