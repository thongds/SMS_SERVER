<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 1/22/17
 * Time: 4:13 PM
 */

namespace App\Http\Controllers\BaseAdminController;

use App\Http\Controllers\Helper\GenerateCallback;
use App\Http\Controllers\Helper\UtilFunction;
use App\Http\Controllers\Helper\Validate;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Helper\ActionKey;
use App\Http\Controllers\Helper\MessageKey;
use App\Http\Controllers\BaseAdminController\Controller;
use App\Http\Controllers\Admin\CDUHelper\CreateNewNormal;
use App\Http\Controllers\Admin\CDUHelper\DeleteDataNormal;
use App\Http\Controllers\Admin\CDUHelper\UpdateDataNormal;
use Validator;



abstract class CDUController extends Controller {

    protected $mainModel ;
    protected $mUniqueFields;
    protected $mPrivateKey;
    protected $mValidateForm;
    protected $mUpdateData;
    protected $viewReturn;
    protected $request;
    protected $mCheckValidateObject;
    protected $page;
    protected $mFieldFile;
    protected $mFieldPath;
    protected $mValidateFormUpdate;
    use FileSupport;
    public function __construct(Model $model,$privateKey,Array $uniqueField,Array $validateForm,$fieldFile = null,$validateFormUpdate = array(),$fieldPath = array()){
       $this->mainModel = $model;
       $this->mPrivateKey = $privateKey;
       $this->mUniqueFields = $uniqueField;
       $this->mValidateForm = $validateForm;
       $this->mCheckValidateObject = new Validate();
       $this->mFieldFile = $fieldFile;
       $this->mValidateFormUpdate = $validateFormUpdate;
       $this->mFieldPath = $fieldPath;
    }
    abstract function returnView($data);

    public function progressPost(Request $request,Array $progressData){
        if($request->get($this->mPrivateKey)==null){
            return $this->createNew($request,$progressData);
        }
        if($request->get($this->mPrivateKey)!=null){
            return $this->progressUpdate($request,$progressData);
        }
    }


    public function progressGet(Request $request){
        $response = new GenerateCallback();
        if($request->get(ActionKey::delete)){
            $this->deleteOldFile($this->getOldFilePath($request,$this->mainModel,$this->mPrivateKey,$this->mFieldPath));
            $response = $this->delete($request);
        }
        if($request->get(ActionKey::active)!=null){
            $response =  $this->active($request);
        }
        if($request->get(ActionKey::isEdit)!=null && $request->get($this->mPrivateKey) != null){
           $this->edit($request);
        }
        return $response;
    }

    protected function createNew(Request $request,Array $progressData){
        $createNew = new CreateNewNormal($this->mainModel);
        $response = $createNew->createNewRow($request,$progressData,$this->mUniqueFields,$this->mValidateForm);
        return $response;
    }

    protected function delete(Request $request){
        $deleteObject = new DeleteDataNormal($this->mainModel);
        return $deleteObject->deleteById($request->get($this->mPrivateKey));
    }
    protected function active(Request $request){
        $activeObject = new UpdateDataNormal($this->mainModel);
        return $activeObject->update($request,Array(),Array(),[$this->mPrivateKey => $request->get($this->mPrivateKey),
            ActionKey::active => $request->get(ActionKey::active)]);
    }
    protected function edit(Request $request){
        $result = $this->mainModel->where($this->mPrivateKey,$request->get($this->mPrivateKey))->get()->toArray();
        if(count($result) > 0){
            $this->mUpdateData = $result[0];
            foreach ($this->mUpdateData as $key => $value){
                if($key == ActionKey::create_at){
                    unset($this->mUpdateData[$key]);
                }
            }
            $request->session()->put(ActionKey::session,$this->mUpdateData);
        }
        return;
    }
    protected function progressUpdate(Request $request,Array $progressData){
        $oldFilePath = array();
        $beforeUpdate = $request->session()->get(ActionKey::session);
        $afterUpdate = [ActionKey::updated_at => UtilFunction::getNow()]+[$this->mPrivateKey => $request->get($this->mPrivateKey)]+$progressData;
        $data = UtilFunction::mergeTwoArray($beforeUpdate,$afterUpdate,[$this->mPrivateKey]);
        $fieldOfDelete = array();
        if($this->mFieldFile !=null ){
            foreach ($this->mFieldFile as $value){
                if($request->file($value)!=null){
                    $fieldOfDelete = $fieldOfDelete + [$value.'_path'];
                }
            }
            if(!empty($fieldOfDelete)){
                $oldFilePath =  $this->getOldFilePath($request,$this->mainModel,$this->mPrivateKey,$fieldOfDelete);
            }
        }
        $response = $this->update($request,$data,$this->mValidateFormUpdate);;
        if($response->getStatus() && $this->mFieldFile !=null ){
            $this->deleteOldFile($oldFilePath);
        }
        return $response;
    }
    protected function update(Request $request,Array $progressData,Array $validateUpdateForm){
        $updateData = new UpdateDataNormal($this->mainModel);
        $response = $updateData->update($request,$this->mUniqueFields,$validateUpdateForm,$progressData);
        return  $response;
    }


}
