<?php
	include("connect.inc");
	
	// add slashes to all variables
	maStripVARS("addslashes");
	
	// connect to database
	$db=connect_db();
	
	// check for correct username password
	$data=login($username,$password);
	if(isset($data))
	{
		//header("Location: http://www.jeevansathi.com/userplane/ic.php?strDestinationUserID=$strDestinationUserID&popMessenger=1");
		$receiversid=$data["PROFILEID"];
		$receiverusername=$data["USERNAME"];
		$checksum=$data["CHECKSUM"];
		header("Location: $SITE_URL/profile/yahoo_chatwindow.php?sendersid=$sendersid&checksum=$checksum");
	}
	else 
	{
		$smarty->assign("ERROR","Either the username or password is wrong. Please try again");
		$smarty->assign("sendersid",$sendersid);
		$smarty->display("gtalk_chat_win_1.htm");
	}
?>
