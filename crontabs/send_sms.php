<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

chdir("$_SERVER[DOCUMENT_ROOT]/profile");
include("mobile_verification_sms.php");
//opening slave
$db_slave=connect_slave();
$last4month=mktime(0,0,0,date("m")-4,date("d"),date("Y"));    // To get the time for previous day
$last_4_month=date("Y-m-d",$last4month);
//QUERY FOR Retrieving the Activated Profile from Last 4 Month
$sql="SELECT PROFILEID,PHONE_MOB FROM JPROFILE WHERE ACTIVATED='Y' AND LAST_LOGIN_DT  BETWEEN '$last_4_month 00:00:00' AND NOW() AND PHONE_MOB NOT IN (SELECT MOBILE FROM MOBILE_VERIFICATION_SMS)";
$result=mysql_query($sql) or  die("$sql".mysql_error());
//closing slave
mysql_close($db_slave);
//opening master
connect_db();
while($row=mysql_fetch_array($result))
{
	$profileid=$row['PROFILEID'];
	$Mobile=$row['PHONE_MOB'];
	//Sending SMS
	if($Mobile)
		SEND_MOBSMS($profileid,$Mobile);
}
 

?>
