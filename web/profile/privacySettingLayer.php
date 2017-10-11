<?php
        include_once("connect.inc");
	include_once("phoneFunctions.php");
        $db=connect_db();
	$data=authenticated();
	$profileid=$data["PROFILEID"];
	if(!$isMobile && !$data)
	{
		$smarty->display("login_layer.htm");
		die;
	}
	if($_POST['ajax']==1)
	{
		$checkedValue = $_POST['checkedValue'];
		hideNumbers($profileid,$checkedValue);
	}
	else
	{
		$smarty->display("privacySetting.htm");
		die;
	}
?>
