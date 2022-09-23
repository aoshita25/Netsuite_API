<?php

class getPurchaseOrder {

    public static function item (string $internalId){
        $service = new NetSuiteService();
        $request = new GetRequest();
        $request->baseRef = new RecordRef();
        $request->baseRef->internalId = $internalId;
        $request->baseRef->type = "purchaseOrder";
        $getResponse = $service->get($request);
        //echo json_encode($res);
        
        if (!$getResponse->readResponse->status->isSuccess) {
            return [0,0];;
        }else {
            $res = $getResponse->readResponse->record;
            //PARA GENERAR ORDEN CABECERA
            $dN = '1';
            $tranId = 'PO'.$res->tranId;
            
            $customfields = $res->customFieldList->customField;
            
            foreach ($customfields as $field) {
                if(isset($field->value->name)) {
                    switch ($field->value->typeId) {
                        case "630":
                            if ($field->value->internalId == "1" ){
                                $Origen = "N";
                            }else {
                                $Origen = "I";
                            }
                            break;
                    }
                }
            }
            $CodCliSolum = 'P20605977406';
            $CodProvCli = $res->vatRegNum;
            $NomProvCli = $res->billingAddress->addressee;
            $FechaOrdenRecibo = date('Ymd', strtotime(explode("T",$res->dueDate)[0]));
            $fechaEntrega = date('Ymd', strtotime(explode("T",$res->tranDate)[0]));
            $PaisOrigen = ucfirst(explode("_",$res->billingAddress->country)[1]);

            $country_names = json_decode(file_get_contents("http://country.io/names.json"), true);
            
            foreach($country_names as $key => $val) {
                if($val == $PaisOrigen){
                    $PaisOrigen = $key;
                    break;
                }
            }
            
            $field_cab = array(
                'DocNum',
                'NumAtCard',
                'CARDCODE',
                'U_BZ_ORIGEN',
                'U_BZ_CODPROV',
                'U_BZ_NOMPROV',
                'DOCDATE',
                'DOCDUEDATE',
                'U_BZ_PAORIGEN',
                'U_BZ_TP_CARGA',
                'U_BZ_PROCVERIF',
                'U_BZ_MUESTRA',
                'U_SYP_MDMT'
            );
            
            $data = array(
                'DocNum'                    => $dN,
                'NumPedido'                 => $tranId,
                'CodigoClienteSolum'        => $CodCliSolum,
                'OrigenOrdenRecibo'         => $Origen,
                'CodProveedorCliente'       => $CodProvCli,
                'NombreProveedorCliente'    => $NomProvCli,
                'FechaOrdenRecibo'          => $FechaOrdenRecibo,
                'Fechaentrega'              => $fechaEntrega,
                'PaisOrigen'                => $PaisOrigen,
                'TipoCarga'                 => "",
                'ProcedimientoVerificación' => "",
                'PorcentajeMuestreo'        => "",
                'Motivo de traslado'        => "02"
            );
            
            $main_header_cab = "";
            $header = "";
            $detail = "";
            
            foreach($field_cab as $value){
                $main_header_cab .= $value."\t";
            }
            
            foreach ($data as $key => $value) {
            
                $header .= $key."\t";
                $detail .= $value."\t";
                /*
                if (mb_strlen($key) > mb_strlen($value)) {
                    $detalle .= str_pad($value, mb_strlen($key));
                    $headers .= $key;
                }elseif (mb_strlen($key) < mb_strlen($value)) {
                    $headers .= str_pad($key, mb_strlen($value));
                    $detalle .= $value;
                }
                $headers .= "\t";
                $detalle .= "\t";
                    */
            }
            
            $texto = $main_header_cab."\n".$header."\n".$detail;
            
            $today = new DateTime();
            $today->setTimezone(new DateTimeZone('America/Lima'));
            $newToday = $today->format("YmdHis");
            $filename_cab = 'SAP_OI_CAB_'.$newToday.'.txt';
            //Genera archivo txt
            $fh = fopen($filename_cab, 'w');
            fwrite($fh, $texto);
            fclose($fh);
            
            
            //PARA GENERAR ORDEN DETALLE
            if(isset($res->currencyName)){
                switch ($res->currencyName) {
                    case "US Dollar":
                        $currencyName = 'USD';
                        break;
                    case "Sol":
                        $currencyName = 'SOL';
                        break;
                    default:
                        $currencyName = "";
            
                }
            }else{
                $currencyName = "";
            }

            $itemList = $res->itemList->item;
            //echo json_encode($itemList);
            
            $data = array();
            $cont = 0;
            
            $field_det = array(
                'ParentKey',
                'NumPedido',
                'LineNum',
                'ItemCode',
                'QUANTITY',
                'MeasureUnit',
                'UnitsOfMeasurment',
                'Currency',
                'Price',
                'U_BZ_TRA_ESPECIAL',
                'WarehouseCode',
                'UoMEntry'
            );
            
            foreach($itemList as $value){
                $info=array(
                    'DocNum'                        => $dN,
                    'NumPedido'                     => $tranId,
                    'CorrelativoLinea'              => $cont,
                    'CodigoArticulo'                => 'wo'.$value->item->internalId,
                    'Cantidad'                      => $value->quantity,
                    'UnidadMedidadeCantidad'	    => "UND", //cambiar,solo es referecial
                    'UnidadesInventarioporCantidad' => '1',
                    'MonedaPrecio'                  => $currencyName,
                    'PrecioporCantidad'             => $value->rate,
                    'TratamientoEspecial'           => 'N',
                    'CodigoAlmacen'                 => 'L01',
                    'UomEntry'                      => '-1',
                );
                $cont++;
                array_push($data, $info);
            }
            
            $main_header_det = "";
            $header = "";
            $detail = "";
            $band = 1;
            
            foreach($field_det as $value){
                $main_header_det .= $value."\t";
            }
            
            foreach ($data as $lista) {
                foreach ($lista as $key => $value) {
            
                    if ($band == 1) {
                        $header .= $key."\t";
                    }
                    $detail .= $value."\t";
                    /*
                    if (mb_strlen($key) > mb_strlen($value)) {
                        $detalle .= str_pad($value, mb_strlen($key));
                        if ($band == 1) {
                            $headers .= $key;
                            $headers .= "\t";
                        }
                    }elseif (mb_strlen($key) < mb_strlen($value)) {
                        if ($band == 1) {
                            $headers .= str_pad($key, mb_strlen($value));
                            $headers .= "\t";
                        }
                        $detalle .= $value;
                    }
                    $detalle .= "\t";
                    */
                }
                $detail .= "\n";
                $band++;
            }
            
            $texto = $main_header_det."\n".$header."\n".$detail;
            
            $today = new DateTime();
            $today->setTimezone(new DateTimeZone('America/Lima'));
            $newToday = $today->format("YmdHis");
            $filename_det = 'SAP_OI_DET_'.$newToday.'.txt';
            //Genera archivo txt
            $fh = fopen($filename_det, 'w');
            fwrite($fh, $texto);
            fclose($fh);

            return [$filename_cab, $filename_det];
        }
    }
}