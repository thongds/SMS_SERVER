<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 1/21/17
 * Time: 2:10 PM
 */
namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\BaseAdminController\CDUController;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends CDUController {

    private $routers = array('get' => 'get_category','post' => 'post_category');
    private $uniqueFields = array('name');
    private $privateKey = 'id';
    private $validateForm = ['name'=>'required|max:255'];
    private $pagingNumber = 3;
    public function __construct(){
        parent::__construct(new Category(),$this->privateKey,$this->uniqueFields,$this->routers,$this->validateForm);
    }

    public function index(Request $request){
        $page = $request->get('page');
        if ($request->isMethod('POST')){
            $active = !empty($request->get('active')) ? 1 : 0 ;
            $progressData = ['active' => $active,'name' => $request->get('name')];
            $this->processPost($request,$progressData,function ($status,$message){
                if($message!=null){
                    foreach ($message as $value){
                        $this->mValidateMaker->errors()->add('field',$value);
                    }
                }
                return redirect()->route('get_category')->withErrors($this->mValidateMaker);
            });
        }
        if ($request->isMethod('GET')){
            $this->processGet($request,function ($data){

            });
        }

        $categoryList = $this->mainModel->orderBy('created_at')->paginate($this->pagingNumber);
        return view('admin/setting/category.categoryIndex',['category_list'=>$categoryList,'page'=>$page,'isEdit'=>$request->get('isEdit'),'update_data' =>$this->mUpdateData]);
    }
}