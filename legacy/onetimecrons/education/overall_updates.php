<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

//INCLUDE FILES HERE
include_once($_SERVER['DOCUMENT_ROOT']."/profile/config.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
//INCLUDE FILE ENDS

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObjM = new Mysql;
$dbM = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$mysqlObjS = new Mysql;
$dbS = $mysqlObjS->connect("slave") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

$sql="SET @DONT_UPDATE_TRIGGER=1";
mysql_query($sql,$dbM) or die(mysql_error().$sql);

updateLevel("JPROFILE","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|JPROFILE\n";
updateLevel("SEARCH_MALE","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|SEARCH_MALE\n";
updateLevel("SEARCH_FEMALE","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|SEARCH_FEMALE\n";
updateTwoWayMatch("EDUCATION_MALE_PERCENT","twowaymatch",$mysqlObjM,$dbM);
echo "twowaymatch|EDUCATION_MALE_PERCENT\n";
updateTwoWayMatch("EDUCATION_FEMALE_PERCENT","twowaymatch",$mysqlObjM,$dbM);
echo "twowaymatch|EDUCATION_FEMALE_PERCENT\n";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
mysql_close($dbS);
//CLOSING ENDS

function updateLevel($table_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
	$select_statement = "SELECT VALUE,OLD_VALUE FROM newjs.EDUCATION_LEVEL_NEW ORDER BY SORTBY";
	$result = $mysqlObjM->executeQuery($select_statement,$dbM) or $mysqlObjM->logError($select_statement);
	while($row = $mysqlObjM->fetchArray($result))
	{
		$select_statement1 = "SELECT ".$primary_key." FROM ".$db_name.".".$table_name." WHERE EDU_LEVEL_NEW = ".$row["VALUE"]." AND EDU_LEVEL!=".$row["OLD_VALUE"];
		//echo $select_statement1."\n";
		$result1 = $mysqlObjS->executeQuery($select_statement1,$dbS) or $mysqlObjS->logError($select_statement1);
		while($row1 = $mysqlObjS->fetchArray($result1))
		{
			$update_statement = "UPDATE ".$db_name.".".$table_name." SET EDU_LEVEL = ".$row["OLD_VALUE"]." WHERE ".$primary_key." = ".$row1[$primary_key]." AND EDU_LEVEL_NEW = ".$row["VALUE"]." AND EDU_LEVEL!=".$row["OLD_VALUE"];
			//echo $update_statement."\n";
			$mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
		}
	}

		$select_statement1 = "SELECT ".$primary_key." FROM ".$db_name.".".$table_name." WHERE EDU_LEVEL_NEW = 27";
		//echo $select_statement1."\n";
		$result1 = $mysqlObjS->executeQuery($select_statement1,$dbS) or $mysqlObjS->logError($select_statement1);
		while($row1 = $mysqlObjS->fetchArray($result1))
		{
			$update_statement = "UPDATE ".$db_name.".".$table_name." SET EDU_LEVEL = 6,EDU_LEVEL_NEW = 19 WHERE ".$primary_key." = ".$row1[$primary_key]." AND EDU_LEVEL_NEW = 27";
			//echo $update_statement."\n";
			$mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
		}
}

function updateTwoWayMatch($table_name,$db_name,$mysqlObjM,$dbM)
{
	$select_statement = "SELECT VALUE FROM newjs.EDUCATION_LEVEL_NEW WHERE VALUE>24";
        $result = $mysqlObjM->executeQuery($select_statement,$dbM) or $mysqlObjM->logError($select_statement);
	$insert_statement = "INSERT INTO ".$db_name.".".$table_name."(EDUCATION,PERCENT) VALUES ";
        while($row = $mysqlObjM->fetchArray($result))
        {
		$insert_statement = $insert_statement."(".$row["VALUE"].",0.001),";
	}
	$insert_statement = rtrim($insert_statement,",");
	//echo $insert_statement."\n";
	$mysqlObjM->executeQuery($insert_statement,$dbM) or $mysqlObjM->logError($insert_statement);
}
?>

