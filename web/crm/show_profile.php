<?php
	// getParams:profileid,username,cid,show
	
	if(!$_SERVER['DOCUMENT_ROOT'])
	        $_SERVER['DOCUMENT_ROOT'] =JsConstants::$docRoot;
		
	$urlPath =$_SERVER['DOCUMENT_ROOT'];
        include_once($urlPath."/crm/connect.inc");
	include_once($urlPath."/crm/mainmenunew.php");
	include_once($urlPath."/crm/viewprofilenew.php");

        $privilage = getprivilage($cid);
        $priv = explode("+",$privilage);

	$checksum  	=md5($profileid)."i".$profileid;
	$statsView 	=profileview($profileid,$checksum,'',$cid);
	$profileView 	=viewprofile($username,"internal",$priv);
	
	$message =$statsView.$profileView;	
	echo $message;
?>
