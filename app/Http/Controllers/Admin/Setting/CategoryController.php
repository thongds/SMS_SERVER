<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 1/21/17
 * Time: 2:10 PM
 */
namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Admin\CDUHelper\CreateNewNormal;
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
        $this->request = $request;
        $this->page = $request->get('page');
        if ($request->isMethod('POST')){
            $active = !empty($request->get('active')) ? 1 : 0 ;
            $progressData = ['active' => $active,'name' => $request->get('name')];
            if($request->get($this->mPrivateKey)==null){
                $createNew = new CreateNewNormal(new Category());
                $createNew->createNewRow($request,$progressData,$this->uniqueFields,$this->validateForm);
            }
            $this->processPost($request,$progressData,function ($status,$message){
                if($message!=null){
                    foreach ($message as $value){
                        $this->mValidateMaker->errors()->add('field',$value);
                    }
                }
            });
        }
        if ($request->isMethod('GET')){
            $this->processGet($request,function ($data){
                $this->responseData = $data;
            });
        }
        return $this->returnView($this->responseData);
    }
    public function returnView($data){
        $categoryList = $this->mainModel->orderBy('created_at')->paginate($this->pagingNumber);
        if(count($this->mValidateMaker->errors()->toArray())>0)
            return view('admin/setting/category.categoryIndex',['category_list'=>$categoryList,
                'page'=>$this->page,'isEdit'=>$this->request->get('isEdit'),
                'update_data' =>$this->mUpdateData])->withErrors($this->mValidateMaker);
        return view('admin/setting/category.categoryIndex',['category_list'=>$categoryList,
            'page'=>$this->page,'isEdit'=>$this->request->get('isEdit'),
            'update_data' =>$this->mUpdateData]);

    }
}