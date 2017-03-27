<?php 
/**
 * This file updates the occupation values to another value
 * Author: Ankit Shukla 8th Aug 2016
 */
$flag_using_php5 = 1;
include_once("/usr/local/scripts/DocRoot.php");
include("config.php");
include("connect.inc");
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/classes/globalVariables.Class.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");

global $updateTypeArray;

$lastLoginDate = $argv[1];

$fieldsOccupation = array(
"44"=>"Looking for a job",
"36"=>"Not working",
"5"=>"Actor/Model",
"2"=>"Advertising Professional",
"3"=>"Agent",
"32"=>"Agriculture/Dairy",
"46"=>"Air Hostess",
"6"=>"Architect",
"7"=>"Banking Professional",
"40"=>"Beautician",
"8"=>"BPO/ITES",
"13"=>"Businessperson",
"33"=>"Civil Services (IAS/ IFS/ IPS/ IRS)",
"50"=>"Consultant",
"10"=>"Corporate Communication",
"11"=>"Corporate Planning Professional",
"12"=>"Customer Services",
"34"=>"Defence",
"57"=>"Doctor",
"60"=>"Education Professional",
"27"=>"Engineer - Non IT",
"14"=>"Export/Import",
"15"=>"Fashion Designer",
"1"=>"Financial Services/Accounting",
"39"=>"Fitness Professional",
"35"=>"Govt. Services",
"21"=>"Hardware/Telecom",
"24"=>"Healthcare Professional",
"47"=>"Hotels/Hospitality Professional",
"19"=>"HR Professional",
"51"=>"Interior Designer",
"9"=>"Journalist",
"22"=>"Lawyer/Legal Professional",
"23"=>"Logistics/SCM Professional",
"49"=>"Marketing Professional",
"54"=>"Media Professional",
"42"=>"Merchant Navy",
"38"=>"NGO/Social Services",
"53"=>"Nurse",
"45"=>"Pilot",
"56"=>"Police",
"25"=>"Printing/Packaging",
"58"=>"Professor/Lecturer",
"30"=>"Project Manager - IT",
"59"=>"Project Manager - Non IT",
"61"=>"Research Professional",
"18"=>"Restaurateur",
"37"=>"Retired",
"28"=>"Sales Professional",
"62"=>"Scientist",
"16"=>"Secretary/Front Office",
"29"=>"Security Professional",
"52"=>"Self Employed",
"26"=>"Service Engineering",
"20"=>"Software Professional",
"55"=>"Sportsperson",
"41"=>"Student",
"31"=>"Teacher",
"48"=>"Top Management (CXO, M.D. etc.)",
"4"=>"Travel/Ticketing",
"17"=>"Web/Graphic Design",
"43"=>"Others",
);
$updateTypeArray = array("single","comma","pipehash");
// Column mapping and values to update
$columnToUpdate = array(0=>array("column_name"=>"OCCUPATION","column_name_rev"=>"PARTNER_OCC","column_trends"=>"OCCUPATION_VALUE_PERCENTILE","new_value"=>"20","old_value"=>"26"),1=>array("column_name"=>"OCCUPATION","column_name_rev"=>"PARTNER_OCC","column_trends"=>"OCCUPATION_VALUE_PERCENTILE","new_value"=>"13","old_value"=>"14"),2=>array("column_name"=>"OCCUPATION","column_name_rev"=>"PARTNER_OCC","column_trends"=>"OCCUPATION_VALUE_PERCENTILE","new_value"=>"43","old_value"=>"4"),3=>array("column_name"=>"OCCUPATION","column_name_rev"=>"PARTNER_OCC","column_trends"=>"OCCUPATION_VALUE_PERCENTILE","new_value"=>"13","old_value"=>"25"),4=>array("column_name"=>"OCCUPATION","column_name_rev"=>"PARTNER_OCC","column_trends"=>"OCCUPATION_VALUE_PERCENTILE","new_value"=>"13","old_value"=>"18"),5=>array("column_name"=>"OCCUPATION","column_name_rev"=>"PARTNER_OCC","column_trends"=>"OCCUPATION_VALUE_PERCENTILE","new_value"=>"43","old_value"=>"39"),6=>array("column_name"=>"OCCUPATION","column_name_rev"=>"PARTNER_OCC","column_trends"=>"OCCUPATION_VALUE_PERCENTILE","new_value"=>"36","old_value"=>"37"),7=>array("column_name"=>"OCCUPATION","column_name_rev"=>"PARTNER_OCC","column_trends"=>"OCCUPATION_VALUE_PERCENTILE","new_value"=>"8","old_value"=>"12"),8=>array("column_name"=>"OCCUPATION","column_name_rev"=>"PARTNER_OCC","column_trends"=>"OCCUPATION_VALUE_PERCENTILE","new_value"=>"43","old_value"=>"3"));

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
//  "1"=>array("table_name"=>"newjs.SEARCH_FEMALE","update_type"=>"single","conn_type"=>"master","column"=>"column_name","unique_key"=>"PROFILEID"),
//  "2"=>array("table_name"=>"newjs.SEARCH_MALE","update_type"=>"single","conn_type"=>"master","column"=>"column_name","unique_key"=>"PROFILEID"),
//  "3"=>array("table_name"=>"newjs.SEARCH_FEMALE_REV","update_type"=>"comma","conn_type"=>"master","column"=>"column_name_rev","unique_key"=>"PROFILEID"),
//  "4"=>array("table_name"=>"newjs.SEARCH_MALE_REV","update_type"=>"comma","conn_type"=>"master","column"=>"column_name_rev","unique_key"=>"PROFILEID"),
//  "5"=>array("table_name"=>"newjs.JPARTNER","update_type"=>"comma","conn_type"=>"slave","column"=>"column_name_rev","unique_key"=>"PROFILEID"),
//  "6"=>array("table_name"=>"search.LATEST_SEARCHQUERY","update_type"=>"comma","conn_type"=>"master","column"=>"column_name","unique_key"=>"ID"),
//  "7"=>array("table_name"=>"newjs.SEARCH_AGENT","update_type"=>"comma","conn_type"=>"master","column"=>"column_name","unique_key"=>"ID"),
//  "8"=>array("table_name"=>"twowaymatch.TRENDS","update_type"=>"pipehash","conn_type"=>"master","column"=>"column_trends","unique_key"=>"PROFILEID"),
);
// Master and slave connection object
global $mysqlObjS , $mysqlObjM;

$mysqlObjM = new Mysql;
$connMaster = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$connMaster);

$sql="SET @DONT_UPDATE_TRIGGER=1";
mysql_query($sql,$connMaster) or die(mysql_error().$sql);

$mysqlObjS = new Mysql;
$connSlave = $mysqlObjS->connect("slave") or logError("Unable to connect to slave","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$connSlave);

$jprofileUpdateObj = JProfileUpdateLib::getInstance();
$jprofileSlaveObj = new JPROFILE('newjs_slave');
foreach($columnToUpdate as $columnData){
        foreach($tableArray as $tableArr){
                if($tableArr['table_name'] == 'newjs.JPROFILE'){
                                updateOccupationInJprofile($jprofileUpdateObj,$jprofileSlaveObj,$columnData,$tableArr,$lastLoginDate);
                }
                elseif($tableArr["conn_type"] == "master"){
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
                $where = " POSITION( '|$oldValue#' IN OCCUPATION_VALUE_PERCENTILE ) <>0";  
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

//this function will send a mail to user whose row is being updated
function sendMailToUser($email,$oldValue,$newValue)
{
    global $fieldsOccupation;
    $oldValue = $fieldsOccupation["$oldValue"];
    $newValue = $fieldsOccupation["$newValue"];
    $mailObj = new SendMail();
    $mailObj->send_email($email,"Hi,\n
We are removing the occupation of '$oldValue' from Jeevansathi and hence have temporarily placed you in the occupation category '$newValue'.\n
In case you want to change this, you are most welcome to login and edit your profile.\n\n
Warm regards\n
Team Jeevansathi","Kindly note the following changes that have been made to your profile","info@jeevansathi.com",'','','','','','','','',"Jeevansathi Info");
}

function updateOccupationInJprofile($jprofileUpdateObj,$jprofileSlaveObj,$columnToUpdate,$tableArr,$dateVal){
    $exrtaWhereCond[$columnToUpdate[$tableArr['column']]]=$columnToUpdate['old_value'];
    $greaterThanCondition['LAST_LOGIN_DT']=$dateVal;
    $jprofileUpdateObj->__destruct();
    $jprofileSlaveObj = new JPROFILE('newjs_slave');
    $profiles = $jprofileSlaveObj->getArray($exrtaWhereCond,'',$greaterThanCondition,'PROFILEID,EMAIL');
    $arrFields = array($columnToUpdate[$tableArr['column']]=>$columnToUpdate['new_value']);
    $jprofileUpdateObj->__destruct();
    $jprofileUpdateObj = JProfileUpdateLib::getInstance('newjs_master');
    foreach ($profiles as $key=>$value){
        $res = $jprofileUpdateObj->editJPROFILE($arrFields,$value['PROFILEID'],'PROFILEID');
        sendMailToUser($value['EMAIL'],$columnToUpdate['old_value'],$columnToUpdate['new_value']);
    }
}


?>