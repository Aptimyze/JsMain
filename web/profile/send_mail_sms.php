<?php
include("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
$db=connect_db();
$smarty->relative_dir="jeevansathi/";
$profileid=$argv[1];
if(is_numeric($profileid))
{
	$sql="select USERNAME,EMAIL,PHONE_MOB,PASSWORD from newjs.JPROFILE where PROFILEID='$profileid'";
	$res=mysql_query_decide($sql);
	if($row=mysql_fetch_array($res))
	{
		$to=$row['EMAIL'];
		//$to='nikcomestotalk@gmail.com';
		$from="info@jeevansathi.com";
		$subject="Welcome back to Jeevansathi.com!";
		$message="";	//Missing
		$smarty->assign ("username",$row['USERNAME']);
		$smarty->assign ("password",$row['PASSWORD']);
		$smarty->assign ("HEAD_MAILER",$smarty->fetch("head_mailer.htm"));
		$smarty->assign ("SUBFOOTER_MAILER",$smarty->fetch("subfooter_mailer.htm"));
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$output = $smarty->fetch("archived_recovery.htm");
		
		send_email($to,$output,$subject,$from);
		$mobile =mobileformat($row['PHONE_MOB']);
		
		//$mobile="9911121780";
		$message="Your account $row[USERNAME] has been activated on Jeevansathi.com . You may now login to Jeevansathi.com and access over 6 lakh profiles. Login now or call 1800-419-6299.";
		$from="9870803838";
        	$message=rawurlencode($message);
			
		//send_sms($message,$from,$mobile,$profileid);
	}
}
?>
