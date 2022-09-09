<?php
require_once 'class/getInventory.php';
require_once 'class/getPurchaseOrder.php';
require_once 'class/getDevolucion.php';
require_once 'class/getTransferOrder.php';
require_once 'PHPToolkit/NetSuiteService.php';
require 'vendor/autoload.php';

if(isset($_POST["id"]) && isset($_POST["tipo"])){

    $internal_id = $_POST["id"];

    switch ($_POST["tipo"]){
        case "articulo":
            getInventory::item($internal_id);
            echo "SE GENERÓ ARCHIVO";
            break;

        case "devolucion":
            getDevolucion::item($internal_id);
            echo "SE GENERÓ ARCHIVO";
            break;

        case "orden":
            getPurchaseOrder::item($internal_id);
            echo "SE GENERÓ ARCHIVO";
            break;

        case "despacho":
            getTransferOrder::item($internal_id);
            echo "SE GENERÓ ARCHIVO";
            break;
    }
    
}
?>