<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 2/12/17
 * Time: 8:26 AM
 */

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\BaseAdminController\Controller;
use Illuminate\Support\Facades\DB;

class HomePageController extends Controller
{
    public function __construct()
    {
    }

    public function hostSong(){
        $data = DB::table('hot_song')->where('active','1')->get();
        echo json_encode($data);
    }
    public function menuSong(){
        $languageArray = array();
        $language = DB::table('language')->where('active',1)->get();
        foreach ($language as $key =>$value){
            $languageArray[$value['name']] =$value['id'];
        }
        echo json_encode($languageArray);
    }
    public function mainPageByCategory(){
        $categoryArray = array();
        $categoryData = DB::table('category')->where('active',1)->get();
        $newSongData = DB::table('new_least_song')->where('active',1)->get();

        foreach ($categoryData as $key =>$value){
            $categoryArray[$value['name']] =$value['id'];
        }

        $result = $categoryArray;
        foreach ($newSongData as $key => $value){
            foreach ($categoryArray as $key1 => $value1){
                if($value1 == $value['category_id']){
                    if(is_int($result[$key1])){
                        $result[$key1] = array($value);
                    }else{
                        array_push($result[$key1],$value);
                    }
                }
            }
        }
        echo json_encode($result);
    }

}