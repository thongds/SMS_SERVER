<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 1/25/17
 * Time: 10:41 AM
 */

namespace App\Http\Controllers\Admin\Setting;


use App\Http\Controllers\BaseAdminController\CDUController;
use Illuminate\Http\Request;
use App\Models\Language;

class LanguageController extends CDUController{
    private $routers = array('GET' => 'get_language','POST' => 'post_language');
    private $uniqueFields = array('name');
    private $privateKey = 'id';
    private $validateForm = ['name'=>'required|max:255'];
    private $pagingNumber = 3;
    public function __construct(){
        parent::__construct(new Language(),$this->privateKey,$this->uniqueFields,$this->routers,$this->validateForm);
    }

    public function index(Request $request){
        $this->request = $request;
        $this->page = $request->get('page');
        if ($request->isMethod('POST')){

            $active = !empty($request->get('active')) ? 1 : 0 ;
            $progressData = ['active' => $active,'name' => $request->get('name')];
            $this->processPost($request,$progressData,function ($status,$message){
                if($message!=null){
                    foreach ($message as $value){
                        $this->mValidateMaker->errors()->add('field',$value);
                    }
                }
                return $this->returnView();
            });
        }
        if ($request->isMethod('GET')){
            $this->processGet($request,function ($data){

            });
        }

        return $this->returnView();

    }
    public function returnView($data = null)
    {
        $listData = $this->mainModel->orderBy('created_at')->paginate($this->pagingNumber);
        if(count($this->mValidateMaker->errors()->toArray())>0)
            return view('admin/setting/language.languageIndex',['listData'=>$listData,
                'page'=>$this->page,'isEdit'=>$this->request->get('isEdit'),'update_data' =>$this->mUpdateData])->withErrors($this->mValidateMaker);
        return view('admin/setting/language.languageIndex',['listData'=>$listData,
            'page'=>$this->page,'isEdit'=>$this->request->get('isEdit'),'update_data' =>$this->mUpdateData]);

    }
}