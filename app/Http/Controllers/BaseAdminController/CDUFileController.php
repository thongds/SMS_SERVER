<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 1/26/17
 * Time: 11:43 AM
 */

namespace App\Http\Controllers\BaseAdminController;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Helper\ActionKey;
use App\Http\Controllers\Helper\MessageKey;
use Validator;

abstract class CDUFileController extends CDUController{

    protected $mFieldFile;
    protected $mFieldPath;
    protected $mValidateFormUpdate;
    public function __construct(array $fieldFile,array $fieldPath,Model $model, $privateKey, array $uniqueField, array $router, array $validateForm, array $validateFormUpdate)
    {
        $this->mFieldFile = $fieldFile;
        $this->mFieldPath = $fieldPath;
        $this->mValidateFormUpdate = $validateFormUpdate;
        parent::__construct($model, $privateKey, $uniqueField, $router, $validateForm);
    }

    public function processPost(Request $request,Array $processData,$callback){

        // check field unique

        //create new
        if($request->get($this->mPrivateKey)==null){
            $this->checkValidateByForm($request,$this->mValidateForm);
            foreach ($this->mUniqueFields as $uniqueFieldName){
                $nameRepose = $this->mainModel->where($uniqueFieldName,$request->get($uniqueFieldName))->get()->toArray();
                if(!empty($nameRepose)){
                    $this->isHaveUniqueError = true;
                    $this->message = array_merge($this->message,[$uniqueFieldName." ".MessageKey::wasExist]);
                }
            }
            if ($this->isHaveUniqueError) {
                $callback(true,$this->message);
                return;
            }

            if($this->create($processData)){
                $this->message = array_merge($this->message,[MessageKey::createSuccessful]);
                $callback(true,$this->message);
                return;
            }else{
                $this->message = array_merge($this->message,[MessageKey::cannotSave]);
                $callback(false,$this->message);
                return;
            }

        }
        //update
        if($request->get($this->mPrivateKey) !=null){
            $this->checkValidateByForm($request,$this->mValidateFormUpdate);
            $fieldOfDelete = array();
            foreach ($this->mFieldFile as $value){
                if($request->file($value)!=null){
                    $fieldOfDelete = array_merge($fieldOfDelete,[$value.'_path']);
                }
            }
            if(!empty($fieldOfDelete)){
                $this->deleteOldFile($request,$fieldOfDelete);
            }
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
            $this->deleteOldFile($request,$this->mFieldPath);
            $status =(boolean)$this->delete($request->get($this->mPrivateKey));
            $callback($status,null);
            return;
        }
        if($request->get(ActionKey::active)!=null){
            $status = (boolean)$this->changeStatus($request->get($this->mPrivateKey),$request ->get(ActionKey::active));
            $callback($status,null);
            return;
        }
        if($request->get(ActionKey::isEdit)!=null && $request->get($this->mPrivateKey) != null){
            $result = $this->mainModel->where($this->mPrivateKey,$request->get($this->mPrivateKey))->get()->toArray();
            if(count($result) > 0)
                $this->mUpdateData = $result[0];
            $callback((boolean)$result,null);
            return;
        }
        //no one case match
        $callback(null,null);
        return;

    }
    public function deleteOldFile(Request $request,Array $fieldOfPathDelete){
        $data = $this->mainModel->where($this->mPrivateKey,$request->get($this->mPrivateKey))->get()->toArray();
        if($data !=null){
            foreach ($fieldOfPathDelete as $value){
                unlink($data[0][$value]);
            }
        }
    }
    public function checkValidateByForm(Request $request,Array $validateForm){
        $this->validate($request,$validateForm);
    }
    public function progressFileData(Request $request,Array $fieldFile,Array $progressFileData){
        foreach ($fieldFile as $item){
            $fileUpload = $this->_getFilepath($request->file($item));
            if($fileUpload !=null)
                $progressFileData = array_merge($progressFileData,[$item => $fileUpload['link'],
                    $item.'_path' => $fileUpload['path']]);
        }
        return $progressFileData;
    }

}