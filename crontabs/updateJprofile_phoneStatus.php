<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
/***************************************************************************************************************
* FILE NAME     : dup_updatePhoneStatus.php  
* DESCRIPTION   : Cron script to update JPROFILE for MOB_STATUS and LANDL_STATUS, these field are added new for mobile/landlein status
*****************************************************************************************************************/

include "connect.inc";
$db =connect_db();
$db_slave=connect_slave();
mysql_query("set session wait_timeout=10000",$db);
mysql_query("set session wait_timeout=10000",$db_slave);
$StartTime =date("Y-m-d G:i:s",time());


//Porting of MOBILE_VERIFICATION_IVR to JPROFILE
	$sql1 = "SELECT DISTINCT `PROFILEID` from newjs.MOBILE_VERIFICATION_IVR WHERE ENTRY_DT>='2010-10-22'";
	$result=mysql_query($sql1,$db_slave) or logError($sql1); 
	while($row=mysql_fetch_array($result))
	{
		$profileid = $row['PROFILEID'];
		$sql2 = "UPDATE newjs.JPROFILE SET MOB_STATUS='Y' where `PROFILEID`='$profileid'";
		mysql_query($sql2,$db) or logError($sql2);
        JProfileUpdateLib::getInstance()->removeCache($profileid);
	}

//Porting of LANDL_VERIFICATION_IVR to JPROFILE
        $sql1 = "SELECT DISTINCT `PROFILEID` from newjs.LANDLINE_VERIFICATION_IVR WHERE ENTRY_DT>='2010-10-22'";
        $result=mysql_query($sql1,$db_slave) or logError($sql1);;
        while($row=mysql_fetch_array($result))
        {
                $profileid = $row['PROFILEID'];
                $sql2 = "UPDATE newjs.JPROFILE SET LANDL_STATUS='Y' where `PROFILEID`='$profileid'";
                mysql_query($sql2,$db) or logError($sql2);;
                JProfileUpdateLib::getInstance()->removeCache($profileid);
        }

//Porting of MOBILE_VERIFICATION_SMS to JPROFILE
	$sql1 = "SELECT DISTINCT `MOBILE` from newjs.MOBILE_VERIFICATION_SMS WHERE ENTRY_DT>='2010-10-22'";
        $result=mysql_query($sql1,$db_slave) or logError($sql1);
        while($row=mysql_fetch_array($result))
        {
                $mobile = $row['MOBILE'];
                $sql2 = "UPDATE newjs.JPROFILE SET MOB_STATUS='Y' where `PHONE_MOB` IN ('0$mobile','91$mobile','+91$mobile','$mobile')";
                mysql_query($sql2,$db) or logError($sql2);;
        }

//Porting of INVALID_PHONE to JPROFILE
        $sql1 = "SELECT  DISTINCT `PROFILEID` from incentive.INVALID_PHONE WHERE ENTRY_DT>='2010-10-22'";
        $result=mysql_query($sql1,$db_slave) or logError($sql1);
        while($row=mysql_fetch_array($result))
        {
                $profileid = $row['PROFILEID'];
                $sql2 = "UPDATE newjs.JPROFILE SET PHONE_FLAG='I' where `PROFILEID`='$profileid'";
                mysql_query($sql2,$db) or logError($sql2);;
                JProfileUpdateLib::getInstance()->removeCache($profileid);
        }


$EndTime =date("Y-m-d G:i:s",time());
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
$to ="manoj.rana@naukri.com";
$from   ="matchpoint@jeevansathi.com";
$subject="updateJprofile_phoneStatus.php ";
$body 	="StartTime:".$StartTime."<br>EndTime:".$EndTime;
send_email($to,$body,$subject,$from);


?>
