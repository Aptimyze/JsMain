<?php

$flag_using_php5=1;
include_once("connect.inc");
include_once("ap_functions.php");

if(authenticated($cid))
{
	$profileid 	=$_GET['PROFILEID'];
	$name 		=getname($cid);
	if($profileid && $name){
		$sendReq = sendAdRequestToManager($profileid,$name,$SITE_URL);
		echo 'true';
		die;
	}
}
else
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
	die;
}

?>
