<?php
/**
 * noprofile handles all the error message that are passed/forward by
 * other actions. It handles no profile, filter, contacted, hidden, 
 * deleted errors and shows appropriate message to user.
 *
 * @package    
 * @subpackage 
 * @author     
 * @version    
 */
class noprofileApiAction extends sfAction
{
	
	//~ public $smarty;
	
	public function execute($request)
	{
		//Contains login credentials
		$this->loginData=$request->getAttribute("loginData");
		
		//~ $this->isMobile=MobileCommon::isMobile("JS_MOBILE");
			
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
			
			// Handle Next and Previous Link 
 			$this->HandleNextPrevious();
			
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
		}
		else if($request->getAttribute(ERROR)==2) //Not activated
		{
			
			if($hidden=="N" || $hidden=="U" || $hidden=="P")
				$this->MESSAGE=sfConfig::get("app_profile_screened");
			elseif($hidden=="H")
				$this->MESSAGE=sfConfig::get("app_profile_hidden");
			elseif($hidden=="D")
				$this->MESSAGE="The profile ".$this->profile->getUSERNAME()." was deleted";
		}
		else if($request->getAttribute(ERROR)==3 || (!$this->loginData[PROFILEID] &&  (in_array($request->getAttribute(ERROR),array(5,6,7))))) //Called for login
		{
			$this->LOGIN_REQUIRED=1;
			$title="Please login to view profile.";
		}
		else if($request->getAttribute(ERROR)==4) //Same gender and privacy set
		{
			$this->MESSAGE="Sorry. You cannot view the detailed profile of this user as $his_her privacy  options prevent you from doing so.";
		}
		else if($request->getAttribute(ERROR)==5) //Privacy set to contacted peoples only
		{
			$this->MESSAGE=sfConfig::get("app_profile_no_contact");
		}
		else if($request->getAttribute(ERROR)==6) //Privacy set to filtered contact only
		{
			$this->MESSAGE=sfConfig::get("app_profile_filtered");
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
		
		//For Api Custom Message
		$respObj = ApiResponseHandler::getInstance();
		
		$iErrorID = $request->getAttribute(ERROR);
		if($iErrorID == 1)
		{
			//$this->MESSAGE = sfConfig::get("app_api_profile_unknown");
			$this->MESSAGE = "Profile ID provided is invalid";
		}
		else if($iErrorID == 2)
		{
			$hidden=$this->profile->getACTIVATED();
			
			if($hidden=="N" || $hidden=="U" || $hidden=="P")
				$this->MESSAGE=sfConfig::get("app_profile_screened");
			elseif($hidden=="H")
				$this->MESSAGE=sfConfig::get("app_profile_hidden");
			elseif($hidden=="D")
				$this->MESSAGE =  "The profile with this ID is deleted";
		}
		else if($iErrorID == 3 || $this->LOGIN_REQUIRED == 1)
		{
			$this->MESSAGE = sfConfig::get("app_api_profile_requires_login");
		}
		else if($iErrorID == 4)
		{
			$this->MESSAGE = $this->MESSAGE; // As in desktop site
		}
		else if($iErrorID == 5)
		{
			$this->MESSAGE = sfConfig::get("app_api_profile_hidden");
		}
    else if($iErrorID == ProfileEnums::IGNORED_BY_ME)
		{
			$this->MESSAGE = sfConfig::get("app_profile_ignored_by_me");
      $this->MESSAGE = str_replace("<USERNAME>", $this->profile->getUSERNAME(),$this->MESSAGE);
		}
    else if($iErrorID == ProfileEnums::IGNORED_BY_OTHER)
		{
			$this->MESSAGE = sfConfig::get("app_profile_ignored_by_other");
      $this->MESSAGE = str_replace("<USERNAME>", $this->profile->getUSERNAME(),$this->MESSAGE);
		}
		else
		{
			$this->MESSAGE = "Your profile doesn’t match ".$this->profile->getUSERNAME()."’s filters, so the user's profile can't be shown to you.";
		}
		
		if($iErrorID != 10)
		{
			$arrOut = ResponseHandlerConfig::$NO_PROFILE_ERROR;
			$arrOut['message'] = (strlen($this->MESSAGE)!=0)? $this->MESSAGE : sfConfig::get("app_api_profile_unknown");
		}
		else if($iErrorID == 10)//Search ID Expire 
		{
			$arrOut = ResponseHandlerConfig::$SEARCH_EXPIRED_SEARCHID;
		}
		
		//Actual Offset
		$iOffset =-1;	
		if(is_numeric($this->actual_offset))
			$iOffset = $this->actual_offset + 1;
		
		$infoOut['about']['username'] = $this->TopUsername;		
		$infoOut['page_info']['page_offset'] = $iOffset;
		$infoOut['about']['gender'] = $gender;
		$infoOut['about']['loginRequired'] = $this->LOGIN_REQUIRED;
		
		$respObj->setHttpArray($arrOut);
		$respObj->setResponseBody($infoOut);
		$respObj->generateResponse();
		die;

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
	private function SetNextPreviousOffset()
	{
		$request=sfContext::getInstance()->getRequest();
		$val = $request->getParameter('actual_offset');
		
		if(isset($val))
		{
			$request->setParameter('actual_offset',($val - 1));
		}
	}
	/**
	 * Update class variable profile if profileid is passed
	 * @param $profileid Integer profileid of user
	 * @throws jsException if blank $profileid is passed
	 * @access public
	 */
	public function setViewed($profileid)
	{
		DetailActionLib::fillProfileData($profileid,$this);
	}
	
	/**
	 * HandleNextPrevious()
	 * 
	 * Handles Various Cases, Where we get profilechecksum from other services
	 * @param void
     * @return void
     * @access private 
	 */
	private function HandleNextPrevious()
	{
		$request	= sfContext::getInstance()->getRequest();
		$szContactID = $request->getParameter("contact_id");
		$iTotalRecord = $request->getParameter('total_rec');
		$iOffset = $request->getParameter('actual_offset');//Offset Range from 1 to TotalRecords
		if($request->getParameter('profilechecksum'))
			return;
		if(strlen($szContactID)!=0 && $this->loginProfile->getPROFILEID() && ($iOffset+1)>0 && ($iOffset+1)<=$iTotalRecord)
		{
			$objProfileDisplay = new profileDisplay;
			
			// Adding +1 in offset as ProfileDisplay ID starts from 1 to total rec
			$this->profilechecksum = $objProfileDisplay->getNextPreviousProfile($this->loginProfile,$szContactID,$iOffset + 1,$request->getParameter("stype"));
			
			// No need to Subtract -1, as we already did that in apidetailv1 action
			$this->actual_offset = $iOffset; 
			
			$this->stype=$request->getParameter("stype");
			$this->Sort=$request->getParameter("Sort");
			$this->actual_offset_real=$this->actual_offset;
			$this->total_rec=$request->getParameter("total_rec");
			
			//ProfileID
			$iProfileID = JsCommon::getProfileFromChecksum($this->profilechecksum);
			$this->next_prev_prof=$iProfileID;
			
			//Seting profile class for this profileid.
			if($this->next_prev_prof)
				$this->setViewed($this->next_prev_prof);
		}
		else
		{
			//Next Previous
			//$this->SetNextPreviousOffset();Offset Already adjusted in ApiDetail
			DetailActionLib::Show_Next_Previous($this);
		}
	}
}
