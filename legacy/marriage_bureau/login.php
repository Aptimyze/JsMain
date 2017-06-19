<?php
/**
*       Filename        :       login.php
*       Description     :
*       Created by      : 	Nikhil Tandon
**/
require_once("connectmb.inc");
$db=connect_dbmb();
if($Submit || $checksum)
{
	if($Submit)
		$data=loginmb($username,$password);
	else
		$data=authenticatedmb($checksum);
	if($data=="")
	{
		$error=1;
		$smarty->assign('error_username',$error);
		$smarty->assign('username',$username);
		$smarty->display('login.htm');
	}
	else
	{
		$justloggedin=1;
		include('index1.php');
	}
}
else
{
	$smarty->display('login.htm');
	//$smarty->display('test.htm');
}
?>
