<?php
/*********************************************************************************************
* FILE NAME   	: obSalesDailyUpdateProcess.php 
* DESCRIPTION 	: Change the Dialer Dial status to 0
* MADE BY     	: MANOJ RANA 
*********************************************************************************************/
include_once("MysqlDbConstants.class.php");

//Open connection at JSDB
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],MysqlDbConstants::$dialer['USER'],MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");

$date       	=date("Y-m-d",time()-24*60*60);
$campaignName	='OB_Sales';

// Stop profiles which are 24 hours old
$query1 = "UPDATE easy.dbo.ct_$campaignName SET Dial_Status=0 WHERE Dial_Status=1 and Lead_id<='$date'";
mssql_query($query1,$db_dialer)  or $dialerLogObj->logError($query1,$campaignName,$db_dialer,1);

?>
