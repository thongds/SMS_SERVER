<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 2/3/17
 * Time: 3:07 PM
 */

namespace App\Http\Controllers\Admin\CDUHelper;


use App\Http\Controllers\Helper\GenerateCallback;
use App\Http\Controllers\Helper\MessageKey;
use App\Http\Controllers\Helper\Validate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UpdateDataNormal extends CreateNewNormal{

    protected  $mModel;
    protected $validateObject;
    public function __construct(Model $model){
        $this->mModel = $model;
        $this->validateObject = new Validate();
    }
    public function validateUpdateRowById(Array $progressData){
        $response = new GenerateCallback();
        $response->setStatus(true);
        if(!array_key_exists('id',$progressData) || (array_key_exists('id',$progressData) && empty($progressData['id']))){
                $response->setStatus(false);
                $response->setMessage(MessageKey::cannotUpdate);
         }
        return $response;
    }
    public function update(Request $request,Array $uniqueForm,Array $validateForm,Array $progressData){
        $this->validateObject->checkValidate($request,$validateForm);
        $responseCheckId = $this->validateUpdateRowById($progressData);
        if(!$responseCheckId->getStatus())
            return $responseCheckId;
        $uniqueResponse = $this->validateUnique($uniqueForm,$progressData);
        if(!$uniqueResponse->getStatus())
            return $uniqueResponse;
        $response = new GenerateCallback();
        if($this->updateRow($progressData['id'],$progressData)){
                $response->setStatus(true);
                $response->setMessage(MessageKey::updateSuccessful);
        }else{
                $response->setStatus(false);
                $response->setMessage(MessageKey::cannotUpdate);
        }

        return $response;
    }
    public function updateRow($id,$progressData){
        $databaseResult = $this->mModel->find($id);
        if(empty($databaseResult))
            return false;
        foreach ($progressData as $field => $data  ){
            $databaseResult->$field = $data;
        }
        return $databaseResult->save();
    }
}