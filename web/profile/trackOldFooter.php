<?php
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
$date = date("Y-m-d");
$file = fopen(sfConfig::get("sf_upload_dir")."/SearchLogs/oldFooter_".$date.".txt","a");
$errString = "LABEL = ".$label." | VALUE = ".$value;
$http_msg=print_r($_SERVER,true);
$errString = $http_msg."-->".$errString;
fwrite($file,$errString."\n");
fclose($file);
?>
