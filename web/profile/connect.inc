<?php
$jsb9_track_stime=microtime(true);
if($fromCrontab) chdir(dirname(__FILE__));
// maximum zlib compression level is 9
ini_set('zlib.output_compression_level', 5);

$php5_path=JsConstants::$php5path;

$TOUT1 = 1800;
$TOUT2 = 7200;
//if($nikhil_marriage_bureau!=1)
	include_once("config.php");

$lang=$_COOKIE['JS_LANG'];
if($lang)
{
	header('Content-Type: text/html; charset=UTF-8');
	$smarty->assign("JS_LANG",$lang);
}

include_once($_SERVER['DOCUMENT_ROOT']."/profile/bot_attack_prevention.php");
include_once("tables.php");
include_once($_SERVER['DOCUMENT_ROOT']."/jsmb/mb_comfunc.php");
//include_once("profile/hits.php");
$banners=array("CHAT","SMS");
$bannerid=rand(0,1);
$bannerid=$banners[$bannerid];

$smarty->assign("random_image",rand(1,9999999));
$smarty->assign("BANNERID",$bannerid);
$smarty->assign("LEFTBANNER",basename($_SERVER['PHP_SELF']));

if($_COOKIE['SULEKHACO']=="yes")
	$smarty->assign("SULEKHACO",1);

$mob_cookie_arr=explode(",",preg_replace('/[^A-Za-z0-9\. -]/', '', $_COOKIE['JS_MOBILE']));
$MOB_COOKIE=$mob_cookie_arr[0];
if($MOB_COOKIE=='Y')
	$smarty->assign("MOB_YES",1);
if($_COOKIE['NEWJS_DESKTOP']!='Y' && $MOB_COOKIE=='Y')
	$isMobile=1;

include_once(JsConstants::$docRoot."/classes/LoginTracking.class.php");
include_once("google_key.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once("connect_db.php");
include_once("js_encryption_functions.php");
include_once(JsConstants::$docRoot."/commonFiles/connect_dd.inc");
include_once("connect_reg.inc");
include_once("connect_auth.inc");
include_once("connect_functions.inc");
include_once("thumb_identification_array.inc");
include_once("common_functions.inc");
include_once("commonfile.php");
include_once("tracking.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/incomeCommonFunctions.inc");
//include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
//include_once($_SERVER['DOCUMENT_ROOT']."/classes/class.rc4crypt.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/contacts_functions.php");
include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
//File that will clean all the xss and sql injection from the variable.
//include_once($_SERVER['DOCUMENT_ROOT']."/profile/all_elements/clean_xss_data.php");
if($SITE_URL=="http://www.jeevansathi.com" || $SITE_URL=='')
        $domain='';
else
        $domain='';

//unsetting legacy users login session, those  having old cookie 


if($isMobile)
{
    $mobile_logout_link_url = "<a href=\"$SITE_URL/P/logout.php?mobile_logout=1\" style=\"font: 12px arial; float: right; font-weight: bold; color: #0046C5; margin-right: 10px;\">Logout</a>";
    $smarty->assign("MOBILE_LOGOUT_LINK", $mobile_logout_link_url);
	$smarty->assign("googleAnalyticsImageUrl",googleAnalyticsGetImageUrl());
		include_once("wap_filelist.php");
		$scrNameArr= explode("/",$_SERVER['SCRIPT_NAME']);
		$scrName=$scrNameArr[count($scrNameArr)-1];
	//if(!$phoneVerificationFromMobile && !in_array($scrName,$wap_filelist))
	if(false)
	{
		$smarty->assign("ERROR_MESSAGE","Sorry. This page is available only on desktop version.<br/>");
		$header=$smarty->fetch("mobilejs/jsmb_header.html");
		$footer=$smarty->fetch("mobilejs/jsmb_footer.html");
		$smarty->assign("HEADER",$header);
		$smarty->assign("FOOTER",$footer);
		$smarty->display("mobilejs/confirmation_template.html");
		die;
	}
}
include_once($_SERVER['DOCUMENT_ROOT']."/classes/authentication.class.php");
$protect_obj=new protect;

//if(!isset($_COOKIE['JS_SOURCE']) && ($source!="") && ($source!="ofl_prof")){
//	setcookie("JS_SOURCE",$source,time()+2592000,"/",$domain);
	//savehit($source,$_SERVER['PHP_SELF']);
//}
//else
	//savehit("IP",$_SERVER['PHP_SELF']);

if (!headers_sent()) 
	setcookie("test_js","y",time()+2592000,"/",$domain);
if(function_exists("common_assign")&&!$fromCron)
	common_assign($data);

function zip($abc=''){
	global $dont_zip_now;
	$zipIt = 0;
	if(strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	      $zipIt = 1;
	if($zipIt && !$dont_zip_now){
		$dont_zip_now=1;
		ob_start("ob_gzhandler");
	}
	return $zipIt;
}

function zip_flush($zipIt,$dont_zip_now){
	if($zipIt && !$dont_zip_now)
	      ob_end_flush();
}

?>
