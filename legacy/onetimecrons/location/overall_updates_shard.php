<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

//INCLUDE FILES HERE
include_once($_SERVER['DOCUMENT_ROOT']."/profile/config.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
include_once("update_functions.php");
//INCLUDE FILE ENDS

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObjM = new Mysql;
$dbM = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$mysqlObjS = new Mysql;
$dbS = $mysqlObjS->connect("slave") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

$filename = "overall_updates.csv";		//This filename to be used for overall updates of city values for merges and deletes
$data = read_file($filename);
foreach ($data as $k=>$v)
{
 	$values = explode("|",trim($v));
   	if (trim($values[0]) && trim($values[1]))
    	{
             	$old_value[] = trim($values[0]);
            	$new_value[] = trim($values[1]);
      	}
}

$activeServerId = $_SERVER['argv'][1];

        $myDbName=getActiveServerName($activeServerId,'master',$mysqlObjM);
        $shardDbM=$mysqlObjM->connect("$myDbName");
        mysql_query('set session wait_timeout=86400,interactive_timeout=86400,net_read_timeout=86400',$shardDbM);

        $myDbName=getActiveServerName($activeServerId,'slave',$mysqlObjS);
        $shardDbS=$mysqlObjS->connect("$myDbName");
        mysql_query('set session wait_timeout=86400,interactive_timeout=86400,net_read_timeout=86400',$shardDbS);

	////'ABC','BCD','FGH'
	comma_separated_type1_update($old_value,$new_value,"JPARTNER","PARTNER_CITYRES","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$shardDbM,$shardDbS);
	echo "SHARD".$activeServerId." newjs|JPARTNER|PARTNER_CITYRES|PROFILEID \n";
	
	mysql_close($shardDbS);
	mysql_close($shardDbM);

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
mysql_close($dbS);
//CLOSING ENDS
?>
