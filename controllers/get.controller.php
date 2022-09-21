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
        if(!empty($response)){
            $json = array(
                'status' => 200,
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