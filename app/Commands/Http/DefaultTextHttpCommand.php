<?php 
/**
 * Комманда для обработки http запросов для текстов по умолчанию (Default Text)
 */
namespace app\Commands\Http;

use app\Requests\Request;

class DefaultTextHttpCommand extends HttpCommand
{
    /**
     * Display a listing of the resource.
     *
     * @return 
     */
    public function index(Request $request)
    {
        echo '<p>Index!</p>';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  app\Requests\Request  $request
     * @return 
     */
    public function store(Request $request)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  app\Requests\Request  $request
     * @param  int  $id
     * @return 
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return 
     */
    public function destroy(Request $request)
    {

    }
}
