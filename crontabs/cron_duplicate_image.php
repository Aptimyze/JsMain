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
//include_once("../profile/config.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/classes/Mysql.class.php");
//INCLUDE FILE ENDS

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObj = new Mysql;
$db = $mysqlObj->connect("slave") or die(mysql_error());//logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
//CONNECTION MAKING ENDS

$time = "2012-02-16 00:00:00";

$statement = "select PROFILEID from newjs.PICTURE_NEW WHERE UPDATED_TIMESTAMP>=\"".$time."\" GROUP BY PROFILEID HAVING COUNT(PROFILEID)>1";
$result = $mysqlObj->executeQuery($statement,$db) or die(mysql_error($statement));

while($row = $mysqlObj->fetchArray($result))
{
	$profileIds[] = $row["PROFILEID"];	
}
$profileIdStr = implode(",",$profileIds);

$statement1 = "SELECT PROFILEID,MainPicUrl FROM newjs.PICTURE_NEW WHERE PROFILEID IN (".$profileIdStr.") AND UPDATED_TIMESTAMP>=\"".$time."\" ORDER BY FIELD(PROFILEID,".$profileIdStr.")";
$result1 = $mysqlObj->executeQuery($statement1,$db) or die(mysql_error($statement1));

while($row1 = $mysqlObj->fetchArray($result1))
{
	$resultArr[$row1["PROFILEID"]][] = $row1["MainPicUrl"];
}

foreach($profileIds as $k=>$v)
{
	foreach($resultArr[$v] as $kk=>$vv)
	{
		if($kk==0)
			$source_content = file_get_contents($vv);
		else
			$other_content = file_get_contents($vv);
		if($kk!=0)
		{
			if($source_content == $other_content)
			{
				echo $v.",";
				unset($other_content);
				$outputArr[] = $v;
				
				break;
			}
		}
		unset($other_content);
	}
	unset($source_content);
}

//$outputArrStr = implode(",",$outputArr);
//echo $outputArrStr;
?>
