<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 1/26/17
 * Time: 4:13 PM
 */

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\BaseAdminController\CDUFileController;
use App\Models\DefaultImage;
use Illuminate\Http\Request;

class DefaultImageController extends CDUFileController{
    private $routers = array('GET' => 'get_default_image','POST' => 'post_default_image');
    private $uniqueFields = array('name');
    private $fieldFile = array('avatar','logo','content');
    private $fieldPath = array('avatar_path','logo_path','content_path');
    private $privateKey = 'id';
    private $validateForm = ['name'=>'required|max:255','avatar' => 'required','logo' => 'required','content' => 'required'];
    private $validateFormUpdate = ['name'=>'required|max:255'];
    private $pagingNumber = 3;
    public function __construct(){

        parent::__construct($this->fieldFile,$this->fieldPath,new DefaultImage(),$this->privateKey,$this->uniqueFields,$this->routers,$this->validateForm,$this->validateFormUpdate);
    }

    public function index(Request $request){
        $this->request = $request;
        $this->page = $request->get('page');
        if ($request->isMethod('POST')){

            $active = !empty($request->get('active')) ? 1 : 0 ;
            $progressData = ['active' => $active,'name' => $request->get('name')];
            $progressData = array_merge($progressData, $this->progressFileData($request,$this->fieldFile,$progressData));

            $this->processPost($request,$progressData,function ($status,$message){
                if($message!=null){
                    foreach ($message as $value){
                        $this->mValidateMaker->errors()->add('field',$value);
                    }
                }
                return $this->returnView(null);
            });
        }
        if ($request->isMethod('GET')){
            $this->processGet($request,function ($status,$data){

            });
        }
        return $this->returnView(null);

    }
    public function returnView($data)
    {
        $listData = $this->mainModel->orderBy('created_at')->paginate($this->pagingNumber);
        if(count($this->mValidateMaker->errors()->toArray())>0)
            return  view('admin/setting/defaultImage.defaultImageIndex',['listData'=>$listData,'router' => $this->routers,'page'=>$this->page,
                'isEdit'=>$this->request->get('isEdit'),'update_data' =>$this->mUpdateData])->withErrors($this->mValidateMaker);
        return  view('admin/setting/defaultImage.defaultImageIndex',['listData'=>$listData,'router' => $this->routers,'page'=>$this->page,
            'isEdit'=>$this->request->get('isEdit'),'update_data' =>$this->mUpdateData]);

    }

}