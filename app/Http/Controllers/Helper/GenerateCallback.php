<?php
/**
 * Created by PhpStorm.
 * User: ssd
 * Date: 2/3/17
 * Time: 1:39 PM
 */

namespace App\Http\Controllers\Helper;


class GenerateCallback{
    private $status = false;
    public $responseMessage = Array();
    public $statusJsonKey = "status";
    public $messageJsonKey = "message";
    public function __construct($status = false,Array $message = Array()){
        $this->status = (boolean)$status;
        $this->responseMessage = $message;
    }

    public function getStatus(){
        return $this->status;
    }
    public function getMessage(){
        return $this->responseMessage;
    }
    public function responseJSON(){
        $jsonFormat = [$this->statusJsonKey => $this->status,$this->messageJsonKey => $this->responseMessage];
        return json_encode($jsonFormat);
    }
    public function setStatus($status){
        $this->status = $status;
    }
    public function setMessage($message){
        array_push($this->responseMessage,$message);
    }
    public function setData($status,$message){
        $this->status = $status;
        array_push($this->responseMessage,$message);
    }
}