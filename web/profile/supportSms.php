<?php
/*************************************
Description: Cron for sending scheduled activity sms
Frequency: Wednesday and Saturday
**************************************/
chdir(dirname(__FILE__));
ini_set("max_execution_time","0");
ini_set('memory_limit', '128M');
include_once "connect.inc";
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once("functions.inc");
$db_slave = connect_slave();
$db_master = connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_master);
$day_90=mktime(0,0,0,date("m"),date("d")-90,date("Y"));
$back_90_days=date("Y-m-d",$day_90);

$chunk = 2000;
mysql_query("set session wait_timeout=10000",$db_slave);
$sms = getSmsStatus();

if($sms["S_VERIFY"] || $sms["S_PHOTO"] || $sms["S_PERCENT"])
{
	$sql = "SELECT PROFILEID, USERNAME, HAVEPHOTO, PHONE_MOB, MOB_STATUS, GET_SMS, COUNTRY_RES, SERVICE_MESSAGES, SUBSCRIPTION, LAST_LOGIN_DT as lastLoginDate FROM newjs.JPROFILE WHERE LAST_LOGIN_DT>='$back_90_days' AND ACTIVATED='Y' AND SERVICE_MESSAGES!='U' AND GET_SMS!='N' AND COUNTRY_RES='51' AND PHONE_MOB!=''  and  activatedKey=1 ";
	$res = mysql_query($sql, $db_slave) or die(trackSmsError($sql, $db_slave, "SUPPORT"));

	$count = mysql_num_rows($res);
	$totalChunks=ceil($count/$chunk);
	for($j = 0; $j<$totalChunks; $j++)
	{
		$trans = 0;
		$skip = $j*$chunk;
                mysql_data_seek($res,$skip);
		while(($row = mysql_fetch_array($res)) && $trans<$chunk)
		{
			$profileDetail[$row["PROFILEID"]]["USERNAME"] = $row["USERNAME"];
			$profileDetail[$row["PROFILEID"]]["PROFILEID"] = $row["PROFILEID"];
			$profileDetail[$row["PROFILEID"]]["PHONE_MOB"] = $row["PHONE_MOB"];
			$profileDetail[$row["PROFILEID"]]["MOB_STATUS"]= $row["MOB_STATUS"];
			$profileDetail[$row["PROFILEID"]]["HAVEPHOTO"] = $row["HAVEPHOTO"];
			$trans++;
		}	
		$profileDetail = getMobValidityArr($profileDetail, $db_slave);
		foreach($profileDetail as $key=>$val)
		{
			$profileDetail[$val["PROFILEID"]]["SMS_TYPE"] = "SS";
			if($sms["S_VERIFY"] && !$val["MOB_VERIFIED"])
				$profileDetail[$val["PROFILEID"]]["KEY"] = "S_VERIFY";
			else
			{
				if($val["MOB_VERIFIED"])
				{
					if($sms["S_PHOTO"]  && ($val["HAVEPHOTO"] == 'N' || $val["HAVEPHOTO"] == ''))
						$profileDetail[$val["PROFILEID"]]["KEY"] = "S_PHOTO";
					else
					{
						if($sms["S_PERCENT"])
						{
							$cScoreObject = ProfileCompletionFactory::getInstance(null,null,$val["PROFILEID"]);
							$percent = $cScoreObject->getProfileCompletionScore();
							//$percent = profile_percent($val["PROFILEID"]);
							if($percent<70)
								$profileDetail[$val["PROFILEID"]]["KEY"] = "S_PERCENT";
						}
					}	
				}
			}
			if(!$profileDetail[$val["PROFILEID"]]["KEY"])
			{
				unset($profileDetail[$val["PROFILEID"]]);
			}
		}

		if($profileDetail)
		{
			$smsDetail = array();
			$xmlData = "";
			foreach($profileDetail as $key=>$val)
			{
				$smsDetail[$val["PROFILEID"]]["PROFILEID"] = $val["PROFILEID"];
				$smsDetail[$val["PROFILEID"]]["SMS_TYPE"] = $val["SMS_TYPE"];
				$smsDetail[$val["PROFILEID"]]["KEY"] = $val["KEY"];
				$smsDetail[$val["PROFILEID"]]["MESSAGE"] = getScheduledSms($val["KEY"], $val);
				$smsDetail[$val["PROFILEID"]]["PHONE_MOB"] = validateMobilePhone($val["PHONE_MOB"]);
				$smsDetail[$val["PROFILEID"]]["MOB_VERIFIED"] = $val["MOB_VERIFIED"];
				unset($profileDetail[$key]);
			}
			if($smsDetail)
			{
				$hour = getIndianTime();
				if($hour >= 10 && $hour <= 20)
				{
					foreach($smsDetail as $key=>$val)
					{
						$from = getFromMobile($val["PHONE_MOB"]);
						$smsDetail[$val['PROFILEID']]["SENT"] = "Y";
						$xmlData = $xmlData . generateReceiverXmlData($val['PROFILEID'], $val["MESSAGE"], $from, $val["PHONE_MOB"]);
					}
					sendSMS($xmlData,"priority");
				}
				insertMultipleSmsDetail($smsDetail);
			}
			unset($smsDetail);
		}
	}
}
trackSmsError("", "", "Sent Support SMS");
?>
