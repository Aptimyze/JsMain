<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/*
@author: Kumar Anand
*/

//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible

//INCLUDE FILES HERE
include_once("../htdocs/profile/config.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/classes/Mysql.class.php");
//INCLUDE FILE ENDS

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObjM = new Mysql;
$dbM = $mysqlObjM->connect("master") or die(mysql_error());//logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);
//CONNECTION MAKING ENDS

$statement = "SELECT distinct i.PROFILEID AS PROFILEID,j.HAVEPHOTO AS HAVEPHOTO,j.PHOTOSCREEN AS PHOTOSCREEN FROM ((MIS.INVALID_SCREENING_ENTRIES_TRACKING i INNER JOIN newjs.PICTURE_FOR_SCREEN_NEW p ON i.PROFILEID = p.PROFILEID) LEFT JOIN newjs.JPROFILE j ON i.PROFILEID = j.PROFILEID)";
$result = $mysqlObjM->executeQuery($statement,$dbM) or die(mysql_error($statement));

while($row = $mysqlObjM->fetchArray($result))
{
	if($row["HAVEPHOTO"]=="N" || $row["HAVEPHOTO"]=="")
	{
		$row["HAVEPHOTO"] = "U";
	}
	$update_statement = "UPDATE newjs.JPROFILE SET PHOTOSCREEN = 0,HAVEPHOTO = \"".$row["HAVEPHOTO"]."\" WHERE PROFILEID = ".$row["PROFILEID"];
	$delete_statement = "DELETE FROM MIS.INVALID_SCREENING_ENTRIES_TRACKING WHERE PROFILEID = ".$row["PROFILEID"];
	$mysqlObjM->executeQuery($update_statement,$dbM) or die(mysql_error($update_statement));
	$mysqlObjM->executeQuery($delete_statement,$dbM) or die(mysql_error($delete_statement));
	echo $row["PROFILEID"];
}

?>
