<?php
/**
 * ftoActions class
 *
 * @author Ankit Garg <ankit.garg@jeevansathi.com>
 * @created Tue Dec 15 09:16:45 IST 2012
 * @package jeevansathi
 * @subpackage fto
 */
/**
 * ftoActions class
 *
 * <p>
 * This class executes offer pages.
 * </p>
 *
 * @extends sfActions 
 */
class ftoActions extends sfActions
{
  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   * @access public
   */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }
  /**
   * Executes offer page
   *
   * <p>
   * This function displays offer pages based on the substate of logged in profile.
   * </p>
   *
   * @param $request sfWebRequest
   * @uses NumberToWords::convertNumber() of NumberToWords class to convert numbers into words
   * @uses PictureArray class to get photos
   * @uses LoggedInProfile class to get Profile related values.
   * @uses JsCommon::isMobile() function to detect mobile device
   * @uses CommonFunction class for creating checksum from profileid and vice-versa
   * @uses FtoOfferLib::getProfileDetails() to get details of other profile
   * @uses FtoOfferLib::getProfileDrafts() to get preset EOI messages
   * @uses FtoOfferLib::getSuggestedProfiles() to get suggested profiles for loggedin user.
   * @access public
   */
  public function executeOffer(sfWebRequest $request)
  {
    
    $this->loginProfile = LoggedInProfile::getInstance();
    $this->loginProfile->getDetail("", "", "*");
    $this->userChecksum = CommonFunction::createChecksumForProfile($this->loginProfile->getPROFILEID());
    $this->loginUsername = $this->loginProfile->getUSERNAME();
    $this->loginGender = $this->loginProfile->getGENDER();
    $this->state = $this->loginProfile->getPROFILE_STATE()->getFTOStates()->getState();
    $this->subState = $this->loginProfile->getPROFILE_STATE()->getFTOStates()->getSubState();
    $this->expiry = $this->loginProfile->getPROFILE_STATE()->getFTOStates()->getExpiryDate();

    /* Trac #1595 */
    $this->inboundAcceptLimit = NumberToWords::convertNumber($this->loginProfile->getPROFILE_STATE()->getFTOStates()->getInboundAcceptLimit());

    /** Photo Logic Starts **/
    $profileObjArray[0] = $this->loginProfile;
    $photoObj = new PictureArray($profileObjArray);
    $photoObjArray = $photoObj->getProfilePhoto();

    if ($photoObjArray[$this->loginProfile->getPROFILEID()]) {
      $this->loginThumbnailPicUrl = $photoObjArray[$this->loginProfile->getPROFILEID()]->getThumbailUrl();
    }
    else {
      $noPhotoUrl = '';
      if ($this->loginGender === "M") {
        $noPhotoUrl = PictureService::getRequestOrNoPhotoUrl("noPhoto", "ThumbailUrl", "M");
      }
      else {
        $noPhotoUrl = PictureService::getRequestOrNoPhotoUrl("noPhoto", "ThumbailUrl", "F");
      }
      $this->loginThumbnailPicUrl = $noPhotoUrl;
    }
    /** Photo Logic Ends **/

    $this->expiryDate = date('c', JSstrToTime($this->expiry)); // Get ISO format date
    $this->currentDate = date('c');
    $this->day = date("j", JSstrToTime($this->expiryDate));
    $this->month = date("F", JSstrToTime($this->expiryDate));
    $this->year = date("Y", JSstrToTime($this->expiryDate));
    $this->superscript = date("S", JSstrToTime($this->expiryDate));
    $this->profilechecksum = trim($request->getParameter("profilechecksum")); // get profilechecksum from request object.

    $fromReferer = (trim(stripslashes($request->getParameter("fromReferer"))) == 0) ? 0 : 1; // if this is set and FROMPOST is not set, the user will be redirected to page from which he/she landed on offer page.
    $fromPost = (trim(stripslashes($request->getParameter("FROMPOST"))) == 1) ? 1 : 0; // if set, redirect to Detailed profile page of other profile.

    $this->showBackLink = (($fromReferer === 0) && ($fromPost === 0)) ? 0 : 1; // Whether back link needs to be shown?

    if ($fromPost === 1) {
      if (($this->profilechecksum !== "multi") && ($this->profilechecksum !== "")) {
        $this->refererUrl = sfConfig::get('app_site_url') . "/P/viewprofile.php?profilechecksum=$this->profilechecksum";
      }
      else {
        $this->refererUrl = sfConfig::get('app_site_url') . "/P/mainmenu.php";
      }
    }
    else if ($fromReferer === 1) {
      $this->refererUrl = $request->getReferer(); // get referer url
    }

    // For displaying conditional sections on offer pages.
    $this->noContact = 0; // Will display profile snippet of other profile if set to 1
    $this->showSuggestedMatches = 0; // Will display suggested matches section if no profile checksum is present or multi profiles selected.

    $this->mobile = MobileCommon::isMobile("JS_MOBILE"); // Detects mobile device


	//Call pixel code in case user coming on fto offer page after mobile registration
	if($request->getAttribute('from_mob_reg')){
		if(trim($request->getParameter('groupname'))) 
			$this->pixelcode=CommonFunction::fetchPixelcode($request->getParameter('groupname'),$request->getParameter('adnetwork1'));
	}
    if ("Y" === $this->loginProfile->getPROFILE_STATE()->getActivationState()->getINCOMPLETE()) {
		if ($this->mobile) {
        $this->redirect(sfConfig::get('app_site_url'));
      }
      else {
	if(FTOLiveFlags::IS_FTO_LIVE || $this->subState!='F')
		$this->setTemplate("offerI"); // Set offer page corresponding to incomplete profile.
	else
		$this->redirect(sfConfig::get("app_site_url"));
      }
    }

    else if($this->state == FTOStateTypes::FTO_ELIGIBLE) { // C states
      
      if ($this->mobile) {
        $this->setTemplate("mobileOfferC"); // Set offer page corresponding to profile in C1/C2/C3 state (mobile device)
      }
      else {
        $this->setTemplate("offerC"); // Set offer page corresponding to profile in C1/C2/C3 state
      }
    }

    else if($this->state == FTOStateTypes::FTO_ACTIVE) { // D states
      
      if (($this->profilechecksum !== "multi") && ($this->profilechecksum !== "") && ($this->profilechecksum !== $this->userChecksum)) { // profilechecksum is present
        
        $this->profileid = JsCommon::getProfileFromChecksum($this->profilechecksum);
        
        list($this->profileObject, $this->profileObj) = FtoOfferLib::getProfileDetails($this->profileid); // get other profile details
        
        $contactObj = new Contacts($this->loginProfile, $this->profileObject); // get contact details between two profiles
        
        $this->otherThumbnailPicUrl = $this->profileObj["thumbnailPicUrl"];
      }
      else {
        $this->otherThumbnailPicUrl = "NA"; // Inside template, NA is detected and stock images are displayed based on login gender.
      }

      $this->draft = stripslashes(FtoOfferLib::getProfileDrafts($this->loginProfile)); // get preset eoi message

      if($this->profileid && $contactObj->getType() === ContactHandler::NOCONTACT) // No contact
      {
        if ($this->mobile) { // if it's a mobile device.
          
          $this->suggestedProfiles = FtoOfferLib::getSuggestedProfiles($this->loginProfile->getPROFILEID()); // Get suggested profiles
          
          if ($this->suggestedProfiles !== -1) {
            $this->showSuggestedMatches = 1; // show suggested matches only.
          }
        }
        
        $this->noContact = 1; // Show profile snippet.
      }
      else {
        
        $this->suggestedProfiles = FtoOfferLib::getSuggestedProfiles($this->loginProfile->getPROFILEID()); // Get suggested profiles.

        if ($this->suggestedProfiles !== -1) {
          $this->showSuggestedMatches = 1; // Show suggested matches
        }
      }
      
      if($this->subState == "D1") {
        
        if ($this->mobile) {
          $this->setTemplate("mobileOfferD1"); // Set template corresponding to D1 state (mobile device)
        }
        else {
          $this->setTemplate("offerD1"); // Set template corresponding to D1 state
        }
      }
      
      else if($this->subState == "D2" || $this->subState == "D3" || $this->subState == "D4") // for states in D2/D3/D4
      {
        
        if ($this->mobile) {
          $this->setTemplate("mobileOfferD2"); // Set template corresponding to D2/D3/D4 (mobile device)
        }
        else {
          $this->setTemplate("offerD2"); // Set template corresponding to D2/D3/D4
        }
      }
    }

    else if($this->state == FTOStateTypes::FTO_EXPIRED) { // For profiles in E states
      
      if ($this->subState == "E1" || $this->subState == "E2") { // for profiles in E1/E2 states
        
        if ($this->mobile) {
          $this->setTemplate("mobileOfferE"); // Set template corresponding to E1/E2 (mobile device)
        }
        else {
          $this->setTemplate("offerE"); // Set template corresponding to E1/E2
        }
      }
      
      else if ($this->subState == "E4") { // for state in E4 show offer pages corresponding to D2/D3/D4 states
        
        if (($this->profilechecksum !== "multi") && ($this->profilechecksum !== "") && ($this->profilechecksum !== $this->userChecksum)) {
          
          $this->profileid = JsCommon::getProfileFromChecksum($this->profilechecksum);
          list($this->profileObject, $this->profileObj) = FtoOfferLib::getProfileDetails($this->profileid);
          $contactObj = new Contacts($this->loginProfile, $this->profileObject);
          $this->otherThumbnailPicUrl = $this->profileObj["thumbnailPicUrl"];
        }
        else {
          $this->otherThumbnailPicUrl = "NA";
        }

        $this->draft = stripslashes(FtoOfferLib::getProfileDrafts($this->loginProfile)); // get EOI message

        if($this->profileid && $contactObj->getType() === ContactHandler::NOCONTACT)//No contact
        {
          if ($this->mobile) {
            
            $this->suggestedProfiles = FtoOfferLib::getSuggestedProfiles($this->loginProfile->getPROFILEID());
            if ($this->suggestedProfiles !== -1) {
              $this->showSuggestedMatches = 1;
            }
          }
          $this->noContact = 1; // Display profile snippet of other profile
        }
        else {
          
          $this->suggestedProfiles = FtoOfferLib::getSuggestedProfiles($this->loginProfile->getPROFILEID());

          if ($this->suggestedProfiles !== -1) {
            $this->showSuggestedMatches = 1; // Display suggested matches
          }
        }

        if ($this->mobile) {
          $this->setTemplate("mobileOfferD2"); // Set template of D2 state (mobile device)
        }
        else {
          $this->setTemplate("offerD2"); // Set template of D2 state
        }
      }
      else if ($this->subState == "E3" || $this->subState == "E5") { // For profiles in E3/E5 state, redirect to home page of loggedin profile.
        $this->redirect(sfConfig::get("app_site_url"));
      }
    }

    else if($this->state == FTOStateTypes::NEVER_EXPOSED) { // For F/G states, redirect to home page of loggedin profile
      $this->redirect(sfConfig::get("app_site_url"));
    }

    else {
      $this->redirect(sfConfig::get("app_site_url")); // For any other case, redirect to home page of loggedin profile
    }
  }
} // end of executeOffer
