<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/**********************************************************************************\
                FILE NAME       :       admin_inc.php
                FILES INCLUDED  :       connect.inc
		FUNCTION DEFINED:	time_day(date,int)
                DETAILS         :       Allot the profiles to the CRM department for tele sales
					on monthly basis	 
\**********************************************************************************/

ini_set("memory_limit","128M");
ini_set("max_execution_time","0");
include("../connect.inc");

$db2=connect_737();

$sql="SELECT VALUE,NEAR_BRANCH FROM incentive.BRANCH_CITY WHERE NEAR_BRANCH NOT IN ('','UP25')";
$res_brn=mysql_query($sql,$db2) or die("$sql".mysql_error($db2));
while($row_brn=mysql_fetch_array($res_brn))
{
	$val=$row_brn['VALUE'];
	$near=$row_brn['NEAR_BRANCH'];
	$brancharr[]=$val;
	$brnarr[$val]=$near;
}
unset($val);
unset($near);
mysql_free_result($res_brn);

$brnstr=implode("','",$brancharr);
//unset($brancharr);

//$sql="SELECT PROFILEID, CITY_RES,PHONE_RES,PHONE_MOB from newjs.JPROFILE where ENTRY_DT < '2006-01-24 00:00:00' AND COUNTRY_RES=51 AND CITY_RES IN ('$brnstr') order by ENTRY_DT,GENDER,AGE"; 
$sql="SELECT PROFILEID, CITY_RES,PHONE_RES,PHONE_MOB from newjs.JPROFILE where ENTRY_DT < '2006-03-24 00:00:00' order by ENTRY_DT,GENDER,AGE"; 

$result=mysql_query($sql,$db2) or die("$sql".mysql_error($db2));//logError($sql);
while($myrow=mysql_fetch_array($result))
{
	$city_res=$myrow['CITY_RES'];
	if(in_array($city_res,$brancharr))
	{
		$near=$brnarr[$city_res];

		$proid[$near][]=$myrow['PROFILEID'];
		$phoneres[$near][]=$myrow['PHONE_RES'];
		$phonemob[$near][]=$myrow['PHONE_MOB'];
	}
}
unset($brancharr);
mysql_free_result($result);
mysql_close($db2);

$db=connect_db();
$sql="SELECT USERNAME, incentive.BRANCHES.VALUE as NEAR_BRANCH from jsadmin.PSWRDS, incentive.BRANCHES where PRIVILAGE like '%IUO%' and UPPER(PSWRDS.CENTER)=UPPER(BRANCHES.NAME) AND jsadmin.PSWRDS.ACTIVE='Y'";
$result=mysql_query($sql,$db) or die("$sql".mysql_error($db));//logError($sql,$db);
while($myrow1=mysql_fetch_array($result))
{
	$user[$myrow1["NEAR_BRANCH"]][]=$myrow1['USERNAME'];
}

mysql_free_result($result);

$sql="SELECT VALUE from incentive.BRANCHES where 1";
$result=mysql_query($sql,$db) or die("$sql".mysql_error($db));//logError($sql,$db);
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

		$sql="INSERT DELAYED IGNORE into incentive.MAIN_ADMIN (PROFILEID, ALLOT_TIME, ALLOTED_TO, MODE,RES_NO,MOB_NO, STATUS, FOLLOWUP_TIME) values ('$proid_value',now(),'$user_value','O','".addslashes($phoneres_value)."','".addslashes($phonemob_value)."','', '0')";
		mysql_query($sql,$db) or logError($sql,$db);
		$j=$j+1;
		if($j==$cnt_user)
		{
			$j=0;
		}
	}
}
?>
