<?php
/**
 * noprofile handles all the error message that are passed/forward by
 * other actions. It handles no profile, filter, contacted, hidden, 
 * deleted errors and shows appropriate message to user.
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Nikhil dhiman
 * @version    SVN: $Id: noprofileAction.class.php 23810 2011-07-12 11:07:44Z nikhil.dhiman $
 */
class noprofileAction extends sfAction
{
	
	public $smarty;
	
	/**
	 * Automatically executes before execute function call
	 */
	public function preExecute()
	{
	//	$this->start_tm=microtime(true);
		
	}
	
	public function execute($request)
	{
		
		global $smarty,$data;
		$this->smarty=$smarty;

		//Contains login credentials
		$this->loginData=$data=$request->getAttribute("loginData");
		
		$this->isMobile=MobileCommon::isMobile("JS_MOBILE");
		$this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsProfilePageUrl);	
		//Viewer and Viewed profile ids
		$this->profile=Profile::getInstance();
		$this->loginProfile=LoggedInProfile::getInstance();
		
		if($this->profile instanceOf Profile)
		{
			$hidden=$this->profile->getACTIVATED();
			
			$privacy=$this->profile->getPRIVACY();
			$gender=$this->profile->getGENDER();
			
			
			$this->FROM_PROFILEPAGE=1;
			//From album 
			if($request->getParameter("subject")=="viewphotos")
			{
				$this->FROM_PROFILEPAGE=0;
			}
			
			//Top username.
			$this->TopUsername=ProfileCommon::getTopUsername($this->profile->getUSERNAME());
            
			//Stop navigation b/w username-photos
			$this->stopAlbumView=1;
			$JSTrackingObj = new JSResponseTracking();
			$this->responseTracking = $JSTrackingObj->getProfilePageTracking($request);
			
			$this->HandleNextPrevious();
			//To set Breadcrumb on page.
			$viewSimilar = $_GET['viewSimilar']; //added for similar profiles section on profile page
			$this->setNavigation($viewSimilar);
			
			$this->showIntroCall($request->getAttribute("contactStatus"));
			$this->commonAssign();
			if($gender=='M')
				$his_her="his";
			else
				$his_her="her";
		}
		if($this->loginProfile instanceOf Profile)
		{
			$this->loginProfileId = $this->loginProfile->getPROFILEID();			
		}
		
		if($request->getAttribute(ERROR)==1) //Profile not found
		{
			$this->NO_PROFILE="Search by profile Id";
			$this->MESSAGE=sfConfig::get("app_profile_not_found");
                        $this->PRIMARYMESSAGE = noProfileConstants::PROFILE_NOT_FOUND;
			
		}
		else if($request->getAttribute(ERROR)==2) //Not activated
		{
			
			if($hidden=="N" || $hidden=="U" || $hidden=="P"){
                          $this->MESSAGE=sfConfig::get("app_profile_screened");
                          $this->PRIMARYMESSAGE = noProfileConstants::PROFILE_SCREENED;
                        }
			elseif($hidden=="H"){
                                $this->noIndexNoFollow = 1;
				$this->MESSAGE=sfConfig::get("app_profile_hidden");
                                $this->PRIMARYMESSAGE = noProfileConstants::PROFILE_HIDDEN;
                        }
			elseif($hidden=="D"){
                                $this->noIndexNoFollow = 1;
				$this->MESSAGE="The profile ".$this->profile->getUSERNAME()." was deleted";
                                $this->PRIMARYMESSAGE = noProfileConstants::API_PROFILE_DELETED;
                        }
		}
		else if($request->getAttribute(ERROR)==3 || (!$this->loginData[PROFILEID] &&  (in_array($request->getAttribute(ERROR),array(5,6,7))))) //Called for login
		{
			$this->LOGIN_REQUIRED=1;
			$title="Please login to view profile.";
		}
		else if($request->getAttribute(ERROR)==4) //Same gender and privacy set
		{
			$this->MESSAGE="Sorry. You cannot view the detailed profile of this user as $his_her privacy  options prevent you from doing so.";
                        $this->PRIMARYMESSAGE = noProfileConstants::API_PROFILE_DELETED;
		}
		else if($request->getAttribute(ERROR)==5) //Privacy set to contacted peoples only
		{
			$this->MESSAGE=sfConfig::get("app_profile_no_contact");
                        $this->PRIMARYMESSAGE = noProfileConstants::PROFILE_NO_CONTACT;
		}
		else if($request->getAttribute(ERROR)==6) //Privacy set to filtered contact only
		{
			$this->MESSAGE=sfConfig::get("app_profile_filtered");
                        $this->PRIMARYMESSAGE = noProfileConstants::API_PROFILE_FILTERED;
		}
		else if($request->getAttribute(ERROR)==7) //Coming from album page and photo privacy filtered
		{
			$this->MESSAGE=sfConfig::get("app_profile_photo_privacy");
		}
		else if($request->getAttribute(ERROR)==8) //Main photo under screening
		{
			$this->MESSAGE=sfConfig::get("app_profile_photo_screen");
		}
		else if($request->getAttribute(ERROR)==9) //No photo
		{
			$this->MESSAGE=sfConfig::get("app_profile_photo_absent");
			$this->MESSAGE=str_replace("SITE_URL",sfConfig::get("app_site_url"),$this->MESSAGE);
			$this->MESSAGE=str_replace("PROFILECHECKSUM",$this->PROFILECHECKSUM,$this->MESSAGE);
			$this->MESSAGE=str_replace("USERNAME",$this->profile->getUSERNAME(),$this->MESSAGE);
			
			$title="Photo not uploaded";
		}
		else if($request->getAttribute(ERROR)==10)//Search Id Expire Case
		{
                    if(MobileCommon::isDesktop()){
                        $this->MESSAGE ="Results have changed since last time you searched. Kindly perform your search again.";
                        $this->PRIMARYMESSAGE = noProfileConstants::API_PROFILE_UNKNOWN;
                        $this->searchIdExpired = 1;
                    }
                    else
			$this->forward("static","searchIdExpire");
		}
    else if($request->getAttribute(ERROR) == ProfileEnums::IGNORED_BY_ME)
		{
			$this->MESSAGE = sfConfig::get("app_profile_ignored_by_me");
      $this->MESSAGE = str_replace("<USERNAME>", $this->PROFILENAME,$this->MESSAGE);
      $this->PRIMARYMESSAGE = noProfileConstants::IGNORED_BY_ME;
		}
    else if($request->getAttribute(ERROR) == ProfileEnums::IGNORED_BY_OTHER)
		{
			$this->MESSAGE = sfConfig::get("app_profile_ignored_by_other");
      $this->MESSAGE = str_replace("<USERNAME>", $this->PROFILENAME,$this->MESSAGE);
      $this->PRIMARYMESSAGE = noProfileConstants::PROFILE_IGNORED_BY_OTHER;
		}
		//$this->smartyAssign($request,$smarty,$data);
		$this->STYPE = $request->getParameter('stype');
		$response=sfContext::getInstance()->getResponse();
		
		if($title)
			$response->setTitle($title);
		else	
			$response->setTitle($this->MESSAGE);
		$this->SeoFooter($request);
		//$response->setTitle("Profile Page");
		ProfileCommon::old_smarty_assign($this);
		$this->setLayoutMobile();
                if(MobileCommon::isDesktop() && $request->getParameter("oldjspc")!=1)
                {
                    $this->callSearchFunctions($request);
                    $this->setTemplate("jspcNoProfile");
                }
		
		unset($this->smarty);
		
	}
	/**
	 * Seo footer only oon logout and staic url call
	 */
	function SeoFooter($request)
  {
		if(!$this->loginData[PROFILEID] && $request->getParameter("canurl"))
		{
				JsCommon::SeoFooter($this);
		}
  }
	/**
	 * change template layout for mobile
	 * 
	 */
	function setLayoutMobile()
	{
		
		if($this->isMobile)
		{
			sfContext::getInstance()->getResponse()->addMeta('robots',"noindex, nofollow", true,true);
			if(!$this->STYPE)
			{
					$this->STYPE=29;
			}			
            if(MobileCommon::isNewMobileSite())
            {
                $this->errorMsg = 1;
                $this->jsmsNoProfileView();
                $this->setTemplate("_mobViewProfile/jsmsViewProfile");
            }  
            else
            {
                $this->setTemplate("jsmb_noprofile");
            }
		}
	}
	/**
	 * show intro call object
	 */
	function showIntroCall($type)
	{
		//Error page , no need to show intro call option to him/her
		//ProfileCommon::addIntroCall($type,$this);
		
	}
	/**
	 * Breadcrump only if viewed profile exist.
	 */
	private function setNavigation($viewSimilar='')
	{
		if($this->profile!=null)
		{
			if($viewSimilar == 1)//added for similar profiles section on profile page
			{
				$navigation_link=navigation("CVS_NEW","",$this->profile->getUSERNAME());
			}
			else
                        {
				$navigation_link=navigation("DP","",$this->profile->getUSERNAME());	
			}	
		}
	}
	/**
	 * Handles common variables that need by the template
	 * 
	 */
	private function commonAssign()
	{
		//If viewed profile exist.
		if($this->profile->getPROFILEID())
		{
			$this->sim_contact=$this->profile->getPROFILEID();
			$this->viewed_gender=$this->profile->getGENDER();
			$this->PROFILECHATID=$this->profile->getPROFILEID();
			$this->PROFILENAME=$this->profile->getUSERNAME();
			$this->GENDER=$this->profile->getGENDER();
			if($this->GENDER=='M')
					$this->HIMHER="Him";
			else
					$this->HIMHER="Her";
					
			$this->other_profileid=$this->profile->getPROFILEID();
			$this->PROFILECHECKSUM_NEW=$this->PROFILECHECKSUM=JSCOMMON::createChecksumForProfile($this->profile->getPROFILEID());

		}
		
	}
	/**
	 * Update class variable profile if profileid is passed
	 * @param $profileid Integer profileid of user
	 * @throws jsException if blank $profileid is passed
	 */
	public function setViewed($profileid)
	{
		if($profileid)
		{
			$this->profile=Profile::getInstance();
			$this->profile->getDetail($profileid,"PROFILEID","","RAW");
		}
		else
				throw new jsException("Please pass on the profileid");
		
	}
	
	public function jsmsNoProfileView()
	{
		$request = sfContext::getInstance()->getRequest();
		$cGender = $this->profile->getGENDER();
		
		$this->noProfileIcon = 'female_nopro';
		if($cGender == 'M')
		{
			$this->noProfileIcon = 'male_nopro';
		}
		
		if($request->getAttribute(ERROR)==6) //Privacy set to filtered contact only
		{
			$this->noProfileIcon = 'pro_filtered';
		}
        $this->myPreView  = 0;
        if($this->loginProfile->getPROFILEID() && $this->profile->getPROFILEID() && $this->loginProfile->getPROFILEID() == $this->profile->getPROFILEID() )
        {
            $this->myPreView  = 1;        
        }
        $this->tupleId = $request->getParameter('tupleId');
        
        if($this->tupleId && intval($this->tupleId))
        {
            
            if($this->tupleId != 1)
                $this->preTupleId = $this->tupleId - 1;
            $this->nextTupleId = $this->tupleId + 1;
            //Remove tupleId from other_params
            if($this->other_params)
            {
                $arrOtherParams = explode('&',$this->other_params);
                foreach($arrOtherParams as $key=>$val)
                {
                    if(stristr($val,'tupleId')!=false)
                        unset($arrOtherParams[$key]);
                }
                $this->other_params = implode('&',$arrOtherParams);
            }
        }
        
        if((!$this->profile ||  !$this->profile->getPROFILEID() || !$this->profile->getUSERNAME()) && 
           (!$this->MESSAGE || !strlen($this->MESSAGE))
          )
        {
            $this->MESSAGE=sfConfig::get("app_profile_not_found");
        }
	}
    private function HandleNextPrevious()
	{
    	$request	= sfContext::getInstance()->getRequest();
        DetailActionLib::handleNextPreviousLogic($request,$this);
	}
    private function callSearchFunctions($request){
        //Search QSB
	$searchJspcObj=new SearchJSPC();
	$paramArr=array();
	$paramArr['actionObject']=$this;
	$request->setParameter("QuickSearchBand","1");
	$paramArr['request']=$request;
	$searchJspcObj->getQSBData($paramArr);
    }
    
}
