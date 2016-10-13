<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/sms_inc.php");
include($_SERVER['DOCUMENT_ROOT']."/classes/SMSLib.class.php");

$master=connect_ddl();
$SMSLib = new SMSLib("S");
$sql= "TRUNCATE TABLE TEMP_SMS_DETAIL";
$res=mysql_query($sql,$master) or $SMSLib->errormail($sql,mysql_errno().":".mysql_error());
?>

