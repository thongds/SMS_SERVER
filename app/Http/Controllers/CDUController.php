<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 1/22/17
 * Time: 4:13 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Validator;


class CDUController extends Controller {

    protected $mainModel ;
    protected $mRouters;
    protected $mFields;
    protected $mUniqueFields;
    protected $mValidateMaker ;
    protected $mPrivateKey;
    protected $mValidateForm;
    protected $mUpdateData;

    public function __construct(Model $model,$privateKey,Array $uniqueField,Array $router,Array $validateForm){
       $this->mainModel = $model;
       $this->mPrivateKey = $privateKey;
       $this->mRouters = $router;
       $this->mUniqueFields = $uniqueField;
       $this->mValidateForm = $validateForm;
       $this->mValidateMaker = Validator(array(),array(),array());
    }

    public function delete($id){
        $this->mainModel->destroy($id);
    }
    public function changeStatus($id,$status){
        $result = $this->mainModel->find($id);
        $result->active = $status;
        $result->save();
    }
    public function create(Array $progressData){

        foreach ($progressData as $field => $data  ){
            $this->mainModel->$field = $data;
        }
        return $this->mainModel->save();
    }
    public function update($id,$progressData){
        $databaseResult = $this->mainModel->find($id);
        foreach ($progressData as $field => $data  ){
            $databaseResult->$field = $data;
        }
        return $databaseResult->save();
    }
    public function processPost(Request $request,Array $processData){
        $this->validate($request,$this->mValidateForm);
        //create new
        if($request->get($this->mPrivateKey)==null){
            foreach ($this->mUniqueFields as $uniqueFieldName){
                $nameRepose = $this->mainModel->where($uniqueFieldName,$request->get($uniqueFieldName))->get()->toArray();
                if(!empty($nameRepose)){
                    $this->mValidateMaker->errors()->add('field', "'.$uniqueFieldName.' was exist");
                    return redirect()->route($this->mRouters['get'])->withErrors($this->mValidateMaker);
                }
            }
            if($this->create($processData)){
                $this->mValidateMaker->errors()->add('field', "Create new Successful!");
            }else{
                $this->mValidateMaker->errors()->add('field', "can not save!");
            }

        }
        //update
        if($request->get($this->mPrivateKey) !=null){
            if($this->update($request->get('id'),$processData)){
                $this->mValidateMaker->errors()->add('field', "Update Successful!");
            }else{
                $this->mValidateMaker->errors()->add('field', "can not update!");
            }
        }

        return redirect()->route('get_category')->withErrors($this->mValidateMaker);
    }
    public function processGet(Request $request){
        if($request->get('delete')){
            $this->delete($request->get('id'));
        }
        if($request->get('active')!=null){
            $this->changeStatus($request->get('id'),$request->get('active'));
        }
        if($request->get('isEdit')!=null && $request->get('id') != null){
            $update_data = $this->mainModel->where('id',$request->get('id'))->get()->toArray();
            $this->mUpdateData = $update_data[0];
        }
    }
}