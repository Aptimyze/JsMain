<?php 
/**
 * This file distinct values of city_india and city_res
 * Author: Bhavana Kadwal 19th July 2016
 */
$flag_using_php5 = 1;
include_once("/usr/local/scripts/DocRoot.php");
include("config.php");
include("connect.inc");
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/classes/globalVariables.Class.php");

global $updateTypeArray,$shardNumber;
$shardNumber = $argv[1];
if(!$shardNumber){
        $shardNumber = 0;
}
$updateTypeArray = array("single","comma","pipehash");
// Column mapping and values to update
$columnToUpdate = array(array("column_name1"=>"CITY_RES","column_name2"=>"CITY_INDIA","column_name_rev1"=>"PARTNER_CITYRES","column_name_rev2"=>"CITY_INDIA"));

/**
 * Table array to update
 * table name - Name of table
 * update_type - single / comma separated value
 * conn_type - master or shard
 * column - column name to be update from $columnToUpdate array as jprofile and partner table column name are different
 * unique_key = unique key of table
 */
$tableArray = array(
  "3"=>array("table_name"=>"newjs.JPARTNER","update_type"=>"comma","conn_type"=>"slave","column1"=>"column_name_rev1","column2"=>"column_name_rev2","unique_key"=>"PROFILEID","UPDATE_TABLE"=>array('table_nameM'=>'newjs.SEARCH_FEMALE_REV','table_nameF'=>'newjs.SEARCH_MALE_REV','column_name'=>"PARTNER_CITYRES",'p_key'=>"PROFILEID",'updatedValue'=>"PARENT_VALUE","UPDATE_TABLE"=>array('table_nameM'=>'newjs.SEARCH_FEMALE','table_nameF'=>'newjs.SEARCH_MALE','column_name'=>"LAST_MODIFIED",'p_key'=>"PROFILEID",'updatedValue'=>"now()"))),
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
                $connArray = getShardConnection($shardNumber);  
                if($connArray['slave'] && $connArray['master']){
                        mergeCityResInTables($tableArr["table_name"],$columnData[$tableArr["column1"]],$columnData[$tableArr["column2"]],$connArray['slave'],$connArray['master'],$tableArr["update_type"],true,$tableArr["unique_key"],$tableArr["UPDATE_TABLE"],$connMaster);
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
        $sql="SET @DONT_UPDATE_TRIGGER=1";
        mysql_query($sql,$shardConnMaster) or die(mysql_error().$sql);

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
function mergeCityResInTables($tableName,$columnName1,$columnName2,$slaveConn,$masterConn,$updateType,$quotedValue,$pKey,$updateTable,$connMasterDep){
        global $mysqlObjS , $mysqlObjM, $updateTypeArray;
        $limit = 2000;
        $offset = 0;
        $flag = 1;
        if(!in_array($updateType,$updateTypeArray)){
                return;
        }
        
        if($quotedValue === true){
                $oldValue = "'".$oldValue."'";
                $newValue = "'".$newValue."'";
        }
        do{
                $selectSql="SELECT $pKey,$columnName1,$columnName2,GENDER FROM $tableName WHERE $columnName2 IS NOT NULL AND $columnName2 != '' LIMIT $limit";
                $result = $mysqlObjS->executeQuery($selectSql,$slaveConn) or $mysqlObjS->logError($selectSql);
                $offset += $limit;
                if(!$mysqlObjS->numRows($result)){
                        $flag = 0;
                }else{
                        while($row = $mysqlObjS->fetchAssoc($result))
                        { 
                                if($row[$pKey] != ''){
                                        $city_res = explode(',',$row[$columnName1]);
                                        $city_india = explode(',',$row[$columnName2]);
                                        $city_res = trim(implode(',',  array_unique(array_merge($city_res,$city_india))),',');
                                        $updateSql = "UPDATE $tableName SET $columnName1 = \"$city_res\", $columnName2 = '' WHERE $pKey = ".$row[$pKey]."" ;
                                        $mysqlObjM->executeQuery($updateSql,$masterConn) or $mysqlObjM->logError($updateSql);
                                        
                                        if(!empty($updateTable)){
                                                $updateby = $updateTable['updatedValue'];
                                                if($updateTable['updatedValue'] == 'PARENT_VALUE'){
                                                        $updateby = $city_res;
                                                }
                                                $updateSql2 = "UPDATE ".$updateTable['table_name'.$row['GENDER']]." SET ".$updateTable['column_name']." = \"$updateby\" WHERE ".$updateTable['p_key']." = ".$row[$pKey]."" ;
                                                $mysqlObjM->executeQuery($updateSql2,$connMasterDep) or $mysqlObjM->logError($updateSql2);
                                                
                                                if(!empty($updateTable['UPDATE_TABLE'])){
                                                        $updateby = $updateTable['UPDATE_TABLE']['updatedValue'];
                                                        if($updateTable['UPDATE_TABLE']['updatedValue'] == 'PARENT_VALUE'){
                                                                $updateby = '"'.$city_res.'"';
                                                        }
                                                        $updateSql3 = "UPDATE ".$updateTable['UPDATE_TABLE']['table_name'.$row['GENDER']]." SET ".$updateTable['UPDATE_TABLE']['column_name']." = $updateby WHERE ".$updateTable['UPDATE_TABLE']['p_key']." = ".$row[$pKey]."" ;
                                                        $mysqlObjM->executeQuery($updateSql3,$connMasterDep) or $mysqlObjM->logError($updateSql3);
                                                }
                                        }
                                }
                        }
                }
        }while($flag == 1);
}
?>