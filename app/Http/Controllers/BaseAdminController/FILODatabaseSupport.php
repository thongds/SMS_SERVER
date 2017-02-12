<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 2/12/17
 * Time: 1:31 PM
 */

namespace App\Http\Controllers\BaseAdminController;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait FILODatabaseSupport{

    public function fifoDatabase($table){
        if(is_string($table)){
            $result = DB::table($table)->count();
            if($result>2){
                $maxDate = DB::table($table)->min('created_at');
                $id = DB::table($table)->where('created_at', '=',$maxDate)->value('id');
                $delete = DB::table($table)->where('id','=',$id)->delete();
                return (bool)$delete;
            }else{
                return true;
            }
        }
        return false;
    }

}