<?php 
/**
 * This file updates the Verification seal values to another value
 * Author: Ankit Shukla 29 Nov 2017
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

/*$sqlAd = "SELECT PROFILEID FROM PROFILE_VERIFICATION.AADHAR_VERIFICATION WHERE VERIFY_STATUS = 'Y'";
$resultAd = $mysqlObjS->executeQuery($sqlAd,$connSlave) or $mysqlObjS->logError($sqlAd);

while($row = $mysqlObjS->fetchAssoc($resultAd)){
    $aadhaarArr[] = $row['PROFILEID'];
}*/


foreach($genderArr as $key=>$val){
    $sqlS = "SELECT PROFILEID FROM SEARCH_".$val;
    $resultS = $mysqlObjS->executeQuery($sqlS,$connSlave) or $mysqlObjS->logError($sqlS);

    while($row = $mysqlObjS->fetchAssoc($resultS))
    { 
        if($row['PROFILEID']){
            $profileId = $row['PROFILEID'];
            $sealArr = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$VERIFICATION_SEAL_ARRAY;
            $docArr = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$ATTRIBUTE_DOCUMENT;

            $sealInitiate = array_fill(0, count($sealArr), "N");
            $fsoObj = ProfileFSO::getInstance("newjs_slave");
            if ($fsoObj->check($profileId) == 0) {
                    $makeSeal[0] = "0";
            } else {
                    $makeSeal[0] = "F";
                    $sealObj = new PROFILE_VERIFICATION_DOCUMENTS("newjs_slave");
                    $seal = $sealObj->sealDetails(array($profileId));
                    if($seal != 0){
                      foreach ($seal as $sealKey => $sealValue) {
                              $makeSeal[$sealArr[$sealKey]] = array_flip($docArr[$sealKey])[$sealValue];
                      }
                    }
            }
            $sealFinalArr = array_replace($sealInitiate, $makeSeal);
            $aadhaarObj = new aadharVerification("newjs_slave");
            $proID = $profileId;
            $aadhaarDetails = $aadhaarObj->getAadharDetails($proID);
            if($aadhaarDetails[$proID]['AADHAR_NO'] && $aadhaarDetails[$proID]['VERIFY_STATUS'] == 'Y')
                $sealFinalArr[] = 'A';
            else
                $sealFinalArr[] = 'N';
            $finalVerificationSeal = implode(",", $sealFinalArr);

            $sqlU = "UPDATE newjs.SEARCH_".$val." SET VERIFICATION_SEAL = '".$finalVerificationSeal."' WHERE PROFILEID = ".$profileId;
            
            $mysqlObjM->executeQuery($sqlU,$connMaster) or $mysqlObjM->logError($sqlU);
        }

    }
}