<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/**
 * Re-Run deleteprofile & retrieve profile crons for the profile which are not execute successfully at runtime.
 * author  lavesh
**/

include("connect.inc");

$db=connect_db();
mysql_query("set session wait_timeout=10000");
$days7dayOld= date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-7, date("Y")));

$counter=0;
$sql="select PROFILEID from newjs.NEW_DELETED_PROFILE_LOG WHERE 211DB='0'";
$sql.=" AND DATE>'$days7dayOld'";
$result=mysql_query($sql) or mysql_error_mail(mysql_error());
while($myrow=mysql_fetch_array($result))
{
	$counter++;
	$sql1="select PROFILEID from newjs.JPROFILE where PROFILEID=" . $myrow['PROFILEID'] . " and ACTIVATED='D'";
	$result1=mysql_query($sql1) or mysql_error_mail(mysql_error());
	if(mysql_num_rows($result1)>0)
	{
		passthru(JsConstants::$php5path." $_SERVER[DOCUMENT_ROOT]/profile/deleteprofile_bg.php " . $myrow['PROFILEID'] . " > /dev/null");
	}
}
$msg=" Delete Counter=".$counter;

$counter=0;
$sql="select PROFILEID from RETRIEVE_PROFILE_LOG WHERE MAINDB='0'";
$sql.=" AND DATE>'$days7dayOld'";
$result=mysql_query($sql) or mysql_error_mail(mysql_error());
while($myrow=mysql_fetch_array($result))
{
	$counter++;
	$sql1="select PROFILEID from JPROFILE where PROFILEID=" . $myrow['PROFILEID'] . " and ACTIVATED='Y'";
	$result1=mysql_query($sql1) or mysql_error_mail(mysql_error());
	if(mysql_num_rows($result1)>0)
	{
		passthru(JsConstants::$php5path." $_SERVER[DOCUMENT_ROOT]/profile/retrieveprofile_bg.php " . $myrow['PROFILEID'] . " > /dev/null");
	}
}
//$msg.="\n Retrieve Counter=".$counter;
//mysql_error_mail($msg);

function mysql_error_mail($msg='')
{
	//echo $msg;die;
	mail("lavesh.rawat@jeevansathi.com","delete_profile_cron.php",$msg);
	die;
}
?>
