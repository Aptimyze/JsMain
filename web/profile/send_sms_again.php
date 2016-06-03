<?php
include ("connect.inc");
include ("mobile_verification_sms.php");
$db=connect_db();
$last30days=mktime(0,0,0,date("m"),date("d")-30,date("Y")); // To get the time for previous days
$last_30_days=date("Y-m-d",$last30days);
$last7days=mktime(0,0,0,date("m"),date("d")-7,date("Y"));
$last_7_days=date("Y-m-d",$last7days);
$sql="SELECT JPROFILE.PROFILEID,PHONE_MOB FROM JPROFILE,SENT_VERIFICATION_SMS WHERE JPROFILE.PROFILEID=SENT_VERIFICATION_SMS.PROFILEID AND JPROFILE.ACTIVATED='Y' AND (SENT_VERIFICATION_SMS.ENTRY_DT BETWEEN '$last_30_days 00:00:00' AND '$last_30_days 23:59:59'  OR SENT_VERIFICATION_SMS.ENTRY_DT BETWEEN '$last_7_days 00:00:00' AND '$last_7_days 23:59:59') AND JPROFILE.PROFILEID NOT IN(SELECT JPROFILE.PROFILEID FROM JPROFILE , MOBILE_VERIFICATION_SMS WHERE JPROFILE.PHONE_MOB=MOBILE_VERIFICATION_SMS.MOBILE AND JPROFILE.ACTIVATED='Y' AND MOBILE_VERIFICATION_SMS.STATUS='Y')";
$result=mysql_query_decide($sql);
while($row=mysql_fetch_array($result))
{
	$profileid=$row['PROFILEID'];
	$Mobile=$row['PHONE_MOB'];
	//Sending SMS
	if($Mobile)
        	SEND_MOBSMS($profileid,$Mobile);
}

?>
