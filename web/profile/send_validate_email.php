<?php
include("connect.inc");

$db=connect_db();

list($val,$profileid)=explode("i",$checksum);

if($val==md5($profileid))
{
	$sql="SELECT USERNAME,EMAIL FROM newjs.JPROFILE_AFFILIATE WHERE ID='$profileid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$myrow=mysql_fetch_array($result);
	$username=$myrow['USERNAME'];
	$email=$myrow["EMAIL"];

	$from="info@jeevansathi.com";
	$subject="JeevanSathi.com - Account Activation";
	$message="Dear $username<br>Thank you for registering on JeevanSathi.com and becoming an esteemed member of this family.<br><br>Now you are just one step away from your dream life partner. Just click on the following link to activate your matrimonial profile on our website. <br><br>This will make your profile visible to the members according to the privacy preferences you have chosen. Hence we request you to activate your matrimonial profile. <br><br>";
	$message.="<a href=\"http://www.jeevansathi.com/profile/validate_function.php?checksum=$checksum\">Activate</a><br>";
	$message.="(IMPORTANT: Your profile will not be displayed on our website if you ignore the activation process.)<br>Hope to see you on JeevanSathi.com soon!<br><br>With warm Regards<br>The JeevanSathi.com Team<br>www.jeevansathi.com";

	send_email($email,$message,$subject,$from);

	$smarty->assign("USER_EMAIL",$email);
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
	$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));

	$smarty->display("activated.htm");
}
else
{
	$smarty->assign("msg_error","Due to a temporary problem your request could not be processed. Please try after a couple of minutes");
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));

	$smarty->display("error_template.htm");
}
?>
