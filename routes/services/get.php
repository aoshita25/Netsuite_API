<?php
require_once "controllers/get.controller.php";
require_once "PHPToolkit/NetSuiteService.php";

$type = $_GET["type"];
$id = $_GET["id"];
$response = GetController::getData($type,$id);

