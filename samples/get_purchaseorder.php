<?php
require_once '../PHPToolkit/NetSuiteService.php';
require_once '../class/DownloadExcel.php';
require '../vendor/autoload.php';

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

DownloadExcel::createExcel($data, $headers);
?>