<?php

require_once '../PHPToolkit/NetSuiteService.php';

$service = new NetSuiteService();

$customer = new Customer();
$customer->lastName = "Doe";
$customer->firstName = "John";
$customer->companyName = "ABC company XX ";
$customer->phone = "123456789";

$subsidiary = new Subsidiary();
$subsidiary->internalId = 1;

$customer->subsidiary = $subsidiary;

$request = new AddRequest();
$request->record = $customer;

$addResponse = $service->add($request);

if (!$addResponse->writeResponse->status->isSuccess) {
    echo "ADD ERROR";
} else {
    echo "ADD SUCCESS, id " . $addResponse->writeResponse->baseRef->internalId;
}

?> 

