<?php
ini_set('max_execution_time','0');
include "connect.inc";
connect_slave();
$profile_cnt = 0;

$sql_mob = "SELECT DISTINCT (MOB_SERIES) AS MOB_NO FROM test.MOBILE_SERIES WHERE MOB_SERIES <> ''";
$res_mob = mysql_query_decide($sql_mob) or die("$sql_mob".mysql_error_js());
while ($row_mob=mysql_fetch_array($res_mob))
{
	$mob_series[] = $row_mob['MOB_NO'];
}

$i = 0;

//$sql = "SELECT PROFILEID, PHONE_MOB, LAST_LOGIN_DT FROM JPROFILE WHERE GENDER = 'M' AND AGE >= '25' AND ACTIVATED = 'Y' AND PHONE_MOB <> '' AND PHONE_MOB <>0 AND COUNTRY_RES='51'";
$sql = "SELECT PROFILEID from SEARCH_MALE WHERE AGE >= '27' AND COUNTRY_RES='51'";
$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
while ($row = mysql_fetch_array($res))
{
	$sql="select PHONE_MOB,LAST_LOGIN_DT from JPROFILE where  activatedKey=1 and PROFILEID='$row[PROFILEID]' AND PHONE_MOB <> '' AND PHONE_MOB <>0";
	$resphone=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	
	if(mysql_num_rows($resphone)<=0)
		continue;
		
	$rowphone=mysql_fetch_array($resphone);
	
	if(trim($rowphone[PHONE_MOB]==""))
		continue;
		
	$sql1 = "SELECT COUNT(*) AS CNT FROM JPROFILE WHERE PHONE_MOB='$rowphone[PHONE_MOB]' AND PROFILEID <> '$row[PROFILEID]' AND ACTIVATED='Y'";

	$res1 = mysql_query_decide($sql1) or die("$sql".mysql_error_js());
	$row1 = mysql_fetch_array($res1);
	
	if ($row1['CNT'] > 0)
	{
		continue;
	}	
	$contact_cnt = 0;

	if (strlen($rowphone['PHONE_MOB']) == 10)
	{
		$checkphone = checkmphone($rowphone['PHONE_MOB']);

		if (!$checkphone)
		{
			$initialdigit = substr($rowphone['PHONE_MOB'],0,2);
			if ($initialdigit != '91')
				$mobile = "91".substr($rowphone['PHONE_MOB'],0,4);
			else
				$mobile = substr($rowphone['PHONE_MOB'],0,6);
			/*$sql_contact = "select count(*) as cnt from CONTACTS where RECEIVER='".$row["PROFILEID"]."' and TYPE='I' AND TIME > '$rowphone[LAST_LOGIN_DT]'";
			$res_contact = mysql_query_decide($sql_contact) or die("$sql_contact".mysql_error_js());
			$row1 = mysql_fetch_array($res_contact);
			$contact_cnt = $row1['cnt'];

			$sql_contact = "select count(*) as cnt from CONTACTS where SENDER='".$row["PROFILEID"]."' and TYPE='A' AND TIME > '$rowphone[LAST_LOGIN_DT]'";
			$res_contact = mysql_query_decide($sql_contact) or die("$sql_contact".mysql_error_js());
			$row2 = mysql_fetch_array($res_contact);
			$contact_cnt+= $row2['cnt'];

			if ($contact_cnt > 0 && in_array($mobile,$mob_series))*/
			if (in_array($mobile,$mob_series))
			{
				$mobile_no = "91".$rowphone['PHONE_MOB'];
				$sql_ins = "INSERT INTO SMS_PROFILEIDS VALUES('$row[PROFILEID]','$mobile_no')";
				mysql_query_decide($sql_ins) or die("$sql_ins".mysql_error_js());
				$i++;
			}
		}
	}
}
?>