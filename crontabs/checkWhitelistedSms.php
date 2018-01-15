<?php
include_once("connect.inc");
include_once("/usr/local/scripts/DocRoot.php");
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/SMSLib.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/SmsVendorFactory.class.php");

$master=connect_db();
$SMSLib = new SMSLib("S");
$smsVendorObj = SmsVendorFactory::getSmsVendor("air2web");


	$sql="SELECT *  FROM TEMP_SMS_DETAIL WHERE SENT!='Y' ORDER BY `PRIORITY` ASC LIMIT 1";
	$res = mysql_query($sql,$master) or $SMSLib->errormail($sql,mysql_errno().":".mysql_error());
	while($row=mysql_fetch_array($res))
	{
			$details[$row['PROFILEID']]=$row;
	}
	foreach($details as $p=>$info)
	{
                $sql_insert=$sql_insert."('$info[PROFILEID]', '$info[SMS_TYPE]', '$info[SMS_KEY]', '".addslashes($info[MESSAGE])."', '$info[PHONE_MOB]', now(), '$sent'),";
			$temp1[]=$info;
	}
	if($sql_insert && ($temp1))
	{
			foreach($temp1 as $key=>$val)
				$xmlData1 = $xmlData1 . $smsVendorObj->generateXml($val['PROFILEID'],$val["PHONE_MOB"],$val["MESSAGE"]);

			if($xmlData1)
				$smsVendorObj->send($xmlData1,"transaction");

		$sql_ex = "INSERT INTO newjs.SMS_DETAIL(PROFILEID, SMS_TYPE, SMS_KEY, MESSAGE, PHONE_MOB, ADD_DATE, SENT) VALUES ".substr($sql_insert,0,-1);
		mysql_query($sql_ex,$master) or $SMSLib->errormail($sql_ex,mysql_errno().":".mysql_error());


		foreach($temp1 as $k1=>$v1)
		{
			$sql_Del="UPDATE `TEMP_SMS_DETAIL` SET `SENT` = 'Y' WHERE PROFILEID='".$v1['PROFILEID']."' AND SMS_KEY='".$v1['SMS_KEY']."'";
	      mysql_query($sql_Del,$master) or $SMSLib->errormail($sql_Del,mysql_errno().":".mysql_error());
	}
}

$to='esha.jain@jeevansathi.com';
//$to='tanu.gupta@jeevansathi.com';
$msg='';
$subject="Scheduled SMS send success mail";
$msg='Cron to send sms and update details table executed successfully<br/><br/>Warm Regards';
//send_email($to,$msg,$subject,"",$cc);

?>
