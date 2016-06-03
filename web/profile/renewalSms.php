<?php
/*************************************
Description: Cron for sending renewal sms
**************************************/
chdir(dirname(__FILE__));
include_once "connect.inc";
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
include_once("../classes/Membership.class.php");
$db_slave = connect_slave();
$db_master = connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_master);
$sms = getSmsStatus();
if($sms["S_RENEW"] || $sms["S_RENEW_POST"])
{
	$memberObj = new Membership;
	if($sms["S_RENEW"] && $sms["S_RENEW_POST"])
		{$start = 0;$end = 2;}
	elseif($sms["S_RENEW"])
		{$start = 0;$end = 1;}
	elseif($sms["S_RENEW_POST"])
		{$start = 1;$end = 2;}
	for($i=$start;$i<$end;$i++)
	{
		$profileDetail = array();
		if($i==1)
			$flag=7;
		$profileIdArr = array();
		$profileIds = "";
		$profileIdsComma = "";
		if($profileIdArr)
		{
		foreach ($profileIdArr as $key=>$val)
		{
			$profileIds = $profileIds.$key.",";
		}
		if($profileIds)
			$profileIdsComma = substr($profileIds,0,-1);
		}

		if($profileIdsComma)
		{
			$sql = "SELECT PROFILEID, USERNAME, PHONE_MOB, MOB_STATUS, GET_SMS, COUNTRY_RES, SERVICE_MESSAGES, SUBSCRIPTION FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID IN ($profileIdsComma) AND ACTIVATED!='D' AND ACTIVATED!='N' AND SERVICE_MESSAGES!='U' AND GET_SMS!='N' AND COUNTRY_RES='51' && PHONE_MOB!=''";
			$res = mysql_query_decide($sql, $db_slave);
			while($row = mysql_fetch_array($res))
			{
				$profileDetail[$row["PROFILEID"]]["USERNAME"]  = $row["USERNAME"];
				$profileDetail[$row["PROFILEID"]]["PROFILEID"] = $row["PROFILEID"];
				$profileDetail[$row["PROFILEID"]]["PHONE_MOB"] = $row["PHONE_MOB"];
				$profileDetail[$row["PROFILEID"]]["MOB_STATUS"]= $row["MOB_STATUS"];
				if($i == 0)
					$profileDetail[$row["PROFILEID"]]["KEY"] = "S_RENEW";
				else
					$profileDetail[$row["PROFILEID"]]["KEY"] = "S_RENEW_POST";
				$profileDetail[$row["PROFILEID"]]["SMS_TYPE"] = "SP";
				$profileDetail[$row["PROFILEID"]]["MESSAGE"] = getScheduledSms($profileDetail[$row["PROFILEID"]]["KEY"], $profileDetail[$row["PROFILEID"]]);
			}
			if($profileDetail)
			{
				$profileDetail = getMobValidityArr($profileDetail);
				$hour = getIndianTime();
				if($hour >= 10 && $hour <= 20)
				{
					foreach($profileDetail as $key=>$val)
					{
						if($val["MOB_VERIFIED"])
						{
							$from = getFromMobile($val["PHONE_MOB"]);
							$profileDetail[$val['PROFILEID']]["SENT"] = "Y";
							$xmlData = $xmlData . generateReceiverXmlData($val['PROFILEID'], $val["MESSAGE"], $from, $val["PHONE_MOB"]);
						}
					}
					if($xmlData)
						sendSMS($xmlData,"priority");
				}
				insertMultipleSmsDetail($profileDetail);
			}
		}
	}
}
?>
