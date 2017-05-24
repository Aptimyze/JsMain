<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/***************************************************************************************************************************
FILE NAME	: cron_horoscopemis.php
DESCRIPTION	: This scripts finds the number of clicks and number of horoscope generated on a particular day and stores
		: them in a table.
CREATED BY	: Sriram Viswanathan.
****************************************************************************************************************************/
//include("../profile/connect.inc");
include($docRoot."/crontabs/connect.inc");

$db = connect_db();

//generate timestamp of one day before current date.
$timestamp = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
$yesterday = date("Y-m-d",$timestamp);

//select the count of clicks on various horoscope links.
$sql = "SELECT COUNT(DISTINCT PROFILEID) AS CNT, TYPE FROM MIS.ASTRO_CLICK_COUNT WHERE ENTRY_DT = '$yesterday' GROUP BY TYPE";
$res = mysql_query($sql) or logError($sql_ins);
while($row=mysql_fetch_array($res))
{
	if($row['TYPE']=='L')
	{
		$horoscope_clicks['LOGIN'] = $row['CNT'];;
	}
	elseif($row['TYPE']=='A')
	{
		$horoscope_clicks['CREATE'] = $row['CNT'];
	}
	elseif($row['TYPE']=='C')
	{
		$horoscope_clicks['UPDATE'] = $row['CNT'];
	}
	elseif($row['TYPE']=='E')
	{
		$horoscope_clicks['EDIT'] = $row['CNT'];
	}
}
unset($row);
//select the count of horoscope generated.
$sql = "SELECT COUNT(DISTINCT PROFILEID) AS CNT, TYPE FROM MIS.ASTRO_DATA_COUNT WHERE ENTRY_DT = '$yesterday' GROUP BY TYPE";
$res = mysql_query($sql) or logError($sql_ins);
while($row=mysql_fetch_array($res))
{
	if($row['TYPE']=='L')
	{
		$horoscope_generated['LOGIN'] = $row['CNT'];
	}
	elseif($row['TYPE']=='A')
	{
		$horoscope_generated['CREATE'] = $row['CNT'];
	}
	elseif($row['TYPE']=='C')
	{
		$horoscope_generated['UPDATE'] = $row['CNT'];
	}
	elseif($row['TYPE']=='E')
	{
		$horoscope_generated['EDIT'] = $row['CNT'];
	}
}
unset($row);
//select the count of user's who opt "yes" under Add horoscope option.
$st_time = $yesterday." 00:00:00";
$end_time = $yesterday." 23:59:59";
$sql = "SELECT COUNT(*) AS CNT FROM newjs.JPROFILE WHERE ENTRY_DT BETWEEN '$st_time' AND '$end_time' AND SHOW_HOROSCOPE='Y'";
$res = mysql_query($sql,$db) or die("$sql".mysql_error());
$row = mysql_fetch_array($res);
$horoscope_show = $row['CNT'];


//insert the cilck and genrated counts into a new table.
$sql_ins = "INSERT INTO MIS.ASTRO_DAILY_COUNT(ENTRY_DT,CLICKS_LOGIN,CLICKS_CREATE,CLICKS_UPDATE,CLICKS_EDIT,GENERATED_LOGIN,GENERATED_CREATE,GENERATED_UPDATE,GENERATED_EDIT,REG_ADD_HORO) VALUES('$yesterday','$horoscope_clicks[LOGIN]','$horoscope_clicks[CREATE]','$horoscope_clicks[UPDATE]','$horoscope_clicks[EDIT]','$horoscope_generated[LOGIN]','$horoscope_generated[CREATE]','$horoscope_generated[UPDATE]','$horoscope_generated[EDIT]','$horoscope_show')";

mysql_query($sql_ins) or logError($sql_ins);
?>
