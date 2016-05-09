<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");

//INCLUDE FILES HERE
//include_once("$_SERVER[DOCUMENT_ROOT]/profile/config.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
//include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
//include_once($_SERVER['DOCUMENT_ROOT']."/profile/mysql_multiple_connections.php");
//INCLUDE FILE ENDS

if(!isset($_SERVER['argv'][1]))
        die("Please Specify Argumnets\n");

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObj = new Mysql;
$dbM = $mysqlObj->connect("master") or die("Unable to connect to master");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$dbS = $mysqlObj->connect("slave") or die("Unable to connect to slave");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

if($_SERVER['argv'][1] == 1)
	$table = array('SEARCH_FEMALE');	
elseif($_SERVER['argv'][1] == 2)
	$table = array('SEARCH_MALE');

foreach($table as $k=>$v)
{
	$statement = "SELECT PROFILEID FROM newjs.".$v." WHERE PRIVACY='C' OR LAST_LOGIN_DT < DATE_SUB(CURDATE(), INTERVAL 5 MONTH) OR INCOME = '0' OR OCCUPATION = '0' OR EDU_LEVEL_NEW = '0' OR RELIGION = '' OR HEIGHT = '0' OR COUNTRY_RES = '0' OR MSTATUS = '' OR RELATION = '' OR MTONGUE = '0' OR CASTE = '0'";
        $result = $mysqlObj->executeQuery($statement,$dbM) or $mysqlObj->logError($statement);
        while($row = $mysqlObj->fetchArray($result))
        {
		$statement1 = "DELETE FROM newjs.".$v." WHERE PROFILEID = ".$row["PROFILEID"];
		$mysqlObj->executeQuery($statement1,$dbM) or $mysqlObj->logError($statement1);
	}

	$statement2 = "SELECT S.PROFILEID FROM newjs.".$v." S LEFT JOIN newjs.JPROFILE J ON S.PROFILEID=J.PROFILEID WHERE J.ACTIVATED!='Y'";
	$result2 = $mysqlObj->executeQuery($statement2,$dbS) or $mysqlObj->logError($statement2);
	while($row2 = $mysqlObj->fetchArray($result2))
        {
		$statement3 = "SELECT ACTIVATED FROM newjs.JPROFILE WHERE PROFILEID = ".$row2["PROFILEID"];
		$result3 = $mysqlObj->executeQuery($statement3,$dbM) or $mysqlObj->logError($statement3);
		$row3 = $mysqlObj->fetchArray($result3);
		if($row3["ACTIVATED"]!='Y')
		{
			$statement4 = "DELETE FROM newjs.".$v." WHERE PROFILEID = ".$row2["PROFILEID"];
			$mysqlObj->executeQuery($statement4,$dbM) or $mysqlObj->logError($statement4);	
		}
	}
	echo $v." DONE\n";
}

echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
mysql_close($dbS);
//CLOSING ENDS
?>
