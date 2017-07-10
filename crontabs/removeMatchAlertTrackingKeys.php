<?php
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once("connect.inc");
$backDate = date("Y-m-d",strtotime("-1 month"));
$currentDate = date("Y-m-d");

$memcacheObj = JsMemcache::getInstance();
while((strtotime($currentDate)-strtotime($backDate)) >= 0)
{	
	$memcacheObj->remove("MatchAlertTracking_MS_".$backDate);
	$memcacheObj->remove("MatchAlertTracking_P_".$backDate);
	$memcacheObj->remove("MatchAlertTrackingNotFromMailer__MS_".$backDate);
	$memcacheObj->remove("MatchAlertTrackingNotFromMailer__P_".$backDate);
	$backDate = date("Y-m-d", strtotime($backDate . ' +1 day'));	
}
