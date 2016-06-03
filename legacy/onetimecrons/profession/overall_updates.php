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

//scoring_new|caste|caste
scoring_update("occupationXgender","scoring_new",$mysqlObjM,$dbM);
echo "scoring_new|occupationXgender \n";

//trends update
trends_update("OCCUPATION_FEMALE_PERCENT","PERCENT","twowaymatch","OCCUPATION",$mysqlObjM,$dbM);
echo "twowaymatch|OCCUPATION_FEMALE_PERCENT \n";
trends_update("OCCUPATION_MALE_PERCENT","PERCENT","twowaymatch","OCCUPATION",$mysqlObjM,$dbM);
echo "twowaymatch|OCCUPATION_MALE_PERCENT \n";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
//CLOSING ENDS
?>

