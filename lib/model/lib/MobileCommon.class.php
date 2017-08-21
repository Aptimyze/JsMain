<?php
/**
 * MobileCommon class mainly contains static function required
 * by repective modules as handling mobile relating computations.
 * 
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Nikhil dhiman
 * @version    SVN: $Id: MobileCommon.class.php 23810 2011-07-14 03:07:44 Nikhil dhiman $
 */
class MobileCommon{

	/* must be called after isMobile */
	public static function isTabletMobile()
	{
		return sfContext::getInstance()->getRequest()->getAttribute("JS_TABLET_MOBILE");
	}
	public static function isMobile()
	{
		//return true;

		if(!sfContext::getInstance()->getRequest()->getAttribute("JS_MOBILE"))
		{
			if (JsConstants::$whichMachine != 'matchAlert') {
				include_once(sfConfig::get("sf_web_dir")."/profile/mobile_detect.php");
			}
		}
		return sfContext::getInstance()->getRequest()->getAttribute("JS_MOBILE");
	}
	public static function getMoreAbout($moreAbtArr)
	{
		$limit=sfConfig::get("app_info_limit_mob");
		foreach($moreAbtArr as $key=>$val)
		{
			if(strlen($val)>$limit)
			{
				$val=str_replace('"',"'",$val);
				$infr_arr=explode('|', wordwrap($val, $limit, '|'));
				if($infr_arr[0]){
					if (strrpos($infr_arr[0],'<b') !== false || strrpos($infr_arr[0],'<') !== false ){
						$key2=substr($infr_arr[0],0,strrpos($infr_arr[0],'<'));
					}
					else
						$key2=$infr_arr[0];
				}				
				$temp[$key."1"]=str_replace("\r","",str_replace("\n","",(nl2br($key2))));
			}
			$temp[$key]=str_replace("\r","",str_replace("\n","",addslashes(($val))));
		}
		if(is_array($temp))
			$moreAbtArr=array_merge($moreAbtArr,$temp);
		return  $moreAbtArr;
	}
	public static function getSnipView($profileObj)
	{
		$snip_view_arr=array();
		$snip_view_arr[]=$profileObj->getAGE();
		$snip_view_arr[]=$profileObj->getDecoratedHeight();
		$snip_view_arr[]=$profileObj->getDecoratedReligion();
		if(ProfileCommon::CasteAllowed($profileObj->getRELIGION()))
			$snip_view_arr[]=ltrim(FieldMap::getFieldLabel("caste_small",$profileObj->getCASTE()),"-");

		$snip_view_arr[]=FieldMap::getFieldLabel("community_small",$profileObj->getMTONGUE());
		$gothra=$profileObj->getDecoratedGothra();
		if($gothra)
			$snip_view_arr[]=$gothra."(Gothra)";
		if($profileObj->getDecoratedEducation())
			$snip_view_arr[]=$profileObj->getDecoratedEducation();
		if($profileObj->getDecoratedIncomeLevel())
			$snip_view_arr[]=$profileObj->getDecoratedIncomeLevel();
		if($profileObj->getDecoratedOccupation())
			$snip_view_arr[]=$profileObj->getDecoratedOccupation();
		$snip_view_str=implode(",",$snip_view_arr);
		
		if($profileObj->getDecoratedCity())
		{
			$residence=$profileObj->getDecoratedCity();
		}
		else
			$residence=$profileObj->getDecoratedCountry();

		$snip_view_str.=" in $residence";
		return $snip_view_str;

	}
	public static function isApp()
	{
		$userAgent=$_SERVER[HTTP_USER_AGENT];
		if(strpos($userAgent,"JsAndroid")!==FALSE)
			return "A";
		elseif(strpos($userAgent,"JsApple")!==FALSE)
			return "I";
		else
			return null;
	}

	public static function isCron()
	{
		$userAgent=$_SERVER[HTTP_USER_AGENT];
		if(strpos($userAgent,"JsCli")!==FALSE)
			return "C";
		else
			return null;
	}


	//checks if it is crm app
	public static function isCrmApp()
	{
		//return "A";
		$userAgent=$_SERVER[HTTP_USER_AGENT];
		if(strpos($userAgent,"fsoAndroid")!==FALSE)
			return "A";
		else
			return null;
	}

	public static function isAppWebView()
	{
		$userAgent=$_SERVER[HTTP_USER_AGENT];
		$userAgentAlt = $_REQUEST['device'];
		if(strpos($userAgent,"JsAndWeb")!==FALSE || strpos($userAgentAlt,"Android_app")!==FALSE){
			return "A";
		}
		else{
			return false;
		}
	}

	public static function isLogin()
	{
		$profileObj = LoggedInProfile::getInstance('newjs_master');
		if($profileObj->getPROFILEID())
			return true;
		else
			return false;
	}
	
	public static function isNewMobileSite()
	{
		$bc=new BrowserCheck;
		$k=$bc->IsHtml5Browser();
//		if(sfContext::getInstance()->getRequest()->getCookie("TO_OLD_JSMS")==1)
//			$k=0;
		if(MobileCommon::isMobile() && !MobileCommon::isApp())
		{
			if($k)
			{
				MobileCommon::TrackUser(1);
				return true;
			}
			else
			{
				MobileCommon::TrackUser(0);
				return false;
			}
		}
	}
	/**
	* Track users who are landing on which page
	*
	**/
	public static function TrackUser($whichSite=0)
	{
		
		$intObj=sfContext::getInstance();
		$rObj=$intObj->getRequest();
		if($rObj->getAttribute("alreadyTrackedJSMS"))
			return;
		$rObj->setAttribute("alreadyTrackedJSMS",1);
		$forced=0;
		if($_COOKIE[TO_OLD_JSMS]==1)
			$forced=1;
		$uagent=addslashes($_SERVER[HTTP_USER_AGENT]);
		if($intObj->getRequest()->getCookie("AC_JSMS")!=1)
		{
			$dbObj=new MIS_JSMS_CATEGORY();
			$dbObj->insertRecord($whichSite,$uagent,$forced);	
			@setcookie("AC_JSMS",1,time()+(60*60*24),"/");
			//$intObj->getResponse()->setCookie('AC_JSMS',1,time()+60*60*24*10,"/");
		}
	}
	/**
	* This function passes the mobile site url to new revamp pages
	* @param obj carries the object of the function of old site action
	* @module,@action for nonSymfony Pages
	*/
	public static function forwardmobilesite($obj="",$module="",$action="",$trueForDesktopSiteAsWell="")
	{
		if(self::isNewMobileSite() || ($trueForDesktopSiteAsWell && self::isDesktop()) )
		{
			if($obj)
			{
				$moduleName = $obj->getModuleName();
				$actionName = $obj->getActionName();
				$output =  MobileSiteForwardConfig::$forwardArr[$moduleName."#".$actionName];
				if($output)
				{
					$outputArr =explode("#",$output);
					if($outputArr && $outputArr[0] && $outputArr[1])
						$obj->forward($outputArr[0],$outputArr[1]);
				}
			}
			else
				self::gotoModuleUrl($module,$action);
		}
	}
	public static function gotoModuleUrl($module,$action)
	{
		$request=sfContext::getInstance()->getRequest();
		$request->setParameter("module",$module);
		$request->setParameter("action",$action);
		$request->setParameter("moduleName",$module);
		$request->setParameter("actionName",$action);
		$request->setRelativeUrlRoot("");
		sfContext::getInstance()->getController()->forward($module,$action);
		die;
	}
	
    /*
     *isIOS App
     * Function to check isIOS App or not, function check on UserAgent Basis
     * @access public static
     * @param void
     * @return True of iOS App else False
     */
    public static function isIOSApp()
    {
    	return (self::isApp() === 'I');
    }
    
    /*
     *isAndroidApp
     * Function to check isAndroid App or not, function check on UserAgent Basis
     * @access public static
     * @param void
     * @return True of iOS App else False
     */
    public static function isAndroidApp()
    {
    	return (self::isApp() === 'A');
    }
    
    public static function isDesktop()
    {
    	if(MobileCommon::isApp() || MobileCommon::isNewMobileSite() || MobileCommon::isAppWebView() || MobileCommon::isMobile())
    		return false;
    	else
    		return true;
    }

    public static function isCrmDesktop()
    {
    	if(MobileCommon::isCrmApp())
    		return false;
    	else
    		return true;
    }

    public static function getChannel(){
    	if(MobileCommon::isApp() )
    		return MobileCommon::isApp();
    	if(MobileCommon::isNewMobileSite())
    		return "MS";
    	if(MobileCommon::isDesktop())
    		return "P";
    	if(MobileCommon::isOldMobileSite())
    		return LoggingEnums::OMS;
    }

	/**
     * @return channel names
     */
	public static function getFullChannelName(){
		switch (self::getChannel()) {
			case LoggingEnums::P:
			{
				$channelName = "JSPC";
				break;
			}
			case LoggingEnums::A:
			{
				$channelName = "JSAA";
				break;
			}
			case LoggingEnums::I:
			{
				$channelName = "JSIA";
				break;
			}
			case LoggingEnums::MS:
			{
				$channelName = "JSMS";
				break;
			} 
			case LoggingEnums::OMS:
			{
				$channelName = "JSOMS";
				break;
			} 
			default:
			{
				$channelName = "UNKNOWN CHANNEL";
				break;
			} 
		}
		return $channelName;
	}

  /*
   * isOldMobileSite
   */
  public static function isOldMobileSite() {
  	return (self::isMobile() && false === self::isNewMobileSite());
  }

  /*
     * function to check whether the link is being opened in an apple device
     * @param 
     * @return value : 1 if the conditions specified are true
     */
  public static function isIOSPhone()
  {
  	$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
  	$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
  	$iPad = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
  	if( $iPod || $iPhone || $iPod)
  	{
  		return 1;
  	}
  }
  public static function getHttpsUrl()
  {
        if(MobileCommon::isApp()=="A")
                return false;
        return true;
  }
}
