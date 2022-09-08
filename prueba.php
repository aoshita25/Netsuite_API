<?php
require_once 'class/getInventory.php';
require_once 'PHPToolkit/NetSuiteService.php';
require 'vendor/autoload.php';

if(isset($_POST["id"]) && isset($_POST["tipo"])){

    $internal_id = $_POST["id"];

    switch ($_POST["tipo"]){
        case "articulo":
            $inventArticle = getInventory::item($internal_id);
            print_r( $inventArticle);
            break;
    }
    
}
?>