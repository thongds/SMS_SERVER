<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\BaseAdminController\CDUController;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Helper\ActionKey;
use App\Http\Controllers\Helper\MessageKey;
class CUDControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    private $routers = array('get' => 'get_category','post' => 'post_category');
    private $uniqueFields = array('name');
    private $privateKey = 'id';
    private $validateForm = ['name'=>'required|max:255'];
    private $pagingNumber = 3;
    private $realID = 1;
    private $fakeID = -1;
    use DatabaseTransactions;

    public function testProgressGetDelete()
    {
        //correct ID
        $cdu = new CDUController(new Category(),$this->privateKey,$this->uniqueFields,$this->routers,$this->validateForm);

        $request = new Request();
        $request->setMethod('GET');
        $request->initialize(array(ActionKey::delete => true,'id' => $this->realID));

        $cdu->processGet($request,function ($data){
            $this->assertTrue($data);
        });


        //wrongID
        $request->setMethod('GET');
        $request->initialize(array(ActionKey::delete => true,'id' => $this->fakeID));
        $cdu->processGet($request,function ($data){
            $this->assertFalse($data);
        });
        //empty ::delete key
        $request->setMethod('GET');
        $request->initialize(array(ActionKey::delete => true,'id' => $this->fakeID));
        $cdu->processGet($request,function ($data){
            $this->assertFalse($data);
        });
        //empty ::id key
        $request->setMethod('GET');
        $request->initialize(array(ActionKey::delete => true));
        $cdu->processGet($request,function ($data){
            $this->assertFalse($data);
        });
        //empty params key
        $request->setMethod('GET');
        $request->initialize(array());
        $cdu->processGet($request,function ($data){
            $this->assertEquals(null,$data);
        });
    }

    public function testProgressGetEdit(){
        //correct ID
        $request = new Request();
        $request->setMethod('GET');

        $request->initialize(array(ActionKey::isEdit => true,'id' => $this->realID));
        $cdu = new CDUController(new Category(),$this->privateKey,$this->uniqueFields,$this->routers,$this->validateForm);
        $cdu->processGet($request,function ($data){
            $this->assertTrue($data);
        });
        //wrong ID
        $request = new Request();
        $request->setMethod('GET');

        $request->initialize(array(ActionKey::isEdit => true,'id' => $this->fakeID));
        $cdu->processGet($request,function ($data){
            $this->assertFalse($data);
        });
        //empty ID

        $request->initialize(array(ActionKey::isEdit => true));
        $cdu->processGet($request,function ($data){
            $this->assertEquals(null,$data);
        });
        //wrong ::isEdit key
        $request = new Request();
        $request->setMethod('GET');

        $request->initialize(array('id' => $this->fakeID));
        $cdu->processGet($request,function ($data){
            $this->assertEquals(null,count($data));
        });
    }

    public function testProgressGetChangeStatus(){
        //correct ID
        $cdu = new CDUController(new Category(),$this->privateKey,$this->uniqueFields,$this->routers,$this->validateForm);
        $request = new Request();
        $request->setMethod('GET');

        $request->initialize(array(ActionKey::active => true,'id' => $this->realID));
        $cdu->processGet($request,function ($data){
            $this->assertTrue($data);
        });
        //wrong ID
        $request->initialize(array(ActionKey::active => true,'id' => $this->fakeID));
        $cdu->processGet($request,function ($data){
            $this->assertFalse($data);
        });
        //empty ::active key
        $request->initialize(array('id' => $this->fakeID));
        $cdu->processGet($request,function ($data){
            $this->assertNull($data);
        });
        //empty ::id params
        $request->initialize(array(ActionKey::active => true));
        $cdu->processGet($request,function ($data){
            $this->assertFalse($data);
        });
        //empty request params
        $request->initialize(array());
        $cdu->processGet($request,function ($data){
            $this->assertNull($data);
        });
    }

    public function testProgressPostCreateNew(){

        $cdu = new CDUController(new Category(),$this->privateKey,$this->uniqueFields,$this->routers,$this->validateForm);
        $request = new Request();
        $request->setMethod('POST');
        $dataProgress = array($cdu->mPrivateKey => null,'name' => 'Rap','active' => 1);
        $request->initialize($dataProgress);
        $cdu->processPost($request,$dataProgress,function ($status,$message){
            $this->assertTrue($status);
            $this->assertEquals(MessageKey::createSuccessful,$message[0]);
        });
    }

}
