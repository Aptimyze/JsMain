<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/jsadmin/connect.inc");
include_once($_SERVER["DOCUMENT_ROOT"]."/classes/Services.class.php");
$serviceObj = new Services;
$services_amount = $serviceObj->getServicesAmount($serviceid,$curtype);
echo $services_amount[$serviceid]["PRICE"];
?>