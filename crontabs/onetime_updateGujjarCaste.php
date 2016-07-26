<?php 
/**
 * This file updates the caste for gujjar and gurjar to a single caste value
 * Author: Bhavana Kadwal 19th July 2016
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
$columnToUpdate = array(array("column_name"=>"CASTE","column_name_rev"=>"PARTNER_CASTE","column_trends"=>"CASTE_VALUE_PERCENTILE","new_value"=>"63","old_value"=>"431"));

/**
 * Table array to update
 * table name - Name of table
 * update_type - single / comma separated value
 * conn_type - master or shard
 * column - column name to be update from $columnToUpdate array as jprofile and partner table column name are different
 * unique_key = unique key of table
 */
$tableArray = array(
  "0"=>array("table_name"=>"newjs.JPROFILE","update_type"=>"single","conn_type"=>"master","column"=>"column_name","unique_key"=>"PROFILEID"),
  "1"=>array("table_name"=>"newjs.SEARCH_FEMALE","update_type"=>"single","conn_type"=>"master","column"=>"column_name","unique_key"=>"PROFILEID"),
  "2"=>array("table_name"=>"newjs.SEARCH_MALE","update_type"=>"single","conn_type"=>"master","column"=>"column_name","unique_key"=>"PROFILEID"),
  "3"=>array("table_name"=>"newjs.SEARCH_FEMALE_REV","update_type"=>"comma","conn_type"=>"master","column"=>"column_name_rev","unique_key"=>"PROFILEID"),
  "4"=>array("table_name"=>"newjs.SEARCH_MALE_REV","update_type"=>"comma","conn_type"=>"master","column"=>"column_name_rev","unique_key"=>"PROFILEID"),
  "5"=>array("table_name"=>"newjs.JPARTNER","update_type"=>"comma","conn_type"=>"slave","column"=>"column_name_rev","unique_key"=>"PROFILEID"),
  "6"=>array("table_name"=>"search.LATEST_SEARCHQUERY","update_type"=>"comma","conn_type"=>"master","column"=>"column_name","unique_key"=>"ID"),
  "7"=>array("table_name"=>"newjs.SEARCH_AGENT","update_type"=>"comma","conn_type"=>"master","column"=>"column_name","unique_key"=>"ID"),
  "8"=>array("table_name"=>"twowaymatch.TRENDS","update_type"=>"pipehash","conn_type"=>"master","column"=>"column_trends","unique_key"=>"PROFILEID"),
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
                        updateCasteInTables($tableArr["table_name"],$columnData[$tableArr["column"]],$columnData["old_value"],$columnData["new_value"],$connSlave,$connMaster,$tableArr["update_type"],false,$tableArr["unique_key"]);
                }else{
                        for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
                        {
                                $connArray = getShardConnection($activeServerId);  
                                if($connArray['slave'] && $connArray['master']){
                                        updateCasteInTables($tableArr["table_name"],$columnData[$tableArr["column"]],$columnData["old_value"],$columnData["new_value"],$connArray['slave'],$connArray['master'],$tableArr["update_type"],true,$tableArr["unique_key"]);
                                }
                        }
                }
        }
}
/**
 * 
 * @global Mysql $mysqlObjM
 * @global Mysql $mysqlObjS
 * @param type $activeServerId active server id
 * @return type array of master and slave connection
 */
function getShardConnection($activeServerId){
        global $mysqlObjM, $mysqlObjS;
        
        $myDbName=getActiveServerName($activeServerId,'master',$mysqlObjM);
        $shardConnMaster=$mysqlObjM->connect("$myDbName");
        mysql_query('set session wait_timeout=86400,interactive_timeout=86400,net_read_timeout=86400',$shardConnMaster);

        $myDbName=getActiveServerName($activeServerId,'slave',$mysqlObjS);
        $shardConnSlave=$mysqlObjS->connect("$myDbName");
        mysql_query('set session wait_timeout=86400,interactive_timeout=86400,net_read_timeout=86400',$shardConnSlave);
        return array('master'=>$shardConnMaster,'slave'=>$shardConnSlave);
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
function updateCasteInTables($tableName,$columnName,$oldValue,$newValue,$slaveConn,$masterConn,$updateType,$quotedValue,$pKey){
        global $mysqlObjS , $mysqlObjM, $updateTypeArray;
        if(!in_array($updateType,$updateTypeArray)){
                return;
        }
        
        if($quotedValue === true){
                $oldValue = "'".$oldValue."'";
                $newValue = "'".$newValue."'";
        }
        
        if($updateType == "comma"){
                $where = ' FIND_IN_SET("'.$oldValue.'",'.$columnName.')';
        }elseif($updateType == "single"){
                $where = "$columnName = $oldValue ";  
        }elseif($updateType == "pipehash"){
                $where = " POSITION( '|$oldValue#' IN CASTE_VALUE_PERCENTILE ) <>0";  
        }
        $selectSql="SELECT $pKey,$columnName FROM $tableName WHERE $where";
        $result = $mysqlObjS->executeQuery($selectSql,$slaveConn) or $mysqlObjS->logError($selectSql);
        
        while($row = $mysqlObjS->fetchAssoc($result))
        { 
                if($row[$pKey] != ''){
                        if($updateType == "single"){
                                $updateSql = "UPDATE $tableName SET $columnName = $newValue WHERE $pKey = ".$row[$pKey]." AND $columnName = $oldValue" ;
                        }elseif($updateType == "comma"){
                                $oldValues = explode(',',$row[$columnName]);
                                foreach($oldValues as $key=>$OVal){
                                        if($OVal == $oldValue){
                                            $oldValues[$key]   = $newValue; 
                                        }
                                }
                                $newValues = implode(',',  array_unique($oldValues));
                                $updateSql = "UPDATE $tableName SET $columnName = \"$newValues\" WHERE $pKey = ".$row[$pKey]." AND $where" ;
                        }elseif($updateType == "pipehash"){
                              $oldValues = explode('|',$row[$columnName]);  
                              $newArray = array();
                              $arrOut = array();
                              foreach($oldValues as $oVal){
                                      $weightage = explode("#",$oVal);
                                      if($weightage[0] == $oldValue || $weightage[0] == $newValue){
                                              $newArray[$newValue] += $weightage[1];
                                      }else{
                                              $newArray[$weightage[0]] = $weightage[1];
                                      }
                              }
                              foreach($newArray as $key=>$newArr){
                                      if($key != '')
                                        $arrOut[] = $key."#".$newArr;
                              }
                              $newValues = "|".implode('|',$arrOut)."|";
                              $updateSql = "UPDATE $tableName SET $columnName = \"$newValues\" WHERE $pKey = ".$row[$pKey]." AND $where" ;
                        }
                        $mysqlObjM->executeQuery($updateSql,$masterConn) or $mysqlObjM->logError($updateSql);
                }
        }
}
?>