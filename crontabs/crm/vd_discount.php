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

// step1,step3
mail($emailId,"step1-3 VD Start", date("Y-m-d H:i:s"));
$variableDiscountObj->addProfileInVddPool();
$variableDiscountObj->logVdProcess('1_3');

// step2,step4
mail($emailId,"step2-4 VD Start", date("Y-m-d H:i:s"));
$variableDiscountObj->filterVdPoolProfiles();
$variableDiscountObj->logVdProcess('2_4');

// step5
mail($emailId,"step5 VD Start", date("Y-m-d H:i:s"));
$variableDiscountObj->calculateVdDiscount();
$variableDiscountObj->logVdProcess('5');

// step6
mail($emailId,"step6 VD Start", date("Y-m-d H:i:s"));
$variableDiscountObj->addVariableDiscount();
$variableDiscountObj->logVdProcess('6');

mail($emailId,"step7 VD End", date("Y-m-d H:i:s"));
?>
