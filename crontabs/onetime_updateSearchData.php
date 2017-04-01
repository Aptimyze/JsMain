<?php 
/**
 * This file deletes the data from search male,search female,reverse and text table on the basis of login date
 * Author: Bhavana Kadwal 31th August 2016
 */
$flag_using_php5 = 1;
include_once("/usr/local/scripts/DocRoot.php");
include("config.php");
include("connect.inc");
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/classes/globalVariables.Class.php");

// Delete condition
$key = "LAST_LOGIN_DT";
$keyValue = "DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
$operator = "<";

$tableArray = array(
  "0"=>array("0"=>"newjs.SEARCH_FEMALE","1"=>"newjs.SEARCH_FEMALE_REV","2"=>"newjs.SEARCH_FEMALE_TEXT"),
  "1"=>array("0"=>"newjs.SEARCH_MALE","1"=>"newjs.SEARCH_MALE_REV","2"=>"newjs.SEARCH_MALE_TEXT")
);
// Master and slave connection object
global $mysqlObjS , $mysqlObjM;

$mysqlObjM = new Mysql;
$connMaster = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$connMaster);

$mysqlObjS = new Mysql;
$connSlave = $mysqlObjS->connect("slave") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$connSlave);


foreach($tableArray as $tables){
        deleteSearchTablesData($tables,$key,$keyValue,$operator,$connSlave,$connMaster);
}

/**
 * 
 * @param type $tableArr - tables array with db as prefix
 * @param type $columnName - column name
 * @param type $columnValue - column value
 * @param type $operator - where operator
 * @param type $slaveConn - slave connection for select
 * @param type $masterConn - Master connection for update
 */
function deleteSearchTablesData($tableArr,$columnName,$columnValue,$operator,$slaveConn,$masterConn){
        global $mysqlObjS , $mysqlObjM, $updateTypeArray;
        $where = $columnName." ".$operator." ".$columnValue;
        $selectSql="SELECT PROFILEID,$columnName FROM ".$tableArr[0]." WHERE $where";
        $result = $mysqlObjS->executeQuery($selectSql,$slaveConn) or $mysqlObjS->logError($selectSql);
        
        while($row = $mysqlObjS->fetchAssoc($result))
        { 
                if($row["PROFILEID"] != ''){
                        $updateSql = "DELETE FROM $tableArr[0] where PROFILEID = ".$row["PROFILEID"]." AND ".$columnName." ".$operator." ".$columnValue ;
                        $mysqlObjM->executeQuery($updateSql,$masterConn) or $mysqlObjM->logError($updateSql);
                        if($mysqlObjM->affectedRows()){
                                $updateSql = "DELETE FROM $tableArr[1] where PROFILEID = ".$row["PROFILEID"];
                                $mysqlObjM->executeQuery($updateSql,$masterConn) or $mysqlObjM->logError($updateSql);
                               
                                $updateSql = "DELETE FROM $tableArr[2] where PROFILEID = ".$row["PROFILEID"];
                                $mysqlObjM->executeQuery($updateSql,$masterConn) or $mysqlObjM->logError($updateSql);
                        }
                }
        }
}
?>