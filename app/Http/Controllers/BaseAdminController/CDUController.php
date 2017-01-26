<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 1/22/17
 * Time: 4:13 PM
 */

namespace App\Http\Controllers\BaseAdminController;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Helper\ActionKey;
use App\Http\Controllers\Helper\MessageKey;
use App\Http\Controllers\BaseAdminController\Controller;
use Validator;



class CDUController extends Controller {

    protected $mainModel ;
    protected $mRouters;
    protected $mFields;
    protected $mUniqueFields;
    protected $mValidateMaker ;
    public $mPrivateKey;
    protected $mValidateForm;
    protected $mUpdateData;
    protected $message = array();
    private  $isHaveUniqueError;
    public function __construct(Model $model,$privateKey,Array $uniqueField,Array $router,Array $validateForm){
       $this->mainModel = $model;
       $this->mPrivateKey = $privateKey;
       $this->mRouters = $router;
       $this->mUniqueFields = $uniqueField;
       $this->mValidateForm = $validateForm;
       $this->mValidateMaker = Validator(array(),array(),array());
    }
    public function processPost(Request $request,Array $processData,$callback){
        $this->checkValidate($request);
        // check field unique

        //create new

        if($request->get($this->mPrivateKey)==null){
            foreach ($this->mUniqueFields as $uniqueFieldName){
                $nameRepose = $this->mainModel->where($uniqueFieldName,$request->get($uniqueFieldName))->get()->toArray();
                if(!empty($nameRepose)){
                    $this->isHaveUniqueError = true;
                    array_push($this->message,$uniqueFieldName." ".MessageKey::wasExist);
                }
            }
            if ($this->isHaveUniqueError) {
                $callback(true,$this->message);
                return;
            }

            if($this->create($processData)){
                array_push($this->message,MessageKey::createSuccessful);
                $callback(true,$this->message);
                return;
            }else{
                array_push($this->message,MessageKey::cannotSave);
                $callback(false,$this->message);
                return;
            }

        }
        //update
        if($request->get($this->mPrivateKey) !=null){
            if($this->update($request->get($this->mPrivateKey),$processData)){
                array_push($this->message,MessageKey::updateSuccessful);
                $callback(true,$this->message);
                return;
            }else{
                array_push($this->message,MessageKey::cannotUpdate);
                $callback(false,$this->message);
                return;
            }
        }
        $callback(false,null);
    }

    public function processGet(Request $request,$callback){
        if($request->get(ActionKey::delete)){
            $result =(boolean)$this->delete($request->get($this->mPrivateKey));
            $callback($result);
            return;
        }
        if($request->get(ActionKey::active)!=null){
            $result = (boolean)$this->changeStatus($request->get($this->mPrivateKey),$request ->get(ActionKey::active));
            $callback($result);
            return;
        }
        if($request->get(ActionKey::isEdit)!=null && $request->get($this->mPrivateKey) != null){
            $result = $this->mainModel->where($this->mPrivateKey,$request->get($this->mPrivateKey))->get()->toArray();
            if(count($result) > 0)
                $this->mUpdateData = $result[0];
            $callback((boolean)$result);
            return;
        }
        //no one case match
        $callback(null);

    }

    public function delete($id){
        return $this->mainModel->destroy($id);
    }
    public function changeStatus($id,$status){
        $result = $this->mainModel->find($id);
        if($result == null)
            return $result;
        $result->active = $status;
        return $result->save();
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
    public function checkValidate(Request $request){
        $this->validate($request,$this->mValidateForm);
    }
    protected function _getFilepath($file_upload){
        if($file_upload == null)
            return '';
        $file_name = time().$file_upload->getFilename().'.'.$file_upload->getClientOriginalExtension();
        $file_upload->move(public_path("uploads"), $file_name);

        return  url('/').'/uploads/'.$file_name;
    }

}
