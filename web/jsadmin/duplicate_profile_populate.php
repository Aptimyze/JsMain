<?php

/**************************************************************************************
  *       Filename        :       duplicate_profile_populate.php
  *       Mantis          :       4781 (Duplicate profile handling)
  *       Description     :       Cron which will insert data on DUPLICATE_NUMBER_PROFILE after fetching it from JPROFILE which has duplicate
numbers.
  *       Created by      :       Anurag Gautam
***************************************************************************************/

include("connect.inc");

$db_slave = connect_slave();
$db_master = connect_db();

ini_set('max_execution_time','0');
ini_set("memory_limit","-1");

mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_slave);
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_master);

$yest_dt=time()-24*60*60*90;  		        // Time of 90 Days Back
$last_login_dt= date("Y-m-d",$yest_dt);         // Date of 90 Days Back

$sql="SELECT COUNT(*) AS C,PHONE_MOB FROM newjs.JPROFILE WHERE ACTIVATED ='Y' AND DATE(LAST_LOGIN_DT)>='$last_login_dt' GROUP BY PHONE_MOB HAVING C>1 ORDER BY PHONE_MOB DESC";
$res= mysql_query($sql,$db_slave) or die(mysql_error1($db_slave));
while($row=mysql_fetch_array($res))
{
	 $mobile=$row['PHONE_MOB'];
	 if($mobile)
	 {
 		$sql_1="SELECT PROFILEID,USERNAME,ENTRY_DT,LAST_LOGIN_DT,GENDER,CASTE,PHONE_MOB,PHONE_RES,RELATION,STD,SUBSCRIPTION FROM newjs.JPROFILE WHERE PHONE_MOB IN ('$mobile') AND ACTIVATED='Y'";
		$res_1= mysql_query($sql_1,$db_slave) or die(mysql_error1($db_slave));
		while($row_1=mysql_fetch_array($res_1))
		{
			$pid=$row_1['PROFILEID'];
			$entry_date=$row_1['ENTRY_DT'];
			$gender=$row_1['GENDER'];
			$caste=$row_1['CASTE'];
			$last_login_dt=$row_1['LAST_LOGIN_DT'];
			$username=$row_1['USERNAME'];
			$phone_res=$row_1['PHONE_RES'];
			$phone_mob=$row_1['PHONE_MOB'];
			$relation=$row_1['RELATION'];
			$subs=$row_1['SUBSCRIPTION'];
			$std=$row_1['STD'];

			$sql_5="SELECT SCORE FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID='$pid'";
			$res_5=mysql_query($sql_5,$db_slave) or die(mysql_error1($db_slave));
			while($row_5=mysql_fetch_array($res_5))
			{
				$score=$row_5['SCORE'];
			}

			$sql_3="REPLACE INTO jsadmin.DUPLICATE_NUMBER_PROFILE (PROFILEID,ENTRY_DT,GENDER,CASTE,SCORE,LAST_LOGIN_DT,USERNAME,PHONE_RES,PHONE_MOB,RELATION,SUBSCRIPTION,STD,DUPLICATE_TYPE) VALUES ('$pid','$entry_date','$gender','$caste','$score','$last_login_dt','$username','$phone_res','$phone_mob','$relation','$subs','$std','M')";
			mysql_query($sql_3,$db_master) or die(mysql_error1($db_master));

			unset($score);
		}
	}
}

$sql_2="SELECT COUNT(*) AS C,PHONE_RES FROM newjs.JPROFILE WHERE ACTIVATED ='Y' AND DATE(LAST_LOGIN_DT)>='$last_login_dt' GROUP BY PHONE_RES HAVING C>1 ORDER BY PHONE_RES DESC";
$res_2=mysql_query($sql_2,$db_slave) or die(mysql_error1($db_slave));
while($row_2=mysql_fetch_array($res_2))
{
	$phone_res_1=$row_2['PHONE_RES'];
	if($phone_res_1)
	{
 		$sql_7="SELECT PROFILEID,USERNAME,ENTRY_DT,LAST_LOGIN_DT,GENDER,CASTE,PHONE_MOB,PHONE_RES,RELATION,STD,SUBSCRIPTION FROM newjs.JPROFILE WHERE PHONE_RES IN ('$phone_res_1') AND ACTIVATED='Y'";
		$res_7= mysql_query($sql_7,$db_slave) or die(mysql_error1($db_slave));
		while($row_7=mysql_fetch_array($res_7))
		{
			$pid_1=$row_7['PROFILEID'];
			$entry_date_1=$row_7['ENTRY_DT'];
			$gender_1=$row_7['GENDER'];
			$caste_1=$row_7['CASTE'];
			$last_login_dt_1=$row_7['LAST_LOGIN_DT'];
			$username_1=$row_7['USERNAME'];
			$phone_mob_1=$row_7['PHONE_MOB'];
			$relation_1=$row_7['RELATION'];
			$subs_1=$row_7['SUBSCRIPTION'];
			$std_1=$row_7['STD'];
			
			$sql_6="SELECT SCORE FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID='$pid_1'";
			$res_6=mysql_query($sql_6,$db_slave) or die(mysql_error1($db_slave));
			while($row_6=mysql_fetch_array($res_6))
			{
				$score_1=$row_6['SCORE'];
			}

			$sql_4="REPLACE INTO jsadmin.DUPLICATE_NUMBER_PROFILE (PROFILEID,ENTRY_DT,GENDER,CASTE,SCORE,LAST_LOGIN_DT,USERNAME,PHONE_RES,PHONE_MOB,RELATION,SUBSCRIPTION,STD,DUPLICATE_TYPE) VALUES ('$pid_1','$entry_date_1','$gender_1','$caste_1','$score_1','$last_login_dt_1','$username_1','$phone_res_1','$phone_mob_1','$relation_1','$subs_1','$std_1','P')";
			mysql_query($sql_4,$db_master) or die(mysql_error1($db_master));

			unset($score_1);
		}
	}
}

mail("Anurag.Gautam@jeevansathi.com","Script duplicate_profile_populate.php ran successfully", date("Y-m-d"));

function mysql_error1($db)
{
	mail("Anurag.Gautam@jeevansathi.com","Error in duplicate_profile_populate.php",mysql_error($db));
}


?>
