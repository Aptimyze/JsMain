<?php
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
if(MobileCommon::isMobile() && !MobileCommon::isNewMobileSite() && !sfContext::getInstance()->getRequest()->getParameter('redirectFromOldSite')){
	$url=JsConstants::$siteUrl."/static/oldMobileSite?redirectFromOldSite=1";
		header("Location: " . $url);die;
}
 include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
function login($username,$password,$allowDeactive=0)
{
	
	global $protect_obj;
	
	if($allowDeactive)
		$protect_obj->allowDeactive=1;

	return $protect_obj->login();
	$loginObj=AuthenticationFactory::getAuthenicationObj();
	$loginObj->setTrackLogin(0);
	$loginObj->login($username,$password,1);
	//print_r($data);
	
	//return $data;
}


function login_affiliate($username,$password)
{
	global $protect_obj;
	
	return $protect_obj->login_affiliate();
	
	//print_r($data);
	
	//return $data;
}

if(!function_exists("authenticated"))
{
function authenticated($checksum="",$from_input_profile="")
{

	
	include_once(JsConstants::$docRoot."/ivr/jsivrFunctions.php");
	global $isMobile,$symfony_using,$smarty,$phoneVerificationFromMobile;
	if(!$symfony_using)
	{
		
		global $protect_obj;
		$return = $protect_obj->checkSession($checksum,$from_input_profile);
			$authenticationLoginObj= AuthenticationFactory::getAuthenicationObj();
			$authenticationLoginObj->setTrackLogin(0);
			$data=$authenticationLoginObj->authenticate(null,$gcm);
		if($return)	
		{
			$smarty->assign("AJAX_CALL_MEMCACHE",Header::checkMemcacheUpdated($return['PROFILEID']));
		}
        helpWidget($return);
	}
	else
	{
		if (class_exists('Header')) 
		$smarty->assign("AJAX_CALL_MEMCACHE",Header::checkMemcacheUpdated($return['PROFILEID']));
		$return = sfConfig::get("LoginData");
	}
	/*
	* brief: phone mandatory block start
	* done by :Esha
	* $phoneVerified  variable holds value stored in memcache to check whether layer should be shown
	* in case value is not in memcache, calculated using hidePhoneLayer function
	* setting it as smarty variable, so that available in htm
	* if logged in from mobile wap, then call phoneVerify.php to show page
	* else handled thorugh javascript on desktop, with the help of smarty variable set initially.
	*/
	if($return['PROFILEID'])
	{			    					
						$showConsentMsg=memcache_call("showConsentMsg_".$return['PROFILEID']);
			                           if(!$showConsentMsg)
										{ 
										$showConsentMsg = JsCommon::showConsentMessage($return['PROFILEID']) ? 'Y' : 'N';
										memcache_call("showConsentMsg_".$return['PROFILEID'],$showConsentMsg);
										}

		if ($showConsentMsg=='Y') {
								$loginProfile = LoggedInProfile::getInstance("",$return[PROFILEID]);
								$loginProfile->getDetail();
								if($loginProfile->getINCOMPLETE()=="Y") $showConsentMsg='N';
							}								
		$smarty->assign("showConsentMsg",$showConsentMsg);
		$phoneVerified = memcache_call($return['PROFILEID']."_PHONE_VERIFIED");
		if(!$phoneVerified)
		{
			$phoneVerified = hidePhoneLayer($return['PROFILEID']);
			memcache_call($return['PROFILEID']."_PHONE_VERIFIED",$phoneVerified);
		}
		if(!MobileCommon::isNewMobileSite() && JsCommon::showPhoneVerificationPageOnMobile($return['PROFILEID'],$isMobile,$phoneVerified,$phoneVerificationFromMobile))
		{
			include_once(JsConstants::$docRoot.'/jsmb/phoneVerify.php');
			die;
		}
		$requestUrl = $_SERVER["REQUEST_URI"];
		$smarty->assign("PHONE_VERIFIED",$phoneVerified);
                                
	}
	if($_GET["crmback"]=="admin")
		$smarty->assign("PHONE_VERIFIED","Y");
	// app promo need to be off for logged in users having app installed
	
		$AppLoggedInUser=LoggedInAppPromo($return['PROFILEID']);
		$smarty->assign("AppLoggedInUser",$AppLoggedInUser);
	//phone mandatory block end
	return $return;
}
}

if(!function_exists("logout"))
{
	function logout()
	{
		global $protect_obj;
		$loginObj=AuthenticationFactory::getAuthenicationObj();
		$loginObj->removeLoginCookies();
		$protect_obj->logout();
	}
}


function TimedOut($headmessage="")
{
	global $protect_obj;
	$loginObj=AuthenticationFactory::getAuthenicationObj();
	$loginObj->removeLoginCookies();
	$protect_obj->TimedOut($headmessage);
}


function login_register()
{
	global $protect_obj;
	$loginObj=AuthenticationFactory::getAuthenicationObj();
	$loginObj->setTrackLogin(0);
	$loginObj->loginFromReg();
	$protect_obj->login_register();
}


function js_checkCookie()
{
        if(isset($_COOKIE['test_js']))
                return true;
        else
                return false;
}


function is_complete_now($profileid)
{
	
	$db_slave=connect_737();
	$db=connect_db();
	$count=0;
	$sql1="SELECT COUNT(*) as cnt FROM JPROFILE WHERE INCOMPLETE='Y' AND RELATION!='' AND EMAIL!='' AND PASSWORD!='' AND CITY_RES!='' AND EDU_LEVEL_NEW!='' AND OCCUPATION!='' AND INCOME!='' AND (PHONE_RES!='' OR PHONE_MOB!='') AND GENDER!='' AND DTOFBIRTH!='' AND MSTATUS!='' AND HEIGHT!='' AND COUNTRY_RES!='' AND MTONGUE!='' AND RELIGION!='' AND CASTE!='' AND LENGTH(YOURINFO)>=100 AND ENTRY_DT<'2009-04-06' AND  activatedKey=1 and PROFILEID='$profileid'";
	$res1=mysql_query($sql1,$db_slave) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql1,"ShowErrTemplate");
	if($row1=mysql_fetch_array($res1))
		$count=$row1["cnt"];
	if($count)
	{
		$jprofileUpdateObj = JProfileUpdateLib::getInstance(); 
	
		$profileid=$profileid;
		$arrFields = array('INCOMPLETE'=>'N','ENTRY_DT'=>'now()','MOD_DT'=>'now()');
		$exrtaWhereCond = "activatedKey='1";
		$jprofileUpdateObj->editJPROFILE($arrFields,$profileid,"PROFILEID",$exrtaWhereCond);
//		$sql2="UPDATE JPROFILE SET INCOMPLETE='N',ENTRY_DT=now(),MOD_DT=now() WHERE PROFILEID=$profileid and activatedKey=1";
	//	$res2=mysql_query($sql2,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql2,"ShowErrTemplate");
	}
}
function LoggedInAppPromo($profileid){
		if($profileid){
		if(JsMemcache::getInstance()->get($profileid."_appPromo")==0 || JsMemcache::getInstance()->get($profileid."_appPromo") ==1)
		{
			return JsMemcache::getInstance()->get($profileid."_appPromo");
		}
		else{
			$dbAppLoginProfiles=new MOBILE_API_APP_LOGIN_PROFILES();
			$appProfileIdFlag=$dbAppLoginProfiles->getAppLoginProfile($profileid);
			if($appProfileIdFlag){
				JsMemcache::getInstance()->set($profileid."_appPromo",0);
				return 0;
			}
			else{
				JsMemcache::getInstance()->set($profileid."_appPromo",1);
				return 1;
			}
		}
		}
		else
			return 1;
	}
?>
