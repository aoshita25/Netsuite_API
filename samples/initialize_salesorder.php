<?php

require_once '../PHPToolkit/NetSuiteService.php';

$service = new NetSuiteService();

$initRef = new InitializeRef();
$initRef->type = "salesOrder";
$initRef->internalId = 6; 

$initRec = new InitializeRecord();
$initRec->type = "cashSale";
$initRec->reference = $initRef;

$request = new InitializeRequest();
$request->initializeRecord = $initRec;

// set ignoreReadOnlyFields parameter
$service->setPreferences(false, false, false,  true);    

$initResponse = $service->initialize($request);

if (!$initResponse->readResponse->status->isSuccess) {
    echo "INITIALIZE ERROR";
} else {
    echo "INITIALIZE SUCCESS, id " . $initResponse->readResponse->record->internalId;
}

?> 

