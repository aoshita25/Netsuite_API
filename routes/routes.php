<?php

$routesArray = array_filter(explode("/",$_SERVER['REQUEST_URI']));

if(count($routesArray) == 0){
    $json = array(
        'status' => 400,
        'result' => 'Not found'
    );
    
    echo json_encode($json,http_response_code($json["status"]));
    return;
}

if(count($routesArray) >= 1 && isset($_SERVER['REQUEST_METHOD'])){
    
    //Peticiones GET
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        
        include "services/get.php";
    }

    //Peticiones POST
    if($_SERVER['REQUEST_METHOD'] == "POST"){
    
        include "services/get.php";
    }

    return;
}
