<?php 
/**
 * This file updates the aadhar no to encrypted aadhar.
 * Author: Reshu Rajput 6 Dec 2017
 */
$flag_using_php5 = 1;
include_once("/usr/local/scripts/DocRoot.php");
include("config.php");
include("connect.inc");
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/classes/globalVariables.Class.php");



// Master and slave connection object
global $mysqlObjS , $mysqlObjM;

$mysqlObjM = new Mysql;
$connMaster = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$connMaster);

$mysqlObjS = new Mysql;
$connSlave = $mysqlObjS->connect("slave") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$connSlave);


// get non encrypted aadhar data
$sql = "SELECT PROFILEID,AADHAR_NO from PROFILE_VERIFICATION.AADHAR_VERIFICATION WHERE AADHAR IS NULL limit 1000";
$result = $mysqlObjS->executeQuery($sql,$connSlave) or $mysqlObjS->logError($sql);
$profiles = array();
while($row = $mysqlObjS->fetchAssoc($result)){
        $profiles[$row['PROFILEID']] = $row['AADHAR_NO'];
}

if($profiles){
           foreach($profiles as $profileId=>$aadharId){
			   $aadhar="";
			   $aadharVerification = new aadharVerification();
			   $aadhar= EncryptionAESCipher::encrypt(aadharVerificationEnums::AADHARENCRYPTIONKEY,$aadharId);
			   $updateSql = "UPDATE PROFILE_VERIFICATION.AADHAR_VERIFICATION SET AADHAR ='".$aadhar."' WHERE PROFILEID = '".$profileId."' AND AADHAR IS NULL";
			   $mysqlObjM->executeQuery($updateSql,$connMaster) or $mysqlObjM->logError($updateSql);
			   $fields = aadharVerificationEnums::$fieldsToCheck;
			   $objProCacheLib = ProfileCacheLib::getInstance();
			   $objProCacheLib->removeFieldsFromCache($profileId,__CLASS__,$fields);
			   $objProCacheLib->__destruct();
			   unset($aadharVerification);
				
            }
}
unset($mysqlObjM);
unset($connMaster);
unset($mysqlObjS);
unset($connSlave);
?>
