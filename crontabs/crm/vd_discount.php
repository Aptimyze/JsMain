<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include ("$docRoot/crontabs/connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/classes/VariableDiscountHandler.class.php");

ini_set('max_execution_time',0);
ini_set('memory_limit',-1);

$myDb 	=connect_ddl();		// master connection
$slaveDb=connect_737();		// slave connection
$variableDiscountObj =new VariableDiscountHandler($myDb,$slaveDb);

$status =$variableDiscountObj->checkDiscountEligibleStatus();
if(!$status)
	successfullDie('die');
$emailId ='manoj.rana@naukri.com';

// step1
mail($emailId,"Step-1 VD (Poll creation)", date("Y-m-d H:i:s"));
$variableDiscountObj->addProfileInVddPool();
$variableDiscountObj->logVdProcess('1');

// step2
mail($emailId,"Step-2 VD (Filter process)", date("Y-m-d H:i:s"));
$variableDiscountObj->filterVdPoolProfiles();
$variableDiscountObj->logVdProcess('2');

// step3
mail($emailId,"Step-3 VD (Discount calculation)", date("Y-m-d H:i:s"));
$variableDiscountObj->calculateVdDiscount();
$variableDiscountObj->logVdProcess('3');

// step4
mail($emailId,"Step-4 VD (Final pool)", date("Y-m-d H:i:s"));
$variableDiscountObj->addVariableDiscount();
$variableDiscountObj->logVdProcess('4','N');

// stepe5
mail($emailId,"Step-5 VD (Done)", date("Y-m-d H:i:s"));

?>

