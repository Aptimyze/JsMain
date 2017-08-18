<?php 

$flag_using_php5 = 1;
include_once("/usr/local/scripts/DocRoot.php");
include("config.php");
include("connect.inc");
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/classes/globalVariables.Class.php");

// Master and slave connection object
global $mysqlObjS , $mysqlObjM;


$mysqlObjM = new Mysql;
$connMaster = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$connMaster);

$mysqlObjS = new Mysql;
$connSlave = $mysqlObjS->connect("slave") or logError("Unable to connect to master","ShowErrTemplate");

mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000,group_concat_max_len = 1000000',$connSlave);

$sql="select group_concat(ID) AS ID from search.LATEST_SEARCHQUERY where GENDER NOT IN ('F','M')";
$result = $mysqlObjS->executeQuery($sql,$connSlave) or $mysqlObjS->logError($sql);

while($row = $mysqlObjS->fetchAssoc($result))
{ 
   $deleteIds=$row["ID"];
}

if($deleteIds)
{
	$sql2="delete from search.LATEST_SEARCHQUERY where ID IN(".$deleteIds.") AND GENDER NOT IN ('F','M')";
	$result = $mysqlObjM->executeQuery($sql2,$connMaster) or $mysqlObjM->logError($sql2);

}

?>
