<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

include('../connect.inc');
$db = connect_db();
$db_slave = connect_slave();

// BACKUP EXISTING DATA BEFORE MODIFICATION
$replicateTablePur = "CREATE TABLE billing.PURCHASES_BACKUP_FEB_2016 LIKE billing.PURCHASES";
mysql_query($replicateTablePur,$db);
$populateTablePur = "INSERT INTO billing.PURCHASES_BACKUP_FEB_2016 SELECT * FROM billing.PURCHASES";
mysql_query($populateTablePur,$db);
// END BACKUP

$sql = 'SELECT * FROM billing.PURCHASES ORDER BY ENTRY_DT DESC';
$res = mysql_query($sql,$db_slave) or die(mysql_error($db_slave));
while($row = mysql_fetch_array($res)){
	
	$billid = $row['BILLID'];
	$entry_dt = $row['ENTRY_DT'];
	$entry_dt = strtotime($entry_dt);

	if(strtotime('1994-01-07 00:00:00') <= $entry_dt && $entry_dt <= strtotime('2003-05-13 23:59:59')){
		$sql2="UPDATE billing.PURCHASES SET TAX_RATE = '5' WHERE BILLID = {$billid}"; 
		$res2=mysql_query($sql2,$db) or die(mysql_error($db)); 	
	}
	
	if(strtotime('2003-14-05 00:00:00') <= $entry_dt && $entry_dt <= strtotime('2004-09-09 23:59:59')){
		$sql2="UPDATE billing.PURCHASES SET TAX_RATE = '8' WHERE BILLID = {$billid}"; 
		$res2=mysql_query($sql2,$db) or die(mysql_error($db)); 	
	}

	if(strtotime('2004-09-10 00:00:00') <= $entry_dt && $entry_dt <= strtotime('2006-04-17 23:59:59')){
		$sql2="UPDATE billing.PURCHASES SET TAX_RATE = '10.20' WHERE BILLID = {$billid}"; 
		$res2=mysql_query($sql2,$db) or die(mysql_error($db)); 	
	}

	if(strtotime('2006-04-18 00:00:00') <= $entry_dt && $entry_dt <= strtotime('2007-05-10 23:59:59')){
		$sql2="UPDATE billing.PURCHASES SET TAX_RATE = '12.24' WHERE BILLID = {$billid}"; 
		$res2=mysql_query($sql2,$db) or die(mysql_error($db)); 	
	}
	
	if(strtotime('2007-05-11 00:00:00') <= $entry_dt && $entry_dt <= strtotime('2009-02-23 23:59:59')){
		$sql2="UPDATE billing.PURCHASES SET TAX_RATE = '12.36' WHERE BILLID = {$billid}"; 
		$res2=mysql_query($sql2,$db) or die(mysql_error($db)); 	
	}

	if(strtotime('2009-24-02 00:00:00') <= $entry_dt && $entry_dt <= strtotime('2012-03-31 23:59:59')){
		$sql2="UPDATE billing.PURCHASES SET TAX_RATE = '10.30' WHERE BILLID = {$billid}"; 
		$res2=mysql_query($sql2,$db) or die(mysql_error($db)); 	
	}

	if(strtotime('2012-04-01 00:00:00') <= $entry_dt && $entry_dt <= strtotime('2015-05-30 23:59:59')){
		$sql2="UPDATE billing.PURCHASES SET TAX_RATE = '12.36' WHERE BILLID = {$billid}"; 
		$res2=mysql_query($sql2,$db) or die(mysql_error($db)); 	
	}

	if(strtotime('2015-06-01 00:00:00') <= $entry_dt && $entry_dt <= strtotime('2015-11-15 23:59:59')){
		$sql2="UPDATE billing.PURCHASES SET TAX_RATE = '14' WHERE BILLID = {$billid}"; 
		$res2=mysql_query($sql2,$db) or die(mysql_error($db)); 	
	}

	if(strtotime('2015-11-16 00:00:00') <= $entry_dt){
		$sql2="UPDATE billing.PURCHASES SET TAX_RATE = '14.50' WHERE BILLID = {$billid}"; 
		$res2=mysql_query($sql2,$db) or die(mysql_error($db)); 	
	} 

	unset($billid, $entry_dt);
}


?>
