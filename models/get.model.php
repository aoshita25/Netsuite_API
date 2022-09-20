<?php
require_once 'class/getInventory.php';
require_once 'class/getInventory.php';
require_once 'class/getPurchaseOrder.php';
require_once 'class/getDevolucion.php';
require_once 'class/getTransferOrder.php';

class GetModel{

    static public function getData($type,$id){

        switch ($type){

            case "articulo":
                $data = getInventory::item($id);
                return $data;
                break;
    
            case "devolucion":
                $data = getDevolucion::item($id);
                return $data;
                //[$fileFromCab, $fileFromDet] = getDevolucion::item($id);
                break;
    
            case "orden":
                $data = getPurchaseOrder::item($id);
                return $data;
                //[$fileFromCab, $fileFromDet] = getPurchaseOrder::item($id);
                break;
    
            case "despacho":
                $data = getTransferOrder::item($id);
                return $data;
                //[$fileFromCab, $fileFromDet] = getTransferOrder::item($id);
                break;
        }
    } 
}