<?php
ini_set("max_execution_time","0");
                                                                                                                             
/************************************************************************************************************************
*    FILENAME           : invalid_phone_mailer.php
*    INCLUDED           : connect.inc
*    DESCRIPTION        : To send email for user having invalid phone numbers.
*    CREATED BY         : lavesh
***********************************************************************************************************************/
include("connect.inc");

$db=connect_db();

//$today = date("Y-m-d");                                                        
$today=date("Y-m-d",mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
$new_mod_dt=date("Y-m-d",mktime(0, 0, 0, date("m"),date("d")+14,date("Y")));
$subject="Make yourself reachable, update contact details now";
$from="info@jeevansathi.com";

$sql="SELECT a.PROFILEID,EMAIL,USERNAME FROM JPROFILE a ,INVALID_PHONE_MAILER b WHERE b.MOD_DT='$today' AND b.COUNT<4 AND b.PROFILEID = a.PROFILEID ";
$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
while($row=mysql_fetch_array($res))
{
	$pid.=$row["PROFILEID"].',';
	$email=$row["EMAIL"];
	$uname=$row["USERNAME"];
	$smarty->assign("uname",$uname);
	$msg=$smarty->fetch("invalid_phone_mailer.htm");
	if($email)
		send_email($email,$msg,$subject,$from);
}
if($pid)
{
	$pid=rtrim($pid,',');
	$sql="UPDATE INVALID_PHONE_MAILER set COUNT=COUNT+1,MOD_DT='$new_mod_dt' WHERE PROFILEID IN($pid)";
	$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
}

?>
