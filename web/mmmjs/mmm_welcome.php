<?php
include"connect.inc";

//// THIS ROUTINE WILL CHECK YOUR AUTHENTICATION AND IF YOUR "cid" HAS EXPIRED THEN IT WILL REDIRECT TO LOGIN PAGE/////////////////////// 
$ip = getenv('REMOTE_ADDR');
if(authenticated($cid,$ip))
{
	$auth=1;
	$un = getuser($cid,$ip);
	$tm=getIST();
	//setcookie ("cid", $cid,$tm+3600);
}
if(!$auth)
{
	$smarty->display("mmm_relogin.htm");
	die;
}	
/////////////AUTHENTICATION ROUTINE ENDS HERE///////////////	

$smarty->assign("cid",$cid);
$smarty->display("mmm_welcome.htm");
?>	
