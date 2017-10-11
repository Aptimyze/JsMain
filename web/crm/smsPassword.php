<?php
include("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/InstantSMS.php");

$data =authenticated($cid);
/* Query string: cid,checksum */       

if($data && $checksum)
{
	$checksumArr =explode("i",$checksum);	
	$profileid =$checksumArr[1];

         //include_once "../profile/InstantSMS.php";
         $sms = new InstantSMS("FORGOT_PASSWORD", $profileid);
         $sms->send();
	
	echo "<font size='2'>SMS for (Username/Password) successfully sent.</font>";

}
else
{
        $msg="Your session has been timed out<br> <br> ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

?>
