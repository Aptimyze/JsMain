<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

//INCLUDE FILES HERE
include_once($_SERVER['DOCUMENT_ROOT']."/profile/config.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once("update_functions.php");
//INCLUDE FILE ENDS

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObjM = new Mysql;
$dbM = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$filename = "overall_updates.csv";		//This filename to be used for overall updates of caste values for merges and deletes
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

foreach($old_value as $k=>$v)
{
 	$update_statement = "UPDATE crawler.crawler_JS_competition_city_res_values_mapping SET JS_FIELD_VALUE = \"".$new_value[$k]."\" WHERE JS_FIELD_VALUE = \"".$v."\"";
//    	echo $update_statement."<br />";
     	$mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
}
echo "crawler|crawler_JS_competition_city_res_values_mapping|JS_FIELD_VALUE\n";

foreach($old_value as $k=>$v)
{
        $update_statement = "UPDATE crawler.crawler_search_results SET CITY_RES = \"".$new_value[$k]."\" WHERE CITY_RES = \"".$v."\"";
//      echo $update_statement."<br />";
        $mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
}
echo "crawler|crawler_search_results|CITY_RES\n";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
//CLOSING ENDS
?>
