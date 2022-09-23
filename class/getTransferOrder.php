<?php
class getTransferOrder {

    public static function item (string $internalId){
        $ubigeoDesc = array(
            '150101'	=> 'Lima-Lima-Lima',  
            '150102'	=> 'Lima-Lima-Ancón',   
            '150103'	=> 'Lima-Lima-Ate',    
            '150104'	=> 'Lima-Lima-Barranco',     
            '150105'	=> 'Lima-Lima-Breña',     
            '150106'	=> 'Lima-Lima-Carabayllo',    
            '150107'	=> 'Lima-Lima-Chaclacayo',     
            '150108'	=> 'Lima-Lima-Chorrillos',     
            '150109'	=> 'Lima-Lima-Cieneguilla',     
            '150110'	=> 'Lima-Lima-Comas',     
            '150111'	=> 'Lima-Lima-El Agustino',    
            '150112'	=> 'Lima-Lima-Independencia',     
            '150113'	=> 'Lima-Lima-Jesús María',    
            '150114'	=> 'Lima-Lima-La Molina',    
            '150115'	=> 'Lima-Lima-La Victoria',    
            '150116'	=> 'Lima-Lima-Lince',     
            '150117'	=> 'Lima-Lima-Los Olivos',    
            '150118'	=> 'Lima-Lima-Lurigancho',     
            '150119'	=> 'Lima-Lima-Lurin',     
            '150120'	=> 'Lima-Lima-Magdalena del Mar',   
            '150121'	=> 'Lima-Lima-Pueblo Libre',    
            '150122'	=> 'Lima-Lima-Miraflores',     
            '150123'	=> 'Lima-Lima-Pachacamac',    
            '150124'	=> 'Lima-Lima-Pucusana',     
            '150125'	=> 'Lima-Lima-Puente Piedra',    
            '150126'	=> 'Lima-Lima-Punta Hermosa',    
            '150127'	=> 'Lima-Lima-Punta Negra',    
            '150128'	=> 'Lima-Lima-Rímac',     
            '150129'	=> 'Lima-Lima-San Bartolo',    
            '150130'	=> 'Lima-Lima-San Borja',    
            '150131'	=> 'Lima-Lima-San Isidro',    
            '150132'	=> 'Lima-Lima-San Juan de Lurigancho',  
            '150133'	=> 'Lima-Lima-San Juan de Miraflores',  
            '150134'	=> 'Lima-Lima-San Luis',    
            '150135'	=> 'Lima-Lima-San Martín de Porres',  
            '150136'	=> 'Lima-Lima-San Miguel',    
            '150137'	=> 'Lima-Lima-Santa Anita',    
            '150138'	=> 'Lima-Lima-Santa María del Mar',  
            '150139'	=> 'Lima-Lima-Santa Rosa',   
            '150140'	=> 'Lima-Lima-Santiago de Surco',   
            '150141'	=> 'Lima-Lima-Surquillo',     
            '150142'	=> 'Lima-Lima-Villa El Salvador',   
            '150143'	=> 'Lima-Lima-Villa María del Triunfo',  
            '150502'	=> 'Lima-Cañete-Asia',     
            '150504'	=> 'Lima-Cañete-Cerro Azul',    
            '150505'	=> 'Lima-Cañete-Chilca',     
            '150509'	=> 'Lima-Cañete-Mala',     
            '150513'	=> 'Lima-Cañete-San Antonio'
        );
        
        $service = new NetSuiteService();
        
        $request = new GetRequest();
        $request->baseRef = new RecordRef();
        $request->baseRef->internalId = $internalId; //1509654,1509655
        $request->baseRef->type = "transferOrder";
        $getResponse = $service->get($request);
        //echo json_encode($getResponse);
        
        if (!$getResponse->readResponse->status->isSuccess) {
            return [0,0];
        }else {
            $res = $getResponse->readResponse->record;
            //PARA GENERAR PEDIDOS CABECERA
            $dN = '1';
            if (isset($res->class->name)) {
                $clase = explode(" ",$res->class->name)[1];
            }else {
                $clase = "";
            }
            
            $tranId = $res->tranId;
            
            $fechaPedDesp = date('Ymd', strtotime(explode("T",$res->tranDate)[0]));
            $CodCliSolum = "C20605977406";
            $CodCliCliSolum = "20605977406";
            $NombreCliCliSolum = $res->transferLocation->name;
            $fechaEntrega = date('Ymd', strtotime(explode("T",$res->shipDate)[0]));
            
            $customfields = $res->customFieldList->customField;
            
            foreach ($customfields as $field) {
                switch ($field->scriptId) {
                    case "custbody24":
                        $RucCliCliSolum = $field->value;
                        break;
                    case "custbody18":
                        $DirEntrega = $field->value;
                        break;
                    case "custbody23":
                        $UbigeoDir = $field->value;
                        break;
                    case "custbody8":
                        $proyecto = $field->value->name;
                        break;
                    case "custbody19":
                        $contratista = $field->value;
                        break;
                    case "custbody20":
                        $teleCont = $field->value;
                        break;
                    case "custbody12":
                        $supervisor = $field->value->name;
                        break;
                    case "custbody22":
                        $telefSup = $field->value;
                        break;
                    default:
                    $UbigeoDir = "";
                }
            }
            
            $obs = $proyecto."|".$tranId."|".$contratista."|".$teleCont."|".$teleCont."|".$supervisor."|".$telefSup;            
            
            $field_cab = array(
                'DocNum',
                'NumAtCard',
                'U_BPP_MDTD',
                'U_BPP_MDSD',
                'U_BPP_MDCD',
                'DocDate',
                'CardCode',
                'U_BZ_CODCLI',
                'U_BZ_RUCCLIENTE',
                'U_BZ_CLIENTE',
                'Address',
                'U_BZ_UBIGEO',
                'Address2',
                'GroupNum',
                'DocDueDate',
                'U_BZ_HICITA',
                'U_BZ_FFINAL',
                'U_BZ_HFCITA',
                'U_BZ_TCANAL',
                'U_BZ_LOTEFT',
                'U_BZ_OBS',
                //'U_BZ_LOCAL',
                'U_SYP_PtoLlegada',
                'U_SYP_DocVta'
            
            
            );
            
            $data = array(
                'DocNum'                        => $dN,
                'NumeroReferenciaDocumento'     => $tranId,
                'TipoDocumento'                 => "",
                'SerieDocumento'                => "",
                'CorrelativoDocumento'          => "",
                'FechaPedidoDespacho'           => $fechaPedDesp,
                'CodigoClienteSolum'            => $CodCliSolum,
                'CodClientedelClienteSolum'     => $CodCliCliSolum,
                'RucClientedelClienteSolum'     => $RucCliCliSolum,
                'NombreClientedelClienteSolum'  => $NombreCliCliSolum,
                'DireccióndeEntrega'            => $DirEntrega,
                'UbigeoDirección'               => $UbigeoDir,
                'DireccióndeEntrega2'           => $ubigeoDesc[$UbigeoDir] ?? "",
                'CondicionesPago'               => "",
                'FechaEntrega'                  => $fechaEntrega,
                'HoraInicio'                    => "",
                'FechaFinEntrega'               => "",
                'HoraFin'                       => "",
                'TipoCanal'                     => $clase,
                'LoteFacturacion'               => "",
                'Observacion'                   => $obs,
                'Punto de Llegada'              => $DirEntrega,
                'CondicionesPago2'              => "-1",
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
            $filename_cab = 'SAP_OD_CAB_'.$newToday.'.txt';
            //Genera archivo txt
            $fh = fopen($filename_cab, 'w');
            fwrite($fh, $texto);
            fclose($fh);
            
            
            //PARA GENERAR ORDEN DETALLE
            $itemList = $res->itemList->item;
            //echo json_encode($itemList);
            
            $data = array();
            $cont = 0;
            
            $field_det = array(
                'Parentkey',
                'NumAtCard',
                'U_BPP_MDTD',
                'U_BPP_MDSD',
                'U_BPP_MDCD',
                'LineNum',
                'ItemCode',
                'BZ_DESCARTICULO',
                'Quantity',
                //'MeasureUnit',
                'UnitsOfMeasurment',
                'Currency',
                'Price',
                'TaxCode',
                'WarehouseCode',
                'UoMEntry'
            );
            
            
            foreach($itemList as $value){
                $info=array(
                    'DocNum'                        => $dN,
                    'UnidadesInventarioporCantidad' => $tranId,
                    'TipoDocumento'                 => "",
                    'SerieDocumento'                => "",
                    'CorrelativoDocumento'          => "",
                    'CorrelativoLinea'              => $cont,
                    'CodArticulo'                   => 'wo'.$value->item->internalId,
                    'DescripcionArticuloCliente'    => explode(" ",$value->item->name)[1],
                    'Cantidad'                      => $value->quantity,
                    'UnidadesInventarioporCantidad' => '1',
                    'MonedadelPrecio'               => "",
                    'PrecioporUndMedInventario'     => "",
                    'CodigoImpuesto'                => "",
                    'CodigoAlmacen'                 => 'L03',
                    'UomEntry'                      => '-1'
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
                        $detail .= str_pad($value, mb_strlen($key));
                        $header .= $key;
                        //if ($band == 1) $header .= $key;
            
                    }elseif (mb_strlen($key) < mb_strlen($value)) {
                        //if ($band == 1) $header .= str_pad($key, mb_strlen($value));
                        $header .= str_pad($key, mb_strlen($value));
                        $detail .= $value;
                    }
                    $detail .= "\t";
                    $header .= "\t";
                    */
                }
                $detail .= "\n";
                $band++;
            }
            
            $texto = $main_header_det."\n".$header."\n".$detail;
            $texto = iconv('UTF-8', 'Windows-1252', $texto);
            $today = new DateTime();
            $today->setTimezone(new DateTimeZone('America/Lima'));
            $newToday = $today->format("YmdHis");
            $filename_det = 'SAP_OD_DET_'.$newToday.'.txt';
            //Genera archivo txt
            $fh = fopen($filename_det, 'w');
            fwrite($fh, $texto);
            fclose($fh);
                
            return [$filename_cab, $filename_det];
        }
    }
}
