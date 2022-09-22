<?php

header('Content-type: application/json');

require_once "models/get.model.php";

class GetController{

    static public function getData($type,$id){

        $response = GetModel::getData($type,$id);
        $return = new GetController();
        $return->fncResponse($response);
    }

    public function fncResponse($response){
        if(!empty($response) && $response = "Upload success"){
            $json = array(
                'status' => 200,
                'result' => $response
            );
        }
        elseif($response = "Upload failed!"){
            $json = array(
                'status' => 400,
                'result' => $response
            );
        }
        else{
            $json = array(
                'status' => 400,
                'result' => 'Not found'
            );
        }
        echo json_encode($json,http_response_code($json["status"]));
    }
}