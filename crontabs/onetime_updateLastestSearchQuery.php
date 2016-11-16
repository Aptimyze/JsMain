<?php 
/**
 * This file MATCHALERTS_DATE_CLUSTER and KUNDLI_DATE_CLUSTER in search.LATEST_SEARCHQUERY
 * Author: Sanyam Chopra 20th October 2016
 */
$flag_using_php5 = 1;
include_once("/usr/local/scripts/DocRoot.php");
include("config.php");
include("connect.inc");
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/classes/globalVariables.Class.php");

global $updateTypeArray;
$updateTypeArray = array("single","comma","pipehash");
// Column mapping and values to update
$columnToUpdate = array(array("column_name1"=>"MATCHALERTS_DATE_CLUSTER","column_name2"=>"KUNDLI_DATE_CLUSTER","new_value"=>"","old_value"=>"0000-00-00 00:00:00"));

/**
 * Table array to update
 * table name - Name of table
 * update_type - single / comma separated value
 * conn_type - master or shard
 * column - column name to be update from $columnToUpdate array as jprofile and partner table column name are different
 * unique_key = unique key of table
 */
$tableArray = array(
  "0"=>array("table_name"=>"search.LATEST_SEARCHQUERY","update_type"=>"single","conn_type"=>"master","column1"=>"column_name1","column2"=>"column_name2","unique_key"=>"ID"),
);
// Master and slave connection object
global $mysqlObjS , $mysqlObjM;

$mysqlObjM = new Mysql;
$connMaster = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$connMaster);

$mysqlObjS = new Mysql;
$connSlave = $mysqlObjS->connect("slave") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$connSlave);

foreach($columnToUpdate as $columnData){
        foreach($tableArray as $tableArr){
                if($tableArr["conn_type"] == "master"){
                        updateCasteInTables($tableArr["table_name"],$columnData[$tableArr["column1"]],$columnData["old_value"],$columnData["new_value"],$connSlave,$connMaster,$tableArr["update_type"],false,$tableArr["unique_key"],$columnData[$tableArr["column2"]]);
                }          
        }
}


/**
 * 
 * @param type $tableName - table name with db as prefix
 * @param type $columnName - column name
 * @param type $oldValue - old value to replace
 * @param type $newValue - new value to update
 * @param type $slaveConn - slave connection for select
 * @param type $masterConn - Master connection for update
 * @param type $updateType single / comma 
 * @param type boolean $quotedValue true if column valuesc are single quoted
 * @param type string $pKey unique key of table
 */
function updateCasteInTables($tableName,$columnName1,$oldValue,$newValue,$slaveConn,$masterConn,$updateType,$quotedValue,$pKey,$columnName2){
        global $mysqlObjS , $mysqlObjM, $updateTypeArray;
        if(!in_array($updateType,$updateTypeArray)){
                return;
        }
        
        if($quotedValue === true){
                $oldValue = "'".$oldValue."'";
                $newValue = "'".$newValue."'";
        }
        
        if($updateType == "single"){
                $where = "$columnName1 = '".$oldValue."'  AND $columnName2 = '".$oldValue."'";  
        }
        $selectSql="SELECT $pKey,$columnName1,$columnName2 FROM $tableName WHERE $where";
        $result = $mysqlObjS->executeQuery($selectSql,$slaveConn) or $mysqlObjS->logError($selectSql);

        while($row = $mysqlObjS->fetchAssoc($result))
        { 
                if($row[$pKey] != ''){
                        if($updateType == "single"){
                                $updateSql = "UPDATE $tableName SET $columnName1 = '".$newValue."' , $columnName2 = '".$newValue."' WHERE $pKey = ".$row[$pKey]." AND $columnName1 = '".$oldValue."' AND $columnName2 = '".$oldValue."'" ;
                        }

                        $mysqlObjM->executeQuery($updateSql,$masterConn) or $mysqlObjM->logError($updateSql);
                }
        }
}
?>