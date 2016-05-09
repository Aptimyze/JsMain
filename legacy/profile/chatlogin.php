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
		header("Location: http://198.65.112.205/profile/chatwindow.php?senderusername=$senderusername&sendersid=$sendersid&status=sender&receiversid=$receiversid&receiverusername=$receiverusername&checksum=$checksum");
	}
	else 
	{
		$smarty->assign("ERROR","Either the username or password is wrong. Please try again");
		$smarty->assign("sendersid",$sendersid);
		$smarty->assign("senderusername",$senderusername);
		$smarty->display("chat_win_1.htm");
	}
?>
