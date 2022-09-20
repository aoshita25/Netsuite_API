<?php
require_once "controllers/get.controller.php";
require_once "PHPToolkit/NetSuiteService.php";

if(isset($_GET["type"]) && isset($_GET["id"])){

    $type = $_GET["type"];
    $id = $_GET["id"];
    $response = GetController::getData($type,$id);

}else{

    $json = array(
        'status' => 400,
        'result' => 'Not found'
    );
    echo json_encode($json,http_response_code($json["status"]));
}

