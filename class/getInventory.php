<?php
class getInventory {

    public static function item (string $internalId){
        $service = new NetSuiteService();
        $request = new GetRequest();
        $request->baseRef = new RecordRef();
        $request->baseRef->internalId = $internalId;
        $request->baseRef->type = "inventoryItem";
        $getResponse = $service->get($request);
        $status = $getResponse->readResponse->status->statusDetail;

        if (isset($status[0]->type) && $status[0]->type == "ERROR"){
            $request = new GetRequest();
            $request->baseRef = new RecordRef();
            $request->baseRef->internalId = $internalId;
            $request->baseRef->type = "lotNumberedInventoryItem";
            $getResponse = $service->get($request);
            $status = $getResponse->readResponse->status->statusDetail;
            //Si tampoco es lote se consulta por serie
            if (isset($status[0]->type) && $status[0]->type == "ERROR"){
                $request = new GetRequest();
                $request->baseRef = new RecordRef();
                $request->baseRef->internalId = $internalId;
                $request->baseRef->type = "serializedInventoryItem";
                $getResponse = $service->get($request);
            }
        }

        if (!$getResponse->readResponse->status->isSuccess) {
            echo "OCURRIÓ UN ERROR";
        }else {
            $items = $getResponse->readResponse->record;

            $CodigoArticulo = 'WO'.$items->itemId;
            $CodigoArticuloClienteSolum = $items->internalId;
            $NombreArticulo = $items->displayName;
            $GrupoInternoSAP = '105';
            $UoMGroupEntry = '-1';
            $CodCliente = 'C20261239923';
            $Cliente = 'SEDISA S.A.C';

            if (isset($items->class->name)) {
                $FamiliaArticulo = explode(":",$items->class->name)[0];
                $SubFamiliaArticulo = explode(":",$items->class->name)[1];
            }else {
                $FamiliaArticulo = "";
                $SubFamiliaArticulo = "";
            }
            
            $TipoMercaderia = 'GE';
            $ArticuloRecepcion = 'tYES';
            $ArticuloDespacho  = 'tYES';
            $ArticuloInventariable = 'tYES';
            $GestionSeries = 'tNO';
            $GestionLotes = 'tNO';
            $UnidadMedRecepcion = trim(explode("(",$items->unitsType->name)[1],")");
            $UnidadInventario = trim(explode("(",$items->unitsType->name)[0]);

            switch (trim(explode("(",$items->unitsType->name)[1],")")) {
                case "CAJAS":
                    $UnidadMedRecepcion = 'CAJ';
                    break;
            }

            switch (trim(explode("(",$items->unitsType->name)[0])) {
                case "UNIDAD":
                    $UnidadInventario ='UND';
                    $UnidadDespacho = 'UND';
                    break;
            }
            $GestionEnTodasTransacciones = 'bomm_OnEveryTransaction';
            $Planeamiento = 'bop_MRP';
            $Abastecimiento = 'bom_Buy';
            $PorAlmacen = 'Y';
        }

        $data = array(
            'CodigoArticulo'              => $CodigoArticulo,
            'CodigoArticuloClienteSolum'  => $CodigoArticuloClienteSolum,
            'NombreArticulo'              => $NombreArticulo,
            'GrupoInternoSAP'             => $GrupoInternoSAP,
            'UoMGroupEntry'               => $UoMGroupEntry,
            'CodCliente'                  => $CodCliente,
            'Cliente'                     => $Cliente,
            'FamiliaArticulo'             => $FamiliaArticulo,
            'SubFamiliaArticulo'          => $SubFamiliaArticulo,
            'TipoMercaderia'              => $TipoMercaderia,
            'ArticuloRecepcion'           => $ArticuloRecepcion,
            'ArticuloDespacho'            => $ArticuloDespacho,
            'ArticuloInventariable'       => $ArticuloInventariable,
            'GestionSeries'               => $GestionSeries,
            'GestionLotes'                => $GestionLotes,
            'UnidadMedRecepcion'          => $UnidadMedRecepcion,
            'UnidadInventario'            => $UnidadInventario,
            'UnidadDespacho'              => $UnidadDespacho,
            'GestionEnTodasTransacciones' => $GestionEnTodasTransacciones,
            'Planeamiento'                => $Planeamiento,
            'Abastecimiento'              => $Abastecimiento,
            'PorAlmacen'                  => $PorAlmacen
        );
        
        $headers = "";
        $detalle = "";

        foreach ($data as $key => $value) {

            if (mb_strlen($key) > mb_strlen($value)) {
                $detalle .= str_pad($value, mb_strlen($key));
                $headers .= $key;
            }elseif (mb_strlen($key) < mb_strlen($value)) {
                $headers .= str_pad($key, mb_strlen($value));
                $detalle .= $value;
            }

            $headers .= "\t";
            $detalle .= "\t";
        }

        $texto = $headers."\n".$detalle;

        $today = new DateTime();
        $today->setTimezone(new DateTimeZone('America/Lima'));
        $newToday = $today->format("YmdHis");  
        //Genera archivo txt
        $filename = 'SAP_PRD_'.$newToday.'.txt';
        $fh = fopen($filename, 'w');
        fwrite($fh, $texto);
        fclose($fh);
        
        return $filename;
    }
}