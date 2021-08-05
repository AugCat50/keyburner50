<?php 
namespace app\Commands\Http;

use app\Requests\Request;
use app\Workers\StatisticsWorker;

class StatisticsHttpCommand extends HttpCommand
{
    /**
     * @return app\Response\Response
     */
    public function index(Request $request)
    {
        $worker = new StatisticsWorker();
        $worker->main($request);
        d($request);
        
    }

    /**
     * 
     * @param  app\Requests\Request $request
     * @return app\Response\Response
     */
    public function show(Request $request)
    {
         return $this->response;
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
