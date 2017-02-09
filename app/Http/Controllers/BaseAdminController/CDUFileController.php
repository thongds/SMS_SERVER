<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 1/26/17
 * Time: 11:43 AM
 */

namespace App\Http\Controllers\BaseAdminController;

use App\Http\Controllers\Helper\GenerateCallback;
use App\Http\Controllers\Helper\Validate;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Helper\ActionKey;
use App\Http\Controllers\Helper\MessageKey;


use Validator;

abstract class CDUFileController extends CDUController{

    protected $mFieldFile;
    protected $mFieldPath;
    protected $mValidateFormUpdate;

    public function __construct(array $fieldFile,array $fieldPath,Model $model, $privateKey, array $uniqueField,
                                array $validateForm, array $validateFormUpdate)
    {
        $this->mFieldFile = $fieldFile;
        $this->mFieldPath = $fieldPath;
        $this->mValidateFormUpdate = $validateFormUpdate;

        parent::__construct($model,$privateKey,$uniqueField,$validateForm);
    }

    public function progressPost(Request $request,Array $progressData){
        if($request->get($this->mPrivateKey)==null){
            return $this->createNew($request,$progressData);
        }
        if($request->get($this->mPrivateKey)!=null){
           return $this->updateFile($request,$progressData);
        }
    }
    public function progressGet(Request $request){
        $response = new GenerateCallback();
        if($request->get(ActionKey::delete)){
            $this->deleteOldFile($request,$this->mFieldPath);
            $response =  $this->delete($request);
        }
        if($request->get(ActionKey::active)!=null){
            $response = $this->active($request);
        }
        if($request->get(ActionKey::isEdit)!=null && $request->get($this->mPrivateKey) != null){
            $this->edit($request);
        }
        return $response;

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

    }
    private function updateFile(Request $request,Array $progressData){
        if($request->get($this->mPrivateKey) !=null){
            $response = $this->update($request,$progressData,$this->mValidateFormUpdate);
            if($response->getStatus()){

                $fieldOfDelete = array();
                foreach ($this->mFieldFile as $value){
                    if($request->file($value)!=null){
                        $fieldOfDelete = array_merge($fieldOfDelete,[$value.'_path']);
                    }
                }
                if(!empty($fieldOfDelete)){
                    $this->deleteOldFile($request,$fieldOfDelete);
                }
            }
            return $response;

        }
    }
    public function processGet(Request $request,$callback,$foreignData = null){
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
        if($request->get(ActionKey::isEdit)!=null && $request->get($this->mPrivateKey) != null && $foreignData !=null){
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
            $fileUpload = $this->_getFilePath($request->file($item));
            if($fileUpload !=null)
                $progressFileData = array_merge($progressFileData,[$item => $fileUpload['link'],
                    $item.'_path' => $fileUpload['path']]);
        }
        return $progressFileData;
    }

    protected function _getFilePath($file_upload){
        if($file_upload == null)
            return '';
        $file_name = time().'_random_'.rand(5, 100).$file_upload->getFilename().'.'.$file_upload->getClientOriginalExtension();
        $file_upload->move(public_path("uploads"), $file_name);
        $fileData = ['link' =>url('/').'/uploads/'.$file_name,'path' =>public_path("uploads").'/'.$file_name ];
        return  $fileData;
    }

}