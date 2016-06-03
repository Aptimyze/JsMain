<?php
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
require_once($_SERVER['DOCUMENT_ROOT']."/sugarcrm/custom/crons/JsEmail.php");
$jsemail=new JsEmail();
$jsemail->sendMessage();
//echo $jsemail->createMessage(array('lead_id'=>'23d','source'=>'Paper','entry_date'=>'12-12-2010'));
//echo $jsemail->createMessage(array('lead_id'=>'23rd','source'=>'newsPaper','gender_c'=>'F','age'=>'42','caste_c'=>'1_33','entry_date'=>'08-10-2010'));
?>
