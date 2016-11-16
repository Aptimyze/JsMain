<?php 
/**
 * This file updates the profile completion score and showhoroscope in serach tables.
 * Author: Bhavana Kadwal 10th November 2016
 */
$flag_using_php5 = 1;
include_once("/usr/local/scripts/DocRoot.php");
include("config.php");
include("connect.inc");
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/classes/globalVariables.Class.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
// Column mapping and values to update
$columnToUpdate = array("column_name"=>"SHOW_HOROSCOPE","column_txt"=>"HOROSCOPE","new_value"=>"'N'","old_value"=>"'Y'","new_value_Jprofile"=>"N");

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
  "1"=>array("table_name"=>"newjs.SEARCH_FEMALE_TEXT","update_type"=>"single","conn_type"=>"master","column"=>"column_txt","unique_key"=>"PROFILEID","UPDATE_TABLE"=>'SEARCH_FEMALE'),
  "2"=>array("table_name"=>"newjs.SEARCH_MALE_TEXT","update_type"=>"single","conn_type"=>"master","column"=>"column_txt","unique_key"=>"PROFILEID","UPDATE_TABLE"=>'SEARCH_MALE')
);
// Master and slave connection object
global $mysqlObjS , $mysqlObjM;

$mysqlObjM = new Mysql;
$connMaster = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$connMaster);

$mysqlObjS = new Mysql;
$connSlave = $mysqlObjS->connect("slave") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$connSlave);


// get horoscope data
$horoscopeSql = "SELECT h.PROFILEID FROM HOROSCOPE h LEFT JOIN ASTRO_DETAILS a ON a.PROFILEID = h.PROFILEID WHERE a.PROFILEID IS NULL";
$horoscopeResult = $mysqlObjS->executeQuery($horoscopeSql,$connSlave) or $mysqlObjS->logError($horoscopeSql);
$profiles = array();
while($row = $mysqlObjS->fetchAssoc($horoscopeResult)){
        $profiles[] = $row['PROFILEID'];
}
if($profiles){
        $jprofileUpdateObj = JProfileUpdateLib::getInstance();
        foreach($profiles as $profileId){
                foreach($tableArray as $tableArr){
                        if($tableArr['table_name'] == 'newjs.JPROFILE'){
                                $arrFields = array($columnToUpdate[$tableArr['column']]=>$columnToUpdate['new_value_Jprofile']);
                                $exrtaWhereCond = $columnToUpdate[$tableArr['column']]."=".$columnToUpdate['old_value']."";
                                //print_r($arrFields);die;
                                $res = $jprofileUpdateObj->editJPROFILE($arrFields,$profileId,"PROFILEID",$exrtaWhereCond);
                        }else{
                                $updateSql = "UPDATE ".$tableArr['table_name']." SET ".$columnToUpdate[$tableArr['column']]." = ".$columnToUpdate['new_value']." WHERE PROFILEID = ".$profileId." AND ".$columnToUpdate[$tableArr['column']]." = ".$columnToUpdate['old_value'] ;
                                $mysqlObjM->executeQuery($updateSql,$connMaster) or $mysqlObjM->logError($updateSql);
                                
                                $updateSql2 = "UPDATE ".$tableArr['UPDATE_TABLE']." SET LAST_MODIFIED = NOW( ) WHERE PROFILEID = '".$profileId."'";
                                $mysqlObjM->executeQuery($updateSql2,$connMaster) or $mysqlObjM->logError($updateSql2);
                        }
                }
                $cScoreObj = ProfileCompletionFactory::getInstance(null,null,$profileId);
                $cScoreObj->updateProfileCompletionScore();
                unset($cScoreObj);
        }

}
unset($mysqlObjM);
unset($connMaster);
unset($mysqlObjS);
unset($connSlave);
?>