<?php

/**
 * profile actions.
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Nikhil dhiman
 * @version    SVN: $Id: actions.class.php 23810 2011-07-14 03:07:44 Nikhil dhiman $
 */
/**
 * DetailedAction class represents the presentation of viewer and viewed profile.<p></p>
 * 	
 *  
 * @author Nikhil dhiman
 */

class detailedAction extends sfAction
{
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	
	public $data;
	public $jprofile_result;
	public $smarty;
	public $loginData;
	public $from_viewprofile;
	public $jpartnerObj;
	public $filter;
	public $contact_status;
	public $contact_status_new;
	public $filter_prof;
	public $spammer;
	public $paid;
	public $contact_matchalert;
	public $visitoralert;
	public $contact_limit_message;
	public $contact_limit_reached;
	/**
	 * This Variable boolean value of search id expire status
	 * @access public
	 * @var boolean
	 */
	public $bFwdTo_SearchIDExpirePage = false;
	
	 /**
     * Automatically calls before the action to execute.
     *
     */
	public function preExecute()
	{
	  
	}
	
	/**
     * Handles Detailed profile of user, all validations, 
     * error message are handled in this.
     *@param $request contains sfWebrequest parameter send by symfony
     *
     */
	public function execute($request)
	{		
		$this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsProfilePageUrl);
		$this->suggAlgoNoOfResultsToBeShownAtATime = sfConfig::get('mod_profile_detailed_suggAlgoNoOfResultsToBeShownAtATime');
		
		//$LibObj = new JsLib_Profile_Detailed;
		global $smarty,$data;
		
		//Contains login credentials
		$this->loginData=$data=$request->getAttribute("loginData");
		//Contains logined Profile information;
		$this->loginProfile=LoggedInProfile::getInstance();
		$this->profile=Profile::getInstance("newjs_masterRep");
		$this->isMobile=MobileCommon::isMobile("JS_MOBILE");
		//Assinging smarty variable
		$this->smarty=$smarty;
                
                // VA Whitelisting
                //whiteListing of parameters
                DetailActionLib::whiteListParams($request);
                
		// Do Horscope Check
		DetailActionLib::DoHorscope_Check();

		// Calculate Next and Previous Link 
		$this->HandleNextPrevious();
        
                /** this section is created using singleton **/	
		if($this->loginProfile && $this->loginProfile->getPROFILEID())
			$viewProfileOptimization = viewProfileOptimization::getInstance($this->loginProfile->getPROFILEID(),$this->profile->getPROFILEID());
		//To set Breadcrumb on page.
		$fromViewSimilar = $_GET['fromViewSimilar']; //added for similar profiles section on profile page
		$this->setNavigation($fromViewSimilar);
		
		//Check for all error message, will forward to Noprofile Acton only if any error
		DetailActionLib::IsNoProfile($this,"fromDetailed");
		
		//Below this shows that viewed profile is always present
		//This function will be called immediately after checkViewed
		$this->commonVariables();
		
		//Update of feature profile.
		$this->featureProfileUpdate();
						
		//Check online status if user is logged in and on desktop channel
    if (MobileCommon::isDesktop() && $this->loginData[PROFILEID]) {
       $this->onlineStatus();
    }
	$nameOfUserObj = new NameOfUser();
	$showNameData = $nameOfUserObj->showNameToProfiles($this->loginProfile,array($this->profile));
	if($showNameData[$this->profile->getPROFILEID()]['SHOW']==true)
	{
		$this->nameOfUser = $showNameData[$this->profile->getPROFILEID()]['NAME'];
	}
	else
		$this->dontShowNameReason = $showNameData[$this->profile->getPROFILEID()]['REASON'];
    //Assings variables required in template, handling legacy.
		$this->smartyAssign();

		//Source tracking
		$this->SourceTracking($request);	
		
		//Horoscope checks for Only old mobile site
    //Showing contact engine
    if (MobileCommon::isOldMobileSite()) {
      $this->horoscopeAvailable(); 
    }
    
        $ceAction = $request->getParameter('performAction');
        if($ceAction=='accept')
        {
         $request->setParameter("internal", 1);   
         ProfileCommon::performContactEngineAction($request,'VDP');
        }
         $this->showContactEngine();
		//appPromotion
		if($request->getParameter("from_mailer"))
			$this->from_mailer=1;
		else
			$this->from_mailer=0;

		//matchAlertThursdayTracking
		if($request->getParameter("fromMatchAlertMailer"))
			$this->fromMatchAlertMailer=1;
		else
			$this->fromMatchAlertMailer=0;
				
		//Update Profile Data, like tables and count which are as follow
		// 	
		//Log View Table
		//Update Ntimes
		//Set Profile Pic and Album Count
		//Check Bookmarked or Ignore
		//Check Photo Request
		//Update Last Login Details
		//Contact Limit Reached
		//Viewd Contact log entry
		//Alter Seen Table
		//View mis update
		DetailActionLib::UpdateAndLog($this);
		
		//Matchalert logging
		$this->matchalertLog();				

		//Call now checks, called only if enabled
		$this->callNowFeature();
		
		//Set labels[profile/partner] on page.
		DetailActionLib::GetProfileData($this);
		
		//Sets title, description, keywords of page, should
		//always below setUserData
		$this->setMetaTags();
		
		if(!$this->loginData[PROFILEID])
			$this->setSeoLinks();
		//Color the label that matching dpp of each other.
		$this->setColorCode();
		
		//Checks for verification seal.
		$this->setVerificationSeal();
		
		//Sets register form or toll-free number
		$this->RegisterOrNumber();
		
		//Fseo footer dall
		$this->SeoFooter($request);
    
		//Assinging smarty variables to this variable to access them on template
		ProfileCommon::old_smarty_assign($this);
		//$this->obj=$this;
		$this->setLayoutMobile();
                if($request->getParameter("oldjspc") == null){
                  $this->setDesktopLayout($request);  
                }
		$zedo = $request->getAttribute("zedo");
		
		$zedo["zedo"]["tag"]["right_banner1"]["size"] = 19;
		$zedo["zedo"]["tag"]["right_banner1"]["id"] = 4;
		//$zedo["tag"]["right_banner1"]["size"] = 86;
		$zedo["zedo"]["tag"]["right_banner1"]["source"] = 3;
		$zedo["zedo"]["tag"]["right_banner1"]["network"] = 1;
		$zedo["zedo"]["tag"]["right_banner1"]["width"] = 270;
		$zedo["zedo"]["tag"]["right_banner1"]["height"] = 600;
		$request->setAttribute("zedo",$zedo);
		//Free memory.
		unset($this->smarty);
		//die("*");
	}
	/**
	*Source trakcing
	*/
	function SourceTracking($request)
	{
		$source=$request->getParameter("source");
		if($source)
		{
	                setcookie("JS_SOURCE",$source,time()+2592000,"/",$domain);
			$now = date("Y-m-d G:i:s");
                	$ip=CommonFunction::getIP();
	                $dbMisHits= new MIS_HITS();
			$pageName="viewprofile.php";
        	        $dbMisHits->insertRecord($source,$now,$pageName,$ip);
		}
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
	 * Show register page to few profiles or show numbers on contact engine 
	 */
	function RegisterOrNumber()
	{
		if($this->loginProfile->getPROFILEID()==null && !isset($_COOKIE['ISEARCH']))
		{
			$this->showContactDetail=1;
			$this->SHOW_CONTACT_TAB_EV=1;
			$this->userMtongue=$mtongue=$this->profile->getMTONGUE();
			
			//Tamil, Telugu, Malayalam, Kannada, Oriya, Bengali, Assamese, Hindi- MP
			$communityArr=array(31,3,17,16,25,6,5,19);
			if(in_array($mtongue,$communityArr))
			{
					$this->showRegisterPage=1;
					/* Code for Registartion Module */

					$curDate=date('Y', JSstrToTime('-6570 days')); // Finding 18 years back year
					for($i=$curDate;$i>=1939;$i--)
					$yearArray[]=$i;
					$this->yearArray=$yearArray;

					for($i=1;$i<=31;$i++)
					$dayArray[]=$i;
					$this->dayArray=$dayArray;
			}
			else
			{
					$number=array("08506896060","08506876060","08506936060","08506846060","08506816060");
					$random=rand(0,4);
					$this->showTollFree=1;
					$this->RANDOMNUMBER=$number[$random];
			}		
			
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
			$request = sfContext::getInstance()->getRequest();
			
			if(!$this->STYPE)
			{
					$this->STYPE="WO";
			}
			$this->responseTracking = urlencode($this->responseTracking);		
			 //JSB9 Mobile Tracking
			$this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobProfilePageUrl);
			switch($this->tabName)
			{
				case 'Send Reminder':
						$this->TO_DO="reminder";
						$this->ActionName="PreSendReminder";
						break;
				case 'Express Interest':
						$this->TO_DO="eoi";
						$this->ActionName="PreEoi";
						break;
				case 'Respond':
						$this->TO_DO="respond";
						$this->ActionName="PreAccept";
						break;
				case 'Send Message':
						$this->TO_DO="message";
						$this->ActionName="PreWrite";
						break;
			}	
			$this->PHOTODISPLAY=$this->profile->getPHOTO_DISPLAY();
            
			
            if(MobileCommon::isNewMobileSite())
            {	
					/*
					*checks if the page is hit from a mailer and if it is being opened in an iPhone
					*added code for deepLinking in IOS
					*/
  					if($request->getParameter("from_mailer"))
  					{
  						$deepLinkingObj = new deepLinking();
  						$resultValue = $deepLinkingObj->getDeepLinkingHeader($request);
  						$this->headerURLDeepLinking = $resultValue;  		
  					}
  					//$this->matchAlertTracking($request->getParameter("stype"));
  					$this->setJsmsViewProfileLayout();
  		    }  
            else
            {
                $this->setTemplate("jsmb_view");
            }
		}
	}
	private function setJsmsViewProfileLayout()
	{
		$request = sfContext::getInstance()->getRequest();
	
		//Call JSMS View 
		$this->profile->setNullValueMarker("");
		DetailActionLib::GetProfilePicForApi($this);
		$objDetailedDisplay = new JsmsView($this);
		$arrOutDisplay = array();
		$arrOutDisplay =  $objDetailedDisplay->getResponse();
		
		////////////////////////////////////////////////////////
		foreach($arrOutDisplay['family'] as $szKey=>$szVal)
		{
			if(strlen($szVal) === 0)
				$arrOutDisplay['family'][$szKey] = null;
		}
		$arrOutDisplay['pic']['pic_count'] = $this->ALBUM_CNT;
        $arrOutDisplay["buttonDetails"] = null;
        $this->stype=$this->getStype();
		$arrPass = array('stype'=>$this->getStype(),"responseTracking"=>$this->responseTracking,'page_source'=>"VDP");

		if($this->loginProfile->getPROFILEID() != $this->profile->getPROFILEID())
		{

			$arrPass['PHOTO'] = $arrOutDisplay['pic']['url'];
			$arrPass['IGNORED'] = $this->IGNORED;
			$arrPass['isBookmarked'] = $this->BOOKMARKED;
			$buttonObj = new ButtonResponse($this->loginProfile,$this->profile,$arrPass);
			$arrOutDisplay["buttonDetails"] = json_encode($buttonObj->getNewButtonArray($arrPass));
		}
		$arrOutDisplay["showTicks"] = $this->CODEDPP;
		$arrOutDisplay["selfProfileId"] = LoggedInProfile::getInstance()->getPROFILEID();
		//this part is added to ensure that even if toShowHoroscope is 'D', astro gets shown
        $arrOutDisplay["about"]["NO_ASTRO"] = $this->changeAstroViewCondition($arrOutDisplay["about"]["toShowHoroscope"],$arrOutDisplay["about"]["NO_ASTRO"]);
		//print_r($arrOutDisplay["buttonDetails"]);die;
		////////////////////////////////////////////////////////
		$this->profile->setNullValueMarker("");
		$this->arrOutDisplay = $arrOutDisplay;
		$this->selfUsername=LoggedInProfile::getInstance()->getPROFILEID() ? LoggedInProfile::getInstance()->getUSERNAME() : "";
				
		//Call CommunicationHistory And GunaScore Api
		$this->showComHistory = null;
		$this->gunaCallRequires = null;
		if($this->loginProfile->getPROFILEID())
		{
			$this->showComHistory 	= 1;
			$this->gunaCallRequires	= 1;
            
            if($this->profile->getGENDER()=='M')
				$this->szHisHer = "him";
			else
				$this->szHisHer = "her";
		}
        $this->myPreView  = 0;
        if($this->loginProfile->getPROFILEID() == $this->profile->getPROFILEID() )
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
            $arrOtherParams = explode('&',$this->other_params);
            foreach($arrOtherParams as $key=>$val)
            {
                if(stristr($val,'tupleId')!=false)
                    unset($arrOtherParams[$key]);
            }
            $this->other_params = implode('&',$arrOtherParams);
        }
        if(!$this->NAVIGATOR)
        {
            $this->NAVIGATOR = $request->getParameter('NAVIGATOR');
        }
        //print_r($this->arrOutDisplay);die;
		$this->setTemplate("_mobViewProfile/jsmsViewProfile");
	}
	/**
	 * Sets page meta tags like canonical url, title , description
	 * and keywords
	 */
	private function setMetaTags()
	{
		//http://www.jeevansathi.com/<bride>-<mother-tongue>-<religion>-<caste>-<username/userID>-profiles  
		$casteAllow=0;
		if(CommonUtility::CasteAllowed($this->profile->getRELIGION()))
			$casteAllow=1;

		//Canonical url
		$can_url=$this->MTONGUE."-".$this->religionSelf;
		if($casteAllow)
			$can_url.="-".$this->CASTE;
			
		//Title
		//strip tags check added to remove meta content in page title
		if($this->GOTHRA && strip_tags($this->GOTHRA)!="")
			$gotra=" - ".$this->GOTHRA;
		if($this->CITY_RES || $this->COUNTRY_RES)
		{
			$location=" - ";
			if($this->CITY_RES)
				$location=$location.$this->CITY_RES.", ";
			if($this->COUNTRY_RES)
				$location=$location.$this->COUNTRY_RES;
				
		}	
		$title=$this->MTONGUE;
		if($casteAllow)
			$title.=" - ".$this->CASTE;
			
		$title.=" - ".$this->religionSelf.$gotra;
		
		if($location)
			$title=$title.$location;
		$title=$title." - ".$this->AGE." - ".$this->profile->getUSERNAME();
		
		//Removal of extra - from location
		$location=ltrim($location," - ");
		//Meta description
		$desc="Looking for an ideal ".$this->MTONGUE;
		if($casteAllow)
			$desc.=" ".$this->CASTE;
		$desc.=" ".$this->religionSelf;
		
		if($this->profile->getGENDER()=="M")
		{
			$whois="groom";
		
			$title="Groom - ".$title;
			$desc=$desc." Groom in $location";
		}
		else
		{
			$whois="bride";
		
			$title="Bride - ".$title;
			$desc=$desc." Bride in $location";
		}
		$desc=$desc."? Your dream Life Partner is just a click away.";
		if(!$this->loginData)
			$desc=$desc." Log on to Jeevansathi.com Now!";
			
		$response=sfContext::getInstance()->getResponse();
		$title=htmlspecialchars_decode($title,ENT_QUOTES);
		$response->setTitle($title);

		$can_url=CommonUtility::CanonicalProfile($this->profile);
		$response->setCanonical(sfConfig::get("app_site_url")."/".$can_url);

		$desc=htmlspecialchars_decode($desc,ENT_QUOTES);
		$response->addMeta('description', $desc);
		
		$response->addVaryHttpHeader("User-Agent");
		$keyword=$this->MTONGUE." ".ucfirst($whois)."s, ";
		if($casteAllow)
			$keyword.=$this->CASTE." ".ucfirst($whois)."s, ";
		
		
		$keyword.=$location." ".ucfirst($whois)."s, ".ucfirst($whois).", ".ucfirst($whois)."s, ".$this->religionSelf." ".ucfirst($whois)."s, Life partner, find ".$whois."s, find $whois, $whois matchmaking, ".$this->OCCUPATION." ".($whois)."s, Jeevansathi.com, Indian matrimony, matrimony, matrimonial, matrimonial";
		$keyword=htmlspecialchars_decode($keyword,ENT_QUOTES);
		$response->addMeta("keyword",$keyword);
		
		}
	
	/**
	 * Sets page seo links for profile section
	 * @param
	 * 
	 */
	private function setSeoLinks()
	{
		$dbObj= new NEWJS_COMMUNITY_PAGES();
		
		$caste = $this->profile->getCASTE();
		$mtongue = $this->profile->getMTONGUE();
		$religion = $this->profile->getRELIGION();
		$city = "'".$this->profile->getCITY_RES()."'";
		$occupation = $this->profile->getOCCUPATION();
		$country = $this->profile->getCOUNTRY_RES();
		$gender = $this->profile->getGENDER();
		if($gender == 'M')
			$page_source = 'G';
		elseif($gender == 'F')
			$page_source = 'B';
		
		$linkArr = $dbObj->getLink($caste, $occupation, $religion, $mtongue, $city, $country);
		$bride_groom_link = $dbObj->getLink($caste='', $occupation='', $religion='', $mtongue, $city='', $country='',$page_source);
		
		if($linkArr)
		{
			foreach($linkArr as $key=>$linkUrl)
			{
				$link=$linkUrl["URL"];
				$type=$linkUrl["TYPE"];
				
				if($link)
				  $link="$link";
				if(strtoupper($type) == 'MTONGUE')
				  $profileLinkArr["MTNG_LINK"]=$link;
				else if(strtoupper($type) == 'OCCUPATION')
				  $profileLinkArr["OCC_LINK"]=$link;
				else if(strtoupper($type) == 'CITY')
				  $profileLinkArr["CITY_LINK"]=$link;
				else if(strtoupper($type) == 'RELIGION')
				  $profileLinkArr["REL_LINK"]=$link;
				else if(strtoupper($type) == 'CASTE')
				  $profileLinkArr["CASTE_LINK"]=$link;
				 else if(strtoupper($type) == 'STATE')
					$profileLinkArr["STATE_LINK"]=$link;
				else if(strtoupper($type) == 'COUNTRY')
					$profileLinkArr["COUNTRY_LINK"]=$link;
				
				unset($link);
				unset($type);
				
			}
		}
		
		$profileLinkArr["BRIDE_GROOM_LINK"]=$bride_groom_link[0][URL];
		$this->profileLinkArr=$profileLinkArr;
	}
	
	/**
	 * Update Photos count
	 * 
	 */
	private function setPhoto()
	{
		$login=0;
		
		if($this->loginProfile->getPROFILEID())
			$login=1;
		$return=ProfileCommon::getprofilePicnCnt($this->profile,$this->contact_status,$login);
		
		$this->PHOTO=$return[0];
		$this->ALBUM_CNT=$return[1];
		$this->stopAlbumView=$return[2];
		
	}
		
     /* 
      * Checks verification seal for viewed profile or not.
     *  set $VerificationSeal if present
     * 
     */
	
	private function  setVerificationSeal()
	{                
                //Verification Seal
                $verificationSealObj=new VerificationSealLib($this->profile);
                $this->verificationSeal=$verificationSealObj->getVerificationSeal();
	}
	/** 
	 * Sets last login date in required format of viewed profile.
	 */
	private function setLastLogin()
	{
		$this->OnlineMes=ProfileCommon::getLastLoginFormat($this->profile->getLAST_LOGIN_DT());
		
	}
	/**
	 * Colors the label of detailed profile and desired partner profile section.
	 *  
	 */ 
	private function setColorCode()
	{
		if($this->loginProfile->getPROFILEID()!=null)
		{
			//Getting partner details of viewer
			$jpartnerObj=ProfileCommon::getDpp($this->loginProfile->getPROFILEID());
		
			//Getting loginned profile desired partner data and setting object as well.
			$this->loginProfile->setJpartner($jpartnerObj);
			//Green label for detailed profile section of viewed profile.
			/*if($jpartnerObj!=null)
			{
					$this->CODEOWN=JsCommon::colorCode($this->profile,$this->loginProfile->getJpartner(),$this->casteLabel,$this->sectLabel);
			}*/
			//Green label for desired partner profile section of viewed profile.
			if($this->profile->getJpartner()!=null)
			{
				$this->CODEDPP=JsCommon::colorCode($this->loginProfile,$this->profile->getJpartner(),$this->casteLabel,$this->sectLabel);                                	
			}
                        
		}	
	}
	/**
	 * Sets the label of detailed profile and desired partner profile section.
	 */
	private function setUserData()
	{
		ProfileCommon::setPageInformation($this,$this->profile);					
	}
	/**
	 * Handling legacy code, to be used by legacy functions
	 * Variables that to be accessed by template
	 */
	private function commonVariables()
	{

		$request=$this->getRequest();
		
		//Creating jprofile_result, required by contact engine function
		if($this->profile->getPROFILEID()!=null)
			$this->jprofile_result[viewed]=$this->profile->convertObjectToArray();
		if($this->loginProfile->getPROFILEID()!=null){	
			$this->jprofile_result[viewer]=$this->loginProfile->convertObjectToArray();
		        $this->LOGGEDIN=1;
			global $jprofile_result;
			$jprofile_result=$this->jprofile_result;
		}
			
		if($this->loginProfile->getGENDER()==$this->profile->getGENDER())
			$this->SAMEGENDER=1;
			
		$clicksource=$request->getParameter("clicksource");
		if((substr($clicksource,0,10))=='matchalert')
			$frommatchalert='Y';
		else if($clicksource=='NRU_alert')
			$frommatchalert='T';
		else
			$frommatchalert='N';
			
		$this->frommatchalert=$frommatchalert;
		$JSTrackingObj = new JSResponseTracking();
		$this->responseTracking = $JSTrackingObj->getProfilePageTracking($request);
		
		if(CommonFunction::isPaid($this->profile->getSUBSCRIPTION()))
			$paid='Y';
		else
			$paid='N';
		$this->paid=$paid;
		$stype=$request->getParameter("stype");	
		if($clicksource=="matchalert1")
			$request->setAttribute("contact_matchalert",1);
		elseif($clicksource=="matchalert2")
			$this->contact_matchalert=2;
		elseif($clicksource=="visitoralert" or $stype="M15")
			$this->visitoralert="Y";
			
		//Used in setting header region
		$this->FROM_PROFILEPAGE=1;	
	}
	/**
	 * Handling smarty variables that are used by previous code,
	 * This function is made to accumulate the smarty code scattered
	 * in old script.
	 */
	private function smartyAssign()
	{
		ProfileCommon::smartyAssign($this,"detailed");
	}
	/**ISALBUM
	 * Fetch which profile to show when Next/Previous link is clicked by users
	 * Currently Next/Previous option is only available if coming from detailed/contact page.
	 */
	private function showNextPrev()
	{
		ProfileCommon::showNextPrev($this);
	}
	/**
	 * Handles callNow feature
	 */
	private function callNowFeature()
	{
		/*
		global $CALL_NOW;
		$request=$this->getRequest();
		
		if($CALL_NOW && $this->loginData)
		{
			$voip_profileid_selected= $this->jprofile_result['viewed']['PROFILEID'];
			$viewer_profileid   	= $this->jprofile_result['viewer']['PROFILEID'];

			// Check for logged in User and Paid Members Only
			if(CommonFunction::isPaid($this->jprofile_result['viewed']["SUBSCRIPTION"]))
			{
				$callAccessArr = callAccess($voip_profileid_selected);
				if($callAccessArr[$voip_profileid_selected] =='Y')
				{
					$this->smarty->assign("CALL_ACCESS",'1');
					if($request->getParameter("call_tab_sel]"))
					{
						$this->smarty->assign("CALL_TAB_SEL",1);
						recordCallnowHits('CALLNOW_CLICK');
					}
					$mypid=$this->loginData["PROFILEID"];
					ivrCallNow($viewer_profileid,$voip_profileid_selected);
					$myprofilechecksum = JSCOMMON::createChecksumForProfile($mypid);
					$this->smarty->assign("myprofilechecksum",$myprofilechecksum);
					$this->smarty->assign("REC_PROFILEID",$voip_profileid_selected);
				}
			}
		}
		*/
		
	}
	/**
	* handles stype logic
	*/
	private function getStype()
	{
		if($this->isMobile)
                {
                        if(!$this->STYPE && !$this->getRequest()->getParameter("stype") && !$this->stype)
                        {
                                        $this->stype="WO";
                        }
                }
		$stype=$this->getRequest()->getParameter("stype");
		if($this->stype)
			$stype=$this->stype;
		if($this->STYPE)
			$stype=$this->STYPE;
		return $stype;
	}
	/**
	 * Handles the display of contact engine[Express/contact/call now tab]
	 */
	private function showContactEngine()
	{
		$this->tabName="Express Interest";
		$this->defaultContactTab=0;
		if($this->loginProfile->getPROFILEID())
		{
			
			$this->contactObj = new Contacts($this->loginProfile, $this->profile);

			if($this->contactObj->getTYPE()!='N')
				$this->LOAD_CONTACT_HISTORY_TAB=1;
			$contactHandlerObj = new ContactHandler($this->loginProfile,$this->profile,"EOI",$this->contactObj,'',ContactHandler::PRE);
			//echo $this->responseTracking;die;
			$contactHandlerObj->setPageSource("VDP");
			sfContext::getInstance()->getRequest()->setParameter("stopEvaluetracking",CONTACT_ELEMENTS::EVALUE_STOP);
			$contactHandlerObj->setElement("PROFILECHECKSUM",JsCommon::createChecksumForProfile($this->profile->getPROFILEID()));
			$contactHandlerObj->setElement("STYPE",$this->getStype());
			$contactHandlerObj->setElement("PR_VIEW",$this->pr_view);
			$contactHandlerObj->setElement("CLICKSOURCE",$this->getRequest()->getParameter("clicksource"));
			$contactHandlerObj->setElement("COUNTLOGIC",1);
			$contactHandlerObj->setElement("RESPONSETRACKING",$this->responseTracking);
			
			$this->contactEngineObj=ContactFactory::event($contactHandlerObj);
			
			$contactHandlerObj=null;
			
			$contactHandlerObj = new ContactHandler($this->loginProfile,$this->profile,"INFO",$this->contactObj,'CONTACT_DETAIL',ContactHandler::PRE);
			$contactHandlerObj->setElement("PROFILECHECKSUM",JsCommon::createChecksumForProfile($this->profile->getPROFILEID()));
			
			$this->contactDetailObj=ContactFactory::event($contactHandlerObj);
			
			$this->tabTemplate();
			$this->tabName();
			$type=$this->contactObj->getTYPE();
			if($type=="I" || $type=="A")
				off_call_history();	
			if(!CommonFunction::isContactVerified($this->loginProfile))
			{
				$this->PH_UNVERIFIED_STATUS = 1;
				if(CommonUtility::InvalidLimitReached($this->loginProfile))
					$this->contactLimitMessage="Not Valid";	
			}
		}
		
	}
	private function tabName()
	{
		$array=array("I"=>"Respond","RN"=>"Express Interest","RI"=>"Send Reminder","A"=>"Send Message","D"=>"Accept","C"=>"","RC"=>"Respond","E"=>"","RE"=>"Express Interest","RA"=>"Send Message","RD"=>"");
		$mobarray=$array;
        $mobarray[D]="Accept";
        $mobarray[RC]="Accept";
//		$preActionUrlArray=array("I"=>"Accept","RN"=>"PreEoi","RI"=>"PreSendReminder","A"=>"PreWrite","D"=>"PreAccept","C"=>"","RC"=>"PreAccept","E"=>"","RE"=>"PreEoi","RA"=>"PreWrite","RD"=>"");
		$preActionUrlArray=array("I"=>"Accept","RN"=>"PostEOI","RI"=>"PostSendReminder","A"=>"PreWrite","D"=>"PostAccept","C"=>"","RC"=>"PostAccept","E"=>"","RE"=>"PostEOI","RA"=>"PreWrite","RD"=>"");
		$type=$this->contactObj->getTYPE();
		$user=$this->contactEngineObj->contactHandler->getContactInitiator();
		if($user==ContactHandler::SENDER)
		{
			$type="R".$type;
		}
		$substate=JsCommon::getProfileState($this->contactEngineObj->contactHandler->getViewer());		
		$this->defaultContactTab=$this->defaultTab($substate, $type);
		$this->tabName=$array[$type];
		if(MobileCommon::isMobile())
            $this->tabName=$mobarray[$type];
		$this->preActionUrl=$preActionUrlArray[$type];
	}
	private function tabTemplate()
	{
		$substate=$this->contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getSubState();
		$flag=$this->contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getAcceptanceFlag();

		$state=str_split(strtolower($substate));
		$ftoArray=array("c","d","e");
		$NeverFto=array("f","g","n");

		//if in E4 state and flag is T
		if($substate=="E4" && $flag=="T")
			$state[0]='d';

		if(in_array(strtolower($substate),$NeverFto) || $substate=="")
				$state[0]='e';
					
		if(CommonFunction::isEvalueMember($this->contactEngineObj->contactHandler->getViewed()->getSUBSCRIPTION()))
			$state[0]='n';		
		//If paid	
		if($this->contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getPaymentStates()->isPAID())
			$state[0]='p';

		if((in_array($state[0],$ftoArray) || $state[0]=='p') && $this->contactObj->getTYPE()=="N")
		{
			$this->tabTemplate="profile_tab_".$state[0];
		}
		if($this->loginProfile->getPROFILE_STATE()->getActivationState()->getINCOMPLETE()==Messages::YES)
			$this->tabTemplate="profile_tab_incomplete";
		
		if($this->loginProfile->getPROFILE_STATE()->getActivationState()->getINCOMPLETE()==Messages::NO && $state[0]!='p')
		{
			if(in_array($state[0],$ftoArray) && $this->contactObj->getTYPE()=="N")
				$pass=1;
			elseif(in_array($this->contactObj->getTYPE(),array("I","A")) && $state[0]=="c")
			{
				if($flag=="I" && $this->contactEngineObj->contactHandler->getContactInitiator()==ContactHandler::SENDER)
					$pass=1;
				elseif($flag=="T")
					$pass=1;
			}
			
		}
		if($this->loginProfile->getPROFILE_STATE()->getActivationState()->getINCOMPLETE()==Messages::YES|| $this->loginProfile->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED()==Messages::YES)
		{
			if($this->profile && $this->loginProfile)
			{
				$contacts_temp_obj = new NEWJS_CONTACTS_TEMP();
				if($contacts_temp_obj->getTempContacts($this->loginProfile->getPROFILEID(),$this->profile->getPROFILEID()))
				{
					$this->tabTemplate="";
					$this->tabTemplateMobile="1";
					$pass=0;
				}
				
			}
		}
		if($pass)
		{
			$this->rowTemplate="profile_row_".$state[0];
			$this->lastrowTemplate="profile_last_".$state[0];
		}
		
		
	}
	/**
	 * Viewed profie is bookmarked or not.
	 * Presence of viewer and viewed is necessary
	 */
	private function isBookmarkedOrIgnore()
	{
		
		$sender=$this->loginProfile->getPROFILEID();
		$receiver=$this->profile->getPROFILEID();
		if($sender && $receiver)
		{
			$bookmark= new NEWJS_BOOKMARKS();
			if($bookmark->isBookmarked($sender,$receiver))
				$this->BOOKMARKED=1;
			$ignore=new IgnoredProfiles("newjs_master");
			if($ignore->ifIgnored($sender,$receiver,"byMe"))
					$this->IGNORED=1;
		}
	}
	/**
	* Viewed contact log 
	*
	*/
	private function viewedContactLog()
	{
		if($this->contactEngineObj && $this->loginProfile && $this->profile)
		{
			$who=$this->contactEngineObj->contactHandler->getContactInitiator();
			$type=$this->contactEngineObj->contactHandler->getContactObj()->getTYPE();
			//Insert into view contact log
			if($type=='I' && $who!=ContactHandler::SENDER)
			{
				$this->viewerDb = JsDbSharding::getShardNo($this->loginProfile->getPROFILEID(),'');
				$evlObj=new NEWJS_EOI_VIEWED_LOG($this->viewerDb);
				$evlObj->insert($this->loginProfile->getPROFILEID(),$this->profile->getPROFILEID());
				$this->viewedDb = JsDbSharding::getShardNo($this->profile->getPROFILEID(),'');
				if($this->viewedDb!=$this->viewerDb)
				{
					$evlObj=new NEWJS_EOI_VIEWED_LOG($this->viewedDb);
					$evlObj->insert($this->loginProfile->getPROFILEID(),$this->profile->getPROFILEID());
				}
			}

                        //
		}
	}
	/**
	 * Will update Seen field of tables CONTACT,PHOTO_REQUEST,HOROSCOPE
	 * etc.
	 * Will help in removing new tag from profileid.
	 */
	private function alterSeenTable()
	{
        file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/alterdetailActionUncalledFunc.txt",var_export($_SERVER,true)."\n",FILE_APPEND);
		//This will help in assingning global variables in alter_Seen_table.
		$fromSym=1;
		$request=$this->getRequest();
		if($this->contactEngineObj)
		{
			$who=$this->contactEngineObj->contactHandler->getContactInitiator();
			if($this->contactEngineObj->contactHandler->getContactObj()->getSEEN()==Contacts::NOTSEEN)
			{
				$currentFlag = $this->contactEngineObj->contactHandler->getContactType();
				$profileMemcacheServiceViewerObj = new ProfileMemcacheService($this->contactEngineObj->contactHandler->getViewer());
				switch($currentFlag)
				{
					case ContactHandler::INITIATED:
						if($who==ContactHandler::RECEIVER){
							if($this->contactEngineObj->contactHandler->getContactObj()->getFILTERED() =="Y")
								$profileMemcacheServiceViewerObj->update("FILTERED_NEW",-1);
							else
								$profileMemcacheServiceViewerObj->update("AWAITING_RESPONSE_NEW",-1);
						}
						break;
					case ContactHandler::ACCEPT:
						if($who==ContactHandler::SENDER)
							$profileMemcacheServiceViewerObj->update("ACC_ME_NEW",-1);
						break;
					case ContactHandler::DECLINE:
						if($who==ContactHandler::SENDER)
							$profileMemcacheServiceViewerObj->update("DEC_ME_NEW",-1);
						break;
				}
				$profileMemcacheServiceViewerObj->updateMemcache();
			}
			$type=$this->contactEngineObj->contactHandler->getContactObj()->getTYPE();
			if(($who==ContactHandler::SENDER && ($type=='A' OR $type=='D')) || ($who!=ContactHandler::SENDER && ($type=='I' || $type=='E')))
				$updatecontact=1;
		}
		
		//Helps in updating CONTACTS table, to handle cases where only 1
		// shards is updated
		$force_query=$request->getParameter("force_query");
		
		$profileid=$this->profile->getPROFILEID();
		
		if($this->loginProfile->getPROFILEID() && $this->loginProfile->getPROFILEID()!=$this->profile->getPROFILEID() && $this->loginProfile->getGENDER()!=$this->profile->getGENDER())
		{
			$mypid=$this->loginProfile->getPROFILEID();
			include(sfConfig::get("sf_web_dir")."/profile/alter_seen_table.php");
		}
	}
	/**
	 * Update no. of times profiles viewed.
	 * Helps in scoring algorithm
	 */
	private function updateNtimes()
	{
		
		include(sfConfig::get("sf_web_dir")."/profile/ntimes_function.php");
		
		if($this->profile->getPROFILEID()){
                    $jpNtimesObj = new NEWJS_JP_NTIMES();
                    $jpNtimesObj->updateProfileViews($this->profile->getPROFILEID());
                }	
	}
	
	/**
	 * Function handles matchalert logging
	 */
	private function matchalertLog()
	{
		$request=$this->getRequest();
		$clicksource=$request->getParameter("clicksource");
		
		if($clicksource=='matchalert1')
		{
			
			$profileid=$this->profile->getPROFILEID();
			$nops=$request->getParameter("npos");
			$logic_used=$request->getParameter("logic_used");
			$MatchAlertlike=$request->getParameter("MatchAlertlike");
			include_once(sfConfig::get("sf_web_dir")."/profile/track_matchalert.php");
			
			$this->frommatchalert="&frommatchalert=1&";
			TrackMatchViewed_MA($profileid,$npos,$logic_used);

			//LIKE/DISLIKE TRACKING : PHASE2
			$receiver=$this->loginProfile->getPROFILEID();
			$match=$profileid;
			if($MatchAlertlike)
			MatchLikedOrNor($MatchAlertlike,$receiver,$match);
			//LIKE/DISLIKE TRACKING
		}
		
	}
	/**
	 * Horoscope is uploaded by viewed user or not
	 */
	private function horoscopeAvailable()
	{
		if(check_astro_details($this->profile->getPROFILEID(),"Y"))
		{					
			if($this->profile->getSHOW_HOROSCOPE()=="N" || $this->profile->getSHOW_HOROSCOPE()==""||$this->profile->getSHOW_HOROSCOPE()=="D")
			{
				$this->HOROSCOPE="N";
				
				$this->HIDE_HORO=1;
			}			
			else 
			{
				
				$this->HIDE_HORO=0;				
				$this->HOROSCOPE="Y";
			}
		}
		else
		{
			$this->HOROSCOPE="N";
			$this->REQUESTHOROSOCOPE="Y";
			$this->HIDE_HORO=0;	
			$chkprofilechecksum = JSCOMMON::createChecksumForProfile($this->loginProfile->getPROFILEID());
			
			/*if($this->is_already_requested($this->profile->getPROFILEID(),$chkprofilechecksum))
				$this->REQUESTED=1;
			else
				$this->REQUESTED =0;*/

		}
	}
	/**
	 * Update online status of viewed user 
	 */
	public function onlineStatus()
	{
		//$this->gtalkOnline();
		$this->userOnline();
	}
	/**
	 * Whether user online on gtalk or not. //NOT BEING USED ANYMORE. Therefore, commented the call above
	 */
	public function gtalkOnline()
	{
		if(JsCommon::gtalkOnline($this->profile->getPROFILEID()))
		{
			$this->GTALK_ONLINE=1;
			$this->profileID=$this->profile->getPROFILEID();
		}
	}
	/**
	 * Whether user online on jeevansathi
	 */
	public function UserOnline()
	{
		
		if(JsCommon::UserOnline($this->profile->getPROFILEID()))
		{
			$this->CHATID=$this->profile->getPROFILEID();
			$this->ISONLINE="1";
		}
	}
	/**
	 * Update view_Log and view_log_trigger table, to make viewed knows who
	 * views his/her profile and also to maintain history
	 */
	private function entryViewLog()
	{
		
		if($this->loginProfile->getPROFILEID())
		{	
			
			$privacy=$this->loginProfile->getPRIVACY();
			$vlt=new VIEW_LOG_TRIGGER();
			//Privacy is not C for login user 
			if($privacy!='C' && $this->loginProfile->getPROFILEID()!=$this->profile->getPROFILEID() && $this->loginProfile->getGENDER()!=$this->profile->getGENDER())
			{
				$vlt->updateViewTrigger($this->loginProfile->getPROFILEID(),$this->profile->getPROFILEID());
			}

			$vlt->updateViewLog($this->loginProfile->getPROFILEID(),$this->profile->getPROFILEID());
		}
	}

	/**
	 * Breadcrump only if viewed profile exist.
	 */
	private function setNavigation($fromViewSimilar='')//the above arguments added for similar profiles section on profile page
	{
		global $isMobile; 
        $request	= sfContext::getInstance()->getRequest();
 		if(MobileCommon::isMobile()) 
 			$isMobile=1;
		if($this->profile!=null)
		{
            $bIsFromJsmsECP = strlen($request->getParameter('similarOf'))!=0?true:false;
            
			if($fromViewSimilar == 1)
				$navigation_link=navigation("DP_NEW","",$this->profile->getUSERNAME());	
			elseif($fromViewSimilar == 2)
				$navigation_link=navigation("CVS_NEW","",$this->profile->getUSERNAME());		
			else
			{
				if(!$_GET['NAVIGATOR'])	
				{
					$navigation_link=navigation("CVS_NEW","",$this->profile->getUSERNAME());		
				}
				else
				{
					$navigation_link=navigation("DP","",$this->profile->getUSERNAME());		
				}
			}
		}
	}
	/**
	 * Update class variable profile if profileid is passed
	 * @param $profileid Integer profileid of user
	 * @throws jsException if blank $profileid is passed
	 */
	public function setViewed($profileid)
	{
		DetailActionLib::fillProfileData($profileid,$this);	
	}
	/**
	 * Update feature profile table if coming 
	 * from featured search
	 */
	private function featureProfileUpdate()
	{
		$stype=$this->getRequest()->getParameter("stype");
		if($stype=='W')
		{
			$fpv=new FEATURED_PROFILE_VIEW("newjs_master");
			$fpv->update();
		}
	}
	
	
	

	/**
	 * Update to horoscope,astro_details,ASTRO_PULLING_REQUEST on 
	 * basis of some condition.
	 */
	private function horoscope_check()
	{
		$request=$this->getRequest();
		$data=$this->loginData;
		global $type;
		if($data["PROFILEID"])
		{
			if($request->getParameter("from_horo_layer") || $request->getParameter("from_registration"))
			{
				SendMail::send_email('esha.jain@jeevansathi.com'," in p profile horoscope page", "profil horos", "profhor@jeevansathi.com");
			}
		}	
	}
		
	private function defaultTab($state,$type)
	{
		if($type == 'RD'|| $type == 'E' || $type=='C')
			return 1;
		else return 0;
		//JSI-544
	/*	$dtab = array();
		
		$dtab['C1']['RN']= 1;
		$dtab['C1']['RI'] = 1;
		$dtab['C1']['RA'] = 1;
		$dtab['C1']['A'] = 1;
		$dtab['C1']['D']= 1;
		
		$dtab['C2']['RN']= 1;
		$dtab['C2']['RI'] = 1;
		$dtab['C2']['RA'] = 1;
		$dtab['C2']['A']= 1;
		$dtab['C2']['D']= 1;
		
		$dtab['C3']['RI'] = 1;
		$dtab['C3']['RA'] = 1;
		$dtab['C3']['A']= 1;
		$dtab['C3']['D']= 1;		
		
		$dtab['D1']['RI'] = 1;
		$dtab['D1']['RA']= 1;
		$dtab['D1']['A']= 1;
				
		$dtab['D2']['RI'] = 1;
		$dtab['D2']['RA']= 1;
		$dtab['D2']['A']= 1;		
		
		$dtab['D3']['RI'] = 1;
		$dtab['D3']['RA']= 1;
		$dtab['D3']['A']= 1;		
		
		$dtab['D4']['RI'] = 1;
		$dtab['D4']['RA']= 1;
		$dtab['D4']['A']= 1;		
		
		$dtab['E1']['RI']= 1;
		$dtab['E1']['RA']= 1;
		$dtab['E1']['A'] = 1;
		
		$dtab['E2']['RI']= 1;
		$dtab['E2']['RA']= 1;
		$dtab['E2']['A'] = 1;
		
		$dtab['F']['RI']= 1;
		$dtab['F']['RA']= 1;
		$dtab['F']['A'] = 1;
		
		$dtab['G']['RI']= 1;
		$dtab['G']['RA']= 1;
		$dtab['G']['A'] = 1;
		
		
		$dtab['E3']['RI'] = 1;
		$dtab['E3']['A'] = 1;		
		
		$dtab['E4']['RI']= 1;
		
		$dtab['E5']['RI']= 1;		
		
		$dtab['P']['A']= 1;
		$dtab['P']['RI'] = 1;
		$dtab['P']['RA'] = 1;

		$dtab['IU']['RN']= 1;
		$dtab['IU']['RI'] = 1;
		
		return $dtab[$state][$type];
	*/	 
	}
    
    private function HandleNextPrevious()
	{
    	$request	= sfContext::getInstance()->getRequest();
        DetailActionLib::handleNextPreviousLogic($request,$this);		
	}
    /*
     * function to fetch data for profile form api
     * @param 
     * @return 
     */
    private function setDesktopLayout($request){
        
        if(MobileCommon::isDesktop()){
          if(!$this->STYPE)
            {
                            $this->STYPE="WO";
            }
            $this->responseTracking = urlencode($this->responseTracking);
            //$this->matchAlertTracking($request->getParameter("stype"));

             //JSB9 Tracking
            $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsProfilePageUrl);
            switch($this->tabName)
            {
                    case 'Send Reminder':
                                    $this->TO_DO="reminder";
                                    $this->ActionName="PreSendReminder";
                                    break;
                    case 'Express Interest':
                                    $this->TO_DO="eoi";
                                    $this->ActionName="PreEoi";
                                    break;
                    case 'Respond':
                                    $this->TO_DO="respond";
                                    $this->ActionName="PreAccept";
                                    break;
                    case 'Send Message':
                                    $this->TO_DO="message";
                                    $this->ActionName="PreWrite";
                                    break;
            }	
        $this->PHOTODISPLAY=$this->profile->getPHOTO_DISPLAY();
        //change link in breadcrumb
        $mtongueLink=FieldMap::getFieldLabel("community_small",$this->profile->getMTONGUE());
		$this->loggedInEmail=$this->loginProfile->getEMAIL();
		$this->senderProfileId=$this->loginProfile->getPROFILEID();
        //Call Desktop View 
        $this->profile->setNullValueMarker("");

        DetailActionLib::GetProfilePicForApi($this);

        $objDetailedDisplay = new desktopView($this);

        $this->arrOutDisplay = array();
        $this->arrOutDisplay =  $objDetailedDisplay->getResponse();
        $arrOutDisplay["buttonDetails"] = null;
         
        $arrPass = array('stype'=>$this->STYPE,"responseTracking"=>$this->responseTracking,'page_source'=>"VDP",'isIgnored'=>$this->arrOutDisplay['page_info']['is_ignored'],'isBookmarked'=>$this->BOOKMARKED,'PHOTO'=>$this->arrOutDisplay['pic']);
        $arrPass["USERNAME"]= $this->profile->getUSERNAME();
        $arrPass["OTHER_PROFILEID"] = $this->profile->getPROFILEID();

		if($this->loginProfile && $this->loginProfile->getPROFILEID() !="" && $this->loginProfile->getPROFILEID() != $this->profile->getPROFILEID())
		{//print_r("arrOutDisplay['pic']['url']");die;
				$buttonObj = new ButtonResponse($this->loginProfile,$this->profile,$arrPass);

				$this->arrOutDisplay["button_details"] = $buttonObj->getButtonArray(array('PHOTO'=>$this->arrOutDisplay['pic']['url'],"IGNORED"=>$this->IGNORED));
		}
		else
		{
			$arrPass["channel"] = MobileCommon::getChannel();
			$arrPass['source'] = "VDP";
			$buttonObj = new ButtonResponse();
			$this->arrOutDisplay["button_details"] = $buttonObj->getLogoutButtonArray($arrPass);
		}
                $this->searchId= $request->getParameter('searchid');
		$this->finalResponse=json_encode($this->arrOutDisplay);
                $this->myProfileChecksum = JSCOMMON::createChecksumForProfile($this->loginProfile->getPROFILEID());
                $this->arrOutDisplay["other_profileid"] = $arrPass["OTHER_PROFILEID"];
        
        //This part was added to allow idfy to go Online percentage wise
        $this->arrOutDisplay["showIdfy"] = CommonFunction::getFlagForIdfy($this->senderProfileId);         	
        //this part is added to ensure that even if toShowHoroscope is 'D', astro gets shown
        $this->arrOutDisplay["about"]["NO_ASTRO"] = $this->changeAstroViewCondition($this->arrOutDisplay["about"]["toShowHoroscope"],$this->arrOutDisplay["about"]["NO_ASTRO"]);            
        $this->setTemplate("_jspcViewProfile/jspcViewProfile");
      }
    }

    private function matchAlertTracking($stype)
    {
    	if(in_array($stype,ProfileEnums::$matchAlertMailerStypeArr))
    	{
    		$channel = MobileCommon::getChannel();
    		$memCacheObj = JsMemcache::getInstance();
    		if($this->fromMatchAlertMailer)
    		{        			
    			$key = "MatchAlertTracking_".$channel."_".date("Y-m-d");   
    		}
    		else
    		{
    			$key = "MatchAlertTrackingNotFromMailer_".$channel."_".date("Y-m-d");
    		}
    		$memCacheObj->incrCount($key);
    		unset($memCacheObj);
    	}
    	  				
    }

    public function changeAstroViewCondition($toShowHoroscope,$noAstro)
    {
    	if($toShowHoroscope == "D" && $noAstro == 1)
    	{
    		$noAstro = 0;
    	}
    	return $noAstro;
    }

}
