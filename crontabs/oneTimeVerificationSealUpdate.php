<?php 
/**
 * This file updates the Verification seal values to another value
 * Author: Ankit Shukla 8th Aug 2016
 */
$flag_using_php5 = 1;
include_once("/usr/local/scripts/DocRoot.php");
include("config.php");
include("connect.inc");
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/classes/globalVariables.Class.php");

$mysqlObjM = new Mysql;
$connMaster = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$connMaster);

$mysqlObjS = new Mysql;
$connSlave = $mysqlObjS->connect("slave") or logError("Unable to connect to slave","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$connSlave);

$genderArr = array('MALE','FEMALE');

$sqlAd = "SELECT PROFILEID FROM PROFILE_VERIFICATION.AADHAR_VERIFICATION WHERE VERIFY_STATUS = 'Y'";
$resultAd = $mysqlObjS->executeQuery($sqlAd,$connSlave) or $mysqlObjS->logError($sqlAd);

while($row = $mysqlObjS->fetchAssoc($resultAd)){
    $aadhaarArr[] = $row['PROFILEID'];
}


foreach($genderArr as $key=>$val){
    $sqlS = "SELECT PROFILEID FROM SEARCH_".$val;
    $resultS = $mysqlObjS->executeQuery($sqlS,$connSlave) or $mysqlObjS->logError($sqlS);

    while($row = $mysqlObjS->fetchAssoc($resultS))
    { 
        if($row['PROFILEID']){
            if(in_array($row['PROFILEID'], $aadhaarArr))
                $toConcat = ",A";
            else
                $toConcat = ",N";

            $sqlU = "UPDATE newjs.SEARCH_".$val." SET VERIFICATION_SEAL = CONCAT(VERIFICATION_SEAL,'".$toConcat."') WHERE PROFILEID = ".$row['PROFILEID'];
            
            $mysqlObjM->executeQuery($sqlU,$connMaster) or $mysqlObjM->logError($sqlU);
        }

    }
}