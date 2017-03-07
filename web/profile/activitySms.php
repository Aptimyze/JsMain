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
include_once("$_SERVER[DOCUMENT_ROOT]/crontabs/astro/lock.php");

$fp=get_lock("activitySms");

$db_slave = connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_slave);
$match_slave=connect_slave81();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$match_slave);
$db_master = connect_db();
$db_ddl = connect_ddl();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_master);

/*Mysql lock
$sql_lock = "SELECT GET_LOCK('activitySms', 36000)";
mysql_query($sql_lock, $match_slave) or die(mysql_error());
*/

$day_90=mktime(0,0,0,date("m"),date("d")-90,date("Y"));
$back_90_days=date("Y-m-d",$day_90);
$day_30=mktime(0,0,0,date("m"),date("d")-30,date("Y"));
$back_30_days=date("Y-m-d",$day_30);
$today = date("Y-m-d");
$sms = getSmsStatus();
$mysqlObj=new Mysql;
global $activeServers,$noOfActiveServers,$slave_activeServers;
for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId, "slave");
        $myDbarr[$myDbName]=$mysqlObj->connect("$myDbName");
        mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$myDbarr[$myDbName]);
}
$chunk = 2000;
$sql = "SELECT * FROM TEMP_ACTIVITY_SMS WHERE SENT!='Y'";
$res = mysql_query($sql, $db_master) or die(trackSmsError($sql, $db_master, "AS_TEMP"));
$count = mysql_num_rows($res);
if($count){
	$activityCount = 0;
	$totalChunks=ceil($count/$chunk);
}else{
	$sql_a = "SELECT PROFILEID, USERNAME, PHONE_MOB, SUBSCRIPTION, DATE(LAST_LOGIN_DT) LAST_LOGIN_DT FROM JPROFILE WHERE DATE(LAST_LOGIN_DT)>='$back_90_days' AND ACTIVATED='Y' AND COUNTRY_RES='51' AND SERVICE_MESSAGES!='U' AND GET_SMS!='N' AND PHONE_MOB!='' AND MOB_STATUS='Y' and   activatedKey=1 ";
	$res_a=mysql_query($sql_a,$db_slave) or die(trackSmsError($sql_a, $db_slave, "AS_LOGIN"));
	$count_a = mysql_num_rows($res_a);
	$totalChunks_a=ceil($count_a/$chunk);
	for($j = 0;$j<$totalChunks_a;$j++){
		$trans_a = 0;
		$row_pool = array();
		$skip_a = $j*$chunk;
		mysql_data_seek($res_a,$skip_a);
		while(($row_a=mysql_fetch_assoc($res_a)) && $trans_a<$chunk){
			$row_pool[$trans_a] = $row_a;
			$trans_a++;
		}
		$sql_ins = "INSERT INTO TEMP_ACTIVITY_SMS(PROFILEID, USERNAME, PHONE_MOB, SUBSCRIPTION, LAST_LOGIN_DT, ADD_TIME) VALUES";
		foreach($row_pool as $k=>$v){
			$sql_ins.="('$v[PROFILEID]', '$v[USERNAME]', '$v[PHONE_MOB]', '$v[SUBSCRIPTION]', '$v[LAST_LOGIN_DT]', now()),";
		}
		$sql_ins = substr($sql_ins,0,-1); 
		//echo $sql_ins;
		$error = 0;
		mysql_query($sql_ins, $db_master) or die($error=1);
		if($error){
			$sql_del = "TRUNCATE TABLE TEMP_ACTIVITY_SMS";
			mysql_query($sql_del,$db_ddl) or die(mysql_error($db_ddl));
			die(trackSmsError($sql_a, $db_master, "AS_TEMP_INSERT"));
		}
	}
	$sql = "SELECT * FROM TEMP_ACTIVITY_SMS";
	$res = mysql_query($sql, $db_master) or die(trackSmsError($sql, $db_master, "AS_TEMP"));
	$count = mysql_num_rows($res);
	$activityCount = 0;
	$totalChunks=ceil($count/$chunk);
}
for($i = 0; $i<$totalChunks; $i++)
{
	$data = array();
	$trans = 0;
	$skip = $i*$chunk;
	mysql_data_seek($res,$skip);
	$profilesLoggedIn = array();
	while(($row=mysql_fetch_assoc($res)) && $trans<$chunk)
	{
		$trans++;
		$profilesLoggedIn[$row["PROFILEID"]]["PROFILEID"] = $row["PROFILEID"];
		$profilesLoggedIn[$row["PROFILEID"]]["LAST_LOGIN_DATE"] = $row["LAST_LOGIN_DT"];
		
		$myDbName=getProfileDatabaseConnectionName($row["PROFILEID"],'slave',$mysqlObj);
		$myDbSlave=$myDbarr[$myDbName];

		if($sms["A_ACCEPT"])
		{
			$sqlA = "SELECT RECEIVER FROM CONTACTS WHERE SENDER='$row[PROFILEID]' AND TYPE='A' AND TIME>='$row[LAST_LOGIN_DT]' LIMIT 4";
			//echo $sqlA."\n";
			$resA=mysql_query($sqlA,$myDbSlave) or die(trackSmsError($sqlA, $myDbSlave, "A_ACCEPT"));
			$eoiReceiver = array();
			while($rowA = mysql_fetch_assoc($resA))
			{
				$eoiReceiver[] = $rowA["RECEIVER"];
			}
			if($eoiReceiver)
			{
				$data[$row["PROFILEID"]]["DATA"] = implode(",", $eoiReceiver);
				$data[$row["PROFILEID"]]["KEY"] = "A_ACCEPT";
				$data[$row["PROFILEID"]]["PROFILEID"] = $row["PROFILEID"];
				$data[$row["PROFILEID"]]["SUBSCRIPTION"] = $row["SUBSCRIPTION"];
				$data[$row["PROFILEID"]]["USERNAME"] = $row["USERNAME"];
				$data[$row["PROFILEID"]]["PHONE_MOB"] = $row["PHONE_MOB"];
			}
		}
		if(!$data[$row["PROFILEID"]]["PROFILEID"])
		{
			if($sms["A_EOI"])
			{
				$sqlC = "SELECT SENDER FROM CONTACTS WHERE RECEIVER='$row[PROFILEID]' AND TYPE='I' AND TIME>='$row[LAST_LOGIN_DT]' AND FILTERED!='Y' LIMIT 4";
				//echo $sqlC."\n";
				$resC=mysql_query($sqlC,$myDbSlave) or die(trackSmsError($sqlC, $myDbSlave, "A_EOI"));
				$eoiSender = array();
				while($rowC = mysql_fetch_assoc($resC))
				{
					$eoiSender[] = $rowC["SENDER"];
				}
				if($eoiSender)
				{
					$data[$row["PROFILEID"]]["DATA"] = implode(",", $eoiSender);
					$data[$row["PROFILEID"]]["KEY"] = "A_EOI";
					$data[$row["PROFILEID"]]["PROFILEID"] = $row["PROFILEID"];
					$data[$row["PROFILEID"]]["SUBSCRIPTION"] = $row["SUBSCRIPTION"];
					$data[$row["PROFILEID"]]["USERNAME"] = $row["USERNAME"];
					$data[$row["PROFILEID"]]["PHONE_MOB"] = $row["PHONE_MOB"];
				}
			}
			if(!$data[$row["PROFILEID"]]["PROFILEID"])
			{
				if($sms["A_PHOTO"])
				{
					$sqlP = "SELECT COUNT(PROFILEID) PHOTO_COUNT FROM PHOTO_REQUEST WHERE PROFILEID_REQ_BY = '$row[PROFILEID]' AND `DATE`>='$row[LAST_LOGIN_DT]'";
					//echo $sqlP."\n";
					$resP=mysql_query($sqlP,$myDbSlave) or die(trackSmsError($sqlP, $myDbSlave, "A_PHOTO")); 
					$rowP = mysql_fetch_assoc($resP);
					if($rowP["PHOTO_COUNT"])
					{
						$data[$row["PROFILEID"]]["DATA"] = $rowP["PHOTO_COUNT"];
						$data[$row["PROFILEID"]]["KEY"] = "A_PHOTO";
						$data[$row["PROFILEID"]]["PROFILEID"] = $row["PROFILEID"];
						$data[$row["PROFILEID"]]["SUBSCRIPTION"] = $row["SUBSCRIPTION"];
						$data[$row["PROFILEID"]]["USERNAME"] = $row["USERNAME"];
						$data[$row["PROFILEID"]]["PHONE_MOB"] = $row["PHONE_MOB"];
					}
				}
				if(!$data[$row["PROFILEID"]]["PROFILEID"])
				{
					if($sms["A_MSG"])
					{
						$Shard=JsDbSharding::getShardNo($row["PROFILEID"],'slave');
						$dbMessageLogObj=new NEWJS_MESSAGE_LOG($Shard);
						$rowG=$dbMessageLogObj->getMessageCountSmsActivity($row['PROFILEID'],$row['LAST_LOGIN_DT']);
						//$sqlG = "SELECT COUNT(SENDER) MSG_COUNT FROM MESSAGE_LOG WHERE RECEIVER = '$row[PROFILEID]' AND IS_MSG='Y' AND TYPE='R' AND `DATE`>='$row[LAST_LOGIN_DT]'";
						//echo $sqlG."\n";
						//$resG=mysql_query($sqlG,$myDbSlave) or die(trackSmsError($sqlG, $myDbSlave, "A_MSG")); 
						//$rowG = mysql_fetch_assoc($resG);
						if($rowG)
						{
							$data[$row["PROFILEID"]]["DATA"] = $rowG;
							$data[$row["PROFILEID"]]["KEY"] = "A_MSG";
							$data[$row["PROFILEID"]]["PROFILEID"] = $row["PROFILEID"];
							$data[$row["PROFILEID"]]["SUBSCRIPTION"] = $row["SUBSCRIPTION"];
							$data[$row["PROFILEID"]]["USERNAME"] = $row["USERNAME"];
							$data[$row["PROFILEID"]]["PHONE_MOB"] = $row["PHONE_MOB"];
						}
					}
					if(!$data[$row["PROFILEID"]]["PROFILEID"])
					{
						if($sms["A_HORO"])
						{
							$sqlH = "SELECT COUNT(PROFILEID) HORO_COUNT FROM HOROSCOPE_REQUEST WHERE PROFILEID_REQUEST_BY = '$row[PROFILEID]' AND `DATE`>='$row[LAST_LOGIN_DT]'";
							//echo $sqlH."\n";
							$resH=mysql_query($sqlH,$myDbSlave) or die(trackSmsError($sqlH, $myDbSlave, "A_HORO"));
							$rowH = mysql_fetch_assoc($resH);
							if($rowH["HORO_COUNT"])
							{
								$data[$row["PROFILEID"]]["DATA"] = $rowH["HORO_COUNT"];
								$data[$row["PROFILEID"]]["KEY"] = "A_HORO";
								$data[$row["PROFILEID"]]["PROFILEID"] = $row["PROFILEID"];
								$data[$row["PROFILEID"]]["SUBSCRIPTION"] = $row["SUBSCRIPTION"];
								$data[$row["PROFILEID"]]["USERNAME"] = $row["USERNAME"];
								$data[$row["PROFILEID"]]["PHONE_MOB"] = $row["PHONE_MOB"];
							}
						}
						/********Removed by product #391
						if(!$data[$row["PROFILEID"]]["PROFILEID"])
						{
							if($sms["A_MATCH"])
							{
								if($row["LAST_LOGIN_DT"]<$back_30_days)
								{
									$lastLoginDate = getMatchTimeStamp($row["LAST_LOGIN_DT"]);
									$sqlM = "SELECT COUNT(USER) MATCH_COUNT FROM matchalerts.LOG WHERE RECEIVER='$row[PROFILEID]' AND `DATE`>='$lastLoginDate'";
									//echo $sqlM."\n";
									$logTableError = 0;
									$resM = mysql_query($sqlM,$match_slave) or ($logTableError = 1);
									if($logTableError){
										sleep(20);
										$sqlM = "SELECT COUNT(USER) MATCH_COUNT FROM matchalerts.LOG WHERE RECEIVER='$row[PROFILEID]' AND `DATE`>='$lastLoginDate'";
										$resM = mysql_query($sqlM,$match_slave) or die(trackSmsError($sqlM, $match_slave, "A_MATCH"));
									}
									$rowM = mysql_fetch_assoc($resM);
									if($rowM["MATCH_COUNT"])
									{
										$data[$row["PROFILEID"]]["DATA"] = $rowM["MATCH_COUNT"];
										$data[$row["PROFILEID"]]["KEY"] = "A_MATCH";
										$data[$row["PROFILEID"]]["PROFILEID"] = $row["PROFILEID"];
										$data[$row["PROFILEID"]]["SUBSCRIPTION"] = $row["SUBSCRIPTION"];
										$data[$row["PROFILEID"]]["USERNAME"] = $row["USERNAME"];
										$data[$row["PROFILEID"]]["PHONE_MOB"] = $row["PHONE_MOB"];
									}
								}
							}
						}
						*************/
					}
				}
			}
		}
	}
	if($data){
		$activityChunk = processSMS($data, $db_slave, $db_master);
		$activityCount=$activityChunk+$activityCount;
	}
}
$sql_del = "TRUNCATE TABLE TEMP_ACTIVITY_SMS";
mysql_query($sql_del,$db_ddl) or die(trackSmsError($sql_del, $db_ddl, "AS_TEMP_TRUNCATE"));
trackSmsError("", "", "Sent $activityCount Activity SMS");

release_lock($fp);
/*Release Mysql lock
$sql_lock = "SELECT RELEASE_LOCK('activitySms')";
mysql_query($sql_lock, $match_slave) or die(mysql_error());
*/

function getMatchTimeStamp($date)
{
	$date = JSstrToTime($date);
	$zero=mktime(0,0,0,01,01,2005);
	$gap=($date-$zero)/(24*60*60);
	return $gap;
}

function processSMS($dataArr, $db_slave, $db_master)
{
	$profileDetail = array();
	foreach($dataArr as $key => $val)
	{
		$eoiArr = array(); 
		if($val["KEY"] == "A_EOI" || $val["KEY"] == "A_ACCEPT")
		{
			$eoiArr = explode(",",$val["DATA"]);
			$profileDetail[$val["PROFILEID"]]["PROFILE_ARR"] = $eoiArr;
			$eoiComma = $eoiComma.$val["DATA"].",";
		}
	}
	if($eoiComma)
	{
		$eoiComma = substr($eoiComma,0,-1);
		@mysql_ping($db_slave);
		$sql = "SELECT PROFILEID,USERNAME,GENDER FROM newjs.JPROFILE WHERE   activatedKey=1 and PROFILEID IN($eoiComma)";
		$res = mysql_query($sql, $db_slave) or die(trackSmsError($sql, $db_slave, "JPROFILE"));
		while($row = mysql_fetch_assoc($res))
		{
			$username[$row["PROFILEID"]] = $row["USERNAME"];
			$gender[$row["PROFILEID"]] = $row["GENDER"];
		}
	}
	if($dataArr)
	{
		foreach($dataArr as $key=>$val)
		{
			$profileDetail[$val["PROFILEID"]]["USERNAME"] = $val["USERNAME"];
			$profileDetail[$val["PROFILEID"]]["PROFILEID"] = $val["PROFILEID"];
			$profileDetail[$val["PROFILEID"]]["PHONE_MOB"] = $val["PHONE_MOB"];
			$profileDetail[$val["PROFILEID"]]["SUBSCRIPTION"] = $val["SUBSCRIPTION"];
			$profileDetail[$val["PROFILEID"]]["KEY"] = $val["KEY"];
			$profileDetail[$val["PROFILEID"]]["DATA"] = $val["DATA"];
			$profileDetail[$val["PROFILEID"]]["SMS_TYPE"] = "AS";
			$userNameArr = array();
			$genderArr = array();
			if($profileDetail[$val["PROFILEID"]]["PROFILE_ARR"])
			{
				foreach($profileDetail[$val["PROFILEID"]]["PROFILE_ARR"] as $key1=>$val1)
				{
					$profileDetail[$val["PROFILEID"]]["OTHER_USERNAME"][$val1] = $username[$val1];
					$profileDetail[$val["PROFILEID"]]["GENDER"][$val1] = $gender[$val1];
					$userNameArr[] = $username[$val1];
					$genderArr[] = $gender[$val1];
				}
				$profileDetail[$val["PROFILEID"]]["OTHER_NAMES"] = getEoiNames($userNameArr);
				$profileDetail[$val["PROFILEID"]]["HIS_HER"] = getHisHer($genderArr);
			}
		}
		unset($dataArr);
	}
	$hour = getIndianTime();
	$smsDetail = array();
	$sentProfileArr = array();
	$activeChunksCount = 0;
	foreach($profileDetail as $key=>$val)
	{
		$smsDetail[$val["PROFILEID"]]["PROFILEID"] = $val["PROFILEID"];
		$smsDetail[$val["PROFILEID"]]["SMS_TYPE"] = $val["SMS_TYPE"];
		$smsDetail[$val["PROFILEID"]]["KEY"] = $val["KEY"];
		$smsDetail[$val["PROFILEID"]]["MESSAGE"] = getScheduledSms($val["KEY"], $val);
		$smsDetail[$val["PROFILEID"]]["PHONE_MOB"] = validateMobilePhone($val["PHONE_MOB"]);
		$smsDetail[$val["PROFILEID"]]["MOB_VERIFIED"] = true;
		$sentProfileArr[] = $val["PROFILEID"];
		$activeChunksCount++;
	}
	unset($profileDetail);
	if($smsDetail)
	{
		$xmlData = "";
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
		if($sentProfileArr){
			$sentProfileIds = implode("','",$sentProfileArr);
			$sql_m = "UPDATE TEMP_ACTIVITY_SMS SET SENT='Y' WHERE PROFILEID IN ('$sentProfileIds')";
			mysql_query($sql_m, $db_master) or die(trackSmsError($sql_m, $db_master, "UPDATE_TEMP_ACTIVITY"));
		}
		unset($smsDetail);
	}
	return $activeChunksCount;
}

function getEoiNames($userNameArr)
{
	$userName = "";
	if($userNameArr)
	{
	if(count($userNameArr) == 1)
	{
		$userName = $userNameArr[0];
	}
	elseif(count($userNameArr) == 2)
	{
		$userName = $userNameArr[0]." and ".$userNameArr[1];
	}
	else
		$userName = "2+";
	/*elseif(count($userNameArr) == 3)
	{
		$userName = $userNameArr[0].", ".$userNameArr[1]." and ".$userNameArr[2];
	}
	else
	{
		$totalCount = count($userNameArr);
		$cnt = $totalCount-2;
		$userName = $userNameArr[0].", ".$userNameArr[1]." and ".$cnt." others";
	}
	*/
	}
	return $userName;
}

function getHisHer($genderArr){
	$hisHer = "";
	if($genderArr){
		if(count($genderArr) == 1)
			$hisHer = $genderArr[0] == "M"?"his":"her";
		else
			$hisHer = "their";
	}
	return $hisHer;
}
	

?>
