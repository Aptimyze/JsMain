<?php
successfullDie("No Longer Needed");
if(!$_SERVER['DOCUMENT_ROOT'])
	$_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;
$path=$_SERVER['DOCUMENT_ROOT']."/profile";
chdir($path);
include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/sms_inc.php");
$db=connect_db();
$log_date = date("Y-m-d",time() + 37800);

$file = $path."/logerror_temp.txt";
$tot_cnt = shell_exec("grep '$log_date' $file | wc -l");

if($tot_cnt > 100)
{
	$mobile 	= "9818424749";
	$message	= "Mysql Error Count have reached $tot_cnt within 5 minutes";
	$from 		= "JSSRVR";
	$profileid 	= "111";
	$smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
    CommonUtility::logTechAlertSms($message, $mobile);
	if($smsState)
		log_file($file);	
}
else
{
	log_file($file);
}

function log_file($file)
{
	$file_open = fopen($file,"w");
	fwrite($file_open,"");
	fclose($file_open);
}

?>
