<?php
/*************************************
Description: Cron for sending one day back remaining sms
**************************************/

$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
chdir($_SERVER["DOCUMENT_ROOT"]."/profile");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
include(JsConstants::$docRoot."/commonFiles/sms_inc.php");


$db_slave = connect_slave();
$db_master = connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_master);
$day_1=mktime(0,0,0,date("m"),date("d")-1,date("Y"));
$back_1day = date("Y-m-d",$day_1);
$curr = date("Y-m-d H:i:s");
$chunk = 2000;
$smsDetail = array();
$sql = "SELECT * FROM newjs.SMS_DETAIL WHERE ADD_DATE BETWEEN '$back_1day 00:00:00' AND '$curr' AND SENT!='Y'";
$res = mysql_query($sql,$db_slave) or trackSmsError($sql, $db_slave, "Sent Instant SMS");
$total = 0;
while($row=mysql_fetch_array($res))
{
	$smsDetail[$total]["KEY"] = $row["SMS_KEY"];
	$smsDetail[$total]["uniqueId"] = $row["PROFILEID"];
	$smsDetail[$total]["TYPE"] = $row["SMS_TYPE"];
	$smsDetail[$total]["ADD_DATE"] = $row["ADD_DATE"];
	$smsDetail[$total]["ID"] = $row["ID"];
	$smsDetail[$total]["number"] = $row["PHONE_MOB"];
	$smsDetail[$total]["message"] = $row["MESSAGE"];
	$total++;
}

if($smsDetail)
{
	$final = array_chunk($smsDetail,$chunk);
	$count = count($smsDetail);
	$availableChunks = count($final);
	for($i=0;$i<$availableChunks;$i++)
	{
		$xmlData = "";
		$xmlData = getXmlData($final[$i]);
		sendSMS($xmlData,"priority");
		updateSmsDetail($final[$i], $db_master);
	}
}
trackSmsError("", "", "Sent $total Instant SMS");
/*$final = array_chunk($sms,$chunk);
$count = count($final);
for($i = 0; $i<$count; $i++)
{
                $profileIdComma = "";
                $profileDetail = array();
                foreach($final[$i] as $key=>$val)
                {
                        $profileIdComma = $profileIdComma.$val["PROFILEID"].",";
                }
                $profileIdComma = substr($profileIdComma,0,-1);
                $sql = "SELECT PROFILEID, USERNAME, PHONE_MOB, GET_SMS, COUNTRY_RES, SERVICE_MESSAGES, SUBSCRIPTION FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID IN ($profileIdComma) AND ACTIVATED='Y' AND SERVICE_MESSAGES!='N' AND GET_SMS!='N' AND COUNTRY_RES='51'";
                $res = mysql_query_decide($sql, $db_slave);
                while($row = mysql_fetch_array($res))
                {
                        $profileDetail[$row["PROFILEID"]]["USERNAME"] = $row["USERNAME"];
                        $profileDetail[$row["PROFILEID"]]["PROFILEID"] = $row["PROFILEID"];
                        $profileDetail[$row["PROFILEID"]]["PHONE_MOB"] = $row["PHONE_MOB"];
                        $profileDetail[$row["PROFILEID"]]["KEY"] = $sms[$row["PROFILEID"]]["KEY"];
                        $profileDetail[$row["PROFILEID"]]["TYPE"] = $sms[$row["PROFILEID"]]["TYPE"];
                }
		//print_r($profileDetail);
		sendSupportSms($profileDetail);
}*/
?>
