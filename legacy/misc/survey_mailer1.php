<?php
/************************************************************************************************************
Filename    : survey_mailer1.php
Description : Fire the mailer asking active users if they are interested in the survey. [2326]
Created On  : 5 October 2007
Created By  : Sadaf Alam
*************************************************************************************************************/
include("../profile/connect.inc");
include("../crm/func_sky.php");

$db=connect_db();

$subject="Jeevansathi wants to hear from you";
$sql="SELECT PROFILEID FROM newjs.SEARCH_MALE";
$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
while($row=mysql_fetch_assoc($res))
{
	$sqldata="SELECT EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$row[PROFILEID]'";
	$resdata=mysql_query_decide($sqldata) or die("$sqldata".mysql_error_js());
	$rowdata=mysql_fetch_assoc($resdata);
	$smarty->assign("PROFILEID",$row["PROFILEID"]);
	$msg=$smarty->fetch("survey_mailer1.htm");
	$email=$rowdata["EMAIL"];
	send_mail($email,'','',$msg,$subject,"customerdesk@jeevansathi.com");
}
$sql="SELECT PROFILEID FROM newjs.SEARCH_FEMALE";
$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
while($row=mysql_fetch_assoc($res))
{
        $sqldata="SELECT EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$row[PROFILEID]'";
        $resdata=mysql_query_decide($sqldata) or die("$sqldata".mysql_error_js());
        $rowdata=mysql_fetch_assoc($resdata);
        $smarty->assign("PROFILEID",$row["PROFILEID"]);
        $msg=$smarty->fetch("survey_mailer1.htm");
        $email=$rowdata["EMAIL"];
        send_mail($email,'','',$msg,$subject,"customerdesk@jeevansathi.com");
}
?>
