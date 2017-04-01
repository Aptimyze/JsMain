<?php
if(!$_SERVER["DOCUMENT_ROOT"])
	return false;

if(!$mob_det_already_called){
require_once($_SERVER["DOCUMENT_ROOT"]."/jsmb/mb_comfunc.php");
if(isset($_COOKIE['JS_MOBILE'])){
	$JS_MOBILE_ARR=explode(",",preg_replace('/[^A-Za-z0-9\. -]/', '', $_COOKIE['JS_MOBILE']));
	if($JS_MOBILE_ARR[0]=='Y')
		$is_mob=1;
}
else{
	$mob_arr=is_mobile($_SERVER['HTTP_USER_AGENT']);
	if($mob_arr['mobileBrowser']=="true" && $mob_arr['is_tablet'] =="false")
		$is_mob=1;
	if($is_mob)
	{
		set_cookie_mobile($mob_arr);
	}
	else
		setcookie('JS_MOBILE','N',time()+31536000,"/");

}
if($is_mob && $_COOKIE['NEWJS_DESKTOP']!='Y')
	$isMobile=1;

if(strstr($_SERVER['PHP_SELF'],'symfony_index.php'))
{
	if(!isset($_COOKIE['JS_TABLET_MOBILE']))
	{
		$mob_arr=is_mobile($_SERVER['HTTP_USER_AGENT'],'checkTablet');
		if($mob_arr['is_tablet']=="true")
			$is_tablet=1;
	}
	elseif($_COOKIE['JS_TABLET_MOBILE']=='Y')
		$is_tablet=1;

        if(!$is_tablet && $is_mob)
                $is_tablet=1;

	if($is_tablet==1)
	{
		setcookie('JS_TABLET_MOBILE','Y',time()+31536000,"/");
		$req_prm=sfContext::getInstance()->getRequest();
		$req_prm->setAttribute('JS_TABLET_MOBILE',1);
	}
	else	
		setcookie('JS_TABLET_MOBILE','N',time()+31536000,"/");
	
}
if(class_exists('sfContext') && $isMobile)
{
	try{
        $req_prm=sfContext::getInstance()->getRequest();
	$req_prm->setAttribute('JS_MOBILE',1);
	$req_prm->setAttribute('googleAnalyticsImageUrl',googleAnalyticsGetImageUrl());
	}catch(Exception $e){}
	
}
$mob_det_already_called=1;
}
