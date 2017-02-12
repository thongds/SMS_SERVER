<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 2/12/17
 * Time: 8:26 AM
 */

namespace App\Http\Controllers\Api\v1;


use App\Http\Controllers\BaseAdminController\CDUAbstractController;
use Illuminate\Support\Facades\DB;

class HomePageController extends CDUAbstractController
{
    public function __construct()
    {
    }

    public function index(){
        $data = DB::table('hot_song')->where('active','1')->get();
        echo json_encode($data);
    }

    public function returnView($data)
    {
        // TODO: Implement returnView() method.
    }

}