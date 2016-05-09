<?php
  $curFilePath = dirname(__FILE__)."/";
 include_once("/usr/local/scripts/DocRoot.php");

include("$_SERVER[DOCUMENT_ROOT]/P/connect.inc");

$mysqlObj=new Mysql;

$dbM=connect_db();
$mysqlObj->executeQuery("set session wait_timeout=10000",$dbM);

$dbS=connect_slave81();
mysql_select_db("matchalerts",$dbS);
$mysqlObj->executeQuery("set session wait_timeout=10000",$dbS);

$myDb1=$mysqlObj->connect("11Master");

$noOfResults = 100;

$sql="SET @DONT_UPDATE_TRIGGER=1";
mysql_query($sql,$dbM) or die(mysql_error($dbM));

$tableExists = 0;

$sql="SHOW TABLES LIKE 'ALERTS_UPDATE'";
$res = mysql_query($sql,$dbS) or die(mysql_error($dbS));
if($row = mysql_fetch_assoc($res))
{
	$tableExists = 1;
}

if($tableExists == 0)
{
	$sql = "CREATE TABLE matchalerts.ALERTS_UPDATE SELECT PROFILEID,'N' AS 'FLAG' FROM newjs.JPROFILE WHERE LAST_LOGIN_DT > DATE_SUB( CURDATE( ) , INTERVAL 7 MONTH ) AND UDATE='0000-00-00' AND ACTIVATED<>'D'";
	mysql_query($sql,$dbS) or die(mysql_error($dbS));

	$sqlAlter = "ALTER TABLE matchalerts.ALERTS_UPDATE ADD PRIMARY KEY (PROFILEID) "; 
	mysql_query($sqlAlter,$dbS) or die(mysql_error($dbS));
}

while($recordsPresent!=1)
{
	$sql2 = "SELECT PROFILEID FROM matchalerts.ALERTS_UPDATE WHERE FLAG='N' LIMIT $noOfResults";
	$res2=mysql_query($sql2,$dbS) or die(mysql_error($dbS));
	unset($profileidsArr);
	while($row2 = mysql_fetch_assoc($res2))
	{
		$profileidsArr[] = $row2['PROFILEID'];
	}
	if(is_array($profileidsArr))
	{
		$profileids = implode(",",$profileidsArr);

		$sql3 = "UPDATE newjs.JPROFILE SET SERVICE_MESSAGES='S', GET_SMS='Y', PROMO_MAILS='S', PERSONAL_MATCHES='A' WHERE PROFILEID IN ($profileids)";
		mysql_query($sql3,$dbM) or die(mysql_error($dbM));

		$sql4 = "UPDATE newjs.JPROFILE_ALERTS SET MEMB_CALLS='S', OFFER_CALLS='S', SERV_CALLS_SITE='S', SERV_CALLS_PROF='S', MEMB_MAILS='S', CONTACT_ALERT_MAILS='S', KUNDLI_ALERT_MAILS='S', PHOTO_REQUEST_MAILS='S', SERVICE_SMS='S', SERVICE_MMS='S', SERVICE_USSD='S', PROMO_USSD='S', SERVICE_MAILS='S', PROMO_MMS='S' WHERE PROFILEID IN ($profileids)";
		mysql_query($sql4,$dbM) or die(mysql_error($dbM));

		$sql5 = "UPDATE visitoralert.VISITOR_ALERT_OPTION SET ALERT_OPTION='D' WHERE PROFILEID IN ($profileids)";
		mysql_query($sql5,$myDb1) or die(mysql_error($myDb1));

		$sql6 = "UPDATE matchalerts.ALERTS_UPDATE SET FLAG='Y' WHERE PROFILEID IN ($profileids) ";
		mysql_query($sql6,$dbS) or die(mysql_error($dbS));

		$start+=$noOfResults;
	}
	else
		$recordsPresent=1;
}
?>
