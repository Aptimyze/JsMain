<?php 
/**
 * This file updates the well known college column
 * Author: Ankit Shukla 12th July 2017
 */
$flag_using_php5 = 1;
include_once("/usr/local/scripts/DocRoot.php");
include("config.php");
include("connect.inc");
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/classes/globalVariables.Class.php");

// Master and slave connection object

$mysqlObjM = new Mysql;
$connMaster = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$connMaster);

$mysqlObjS = new Mysql;
$connSlave = $mysqlObjS->connect("slave") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$connSlave);


$tableArr = array("SEARCH_MALE","SEARCH_FEMALE");

foreach($tableArr as $k=>$tableName){

    $selectSql="SELECT PROFILEID,COLLEGE,PG_COLLEGE FROM $tableName"."_TEXT WHERE COLLEGE !='' OR PG_COLLEGE!=''";
    $result = $mysqlObjS->executeQuery($selectSql,$connSlave) or $mysqlObjS->logError($selectSql);
    $pKey = "PROFILEID";
    $collegeIds = array_flip(FieldMap::getFieldLabel('wellKnownColleges','',1));
    $collegeNames = WellKnownCollegesMap::$COLLEGE_MAP;
    while($row = $mysqlObjS->fetchAssoc($result))
    {
        if($row[$pKey] != ''){
            $ugCollege = $row['COLLEGE'];
            $pgCollege = $row['PG_COLLEGE'];
            $ugClgId = $collegeNames[$ugCollege];
            $pgClgId = $collegeNames[$pgCollege];
            
            if($ugClgId || $pgClgId){
                
                if($ugClgId && $pgClgId){
                    $ug = $collegeIds[$ugClgId];
                    $pg = $collegeIds[$pgClgId];
                    if($ug && $pg)
                        $updateSql = "UPDATE $tableName SET KNOWN_COLLEGE = \"$ug,$pg\" WHERE $pKey = ".$row[$pKey];
                }
                else{
                    if($ugClgId)
                        $newValue = $collegeIds[$ugClgId];
                    else
                        $newValue = $collegeIds[$pgClgId];
                    if($newValue!='')
                        $updateSql = "UPDATE $tableName SET KNOWN_COLLEGE = $newValue WHERE $pKey = ".$row[$pKey];
                }
                
                $mysqlObjM->executeQuery($updateSql,$connMaster) or $mysqlObjM->logError($updateSql);
            }
        }
    }
}
?>

