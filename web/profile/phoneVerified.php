<?php
        include("connect.inc");
        $db=connect_db();
	$data=authenticated();
	if(!$isMobile && !$data)
	{
		$smarty->display("login_layer.htm");
		die;
	}
	$profileid = $data['PROFILEID'];
	$phoneVerified = memcache_call($profileid."_PHONE_VERIFIED");
	if(!$phoneVerified)
	{
		include_once(JsConstants::$docRoot."/ivr/jsivrFunctions.php");
		$phoneVerified = hidePhoneLayer($profileid);
		memcache_call($profileid."_PHONE_VERIFIED",$phoneVerified);
	}
	echo $phoneVerified;
	die;
?>
