<?php 
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
$url=JsConstants::$siteUrl."/static/oldMobileSite?redirectFromOldSite=1";
		header("Location: " . $url);die;
$db=connect_db();
$data=authenticated();
if($data){
	header("Location:$SITE_URL/search/partnermatches");
die;
}
$smarty->assign("LOGIN_ICON",1);
assignHamburgerSmartyVariables($data[PROFILEID]);
if(strstr($_SERVER[HTTP_REFERER],"/register/")||strstr($_SERVER[HTTP_REFERER],"common/resetPassword"))
	$path="";
else
	$path=$_SERVER[HTTP_REFERER];

//Check if referrel is from jeevansathi
if(strpos($path,JsConstants::$siteUrl)===false)
	$path="";

$passwordReset = $_GET['passwordReset'];
$smarty->assign("passwordReset",$passwordReset);
$smarty->assign("PREV_URL",$path);

$smarty->display("mobilejs/jsmb_login.html");
?>
