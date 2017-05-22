<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/***************************************************************************************************************************
Filename    : spam_profiles.php
Description : To calculate weekly contact information of profiles exceeding 50 contacts per day/500 contacts per month [2565]
Created By  : Sadaf Alam
Created On  : 7 Jan 2007
****************************************************************************************************************************/
include("$_SERVER[DOCUMENT_ROOT]/profile/connect.inc");

$fileName =  $_SERVER["SCRIPT_FILENAME"];
$http_msg=print_r($_SERVER,true);
mail("reshu.rajput@gmail.com,lavesh.rawat@gmail.com","For DLL Movement - $fileName",$http_msg);

exit;
$db=connect_db();
exit;
$stamp=mktime(0, 0, 0, date("m"), date("d")-6, date("Y"));
$date7=date("Y-m-d",$stamp);
$stamp=mktime(0, 0, 0, date("m"), date("d")-5, date("Y"));
$date6=date("Y-m-d",$stamp);
$stamp=mktime(0, 0, 0, date("m"), date("d")-4, date("Y"));
$date5=date("Y-m-d",$stamp);
$stamp=mktime(0, 0, 0, date("m"), date("d")-3, date("Y"));
$date4=date("Y-m-d",$stamp);
$stamp=mktime(0, 0, 0, date("m"), date("d")-2, date("Y"));
$date3=date("Y-m-d",$stamp);
$stamp=mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
$date2=date("Y-m-d",$stamp);
$date1=date("Y-m-d");

//$sql="TRUNCATE TABLE MIS.SPAM_PROFILES";
//mysql_query_decide($sql) or die("$sql".mysql_error_js());

$sql="SELECT PROFILEID,USERNAME FROM MIS.CONTACTS_FAULT_MONITOR WHERE TYPE IN ('T','M') AND SPAM_CALC='N'";
$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
while($row=mysql_fetch_assoc($res))
{
        unset($daily_weekly);
        $weekly_contacts=0;
        $score=0;
        $contact_login=0;
	$inbound=0;
	$inaccept_init=0;
	$outaccept_init=0;
	$contacts=0;
	$outaccept=0;
	$inaccept=0;
	$login=0;
	$sqlcon="SELECT COUNT(*) AS COUNT FROM newjs.LOGIN_HISTORY WHERE PROFILEID='$row[PROFILEID]'";
        $rescon=mysql_query_decide($sqlcon) or die("$sqlcon".mysql_error_js());
        $rowcon=mysql_fetch_assoc($rescon);
	$login=$rowcon["COUNT"];
	//Sharding of CONTACTS done by Sadaf
	$stamp2=strtotime($date7);
	$sendersIn=$row["PROFILEID"];
	$contactResult=getResultSet("TIME,TYPE",$sendersIn,'','','','','','','','','','','',"Y");
	if(is_array($contactResult))
	{
		foreach($contactResult as $key=>$value)
		{
			$date_contact=explode(" ",$contactResult[$key]["TIME"]);
			$stamp1=strtotime($date_contact[0]);
			if($stamp2<=$stamp1 && $contactResult[$key]["TYPE"]=="I")
			{
				$weekly_contacts++;
				if($date_contact[0]==$date1)
				$daily_weekly[$date1]++;
				elseif($date_contact[0]==$date2)
				$daily_weekly[$date2]++;
				elseif($date_contact[0]==$date3)
				$daily_weekly[$date3]++;
				elseif($date_contact[0]==$date4)
				$daily_weekly[$date4]++;
				elseif($date_contact[0]==$date5)
				$daily_weekly[$date5]++;
				elseif($date_contact[0]==$date6)
				$daily_weekly[$date6]++;
				elseif($date_contact[0]==$date7)
				$daily_weekly[$date7]++;
			}
			if($contactResult[$key]["TYPE"]=="I")
			$contacts++;
			if($contactResult[$key]["TYPE"]=="A")
			$outaccept++;
		}
		unset($contactResult);
	}
	$receiversIn=$row["PROFILEID"];
	$contactResult=getResultSet("TYPE",'','',$receiversIn,'','','','','','','','','',"Y");
	if(is_array($contactResult))
	{
		foreach($contactResult as $key=>$value)
		{
			if($contactResult[$key]["TYPE"]=="I")
			$inbound++;
			if($contactResult[$key]["TYPE"]=="A")
			$inaccept++;
		}
		unset($contactResult);
	}
	if(is_array($daily_weekly))
	{
       		foreach($daily_weekly as $key=>$value)
        	{
                	if($value>=20)
	                $score++;
        	}
	}
	if($login!=0)
	$contact_login=round($contacts/$login,4);
	if($contacts!=0)
	{
		$inaccept_init=round($inaccept/$contacts,4);
		$outaccept_init=round($outaccept/$contacts,4);
	}
	$sqlcon="REPLACE INTO MIS.SPAM_PROFILES(PROFILEID,WEEKLY_CONTACTS,WEEKLY_CONTACTS_SCORE,CONTACTS_LOGIN_RATIO,ACCEPTANCE_RATIO_OUTBOUND,INBOUND_CONTACTS,ACCEPTANCE_RATIO_INBOUND) VALUES('$row[PROFILEID]','$weekly_contacts','$score','$contact_login','$outaccept_init','$inbound','$inaccept_init')";
	mysql_query_decide($sqlcon) or die("$sqlcon".mysql_error_js());

	$sqlcon="UPDATE MIS.CONTACTS_FAULT_MONITOR SET SPAM_CALC='Y' WHERE PROFILEID='$row[PROFILEID]'";
	mysql_query_decide($sqlcon) or die("$sqlcon".mysql_error_js());
}
?>
