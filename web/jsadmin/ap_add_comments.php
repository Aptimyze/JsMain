<?php

$flag_using_php5=1;
include_once("connect.inc");
include_once("ap_functions.php");
include_once("ap_common.php");	

if(authenticated($cid))
{
	$role 		=fetchRole($cid);
	$profileid 	=$_POST['PROFILEID'];
	$matchid	=$_POST['MATCHID'];
	$name 		=getname($cid);
	if($profileid && $name){
		$comments =trim($comments);
		addProfileComments($profileid,$matchid,$name,$comments,$role);
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
