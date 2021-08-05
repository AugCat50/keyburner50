<?php 
namespace app\Commands\Http;

use app\Requests\Request;
use DomainObjectAssembler\DomainObjectAssembler;

class UserTextHttpCommand extends HttpCommand
{
        /**
     * @return app\Response\Response
     */
    public function index(Request $request)
    {
        
    }

    /**
     * 
     * @param  app\Requests\Request $request
     * @return app\Response\Response
     */
    public function show(Request $request)
    {
        $id          = $request->getProperty('id');
        $assembler   = new DomainObjectAssembler('UserText');
        $identityObj = $assembler->getIdentityObject()
                            ->field('id')
                            ->eq($id);
        $model       = $assembler->findOne($identityObj);
        $text        = $model->getText();
        $this->response->setFeedback($text);
        
        return $this->response;
    }

    /**
     * POST
     * Store a newly created resource in storage.
     *
     * @param  app\Requests\Request  $request
     * @return 
     */
    public function store(Request $request)
    {
        d('store');
        d($request);
        //add
    }

    /**
     * PUT
     * Update the specified resource in storage.
     *
     * @param  app\Requests\Request  $request
     * @param  int  $id
     * @return 
     */
    public function update(Request $request)
    {
        d('update');
        d($request);
        //edit
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
