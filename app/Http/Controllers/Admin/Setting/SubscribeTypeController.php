<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 1/25/17
 * Time: 6:21 PM
 */

namespace App\Http\Controllers\Admin\Setting;


use App\Http\Controllers\BaseAdminController\CDUController;
use App\Models\SubscribeTypeModel;
use Illuminate\Http\Request;

class SubscribeTypeController extends CDUController{

    private $routers = array('GET' => 'get_subscribe_type','POST' => 'post_subscribe_type');
    private $uniqueFields = array('name','role_type');
    private $privateKey = 'id';
    private $validateForm = ['name'=>'required|max:255','total_time' => 'required|numeric','description' => 'required|max:255'];
    private $pagingNumber = 3;
    private $pageTitle = 'Subtitle Type';
    public function __construct(){
        parent::__construct(new SubscribeTypeModel(),$this->privateKey,$this->uniqueFields,$this->routers,$this->validateForm);
    }

    public function index(Request $request){
        $page = $request->get('page');
        if ($request->isMethod('POST')){
            $active = !empty($request->get('active')) ? 1 : 0 ;
            $progressData = ['active' => $active,'name' => $request->get('name'),
                'description' => $request->get('description'),
                'total_time' => $request->get('total_time')];
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
        return view('admin/setting/subscribe_type.subscribeTypeIndex',['router' =>$this->routers,'pageTitle' => $this->pageTitle,
            'listData'=>$listData,'page'=>$page,'isEdit'=>$request->get('isEdit'),'update_data' =>$this->mUpdateData]);
    }
}