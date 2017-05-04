<?php
include_once(JsConstants::$docRoot."/commonFiles/FetchIP.php");
global $cid,$name,$checksum;

if($from_dialer_inbound=='Y' || $from_dialer=='Y' || $from_dialer_phone=='Y' || $dialer_check==1){
       if(isset($_COOKIE["CRM_LOGIN"]))
	    {
	     	//if($dialer_check!=1)
	     		$cid= $_COOKIE["CRM_LOGIN"];
	     	if(!$checksum)
	     		$checksum = $cid;
	    }
	    else
	    {
	    	if(!$checksum)
	     		$checksum = $cid;
	    }
}	
else
{
	$cid = preg_replace('/[^A-Za-z0-9\. -_]/', '',$_COOKIE["CRM_NOTIFICATION_AGENTID"]);
	if(!$checksum)
	    $checksum = $cid;
}
if(!$_GET['name'] && !$_POST['name'])
	$name = preg_replace('/[^A-Za-z0-9\. -_]/', '', $_COOKIE["CRM_NOTIFICATION_AGENT"]);
$ip = FetchClientIP();

function unsetLoginCookies()
{
	$dom="";
	setcookie("CRM_NOTIFICATION_AGENTID",'',0,"/",$dom);
	setcookie("CRM_NOTIFICATION_AGENT",'',0,"/",$dom);
} 

function setLoginCookies($cid,$name)
{
	$dom = "";
	$timeout = 14400;
	setcookie("CRM_NOTIFICATION_AGENTID",$cid,time() + $timeout,"/",$dom);
	setcookie("CRM_NOTIFICATION_AGENT",$name,time() + $timeout,"/",$dom);
	if($from_dialer_inbound=='Y' || $from_dialer=='Y' || $from_dialer_phone=='Y' || $dialer_check==1)
		setcookie("CRM_LOGIN",$cid,time() + $timeout,"/",$dom);
} 
?>
