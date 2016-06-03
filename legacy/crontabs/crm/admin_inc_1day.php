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
//ini_set("memory_limit","64M");
include("../connect.inc");
connect_db();

$tdate="2005-10-31";
$follow_date="2005-11-07";

$sql="SELECT PROFILEID, CITY_RES,PHONE_RES,PHONE_MOB, incentive.BRANCH_CITY.NEAR_BRANCH from newjs.JPROFILE, incentive.BRANCH_CITY where ENTRY_DT between '$tdate 00:00:00' and '$tdate 23:59:59' and newjs.JPROFILE.CITY_RES=incentive.BRANCH_CITY.VALUE and incentive.BRANCH_CITY.NEAR_BRANCH <> '' order by GENDER,AGE"; 
$result=mysql_query($sql) or logError($sql);
$cnt= mysql_num_rows($result);
while($myrow=mysql_fetch_array($result))
{
	$proid[$myrow['NEAR_BRANCH']][]=$myrow['PROFILEID'];
	$phoneres[$myrow['NEAR_BRANCH']][]=$myrow['PHONE_RES'];
	$phonemob[$myrow['NEAR_BRANCH']][]=$myrow['PHONE_MOB'];
}
mysql_free_result($result);
$sql="SELECT USERNAME, incentive.BRANCHES.VALUE as NEAR_BRANCH from jsadmin.PSWRDS, incentive.BRANCHES where PRIVILAGE like '%IUO%' and UPPER(PSWRDS.CENTER)=UPPER(BRANCHES.NAME) AND jsadmin.PSWRDS.ACTIVE='Y'";
$result=mysql_query($sql) or logError($sql);
while($myrow1=mysql_fetch_array($result))
{
	$user[$myrow1["NEAR_BRANCH"]][]=$myrow1['USERNAME'];
}
mysql_free_result($result);
//count($proid["UP25"]);
$sql="SELECT VALUE from incentive.BRANCHES where 1";
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

		$sql="INSERT IGNORE into incentive.MAIN_ADMIN (PROFILEID, ALLOT_TIME, ALLOTED_TO, MODE,RES_NO,MOB_NO, STATUS, FOLLOWUP_TIME) values ('$proid_value',now(),'$user_value','O','".addslashes($phoneres_value)."','".addslashes($phonemob_value)."','N', '$follow_date')";
		mysql_query($sql) or logError($sql);
		$j=$j+1;
		if($j==$cnt_user)
			$j=0;
	}
}

mysql_free_result($result);
/**
*       Function        :       time_day
*       Input           :       time1(string), days(int) 
*       Output          :       time(string) 
*       Description     :       Calculates new time after taking into account number of holidays 
**/
/*
function time_day($dt,$d)
{
//changing input date to IST from EST 
//        $dt=strftime("%Y-%m-%d %H:%M",strtotime("$dt + 10 hours 30 minutes"));
                                                                                                 
        global $db;
	$flag=0;
        $newdate=strftime("%Y-%m-%d",strtotime("$dt + $d days"));
        $sql= "SELECT count(DATE) NUM from jsadmin.HOLIDAY where DATE='$newdate'";
        $result=mysql_query($sql) or logError($sql);
        $myrow=mysql_fetch_row($result);
        $holidays=$myrow[0];
        $return_date=strftime("%Y-%m-%d",strtotime("$newdate + $holidays days"));
                                                                                                 
        while($flag==0)
        {
                $sql1= "SELECT count(DATE) NUM from jsadmin.HOLIDAY where DATE='".strftime("%Y-%m-%d",strtotime("$return_date"))."'";
                $result1=mysql_query($sql1);
                $myrow1=mysql_fetch_row($result1);
                if($myrow1[0]>0)
                {
                        $return_date=strftime("%Y-%m-%d %H:%M",strtotime("$return_date + 1 day"));
                }
                else
                        $flag=1;
        }
        return $return_date;
}*/
?>
