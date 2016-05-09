<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/**********************************************************************************\
                FILE NAME       :       admin_inc.php
                FILES INCLUDED  :       connect.inc
		FUNCTION DEFINED:	time_day(date,int)
                DETAILS         :       Allot the profiles to the CRM department for tele sales
					on daily basis	 
\**********************************************************************************/

ini_set("memory_limit","32M");
include("../connect.inc");
connect_db();

$sql="SELECT DATE_ADD(newjs.JPROFILE.ENTRY_DT,INTERVAL 7 DAY) as follow_dt,PROFILEID, CITY_RES,PHONE_RES,PHONE_MOB, incentive.BRANCH_CITY.NEAR_BRANCH from newjs.JPROFILE, incentive.BRANCH_CITY where ENTRY_DT between '2005-12-29 00:00:00' and '2006-01-03 23:59:59' and newjs.JPROFILE.CITY_RES=incentive.BRANCH_CITY.VALUE and incentive.BRANCH_CITY.NEAR_BRANCH='UP19'  order by ENTRY_DT,GENDER,AGE"; 
$result=mysql_query($sql) or logError($sql);
$cnt= mysql_num_rows($result);
while($myrow=mysql_fetch_array($result))
{
	$proid[$myrow['NEAR_BRANCH']][]=$myrow['PROFILEID'];
	$phoneres[$myrow['NEAR_BRANCH']][]=$myrow['PHONE_RES'];
	$phonemob[$myrow['NEAR_BRANCH']][]=$myrow['PHONE_MOB'];
	$followdt[$myrow['NEAR_BRANCH']][]=substr($myrow['follow_dt'],0,10);
}

mysql_free_result($result);

$sql="SELECT USERNAME, incentive.BRANCHES.VALUE as NEAR_BRANCH from jsadmin.PSWRDS, incentive.BRANCHES where PRIVILAGE like '%IUO%' and UPPER(PSWRDS.CENTER)=UPPER(BRANCHES.NAME) and incentive.BRANCHES.VALUE='UP19' AND ACTIVE='Y'";
$result=mysql_query($sql) or logError($sql);
while($myrow1=mysql_fetch_array($result))
{
	$user[$myrow1["NEAR_BRANCH"]][]=$myrow1['USERNAME'];
}

mysql_free_result($result);

$sql="SELECT VALUE from incentive.BRANCHES where VALUE='UP19'";
$result=mysql_query($sql) or logError($sql);
while($myrow2=mysql_fetch_array($result))
{
	$cnt_proid=count($proid[$myrow2['VALUE']]);	
	$cnt_user=count($user[$myrow2['VALUE']]);
	$j=0;
	for($i=0;$i<$cnt_proid;$i++)
	{
		$proid_value=$proid[$myrow2['VALUE']][$i];
		$phoneres_value=$phoneres[$myrow2['VALUE']][$i];
		$phonemob_value=$phonemob[$myrow2['VALUE']][$i];
		$user_value=$user[$myrow2['VALUE']][$j];
	 	$follow_date=$followdt[$myrow2['VALUE']][$i];
		if($follow_date=='2006-01-08')
		{
			$follow_date='2006-01-09';
		}

		$sql="INSERT IGNORE into incentive.MAIN_ADMIN (PROFILEID, ALLOT_TIME, ALLOTED_TO, MODE,RES_NO,MOB_NO, STATUS, FOLLOWUP_TIME) values ('$proid_value',now(),'$user_value','O','".addslashes($phoneres_value)."','".addslashes($phonemob_value)."','N', '$follow_date')";
		mysql_query($sql) or logError($sql);
		$j=$j+1;
		if($j==$cnt_user)
			$j=0;
	}
}
?>
