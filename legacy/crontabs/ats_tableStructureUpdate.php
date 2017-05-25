<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


/***************************************************************************************************************
* FILE NAME     : ats_tableStructureUpdate.php,every 2 months scheduled cron 
* DESCRIPTION   : Script for creating the new underlying tables for ATS logging with the name ATS_LOGGING_% and alter the structure of table ATS   
****************************************************************************************************************/

$flag_using_php5=1;
include_once("connect.inc");

$db =connect_db();
//$db =connect_misdb();
$tableNameVal 	=array();

// Get the tables names of ATS logging
$sql ="SHOW TABLES FROM MIS LIKE 'ATS_LOGGER_%'";
$res=mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed.");
while($myrow=mysql_fetch_array($res))
{
	$tableName 	=$myrow[0];
	$tableNameArr 	=explode("_",$tableName);
	$tableNameVal[] =$tableNameArr['2'];
}

$tableValue =max($tableNameVal);
$oldTableName ='ATS_LOGGER_'.$tableValue;
$newTableVal=$tableValue+1;
$newTableName ='ATS_LOGGER_'.$newTableVal;

// Create table structure for the new table
$sql ="CREATE TABLE MIS.$newTableName LIKE MIS.$oldTableName";
mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed.");


// Alter the table structure of ATS table for underlying  MYISAM tables
$sql1 ="ALTER TABLE MIS.ATS UNION=(MIS.$oldTableName,MIS.$newTableName)";
mysql_query($sql1,$db) or logError("Due to a temporary problem your request could not be processed."); 


?>
