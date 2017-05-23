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

ini_set("memory_limit","64M");
include("../connect.inc");
connect_db();

$db2=connect_737();

$sql="SELECT VALUE,NEAR_BRANCH FROM incentive.BRANCH_CITY WHERE NEAR_BRANCH='UP19'";
$res_brn=mysql_query($sql,$db2) or die("$sql".mysql_error($db2));
while($row_brn=mysql_fetch_array($res_brn))
{
        $val=$row_brn['VALUE'];
        $near=$row_brn['NEAR_BRANCH'];

	$valarr[]=$val;
        $brnarr[$val]=$near;
}
unset($val);
unset($near);
mysql_free_result($res_brn);

if($valarr)
	$brnstr=implode("','",$valarr);
unset($valarr);

$sql="SELECT PROFILEID, CITY_RES,PHONE_RES,PHONE_MOB from newjs.JPROFILE where ENTRY_DT < '2005-12-29 00:00:00' AND CITY_RES IN ('$brnstr') order by ENTRY_DT,GENDER,AGE";

$result=mysql_query($sql,$db2) or die("$sql".mysql_error($db2));//logError($sql);
while($myrow=mysql_fetch_array($result))
{
        $city_res=$myrow['CITY_RES'];
        $near=$brnarr[$city_res];

        $proid[$near][]=$myrow['PROFILEID'];
        $phoneres[$near][]=$myrow['PHONE_RES'];
        $phonemob[$near][]=$myrow['PHONE_MOB'];
}
mysql_free_result($result);
mysql_close($db2);

$db=connect_db();

/*

$sql="SELECT PROFILEID, CITY_RES,PHONE_RES,PHONE_MOB, incentive.BRANCH_CITY.NEAR_BRANCH from newjs.JPROFILE, incentive.BRANCH_CITY where newjs.JPROFILE.CITY_RES=incentive.BRANCH_CITY.VALUE and incentive.BRANCH_CITY.NEAR_BRANCH='UP19' and ENTRY_DT < '2005-12-28 00:00:00' order by ENTRY_DT,GENDER,AGE"; 

$result=mysql_query($sql) or logError($sql);
$cnt= mysql_num_rows($result);
while($myrow=mysql_fetch_array($result))
{
	$proid[$myrow['NEAR_BRANCH']][]=$myrow['PROFILEID'];
	$phoneres[$myrow['NEAR_BRANCH']][]=$myrow['PHONE_RES'];
	$phonemob[$myrow['NEAR_BRANCH']][]=$myrow['PHONE_MOB'];
}

mysql_free_result($result);
*/


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

		$sql="INSERT IGNORE into incentive.MAIN_ADMIN (PROFILEID, ALLOT_TIME, ALLOTED_TO, MODE,RES_NO,MOB_NO, STATUS, FOLLOWUP_TIME) values ('$proid_value',now(),'$user_value','O','".addslashes($phoneres_value)."','".addslashes($phonemob_value)."','', '0')";
		mysql_query($sql) or logError($sql);
		$j=$j+1;
		if($j==$cnt_user)
		{
			$j=0;
		}
	}
}
?>
