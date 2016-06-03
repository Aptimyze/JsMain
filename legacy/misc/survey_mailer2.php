<?php
/********************************************************************************************
Filename    : survey_mailer2.php
Description : Fire the second mailer with link to survey page to user [2326]
Created On  : 5 October 2007
Created By  : Sadaf Alam
*********************************************************************************************/

include("../profile/connect.inc");
include("../crm/func_sky.php");

$db=connect_db();

$sql="INSERT INTO MIS.SURVEY(PROFILEID,QUES1,QUES2,QUES3,QUES4) VALUES('$profileid','','','','')";
mysql_query_decide($sql) or die("$sql".mysql_error_js());//logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
$sql="SELECT EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
$res=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
$row=mysql_fetch_assoc($res);
$smarty->assign("PROFILEID",$profileid);
$msg=$smarty->fetch("survey_mailer2.htm");
$subject="Jeevansathi survey";
$email=$row["EMAIL"];
send_mail($email,'','',$msg,$subject,"customerdesk@jeevansathi.com");
$smarty->display("survey2.htm");
?>
