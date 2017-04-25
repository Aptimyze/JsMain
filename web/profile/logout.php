<?php
include_once("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
$db=connect_db();
/*
//added by manoranjan for deleting data from userplane which is log out from chat

$sql="DELETE FROM userplane.users WHERE userID='$profileId'";
mysql_query_decide($sql);
 */
// check referer
if(MobileCommon::isNewMobileSite())
        $isMobile=1;

if(MobileCommon::isDesktop() || MobileCommon::isNewMobileSite())
{
    if(isset($_SERVER['HTTP_REFERER']))
    {
        LoggingManager::getInstance()->logThis(LoggingEnums::LOG_INFO,'',array(LoggingEnums::REFERER => $_SERVER['HTTP_REFERER'], LoggingEnums::LOG_REFERER => LoggingEnums::CONFIG_INFO_VA, LoggingEnums::MODULE_NAME => LoggingEnums::LOG_VA_MODULE));
    }
}
if(MobileCommon::isDesktop())
{
	header("Location:".$SITE_URL."/static/logoutPage?fromSignout=1");die;
}

 
if($SHOW_FGT_WINDOW)
{
    $smarty->assign("PREV_URL",$_SERVER['REQUEST_URI']);
    $smarty->assign("home",htmlspecialchars($home));
    $smarty->display("forget_passwd.htm");
    die;
}


/*************Portion of Code added for display of Banners*****************************/
$smarty->assign("NO_BOTTOM_ADSENSE","1");
$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("data",$profileId);
$smarty->assign("bms_topright",11);
$smarty->assign("bms_middle",12);
$smarty->assign("bms_bottom",13);
$smarty->assign("bms_new_win",38);
/***********************End of Portion of Code*****************************************/

$data=array("PROFILEID"=>$profileId);
$bmsObj = new BMSHandler();
$zedo = $bmsObj->setBMSVariable($data,1,$request);

//checking if coming by clicking on logout link.
if($isMobile)
{

    if($mobile_logout)
    {
        $mobile_logout_msg = "You have successfully logged out of Jeevansathi.com";
        $smarty->assign("MOBILE_LOGOUT_MSG", $mobile_logout_msg);
    }
    $smarty->assign("LOGIN_ICON",1);
    $template_name="mobilejs/jsmb_login.html";
    assignHamburgerSmartyVariables();
}
else
{
    $template_name="logout_1.htm";
    if($_COOKIE['AUTHN']){
	    
        setcookie("SULEKHACO", "", time()-3600,"/");
        $template_name="logout_new.htm";
        unset($zedo["zedo"]["tag"]["topsmall"]);
    }
    else
    {
	    unset($zedo["zedo"]["tag"]["right"]);
	    unset($zedo["zedo"]["tag"]["left"]);
    }
}

$smarty->assign("zedoVariable",$zedo);

if(!$data['PROFILEID'])
	$data['PROFILEID'] = $profileId;
$mkeys[] = $data["PROFILEID"]."_DUMMY_USER";
$mkeys[] = $data["PROFILEID"]."_KUNDLI_LINK";
$mkeys[] = $data["PROFILEID"]."_PHONE_VERIFIED";
foreach($mkeys as $k=>$v)
	memcache_call($v,"");

unset($data);
setcookie("oldbrowser", "", time()-3600,"/");
// logout(); case handled in apiAuthentication
helpWidget();
$smarty->assign("NOT_TO_SHOW",1);
$smarty->assign("var_in","0");
$smarty->assign("LOGOUT","1");
$smarty->assign("CURRENTUSERNAME","");
$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
//$smarty->assign("REVAMP_SEARCH_PANEL",$smarty->fetch("revamp_top_search_band.htm"));
//$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
$smarty->assign("head_tab",'my jeevansathi');
if($isMobile)
{
	if(MobileCommon::isNewMobileSite())
	{
		MobileCommon::gotoModuleUrl("static","logoutPage");
		return;
	}
	
}
$smarty->display($template_name);

?>
