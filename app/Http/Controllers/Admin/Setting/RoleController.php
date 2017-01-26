<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 1/25/17
 * Time: 5:43 PM
 */

namespace App\Http\Controllers\Admin\Setting;


use App\Http\Controllers\BaseAdminController\CDUController;
use App\Models\AdminRole;

use Illuminate\Http\Request;

class RoleController extends CDUController{

    private $routers = array('GET' => 'get_role','POST' => 'post_role');
    private $uniqueFields = array('name','role_type');
    private $privateKey = 'id';
    private $validateForm = ['name'=>'required|max:255','role_type' => 'required|numeric'];
    private $pagingNumber = 3;
    private $pageTitle = 'Subtitle Type';
    public function __construct(){
        parent::__construct(new AdminRole(),$this->privateKey,$this->uniqueFields,$this->routers,$this->validateForm);
    }

    public function index(Request $request){
        $page = $request->get('page');
        if ($request->isMethod('POST')){
            $active = !empty($request->get('active')) ? 1 : 0 ;
            $progressData = ['active' => $active,'name' => $request->get('name'),'role_type' => $request->get('role_type')];
            $this->processPost($request,$progressData,function ($status,$message){
                if($message!=null){
                    foreach ($message as $value){
                        $this->mValidateMaker->errors()->add('field',$value);
                    }
                }
                return redirect()->route($this->routers['GET'])->withErrors($this->mValidateMaker);
            });
        }
        if ($request->isMethod('GET')){
            $this->processGet($request,function ($data){

            });
        }

        $listData = $this->mainModel->orderBy('created_at')->paginate($this->pagingNumber);
        return view('admin/setting/adminUser.roleIndex',['router' =>$this->routers,'pageTitle' => $this->pageTitle,
            'listData'=>$listData,'page'=>$page,'isEdit'=>$request->get('isEdit'),'update_data' =>$this->mUpdateData]);
    }
}