<?php
ini_set("max_execution_time","0");
  $curFilePath = dirname(__FILE__)."/";
 include_once("/usr/local/scripts/DocRoot.php");
                                                                                                                             
/************************************************************************************************************************
*    FILENAME           : invalid_email_mailer.php
*    INCLUDED           : connect.inc
*    DESCRIPTION        : To send sms for user having invalid email.
*    CREATED BY         : lavesh
***********************************************************************************************************************/
chdir($_SERVER[DOCUMENT_ROOT]."/profile/");
include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/sms_inc.php");

$db=connect_db();

$today=date("Y-m-d",mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
$new_mod_dt=date("Y-m-d",mktime(0, 0, 0, date("m"),date("d")+14,date("Y")));

//$msg="Mails to your current email id are bouncing back. Please update your email id on jeevansathi.com. Hurry don’t miss the chance to receive an email of your dreams";

$message="Mails%20to%20your%20current%20email%20id%20are%20bouncing%20back.%20Please%20update%20your%20email%20id%20on%20jeevansathi.com.%20Hurry%20don%27t%20miss%20the%20chance%20to%20receive%20an%20email%20of%20your%20dreams";

//$table="newjs.INVALID_EMAIL_SMS_RESPONSE";

$sql="SELECT a.PROFILEID , PHONE_MOB , a.MOD_DT FROM INVALID_EMAIL_MAILER a , JPROFILE b  WHERE a.PROFILEID=b.PROFILEID AND a.MOD_DT IN ('$today','0000-00-00')";
$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
while($row=mysql_fetch_array($res))
{
	$mobile=$row["PHONE_MOB"];
	$profileid=$row["PROFILEID"];

	$valid_rec=send_sms($message,'',$mobile,$profileid,$table);
	if($valid_rec)
	{
		if($row["MOD_DT"]=='0000-00-00')
        	        $ppid.=$profileid.',';
	        else
                	$pid.=$profileid.',';
	}	
	else
		$ppid.=$profileid.',';
}

if($pid)
{
	$pid=rtrim($pid,',');
	$sql="UPDATE INVALID_EMAIL_MAILER set COUNT=COUNT+1,MOD_DT='$new_mod_dt' WHERE PROFILEID IN ($pid)";
	$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
}

if($ppid)
{
	$ppid=rtrim($ppid,',');
	$sql="DELETE FROM newjs.INVALID_EMAIL_MAILER WHERE PROFILEID IN ($ppid)";
	$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
}

?>
