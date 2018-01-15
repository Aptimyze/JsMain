<?php
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
ini_set('memory_limit',-1);
$fromCrontab = 1;
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/ScheduleSms.class.php");

$db_ddl = connect_ddl();

$orgTZ = date_default_timezone_get();
date_default_timezone_set("Asia/Calcutta");
$sms = new ScheduleSms;
date_default_timezone_set($orgTZ);

$sql= "TRUNCATE TABLE newjs.TEMP_SMS_DETAIL";
$res=mysql_query($sql,$db_ddl) or $sms->SMSLib->errormail($sql,mysql_errno().":".mysql_error());
$sms->unsetTempJPROFILE();

$sms->setTempJPROFILE();

$scheduleSettings = getSmsKeysArr($sms->scheduleSettings);

if(in_array("MATCH_ALERT",$scheduleSettings))
{
	for($i = 0;$i<3;$i++)
	{
		$command = JsConstants::$php5path." -f ".$docRoot."/crontabs/match_alert.php ".$i;
		passthru("$command > /dev/null 2> /dev/null &");
	}	
}
foreach($scheduleSettings as $key=>$smsKey){
		echo $smsKey."\n";
		if(!strstr($smsKey,"MATCH_ALERT") && !strstr($smsKey,"VD1") && !strstr($smsKey,"VD2") && !strstr($smsKey,"REQUEST_CALLBACK")){
		$Start = getTime();
		$sms->processData($smsKey);
		$End = getTime();
		echo "Time taken = ".number_format(($End - $Start),2)." secs\n\n\n";
		}		
}
//$sms->unsetTempJPROFILE();
successmail();

function getSmsKeysArr($settings){
	return array_keys($settings);
}

function successmail()
        {
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
                $cc='esha.jain@jeevansathi.com';
                $to='tanu.gupta@jeevansathi.com';
                $msg='';
                $subject="Scheduled SMS success mail";
                $msg='Cron to populate sms in temp table executed successfully<br/><br/>Warm Regards';
//echo $msg."\n\n\n";
                send_email($to,$msg,$subject,"",$cc);
        }


function getTime() 
    { 
    $a = explode (' ',microtime()); 
    return(double) $a[0] + $a[1]; 
    } 


?>
