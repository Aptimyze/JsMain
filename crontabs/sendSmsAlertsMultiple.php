<?php
include_once("/usr/local/scripts/DocRoot.php");
ini_set('memory_limit',-1);
$fromCrontab = 1;
include_once(JsConstants::$docRoot."/profile/connect.inc");

include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once(JsConstants::$docRoot."/classes/SMSLib.class.php");
include_once(JsConstants::$docRoot."/classes/SmsVendorFactory.class.php");
$master=connect_db();
$SMSLib = new SMSLib("S");
$smsVendorObj = SmsVendorFactory::getSmsVendor("air2web");
$sql_sms_type= "SELECT SMS_KEY,SMS_SUBSCRIPTION FROM SMS_TYPE WHERE 1";
$res_sms_type=mysql_query($sql_sms_type,$master) or $SMSLib->errormail($sql_sms_type,mysql_errno().":".mysql_error());
while($row_sms_type=mysql_fetch_assoc($res_sms_type))
{
	$sms_type[$row_sms_type["SMS_KEY"]] = $row_sms_type["SMS_SUBSCRIPTION"];
}
$sql_r = "SELECT * FROM newjs.TEMP_SMS_TIMELIMITS WHERE 1";
$res_r=mysql_query($sql_r,$master) or $SMSLib->errormail($sql_r,mysql_errno().":".mysql_error());
while($row_r=mysql_fetch_assoc($res_r))
{
        $timelimit[$row_r["SMS_KEY"]]["TIME1"] = $row_r["TIME1"];
        $timelimit[$row_r["SMS_KEY"]]["TIME2"] = $row_r["TIME2"];
}



$sql_profile="SELECT COUNT(*) AS COUNT, PROFILEID FROM TEMP_SMS_DETAIL WHERE SENT!='Y' AND MUL_SMS = 'MUL' GROUP BY PROFILEID HAVING COUNT(*)=1";
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
	unset($pId);
	$pstr="'".$pstr."'";
	$sql="SELECT *  FROM TEMP_SMS_DETAIL WHERE PROFILEID IN (".$pstr.") AND SENT!='Y' AND MUL_SMS = 'MUL'";
        $res = mysql_query($sql,$master) or $SMSLib->errormail($sql,mysql_errno().":".mysql_error());
        while($row = mysql_fetch_array($res))
        {
		$arrW[$row[PROFILEID]] = $row;
	}
	foreach($arrW as $kn=>$vn)
	{
		if($vn['SMS_KEY']=="EOI")
			$list["EOI"][] = $vn;
		elseif($vn['SMS_KEY']=="ACCEPT")
                        $list["ACCEPT"][] = $vn;
		elseif($vn['SMS_KEY']=="PHOTO_REQUEST")
                        $list["PHOTO_REQUEST"][] = $vn;
		else
                        $final[] = $vn;
	}
	unset($arrW);
	$arrX = UnseenProfiles($list,$timelimit);
	unset($list);
	if(is_array($final))
		$final = array_merge($final,(array)$arrX);
	else
		$final = $arrX;
	unset($arrX);
	foreach($final as $kx=>$vx)
	{
			$sql_insert=$sql_insert."('$vx[PROFILEID]', '$vx[SMS_TYPE]', '$vx[SMS_KEY]', '".addslashes($vx[MESSAGE])."', '$vx[PHONE_MOB]', now(), '$sent'),";
			if($sms_type[$vx["SMS_KEY"]]=="SERVICE")
				$temp1[]=$vx;
			elseif($sms_type[$vx["SMS_KEY"]]=="PROMO")
				$temp2[]=$vx;
        }
	unset($final);
        if($sql_insert && ($temp1 ||$temp2))
        {
                if($timeCondition)
                {
                        foreach($temp1 as $key=>$val)
                                $xmlData1 = $xmlData1 . $smsVendorObj->generateXml($val['PROFILEID'],$val["PHONE_MOB"],$val["MESSAGE"]);
			unset($temp1);
                        foreach($temp2 as $key=>$val)
                                $xmlData2 = $xmlData2 . $smsVendorObj->generateXml($val['PROFILEID'],$val["PHONE_MOB"],$val["MESSAGE"]);
			unset($temp2);
                        //echo $xmlData1;
                        if($xmlData1)
                                $smsVendorObj->send($xmlData1,"transaction");
			unset($xmlData1);
                        //echo $xmlData2;
                        if($xmlData2)
                                $smsVendorObj->send($xmlData2,"promotion");
			unset($xmlData2);
                }

                $sql_ex = "INSERT INTO newjs.SMS_DETAIL(PROFILEID, SMS_TYPE, SMS_KEY, MESSAGE, PHONE_MOB, ADD_DATE, SENT) VALUES ".substr($sql_insert,0,-1);
                mysql_query($sql_ex,$master) or $SMSLib->errormail($sql_ex,mysql_errno().":".mysql_error());
                $sql_Del="UPDATE `TEMP_SMS_DETAIL` SET `SENT` = 'Y' WHERE PROFILEID IN (".$pstr.") AND SENT!='Y'";
                mysql_query($sql_Del,$master) or $SMSLib->errormail($sql_Del,mysql_errno().":".mysql_error());
                $sql_up ="UPDATE `BEST_MATCH_SMS_LOG` SET `SENT` = 'Y' WHERE PROFILEID IN (".$pstr.") AND SENT!='Y'";
                mysql_query($sql_up,$master) or $SMSLib->errormail($sql_Del,mysql_errno().":".mysql_error());
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
	unset($pId);
	$pstr="'".$pstr."'";
	$list = array();
	$arrX = array();
	$sql="SELECT *  FROM TEMP_SMS_DETAIL WHERE PROFILEID IN (".$pstr.") AND SENT!='Y'";
	$res = mysql_query($sql,$master) or $SMSLib->errormail($sql,mysql_errno().":".mysql_error());
	while($row=mysql_fetch_array($res))
	{
                if($row['SMS_KEY']=="EOI")
                        $list["EOI"][] = $row;
                elseif($row['SMS_KEY']=="ACCEPT")
                        $list["ACCEPT"][] = $row;
                elseif($row['SMS_KEY']=="PHOTO_REQUEST")
                        $list["PHOTO_REQUEST"][] = $row;
                else
                        $final[] = $row;
	}
	$arrX = UnseenProfiles($list,$timelimit);
	unset($list);
	if(is_array($final))
		$final = array_merge($final,(array)$arrX);
	else
		$final = $arrX;
	unset($arrX);
	foreach($final as $kf=>$kv)
	{
		if(!$details[$kv['PROFILEID']] || $details[$kv['PROFILEID']]['PRIORITY']>$kv['PRIORITY'])
			$details[$kv['PROFILEID']]=$kv;
	}
	unset($final);
	foreach($details as $p=>$info)
	{
                $sql_insert=$sql_insert."('$info[PROFILEID]', '$info[SMS_TYPE]', '$info[SMS_KEY]', '".addslashes($info[MESSAGE])."', '$info[PHONE_MOB]', now(), '$sent'),";
		if($sms_type[$info["SMS_KEY"]]=="SERVICE")
			$temp1[]=$info;
		elseif($sms_type[$info["SMS_KEY"]]=="PROMO")
			$temp2[]=$info;
	}
	unset($details);
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
			unset($xmlData1);
			//echo $xmlData2;
			if($xmlData2)
				$smsVendorObj->send($xmlData2,"promotion");
			unset($xmlData2);
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
		unset($temp1);
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
		unset($temp2);
	}
}

$cc='esha.jain@jeevansathi.com';
$to='nitesh.s@jeevansathi.com';
$msg='';
$subject="Scheduled SMS send success mail";
$msg='Cron to send sms and update details table executed successfully<br/><br/>Warm Regards';
send_email($to,$msg,$subject,"",$cc);

function UnseenProfiles($list,$timelimit)
{
	$mysqlObj = new mysql;
	$final = array();
	foreach($list as $kl=>$vl)
	{
		$pid = array();
		$removeProfiles = array();
                foreach ($vl as $kv=>$vv) 
		{
			$pid[getProfileDatabaseConnectionName($vv['PROFILEID'], 'slave')][$vv['PROFILEID']]=$vv;
		}
		foreach($pid as $myDbName=>$data)
		{
			$myDb=$mysqlObj->connect("$myDbName");
			$pa = array_keys($data);
			$pstr = implode("','",$pa);
			switch($kl)
			{
				case "EOI":
					$sql = "SELECT count(*) AS COUNT,RECEIVER AS PROFILEID FROM newjs.CONTACTS where RECEIVER IN ('".$pstr."') AND TIME BETWEEN '" . $timelimit[$kl]['TIME1'] . "' AND '" . $timelimit[$kl]['TIME2'] . "' AND TYPE='I' AND FILTERED!='Y' AND SEEN!='Y' GROUP BY RECEIVER";
					break;
				case "ACCEPT":
					$sql = "SELECT count(*) AS COUNT,SENDER AS PROFILEID FROM newjs.CONTACTS where SENDER IN ('".$pstr."') AND TIME BETWEEN '" . $timelimit[$kl]['TIME1'] . "' AND '" . $timelimit[$kl]['TIME2'] . "' AND TYPE='A'  AND SEEN!='Y' GROUP BY SENDER";
					break;
				case "PHOTO_REQUEST":
					$sql = "SELECT count(*) AS COUNT,PROFILEID_REQ_BY AS PROFILEID FROM newjs.PHOTO_REQUEST where PROFILEID_REQ_BY IN ('".$pstr."') AND DATE BETWEEN '" . $timelimit[$kl]['TIME1'] . "' AND '" . $timelimit[$kl]['TIME2'] . "'  AND SEEN!='Y' GROUP BY PROFILEID_REQ_BY";
					break;
			}
			$res = $mysqlObj->executeQuery($sql,$myDb);
			while($myrow=$mysqlObj->fetchArray($res))
			{
				if($myrow['COUNT']>0)
				{
					$final[]=$data[$myrow['PROFILEID']];
					$finalProfiles[]=$myrow['PROFILEID'];
				}
			}
			foreach($pa as $kx=>$vx)
			{
				if(!in_array($vx,$finalProfiles))
					$removeProfiles[]=$vx;
			}
			unset($pa);
			unset($finalProfiles);
		}
		unset($pid);
		$rstr = implode("','",$removeProfiles);
		unset($removeProfiles);
		if($rstr)
		{
                        $db=$mysqlObj->connect("master");
			$sql_del= "DELETE FROM newjs.TEMP_SMS_DETAIL WHERE `PROFILEID` IN ('".$rstr."') AND SMS_KEY = '".$kl."'";
			$res = mysql_query($sql_del,$db) or $SMSLib->errormail($sql_del,mysql_errno().":".mysql_error());
		}
	}
	return $final;
}
?>
