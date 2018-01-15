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
$sql_sms_type= "SELECT SMS_KEY,SMS_SUBSCRIPTION FROM SMS_TYPE WHERE 1";
$res_sms_type=mysql_query($sql_sms_type,$master) or $SMSLib->errormail($sql_sms_type,mysql_errno().":".mysql_error());
while($row_sms_type=mysql_fetch_assoc($res_sms_type))
{
	$sms_type[$row_sms_type["SMS_KEY"]] = $row_sms_type["SMS_SUBSCRIPTION"];
}



$sql_profile="SELECT COUNT(*) AS COUNT, PROFILEID FROM TEMP_SMS_DETAIL WHERE SENT!='Y' GROUP BY PROFILEID HAVING COUNT(*)=1";
$res_profile = mysql_query($sql_profile,$master) or $SMSLib->errormail($sql_profile,mysql_errno().":".mysql_error());
$count = mysql_num_rows($res_profile);
$chunk=2000;
$totalChunks=ceil($count/$chunk);
for($j = 0;$j<$totalChunks;$j++)
{
        $timeCondition=$SMSLib->inSmsSendTimeRange();
        if($timeCondition)
                $sent='Y';
        else
                $sent='N';
        $trans = 0;
        $temp1 = array();
        $temp2 = array();
	$pId   = array();
	$pstr  ='';
        $xmlData1='';
        $xmlData2='';
        $sql_insert="";
        $skip = $j*$chunk;
        mysql_data_seek($res_profile,$skip);
        while(($row_profile=mysql_fetch_assoc($res_profile)) && $trans<$chunk)
        {
		$pId[$trans]=$row_profile['PROFILEID'];
		$trans++;
	}
	$pstr=implode("','",$pId);
	$pstr="'".$pstr."'";
	$sqlFTO = "SELECT PROFILEID FROM FTO.FTO_CURRENT_STATE WHERE PROFILEID IN (".$pstr.") AND STATE_ID NOT IN ('8','9')";
        $resFTO = mysql_query($sqlFTO,$master) or $SMSLib->errormail($sqlFTO,mysql_errno().":".mysql_error());
	$trans = 0;
	$pId   = array();
	$pstr  ='';
	while($rowFTO =mysql_fetch_array($resFTO))
	{
		$pId[$trans]=$rowFTO['PROFILEID'];
		$trans++;
	}
	if($pId)
	{
		$pstr=implode("','",$pId);
		$pstr="'".$pstr."'";
		$sql="SELECT *  FROM TEMP_SMS_DETAIL WHERE PROFILEID IN (".$pstr.") AND SENT!='Y'";
		$res = mysql_query($sql,$master) or $SMSLib->errormail($sql,mysql_errno().":".mysql_error());
		while($row = mysql_fetch_array($res))
		{
			$sql_insert=$sql_insert."('$row[PROFILEID]', '$row[SMS_TYPE]', '$row[SMS_KEY]', '".addslashes($row[MESSAGE])."', '$row[PHONE_MOB]', now(), '$sent'),";
			if($sms_type[$row["SMS_KEY"]]=="SERVICE")
				$temp1[]=$row;
			elseif($sms_type[$row["SMS_KEY"]]=="PROMO")
				$temp2[]=$row;
		}
		if($sql_insert && ($temp1 ||$temp2))
		{
			if($timeCondition)
			{
				foreach($temp1 as $key=>$val)
					$xmlData1 = $xmlData1 . $smsVendorObj->generateXml($val['PROFILEID'],$val["PHONE_MOB"],$val["MESSAGE"]);
				foreach($temp2 as $key=>$val)
					$xmlData2 = $xmlData2 . $smsVendorObj->generateXml($val['PROFILEID'],$val["PHONE_MOB"],$val["MESSAGE"]);
				//echo $xmlData1;
				if($xmlData1)
					$smsVendorObj->send($xmlData1,"transaction");
				//echo $xmlData2;
				if($xmlData2)
					$smsVendorObj->send($xmlData2,"promotion");
			}

			$sql_ex = "INSERT INTO newjs.SMS_DETAIL(PROFILEID, SMS_TYPE, SMS_KEY, MESSAGE, PHONE_MOB, ADD_DATE, SENT) VALUES ".substr($sql_insert,0,-1);
			mysql_query($sql_ex,$master) or $SMSLib->errormail($sql_ex,mysql_errno().":".mysql_error());
			$sql_Del="UPDATE `TEMP_SMS_DETAIL` SET `SENT` = 'Y' WHERE PROFILEID IN (".$pstr.") AND SENT!='Y'";
			mysql_query($sql_Del,$master) or $SMSLib->errormail($sql_Del,mysql_errno().":".mysql_error());
			$sql_up ="UPDATE `BEST_MATCH_SMS_LOG` SET `SENT` = 'Y' WHERE PROFILEID IN (".$pstr.") AND SENT!='Y'";
			mysql_query($sql_up,$master) or $SMSLib->errormail($sql_Del,mysql_errno().":".mysql_error());
	       }
       }
}

$sql_profile="SELECT COUNT(*) AS COUNT, PROFILEID FROM TEMP_SMS_DETAIL WHERE SENT!='Y' GROUP BY PROFILEID";
$res_profile = mysql_query($sql_profile,$master) or $SMSLib->errormail($sql_profile,mysql_errno().":".mysql_error());
$count = mysql_num_rows($res_profile);
$chunk=2000;
$totalChunks=ceil($count/$chunk);
for($j = 0;$j<$totalChunks;$j++)
{
	$timeCondition=$SMSLib->inSmsSendTimeRange();
	if($timeCondition)
		$sent='Y';
	else
		$sent='N';
	$trans = 0;
	$pId   = array();
	$pstr  = '';
	$temp1 = array();
	$temp2 = array();
	$details = array();
	$xmlData1='';
	$xmlData2='';
	$sql_insert="";
	$skip = $j*$chunk;
	mysql_data_seek($res_profile,$skip);
	while(($row_profile=mysql_fetch_assoc($res_profile)) && $trans<$chunk)
	{
		$pId[$trans]=$row_profile['PROFILEID'];
		$trans++;
	}
	$pstr=implode("','",$pId);
	$pstr="'".$pstr."'";
	$sqlFTO = "SELECT PROFILEID FROM FTO.FTO_CURRENT_STATE WHERE PROFILEID IN (".$pstr.") AND STATE_ID NOT IN ('8','9')";
        $resFTO = mysql_query($sqlFTO,$master) or $SMSLib->errormail($sqlFTO,mysql_errno().":".mysql_error());
	$trans = 0;
	$pId   = array();
	$pstr  ='';
	while($rowFTO =mysql_fetch_array($resFTO))
	{
		$pId[$trans]=$rowFTO['PROFILEID'];
		$trans++;
	}
	if($pId)
	{
		$pstr=implode("','",$pId);
		$pstr="'".$pstr."'";
		$sql="SELECT *  FROM TEMP_SMS_DETAIL WHERE PROFILEID IN (".$pstr.") AND SENT!='Y'";
		$res = mysql_query($sql,$master) or $SMSLib->errormail($sql,mysql_errno().":".mysql_error());
		while($row=mysql_fetch_array($res))
		{
			if(!$details[$row['PROFILEID']] || $details[$row['PROFILEID']]['PRIORITY']>$row['PRIORITY'])
				$details[$row['PROFILEID']]=$row;
		}
		foreach($details as $p=>$info)
		{
			$sql_insert=$sql_insert."('$info[PROFILEID]', '$info[SMS_TYPE]', '$info[SMS_KEY]', '".addslashes($info[MESSAGE])."', '$info[PHONE_MOB]', now(), '$sent'),";
			if($sms_type[$info["SMS_KEY"]]=="SERVICE")
				$temp1[]=$info;
			elseif($sms_type[$info["SMS_KEY"]]=="PROMO")
				$temp2[]=$info;
		}
		if($sql_insert && ($temp1 ||$temp2))
		{
			if($timeCondition)
			{
				foreach($temp1 as $key=>$val)
					$xmlData1 = $xmlData1 . $smsVendorObj->generateXml($val['PROFILEID'],$val["PHONE_MOB"],$val["MESSAGE"]);
				foreach($temp2 as $key=>$val)
					$xmlData2 = $xmlData2 . $smsVendorObj->generateXml($val['PROFILEID'],$val["PHONE_MOB"],$val["MESSAGE"]);

				//echo $xmlData1;
				if($xmlData1)
					$smsVendorObj->send($xmlData1,"transaction");
				//echo $xmlData2;
				if($xmlData2)
					$smsVendorObj->send($xmlData2,"promotion");
			}

			$sql_ex = "INSERT INTO newjs.SMS_DETAIL(PROFILEID, SMS_TYPE, SMS_KEY, MESSAGE, PHONE_MOB, ADD_DATE, SENT) VALUES ".substr($sql_insert,0,-1);
			mysql_query($sql_ex,$master) or $SMSLib->errormail($sql_ex,mysql_errno().":".mysql_error());


			foreach($temp1 as $k1=>$v1)
			{
				$sql_Del="UPDATE `TEMP_SMS_DETAIL` SET `SENT` = 'Y' WHERE PROFILEID='".$v1['PROFILEID']."' AND SMS_KEY='".$v1['SMS_KEY']."'";
				mysql_query($sql_Del,$master) or $SMSLib->errormail($sql_Del,mysql_errno().":".mysql_error());
				if($v1['SMS_KEY']=='MATCH_ALERT')
				{
					$sql_up ="UPDATE `BEST_MATCH_SMS_LOG` SET `SENT` = 'Y' WHERE PROFILEID='".$v1['PROFILEID']."' AND SENT!='Y'";
					mysql_query($sql_up,$master) or $SMSLib->errormail($sql_Del,mysql_errno().":".mysql_error());
				}
			}

			foreach($temp2 as $k1=>$v1)
			{
				$sql_Del="UPDATE TEMP_SMS_DETAIL SET SENT='Y' WHERE PROFILEID='".$v1['PROFILEID']."' AND SMS_KEY='".$v1['SMS_KEY']."'";
				mysql_query($sql_Del,$master) or $SMSLib->errormail($sql_Del,mysql_errno().":".mysql_error());
				if($v1['SMS_KEY']=='MATCH_ALERT' || $v1['SMS_KEY'] == 'MATCH_ALERT1')
				{
					$sql_up ="UPDATE `BEST_MATCH_SMS_LOG` SET `SENT` = 'Y' WHERE PROFILEID='".$v1['PROFILEID']."' AND SENT!='Y'";
					mysql_query($sql_up,$master) or $SMSLib->errormail($sql_Del,mysql_errno().":".mysql_error());
				}
			}
		}
	}
}

$cc='esha.jain@jeevansathi.com';
$to='tanu.gupta@jeevansathi.com';
$msg='';
$subject="Scheduled SMS send success mail";
$msg='Cron to send sms and update details table executed successfully<br/><br/>Warm Regards';
send_email($to,$msg,$subject,"",$cc);

?>
