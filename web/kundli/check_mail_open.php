<?php
//INCLUDE FILES HERE
include_once("TrackingFunctions.class.php");
//INCLUDE FILES HERE

$trackingFunctionsObj = new TrackingFunctions();
$trackingFunctionsObj->trackingMis(1,3);
unset($trackingFunctionsObj);

header('Content-type: image/gif');
$photo = JsConstants::$imgUrl."/profile/images/kundli/img2.gif";
readfile($photo);
?>
