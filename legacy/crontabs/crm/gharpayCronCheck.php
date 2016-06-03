<?php
/* Description:-Cron script for sending all the confirmed requests to skypak for pickups */

$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include ("$docRoot/crontabs/connect.inc");
include($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include($_SERVER['DOCUMENT_ROOT']."/crm/common.inc");
include($_SERVER['DOCUMENT_ROOT']."/crm/func_sky.php");	

$db =connect_db();


	$sql ="SELECT SENT_TO FROM incentive.INVOICE_TRACK WHERE TIME>='2013-04-23 00:00:00' AND SENT_BY='cron_script'";
	$result=mysql_query_decide($sql) or logError("$sql".mysql_error_js());
	$row=mysql_fetch_array($result);
	$idStr =$row['SENT_TO'];
		
	$sql1 ="select PROFILEID,ID from incentive.PAYMENT_COLLECT where ID in($idStr)";
	$result1=mysql_query_decide($sql1) or logError("$sql1".mysql_error_js());
	while($row1=mysql_fetch_array($result1))
	{	
		$profileid =$row1['PROFILEID'];
		$idVal	   =$row1['ID'];		

		$sql2 ="select ID,ARAMEX_DT,ENTRY_DT,ENTRYBY from incentive.LOG where PROFILEID='$profileid' order by ID desc limit 1";
		$result2=mysql_query_decide($sql2) or logError("$sql2".mysql_error_js());
		if($row2=mysql_fetch_array($result2))
		{
			$armex_dt =$row2['ARAMEX_DT'];
			$entry_dt =$row2['ENTRY_DT'];
			$entry_by =$row2['ENTRYBY'];
			$idLog =$row2['ID'];
		}

		//$sql3 ="UPDATE incentive.PAYMENT_COLLECT set AR_GIVEN='',ARAMEX_DT='$armex_dt',ENTRYBY='$entry_by',ENTRY_DT='$entry_dt' where ID='$idVal'";
		//echo "\n";	
		//mysql_query_decide($sql3) or logError("$sql3".mysql_error_js());

		echo $sql4 ="delete from incentive.LOG where ID='$idLog'";
		echo "\n";	
		mysql_query_decide($sql4) or logError("$sql4".mysql_error_js());	 
	}




?>
