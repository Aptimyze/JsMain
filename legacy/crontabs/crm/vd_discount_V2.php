<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include ("$docRoot/crontabs/connect.inc");
include($_SERVER['DOCUMENT_ROOT']."/classes/VariableDiscount_V2.class.php");

$myDb 	=connect_db();		// master connection
$slaveDb=connect_737();		// slave connection
$variableDiscountObj =new VariableDiscount($myDb,$slaveDb);

// step1
$variableDiscountObj->addProfileInVddPool();
$variableDiscountObj->logVdProcess('1');

// step2
$variableDiscountObj->filterVdPoolProfiles();
$variableDiscountObj->logVdProcess('2');

// step3
$variableDiscountObj->setVdDiscount('','','FIXED');
$variableDiscountObj->logVdProcess('3');

// step4
$variableDiscountObj->addVariableDiscount();
$variableDiscountObj->logVdProcess('4');


?>
