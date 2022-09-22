<?php
require_once 'class/getInventory.php';
require_once 'class/getInventory.php';
require_once 'class/getPurchaseOrder.php';
require_once 'class/getDevolucion.php';
require_once 'class/getTransferOrder.php';

class GetModel{

    static public function getData($type,$id){

        define("SERVER","20.94.40.28");
        define("PORT","21");
        define("USER","wow");
        define("PASSWORD","W0VV$@792022");

        $id_ftp=ftp_connect(SERVER,PORT) or die("No se pudo conectar");
        ftp_login($id_ftp,USER,PASSWORD);
        ftp_pasv($id_ftp,true);

        switch ($type){

            case "articulo":
                ftp_chdir($id_ftp, "/IN/INVENTORY/TEST");
                $dir = ftp_pwd($id_ftp);
                $fileFrom = getInventory::item($id);
                $fileTo = $dir."/".$fileFrom;
                $upload = ftp_put($id_ftp, $fileTo, "../".$fileFrom, FTP_ASCII);
                if(!$upload) {
                    return 'Upload failed!';
                } else{
                    return 'Upload success';
                }
                break;
    
            case "devolucion":
                ftp_chdir($id_ftp, "/IN/RETURN/TEST");
                $dir = ftp_pwd($id_ftp);
                [$fileFromCab, $fileFromDet] = getDevolucion::item($id);
                $fileToCab = $dir."/".$fileFromCab;
                $fileToDet = $dir."/".$fileFromDet;
                $uploadCab = ftp_put($id_ftp, $fileToCab, $fileFromCab, FTP_ASCII);
                $uploadDet = ftp_put($id_ftp, $fileToDet, $fileFromDet, FTP_ASCII);
                if($fileFromCab == 0){
                    return "";
                }
                elseif (!$uploadCab || !$uploadDet) {
                    return 'Upload failed!';
                } else {
                    return 'Upload success';
                }
                break;
    
            case "orden":
                ftp_chdir($id_ftp, "/IN/SOURCE/TEST");
                $dir = ftp_pwd($id_ftp);
                [$fileFromCab, $fileFromDet] = getPurchaseOrder::item($id);
                $fileToCab = $dir."/".$fileFromCab;
                $fileToDet = $dir."/".$fileFromDet;
                $uploadCab = ftp_put($id_ftp, $fileToCab, $fileFromCab, FTP_ASCII);
                $uploadDet = ftp_put($id_ftp, $fileToDet, $fileFromDet, FTP_ASCII);
                if($fileFromCab == 0){
                    return "";
                }
                elseif (!$uploadCab || !$uploadDet) {
                    return 'Upload failed!';
                } else {
                    return 'Upload success';
                }
                break;
    
            case "despacho":
                ftp_chdir($id_ftp, "/IN/DELIVERY/TEST");
                $dir = ftp_pwd($id_ftp);
                [$fileFromCab, $fileFromDet] = getTransferOrder::item($id);
                $fileToCab = $dir."/".$fileFromCab;
                $fileToDet = $dir."/".$fileFromDet;
                $uploadCab = ftp_put($id_ftp, $fileToCab, $fileFromCab, FTP_ASCII);
                $uploadDet = ftp_put($id_ftp, $fileToDet, $fileFromDet, FTP_ASCII);
                if($fileFromCab == 0){
                    return "";
                }
                elseif (!$uploadCab || !$uploadDet) {
                    return 'Upload failed!';
                } else {
                    return 'Upload success';
                }
                break;
        }
        ftp_quit($id_ftp);
    } 
}