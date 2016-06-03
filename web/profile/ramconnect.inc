<?php

// maximum zlib compression level is 9
ini_set('zlib.output_compression_level', 5);

$TOUT1 = 1800;
$TOUT2 = 7200;
if($nikhil_marriage_bureau!=1)
{
	include("config.php");
	hello
}

$lang=$_COOKIE['JS_LANG'];
if($lang)
{
	header('Content-Type: text/html; charset=UTF-8');
	$smarty->assign("JS_LANG",$lang);
}

include("tables.php");
//include_once("profile/hits.php");
$banners=array("CHAT","SMS");
$bannerid=rand(0,1);
$bannerid=$banners[$bannerid];

$smarty->assign("random_image",rand(1,9999999));
$smarty->assign("BANNERID",$bannerid);
$smarty->assign("LEFTBANNER",basename($_SERVER['PHP_SELF']));

include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include("connect_db.php");
include("js_encryption_functions.php");
include(JsConstants::$docRoot."/commonFiles/connect_dd.inc");
include("connect_reg.inc");
include("connect_auth.inc");
include("connect_functions.inc");


if($SITE_URL=="http://www.jeevansathi.com" || $SITE_URL=='')
        $domain='.jeevansathi.com';
else
        $domain='';

if(!isset($_COOKIE['JS_SOURCE']) && ($source!="")){
	setcookie("JS_SOURCE",$source,time()+2592000,"/",$domain);
	//savehit($source,$_SERVER['PHP_SELF']);
}
//else
	//savehit("IP",$_SERVER['PHP_SELF']);

if (!headers_sent()) 
	setcookie("test_js","y",time()+2592000,"/",$domain);

?>
