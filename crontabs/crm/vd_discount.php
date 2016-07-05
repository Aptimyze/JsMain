<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include ("$docRoot/crontabs/connect.inc");
include_once(JsConstants::$docRoot."/classes/VariableDiscountHandler.class.php");

ini_set('max_execution_time',0);
ini_set('memory_limit',-1);

$myDb 	=connect_db();		// master connection
$slaveDb=connect_737();		// slave connection
$variableDiscountObj =new VariableDiscountHandler($myDb,$slaveDb);

// step1,step3
$variableDiscountObj->addProfileInVddPool();
$variableDiscountObj->logVdProcess('1_3');

// step2,step4
$variableDiscountObj->filterVdPoolProfiles();
$variableDiscountObj->logVdProcess('2_4');

// step5
$variableDiscountObj->calculateVdDiscount();
$variableDiscountObj->logVdProcess('5');

// step6
$variableDiscountObj->addVariableDiscount();
$variableDiscountObj->logVdProcess('6');

?>
