<?php

/**
 * homepage actions.
 *
 * @package    jeevansathi
 * @subpackage homepage
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class homepageActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
 
  public function executeIndex(sfWebRequest $request)
  {
	  
  	$this->currency=$request->getAttribute("currency");
	$start_tm=microtime(true);
	$this->getResponse()->addVaryHttpHeader("User-Agent");
	if($request->getParameter("desktop")=='Y')
		{//echo "aaaaa";die;
		$desktop_view=1;
		$this->setDesktopCookies($request);
	}
	if($request->getParameter("mobile_view")=='Y')
	{
		$mobile_view=1;
		$is_mob=1;
		$this->setMobileCookies();
		/*@setcookie('NEWJS_DESKTOP',"",0,"/");
  		unset($_COOKIE['NEWJS_DESKTOP']);
  		@setcookie('JS_MOBILE','Y',0,"/");*/
	}
	if($request->getcookie('JS_MOBILE'))
	{
		$JS_MOBILE_ARR=explode(",",$request->getcookie('JS_MOBILE'));
		if($JS_MOBILE_ARR[0]=='Y')
			$is_mob=1;           
	}
	else
	{
		//if(MobileCommon::isMobile())
		//	$is_mob=1;			//set cookie mobile new
		$mob_arr=is_mobile($_SERVER['HTTP_USER_AGENT']);
  if($mob_arr['mobileBrowser']=="true" && $mob_arr['is_tablet'] =="false")
    $is_mob=1;
  if($is_mob)
  {
  	//echo "c";die;
    set_cookie_mobile($mob_arr);
  }
  else{
    setcookie('JS_MOBILE','N',time()+31536000,"/");
}
	}
			// if mobile view set cookie new
	if($request->getParameter("source")!="")
	{
		$source = $request->getParameter("source");
		$this->source = $source;
		$this->setJsCookies($request);
		$this->blockSpamRegistrations($source);
		/*$misSourceObj = new MIS_SOURCE;
		$sourceDetails = $misSourceObj->getSourceFields("*",$source);
		if(!is_array($sourceDetails))
		{
			$misUnknowSourceObj = new MIS_UNKNOWN_SOURCE;
			$misUnknowSourceObj->insertUnknownSource($source);
			$source="unknown";
		}
		else
		{
			$source=$sourceDetails["SourceID"];
			$this->setGroupName($sourceDetails);
		}
		$sourceTrackingObj = new SourceTracking($this->source);
		$sourceTrackingObj->savehit('/profile/registration_new.php');*/
	}
	else
	{
		/*$sourceTrackingObj = new SourceTracking("IP");
		$sourceTrackingObj->savehit($_SERVER['PHP_SELF']);*/
	}
	$this->loginData=$request->getAttribute("loginData");

	if($this->loginData[PROFILEID])
		$this->redirectLoggedInProfile($this->loginData,$is_mob,$mobile_view,$desktop_view,$request);
	// log referer
	else if(isset($_SERVER['HTTP_REFERER']))
	{
		LoggingManager::getInstance()->logThis(LoggingEnums::LOG_INFO,'',array(LoggingEnums::REFERER => $_SERVER['HTTP_REFERER'], LoggingEnums::LOG_REFERER => LoggingEnums::CONFIG_INFO_VA, LoggingEnums::MODULE_NAME => LoggingEnums::LOG_VA_MODULE));
	}
	/***********Mobile (To be integrated)****************/
	if($mobile_view || ($is_mob && ($request->getParameter("desktop")!='Y' && $request->getcookie('NEWJS_DESKTOP')!='Y')))
	{
		$mob_det_already_called=1;
		include_once(sfConfig::get('sf_web_dir')."jsmb/mb_comfunc.php");
		if($this->source && $noreg!='Y')
		{
			$this->redirectToRegister("source=$this->source");
			die;
		}
		if(MobileCommon::isNewMobileSite() && !$data[PROFILEID]){
			$request->setParameter("homepageRedirect",1);
			MobileCommon::gotoModuleUrl("static","logoutPage");
	}

	else
	{
		$zipIt = 0;
		if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	        	$zipIt = 1;
		if($zipIt)
	        	ob_start("ob_gzhandler");
		header('Location:'.JsConstants::$siteUrl.'/P/logout.php');
	        if($zipIt && !$dont_zip_now)
	                ob_end_flush();
		//------------------------SHOW MOBILE LOGIN PAGE END
	    	die;
	}
	}
	/***********Ends here************
	if($request->getParameter("source")!="")
	{
		$this->redirectSEMPage();
		$this->redirectMiniRegForm();
	}
*/
	/*********Success stories************/
	$this->successStory();
	/*********Ends***********************/

	

        /*********Isearch condition condition*********/
        if($request->getcookie("ISEARCH"))
	{
		$id=$request->getcookie("ISEARCH");
		$cookie_setIsearch=1;
	}
	else
		$cookie_setIsearch=0;
        /*********Ends here****************
	/***************HTTP Metas************/
	//$this->getResponse()->addMeta('canonical', sfConfig::get('app_site_url'));
	$this->getResponse()->addMeta('description', "Most trusted Indian matrimony site. 10Lac+ Profiles, 3-level profile check, Search by caste and community, Privacy control & Register FREE! ‘Be Found’ Now");
	$this->getResponse()->addMeta('author', sfConfig::get('app_site_url'));
	$this->getResponse()->addMeta('copyright', date('Y').' jeevansathi.com');
	/***************Ends here*************/
	$end_time=microtime(true)-$start_tm;
	$this->TRACK_FOOT = BrijjTrackingHelper::getTailTrackJs($end_time,true,2,"https://track.99acres.com/images/zero.gif");
	
	
	sfContext::getInstance()->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsHomePageUrl);
	//print_r($this->populateDefaultValues);
	//print_r($this->staticSearchDataArray);
	//die;
  }
  private function redirectLoggedInProfile($loginData,$is_mob='',$mobile_view,$desktop_view,$request)
  {
        if(strpos($_SERVER['HTTP_REFERER'], 'membership') || strpos($_SERVER['HTTP_REFERER'], 'mem_comparison'))
		{
                header("Location:".$SITE_URL."/profile/mem_comparison.php");
                die;
        }
        //$request->setParameter("fromHomepage",1);
        //$a=MobileCommon::isNewMobileSite();
        //var_dump($a);die;
		if(!MobileCommon::isNewMobileSite() && MobileCommon::isMobile()){
			header("Location:".$SITE_URL."/P/mainmenu.php?checksum=".$loginData['CHECKSUM']);
		}
		elseif(MobileCommon::isNewMobileSite()){
			
			if($desktop_view!=1){
				$this->forward("myjs","jsmsPerform");
			}
				
			else{
				$this->forward("myjs","jspcPerform");
			}
		}
		else{
			if($mobile_view!=1)
			{
				$this->forward("myjs","jspcPerform");
			}
			else
			{
				$this->forward("myjs","jsmsPerform");
			}
			
		}
			
      //  if(!$is_mob)
		
      /*  else
        {
                if($loginData['HAVEPHOTO']=='N' || strlen($loginData['HAVEPHOTO'])==0)
                        header("Location:".$SITE_URL."/profile/viewprofile.php?ownview=1");
                else
                {
                        $memchacheObj = new ProfileMemcacheService($loginData['PROFILEID']);
                        $memberToAcceptCount = $memchacheObj->get("AWAITING_RESPONSE");
                        if($memberToAcceptCount > 0)
                                header("Location:".$SITE_URL."/profile/contacts_made_received.php?page=eoi&filter=R");
                        else
                                header("Location:".$SITE_URL."/search/partnermatches");
                }
        }*/
        die;
  }
  private function blockSpamRegistrations($source)
  {
        if(isset($_SERVER['HTTP_REFERER'])) {
                if(strpos($_SERVER['HTTP_REFERER'],'dhanlaxmivarsha.com'))
                        header("Location: $SITE_URL");
        }
        if($source=='mailer_adc'){
                        header("Location: $SITE_URL");
        }
  }

  private function setJsCookies($request)
  {
	//$this->getResponse()->setCookie("JS_SOURCE",$this->source,time()+2592000,"/",$request->getParameter("domain"));
	setcookie('JS_SOURCE',$this->source,time()+2592000,"/",$request->getParameter("domain"));
//var_dump($this->source);die;
	if($request->getCookie("JS_ADNETWORK") || $request->getCookie("JS_ACCOUNT") || $request->getCookie("JS_CAMPAIGN") || $request->getCookie("JS_ADGROUP") || $request->getCookie("JS_KEYWORD") || $request->getCookie("JS_MATCH") || $request->getCookie("JS_LMD"))
	{
		$cookie_str.=":JS_ADNETWORK=".$request->getCookie("JS_ADNETWORK");
		$cookie_str.=":JS_ACCOUNT=".$request->getCookie("JS_ACCOUNT");
		$cookie_str.=":JS_CAMPAIGN=".$request->getCookie("JS_CAMPAIGN");
		$cookie_str.=":JS_ADGROUP=".$request->getCookie("JS_ADGROUP");
		$cookie_str.=":JS_KEYWORD=".$request->getCookie("JS_KEYWORD");
		$cookie_str.=":JS_MATCH=".$request->getCookie("JS_MATCH");
		$cookie_str.=":JS_LMD=".$request->getCookie("JS_LMD");
		$this->getResponse()->setCookie('JS_CAMP',$cookie_str,time()+2592000,"/");
		$this->getResponse()->setCookie("JS_ADNETWORK","",0,"/");
		$this->getResponse()->setCookie("JS_ACCOUNT","",0,"/");
		$this->getResponse()->setCookie("JS_CAMPAIGN","",0,"/");
		$this->getResponse()->setCookie("JS_ADGROUP","",0,"/");
		$this->getResponse()->setCookie("JS_KEYWORD","",0,"/");
		$this->getResponse()->setCookie("JS_MATCH","",0,"/");
		$this->getResponse()->setCookie("JS_LMD","",0,"/");
	}
	else if(($request->getParameter("adnetwork")) || ($request->getParameter("account")) || ($request->getParameter("campaign")) || ($request->getParameter("adgroup")) || ($request->getParameter("keyword")) || ($request->getParameter("match")) || ($request->getParameter("lmd")))
	{
		$cookie_str.=":JS_ADNETWORK=".$request->getParameter("adnetwork");
		$cookie_str.=":JS_ACCOUNT=".$request->getParameter("account");
		$cookie_str.=":JS_CAMPAIGN=".$request->getParameter("campaign");
		$cookie_str.=":JS_ADGROUP=".$request->getParameter("adgroup");
		$cookie_str.=":JS_KEYWORD=".$request->getParameter("keyword");
		$cookie_str.=":JS_MATCH=".$request->getParameter("match");
		$cookie_str.=":JS_LMD=".$request->getParameter("lmd");
		//$this->getResponse()->setCookie('JS_CAMP',$cookie_str,time()+2592000,"/");
		setcookie('JS_CAMP',$cookie_str,time()+2592000,"/");
	}
	if($this->noreg=="Y")
		setcookie('OPEN_JS',3,0,"/");
		//$this->getResponse()->setCookie("OPEN_JS","3",0,"/");
	
  }
 
  private function setMobileCookies(){
  	@setcookie('NEWJS_DESKTOP',"",0,"/");
  unset($_COOKIE['NEWJS_DESKTOP']);
  @setcookie('JS_MOBILE','Y',0,"/");
	//$this->getResponse()->setCookie('NEWJS_DESKTOP','',0,"/");
	//$this->getResponse()->setCookie('NEWJS_DESKTOP','',-1,"/");
	//$this->getResponse()->setCookie('JS_MOBILE','Y',0,"/");
  }

  private function setDesktopCookies($request){
  	setcookie('NEWJS_DESKTOP',$request->getParameter("desktop"),0,"/");
	//$this->getResponse()->setcookie('NEWJS_DESKTOP',$request->getParameter("desktop"),0,"/");
	//die;
	//$this->getResponse()->setCookie('JS_MOBILE','N',0,"/");
  } 
  private function setGroupName($sourceDetails)
  {
	$this->noreg=$sourceDetails['NOREG'];
	$this->groupName=$sourceDetails['GROUPNAME'];
  }

  private function getHomepage($request){
	if($request->getCookie("hv")){
		if($request->getCookie("hv") == "v1")
			$hpVersion = "v1";
		elseif($request->getCookie("hv") == "v2")
			$hpVersion = "v2";
	}
	else{
		$selectHPObj = new MIS_HOMEPAGE_SELECT;
		$hpType = $selectHPObj->selectType();
		if($hpType["v1"] == $hpType["v2"])
			$hpVersion =  "v1";
		else{
			$hpVersion =  array_search(min($hpType),$hpType);
		}
		$this->getResponse()->setCookie("hv",$hpVersion,time()+5184000,"/");
		$selectHPObj->setSelected($hpVersion);
	}
	return $hpVersion;
  }

  private function redirectSEMPage()
  {
	$SEMObj = new MIS_SEM_PAGE_CUSTOMIZE;
	$arrayValues = $SEMObj->sourceSEM();

	if($arrayValues)
	{
		if(in_array($this->source,$arrayValues))
		{
			$newsource=$this->source;
			$this->source="";
			$sempages=1;
			$this->redirectToRegister("source=&newsource=$newsource&sempages=1",1);
			exit;
		}
	}
  }
  private function successStory()
  {
		$individualStoriesObj = new IndividualStories('','',false);
		$this->successStoryData = $individualStoriesObj->showSuccessPoolStory();
  }

  //move to store new
  private function redirectMiniRegForm()
  {
	$miniRegObj = new MIS_MINI_REG_CUSTOMIZE;
	$arrayValues = $miniRegObj->sourceMiniReg();
	//print_r($arrayValues);die;
	if(in_array($this->source,$arrayValues))
	{
		$newsource=$this->source;
		$this->source="";
		$this->redirectToRegister("source=&newsource=$newsource&sempages=1",1,1);
		exit;
	}
  }
  private function redirectToRegister($newsource,$sourcecheck=0,$minireg=0)
  {
	if($sourcecheck)
	{
		unset($_GET[source]);
		unset($_POST[source]);
	}
	if(is_array($_GET))
	{
		foreach($_GET as $key => $value)
			$get_post[] = "$key=$value";
	}
	if(is_array($_POST))
	{
		foreach($_POST as $key => $value)
			$get_post[] = "$key=$value";
	}
	if(is_array($get_post))
		$get_post_string = @implode("&",$get_post);
	$url="/register/page1";
	if($minireg)
		$url="/register/minireg";

	header("Location:$url?$get_post_string"."&".$newsource);
  }
}
