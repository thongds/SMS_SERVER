<?php

/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 2/12/17
 * Time: 1:45 PM
 */
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\BaseAdminController\FILODatabaseSupport;
use \Illuminate\Foundation\Testing\DatabaseTransactions;
use \App\Models\Category;
class FILODatabaseTest extends TestCase{
    use DatabaseTransactions;
    public function testParamInput(){
        $object = $this->getMockForTrait('App\Http\Controllers\BaseAdminController\FILODatabaseSupport');
        $result = $this->invokeMethod($object,'fifoDatabase',['new_least_song']);
        $this->assertTrue($result);
    }

}
