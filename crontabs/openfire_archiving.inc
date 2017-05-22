<?php
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
$tables=array("ofConversation","ofConParticipant","ofMessageArchive");
/**
 * @return required boundary conversation ID for housekeeping purpose
 * @param boolean $flag possible values are true(for once) or false (regular)
 * */

function calc_conversationID($flag){
	global $db;
	$retentionPeriod= 7;
	$currentTime=time();
	$retentionPeriod=$retentionPeriod*24*60*60;
	$retentionDateSec=$currentTime-$retentionPeriod;
	$retentionDate=bcmul($retentionDateSec,1000);
	if($flag){
		//for one time operation We will do insert and rename. We will keep the current database tables as backup tables
	log_openfire("SELECT MIN(conversationID) as minID from openfire.ofConversation where lastActivity >$retentionDate");
	$result=mysql_query("SELECT MIN(conversationID) as minID from openfire.ofConversation where lastActivity >$retentionDate",$db);
	log_openfire(mysql_error());
	$row=mysql_fetch_array($result);
	return $row['minID'];
	}else {
		//For subsequent queries we will do insert ops in backup from current tables and delete operation on current tables. 
	log_openfire("SELECT MAX(conversationID) as maxID from openfire.ofConversation where lastActivity <$retentionDate");
	$result=mysql_query("SELECT MAX(conversationID) as maxID from openfire.ofConversation where lastActivity <$retentionDate",$db);
	log_openfire(mysql_error());
	$row=mysql_fetch_array($result);
	return $row['maxID'];
	}
}
function openfire_housekeeping(){
	global $tables;
	global $db;
	$conversionID=calc_conversationID(false);
	foreach($tables as $table){
		$tmp="SELECT * from openfire.$table where conversationID < $conversionID";
		$backupTableName=$table."backup";
	log_openfire("REPLACE INTO openfire.$backupTableName ".$tmp);
	mysql_query("REPLACE INTO openfire.$backupTableName ".$tmp,$db); 
	log_openfire(mysql_error());
	log_openfire("DELETE FROM openfire.$table where conversationID < $conversionID");
	mysql_query("DELETE FROM openfire.$table where conversationID < $conversionID");
	log_openfire(mysql_error());
	}
}
function one_time(){
	global $db;
	global $tables;
	$minconversionId=calc_conversationID(true);
	$dbDDL = connect_ddl();
	foreach($tables as $table){
	$backupTableName=$table."backup";
	log_openfire("CREATE TABLE empty$table LIKE $table");
	mysql_query("CREATE TABLE empty$table LIKE $table",$db);
	log_openfire(mysql_error());
	log_openfire("RENAME TABLE $table TO $backupTableName,empty$table TO $table");
	mysql_query("RENAME TABLE $table TO $backupTableName,empty$table TO $table");
	log_openfire(mysql_error());
	}
foreach ($tables as $table){
	$backupTableName=$table."backup";
	$tmp="SELECT * from $backupTableName where conversationID >=$minconversionId";
	$op_query="INSERT IGNORE INTO $table ".$tmp;
	log_openfire($op_query);
	mysql_query($op_query,$db);
	log_openfire(mysql_error());
	log_openfire("DELETE FROM $backupTableName where conversationID >=$minconversionId");
	mysql_query("DELETE FROM $backupTableName where conversationID >=$minconversionId",$db);
	log_openfire(mysql_error());
}
log_openfire("DROP INDEX entMsgArchive_con_idx ON ofMessageArchivebackup");
mysql_query("DROP INDEX entMsgArchive_con_idx ON ofMessageArchivebackup",$dbDDL);
log_openfire(mysql_error());
}
function log_openfire($message)
{
	global $logHandle;
	if($logHandle){
	if($message){
		$now=date("d-m-Y:H.i.s");
		fwrite($logHandle,$now."-->".$message."\n");
	}
	}
}
