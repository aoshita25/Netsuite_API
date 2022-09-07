<?php
require_once 'class/getInventory.php';
require_once 'PHPToolkit/NetSuiteService.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

if(isset($_POST["id"]) && isset($_POST["tipo"])){

    $internal_id = $_POST["id"];

    switch ($_POST["tipo"]){
        case "articulo":
            //$inventArticle = getInventory::item($internal_id);
            //print_r($inventArticle);
            $service = new NetSuiteService();
            $request = new GetRequest();
            $request->baseRef = new RecordRef();
            $request->baseRef->internalId = $internal_id;
            $request->baseRef->type = "inventoryItem";
            $getResponse = $service->get($request);

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
                $FamiliaArticulo = explode(":",$items->class->name)[0];
                $SubFamiliaArticulo = explode(":",$items->class->name)[1];
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
            
            $data = array([
                $CodigoArticulo,
                $CodigoArticuloClienteSolum,
                $NombreArticulo,
                $GrupoInternoSAP,
                $UoMGroupEntry,
                $CodCliente,
                $Cliente,
                $FamiliaArticulo,
                $SubFamiliaArticulo,
                $TipoMercaderia,
                $ArticuloRecepcion,
                $ArticuloDespacho,
                $ArticuloInventariable,
                $GestionSeries,
                $GestionLotes,
                $UnidadMedRecepcion,
                $UnidadInventario,
                $UnidadDespacho,
                $GestionEnTodasTransacciones,
                $Planeamiento,
                $Abastecimiento,
                $PorAlmacen
            ]);
            //print_r($data);
            
            $spreadsheet = new Spreadsheet();
            $spreadsheet->setActiveSheetIndex(0);

            $sheet = $spreadsheet->getActiveSheet();

            for ($i = 0, $l = sizeof($data); $i < $l; $i++) { // row $i
                $j = 0;
                foreach ($data[$i] as $k => $v) { // column $j
                    $sheet->setCellValueByColumnAndRow($j + 1, ($i + 1 + 1), $v);
                    $j++;
                }
            }
            print_r($data);
            /*
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$file.'.csv"');
            header('Cache-Control: max-age=0');
            
            $writer = IOFactory::createWriter($spreadsheet, 'Csv');
            //$writer->save('php://output');*/
            break;
    }
    
}
?>