<?php
require_once '../PHPToolkit/NetSuiteService.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

$service = new NetSuiteService();
$request = new GetRequest();
$request->baseRef = new RecordRef();
$request->baseRef->internalId = "1047664";
$request->baseRef->type = "purchaseOrder";
$getResponse = $service->get($request);
$res = $getResponse->readResponse->record;
//echo json_encode($res);

$dN = '1';
$tranId = 'PO'.$res->tranId;
$Origen = 'N';
$CodProvCli = '20608901567';
$NomProvCli = $res->billingAddress->addressee;
$FechaOrdenRecibo = date('Ymd', strtotime($res->dueDate));

switch ($res->currencyName) {
    case "US Dollar":
        $currencyName = 'USD';
        break;
}

$itemList = $res->itemList->item;
//echo json_encode($itemList);

$data = array();
$cont = 0;
foreach($itemList as $value){
    $info=array(
        $dN,
        $tranId,
        $cont,
        'wo'.$value->item->internalId,
        $value->quantity,
        $currencyName,
        'N',
        'L01',
        '-1'
    );
    $cont++;
    array_push($data, $info);
}

$headers = array(
    'DocNum',
    'NumPedido',
    'CorrelativoLinea',
    'CodigoArticulo',
    'Cantidad',
    'MonedaPrecio',
    'TratamientoEspecial',
    'CodigoAlmacen',
    'UomEntry'
);

$spreadsheet = new Spreadsheet();
$spreadsheet->setActiveSheetIndex(0);
$sheet = $spreadsheet->getActiveSheet();

for ($i = 0, $l = sizeof($headers); $i < $l; $i++) {
    $sheet->setCellValueByColumnAndRow($i + 1, 1, $headers[$i]);
}

for ($i = 0, $l = sizeof($data); $i < $l; $i++) { // row $i
    $j = 0;
    foreach ($data[$i] as $k => $v) { // column $j
        $sheet->setCellValueByColumnAndRow($j + 1, ($i + 1 + 1), $v);
        $j++;
    }
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="data.csv"');
header('Cache-Control: max-age=0');
$writer = IOFactory::createWriter($spreadsheet, 'Csv');
$writer->save('php://output');

?>