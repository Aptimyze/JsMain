<?php
$parentUrl=$_GET['parentUrl'];
//echo "parentUrl is ".$parentUrl;
//echo "profileId is >>>$profileId";

require_once("connect.inc");
//include_once("login_intermediate_pages.php");
	//	if(!is_invalid($profileId)){
	$authenticationLoginObj= AuthenticationFactory::getAuthenicationObj();
				$data=$authenticationLoginObj->authenticate(null,$gcm);

$request_uri=$_SERVER['REQUEST_URI'];
$pos = strpos($request_uri,"parentUrl=");
if($pos){
	$subStr=substr($request_uri,$pos+10);
	if($subStr == "")
		$subStr="/";
}
if(substr($subStr,0,2)=="//")
	$subStr=substr($subStr,1);
$smarty->assign("chat_hide","");
if($from_registration==1)
	$smarty->assign("Regd_REDIRECTURL","$subStr.#photohere");
else
	$smarty->assign("REDIRECTURL","$subStr");

if(!$data[PROFILEID] && strpos($subStr,"intermediate.php")===false)
{
	header("HTTP/1.1 301 Moved Permanently"); 
	header("Location: $subStr");
	die;
}

$protect_obj->setchatbarcookie();
$smarty->display("login_redirect.htm");
//}
?>
