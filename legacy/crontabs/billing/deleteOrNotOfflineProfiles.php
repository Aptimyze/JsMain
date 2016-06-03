<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

if($_SERVER['DOCUMENT_ROOT'])
{
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
	include_once($_SERVER['DOCUMENT_ROOT']."/jsadmin/connect.inc");
}
else
{
	$path =$_SERVER[DOCUMENT_ROOT];
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
	include_once($path."/jsadmin/connect.inc");
}
$exp3_date=date("Y-m-d",time()-(3*86400));
$sql_exp3 = "SELECT PROFILEID FROM jsadmin.OFFLINE_EXPIRED_PROFILES WHERE EXP_DATE='$exp3_date'";
$res_exp3 = mysql_query_decide($sql_exp3) or logError($sql_exp3);
while($row_exp3 = mysql_fetch_array($res_exp3))
	$profileid_arr_exp3[] = $row_exp3['PROFILEID'];

if(count($profileid_arr_exp3)>0)
{
	$profileid_str_exp3 = @implode("','",$profileid_arr_exp3);
	
	$sql_email = "SELECT PROFILEID,USERNAME,EMAIL FROM newjs.JPROFILE WHERE PROFILEID IN ('$profileid_str_exp3')";
	$res_email = mysql_query_decide($sql_email) or logError($sql_email);
	while($row_email = mysql_fetch_array($res_email))
	{
		if(!strstr($row_email['EMAIL'],'@jeevansathi.com'))
		{
			$profileid_arr_email[] = $row_email['PROFILEID'];
			$name_arr[$row_email['PROFILEID']] = $row_email['USERNAME'];
			$email_arr[$row_email['PROFILEID']] = $row_email['EMAIL'];
		}
	}
	if(count($profileid_arr_email)>0)
	{			
		for($i=0;$i<count($profileid_arr_exp3);$i++)
		{
			if(!in_array($profileid_arr_exp3[$i],$profileid_arr_email))
				$profileid_arr_not_email[]=$profileid_arr_exp3[$i];
		}
		$profileid_str = @implode("','",$profileid_arr_not_email);
		$profileid_str_email = @implode("','",$profileid_arr_email);
	}
	else
		$profileid_str = @implode("','",$profileid_arr_exp3);
	
	if($profileid_str)
	{
		$sql_jp_upd = "UPDATE newjs.JPROFILE SET PREACTIVATED=IF(ACTIVATED<>'D',ACTIVATED,PREACTIVATED), ACTIVATED='D' WHERE PROFILEID IN ('$profileid_str')";
		mysql_query_decide($sql_jp_upd) or logError($sql_jp_upd);
	}
	if($profileid_str_email)
	{
		$sql_jp_upd_p = "UPDATE newjs.JPROFILE SET PASSWORD='jeevansathi' WHERE PROFILEID IN ('$profileid_str_email')";
		mysql_query_decide($sql_jp_upd_p) or logError($sql_jp_upd_p);		
	
		for($i=0;$i<count($profileid_arr_email);$i++)
		{
			$pid=$profileid_arr_email[$i];
			$email=$email_arr[$pid];
			$name=$name_arr[$pid];
			$from="register@jeevansathi.com";
			$subject="Your offline profile is now converted to an online profile";
			$msg="Dear $name,\nAs a value addition to our customers, we have retained your profile in our database so that users satisfying your desired partner profile can find you when they search you online. We would suggest that you login to http://www.jeevansathi.com regularly to check your contacts and accept/decline them. You can login withyour email address or $name as user name and 'jeevansathi' as password. For security purposes, you are strongly advised to change your password.\n\nWishing you all the best in your partner search.\n\nRegards\nJS Team";
			send_email($email,nl2br($msg),$subject,$from);
		}
	}
}

$sql = "SELECT OFFLINE_EXPIRED_PROFILES.PROFILEID FROM OFFLINE_EXPIRED_PROFILES LEFT JOIN OFFLINE_BILLING ON OFFLINE_EXPIRED_PROFILES.PROFILEID = OFFLINE_BILLING.PROFILEID WHERE ACTIVE = 'Y'";
$res = mysql_query_decide($sql) or logError($sql);
while($row = mysql_fetch_array($res))
        $profileid[] = $row['PROFILEID'];
if(count($profileid)>0)
{
        $profileid_str = @implode("','",$profileid);
	$sql_del = "DELETE FROM OFFLINE_EXPIRED_PROFILES WHERE PROFILEID IN ('$profileid_str')";
        mysql_query_decide($sql_del) or logError($sql_del);
}
?>
