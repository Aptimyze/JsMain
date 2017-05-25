<?php
//INCLUDE FILES HERE
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");

include_once("$_SERVER[DOCUMENT_ROOT]/profile/config.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/dropdowns.php");
//INCLUDE FILE ENDS

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObjM = new Mysql;
$dbM = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);
$table = array("newjs.SEARCH_MALE","newjs.SEARCH_FEMALE");
foreach($table as $k=>$v)
{
	$statement = "SELECT PROFILEID,CASTE FROM ".$v;
	$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);
	while($row = $mysqlObjM->fetchArray($result))
	{
		$caste = getGroupNames($row["CASTE"]);
		if($caste)
		{
			$sql = "UPDATE ".$v." SET CASTE_GROUP = \"".$caste."\" WHERE PROFILEID = ".$row["PROFILEID"];
			$mysqlObjM->executeQuery($sql,$dbM) or die($sql);
		}
	}
echo $v." DONE\n";
}


//CLOSE DATABASE CONNECTION
mysql_close($dbM);
//CLOSING ENDS

function getGroupNames($caste)
{
	global $CASTE_GROUP_ARRAY;
	foreach($CASTE_GROUP_ARRAY as $k=>$v)
	{
		$casteArr = explode(",",$v);
		foreach($casteArr as $kk=>$vv)
		{
			if($vv == $caste || $k == $caste)
			{
				$casteGrp[] = $k;
				break;
			}
		}
	}
	if($casteGrp)
		return implode(",",$casteGrp);
	else
		return 0;
}
?>
