<?php
/**
 * profile actions.
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Jaiswal
 * @version    SVN: $Id: editAction.class.php  $
 */
class editAction extends sfAction {
	public $data;
	public $smarty;
	public $loginData;
	public $from_viewprofile;
	public $jpartnerObj;
	public $filter;
	public $filter_prof;
	public $spammer;
	public $paid;
    
	
	public function preExecute()
	{
		
	}
	/**
	 * Executes index action
	 *
	 * @param sfRequest $request A request object
	 */
	public function execute($request) {
		//Contains login credentials
		global $smarty, $data;
		$this->loginData = $data = $request->getAttribute("loginData");
		 //Jsb9 page load time tracking edit Page Mobile
        $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobEditPageUrl);
		//Contains loggedin Profile information;
		$this->loginProfile = LoggedInProfile::getInstance();
    if($this->loginProfile->getAGE()== "")
      $this->loginProfile->getDetail(); 
                $gotItBandObj = new GotItBand($this->loginProfile->getPROFILEID());
                $this->showGotItBand = $gotItBandObj->showBand(GotItBand::$PROFILE,$this->loginProfile->getENTRY_DT());
                $this->GotItBandPage = GotItBand::$PROFILE;
                $this->GotItBandMessage = GotItBand::$educationPROFILE;
		$this->loginProfile->setNullValueMarker("-");
		//Assign user name
		$nameObj= new NameOfUser;
                $nameData = $nameObj->getNameData($this->loginProfile->getPROFILEID());
                if(!empty($nameData))
                        $this->Name=$nameData[$this->loginProfile->getPROFILEID()]["NAME"];
                
		if((!Flag::isFlagSet("name",$this->loginProfile->getSCREENING()))&& ($this->Name != '') ){
			$this->Name .= "<br/><span class=\"green lf\" style=\"font-size:11px;\">Under screening</span>";
		}
        /////////////////////////////// Profile Completion Score --------------------

		$this->loginProfile->setNullValueMarker("");
		
		$cScoreObject = ProfileCompletionFactory::getInstance(null,$this->loginProfile,null);
		$this->iPCS = $cScoreObject->getProfileCompletionScore();
		$noOfMsg = 4;
		$this->arrMsgDetails = $cScoreObject->GetIncompleteDetails($noOfMsg);
		$this->arrLinkDetails = $cScoreObject->GetLink();
		$this->loginProfile->setNullValueMarker("-");
                
                if($request->getParameter('fromCALHoro') == 1)
			$this->fromCALHoro = 1;
		
		 if($request->getParameter('fromCALAlternate') == 1)
			$this->fromCALAlternate = 1;

		///////////////////////////////
        $this->EditWhatNew = $request->getParameter("EditWhatNew");
        $this->EditWhatNew = $this->changeEditWhatNew();
    if(MobileCommon::isDesktop() &&  !$request->getParameter("oldjspc") && $request->getParameter("oldjspc") !== 1){
      $this->setJspcLayout();
      return;
    } 
		$this->USERNAME = $this->loginProfile->getUSERNAME();
		$this->TopUsername = $this->loginProfile->getUSERNAME();
		$len = strlen($this->TopUsername);
		if ($len > 16) $this->TopUsername = substr($this->TopUsername, 0, 13) . "...";
		//Get Photo
		$pictureServiceObj = new PictureService($this->loginProfile);
		$profile_pic_object = $pictureServiceObj->getProfilePic();
		$this->NO_OF_PHOTOS=$pictureServiceObj->getUserUploadedPictureCount();
		if ($profile_pic_object != NULL) if(!$this->PHOTO = $profile_pic_object->getProfilePicUrl())$this->PHOTO=$profile_pic_object->getMainPicUrl();
		$this->GENDER = $this->loginProfile->getGENDER();
		//rocketFuel Pixel code
		if($request->getParameter('flag')=="INCOMP")
		{
			$dbSource = new MIS_SOURCE();
			$resource = $dbSource->getSourceFields("GROUPNAME", $this->loginProfile->getSOURCE());
			if($resource["GROUPNAME"]=="RocketFuel")
			{
				$this->pixelcode = PixelCode::fetchRocketFuelCode("regPage3");
			}
		}
		//end of rocketfuel code
                
		if(MobileCommon::isMobile())
		{
			//for non screened no photos case
			$this->noScreenPhoto=$pictureServiceObj->isProfilePhotoPresent(1);
			//Pixel code to run only when coming from mobile registration page 4 
			if($request->getParameter('fromReg')==1)
            {
                        //If coming directly from registration, used for google pixel code
				if (trim($request->getParameter('groupname'))) {
					$this->pixelcode = PixelCode::fetchPixelcode($request->getParameter('groupname'), $request->getParameter('adnetwork1'),$this->loginProfile);
				}
            }
			//Getting partner details of viewer
			$screeningMessage = "<br/></td></tr><tr> <td><span class=\"green lf\" style=\"font-size:11px;\">Under screening</span>";
			$this->loginProfile->setScreeningMessage($screeningMessage);
			$jpartnerObj= $this->getJPartner();		
			//Getting loginned profile desired partner data and setting object as well.
			$this->loginProfile->setJpartner($jpartnerObj);
			$this->profile=$this->loginProfile;
			ProfileCommon::setPageInformation($this,$this->loginProfile);
			
			$this->PERSON_SELF=1;
			$this->PROFILENAME=$this->USERNAME;
			if($request->getParameter("from_mailer"))
				$this->from_mailer=1;
			else
				$this->from_mailer=0;
			//Profile Pic 
			$login=0;
			
			//checksum
			$this->PROFILECHECKSUM_NEW=$this->PROFILECHECKSUM=JSCOMMON::createChecksumForProfile($this->profile->getPROFILEID());
			$this->HAVEPHOTO=$this->loginProfile->getHAVEPHOTO();
			if($this->loginProfile->getPROFILEID())
				$login=1;
			//Pixel code to run only when coming from mobile registration page 5
			if($login)
			{
				//If coming directly from registration, used for google pixel code
				if (trim($request->getParameter('groupname'))) {
						$this->pixelcode = PixelCode::fetchPixelcode($request->getParameter('groupname'), $request->getParameter('adnetwork1'),$this->loginProfile);
				}   
			}	
			/*$return=ProfileCommon::getprofilePicnCnt($this->profile,$this->contact_status,$login);
			$this->PHOTO=$return[0];
			$this->ALBUM_CNT=$return[1];
			$this->stopAlbumView=$return[2];*/
			$this->PHOTODISPLAY=$this->profile->getPHOTO_DISPLAY();
			$this->photoUploadSupported = BrowserCheck::checkPhotoUploadSupport();
			//MatchAlert Tracking////////////////////
			ProfileCommon::matchAlertTrackingForDPP($request,$this->loginProfile,"INSERT",MatchAlert_DPP_Tracking::WAP_VIEW);
			//////////////////////////////////////////
			if(!MobileCommon::isNewMobileSite())
				$this->setTemplate("jsmb_view");
			else
				$this->forward("profile","myprofilemob");
		}
		else{
		$screeningMessage = "<br><span class=\"green lf\" style=\"font-size:11px;\">Under screening</span>";
		$this->loginProfile->setScreeningMessage($screeningMessage);
		$profileId = $this->loginProfile->getPROFILEID();
		//Smarty assing
		$this->smarty = $smarty;
		//Get Caste Label
		$this->casteLabel = JsCommon::getCasteLabel($this->loginProfile);
		$this->sectLabel = JsCommon::getSectLabel($this->loginProfile);
		$this->religionSelf = $this->loginProfile->getDecoratedReligion();
		$religion = $this->loginProfile->getReligion();
		
		
		//To include google api in layout.tpl set editPage slot
		$response = $this->getResponse();
		$response->setSlot("editPage", 1);
		//Get Decorated Details of Profile
		$profileSections = new ProfileSections($this->loginProfile,1);
		$this->alternateContacts = $this->loginProfile->getExtendedContacts();
		//				var_dump($this->alternateContacts);die;
		$this->lifeAttrArray = $profileSections->getLifeAttr();
		$this->Hobbies = $profileSections->getHobbies();
		$this->RELATION = $this->loginProfile->getRELATION();
		
		$this->MSGR_CHANNEL = $this->loginProfile->getMESSENGER_CHANNEL();
		$this->SHOW_MSGR_CHANNEL = strpos($this->loginProfile->getMESSENGER_ID(), "@") ? 0 : 1;
		$this->AstroKundaliArr = $profileSections->getAstroKundali();
		$this->AstroKundaliArr["City of Birth"]=str_replace("Delhi Paharganj,","",$this->AstroKundaliArr["City of Birth"]);
		//Education and Occupation Section
		$this->educationAndOccArr = $profileSections->getEducationAndOcc();
		$this->familyArr = $profileSections->getFamilyDetails();
		$this->EditWhatNew = $request->getParameter("EditWhatNew");
		if($this->EditWhatNew=="incompletProfile" && $request->getParameter("mailer"))
		{
			if(IncompleteLib::isProfileIncomplete($this->loginProfile)==0)
			$this->EditWhatNew="";
		}
        
		$this->nextLayer = $request->getParameter("nextLayer");
		$this->DupEmail=$request->getParameter("DupEmail");
		$this->invalid_email=$request->getParameter("invalid_email");
		$this->INFOLEN = strlen($this->loginProfile->getYOURINFO());
		$this->ReligionAndEth = $profileSections->getRelgionAndEthnicity($this->casteLabel, $this->sectLabel);
		$this->isEdit = 1;
		//For incomplete profile open layers
		$this->after_login = $request->getParameter("after_login");
		if (!check_astro_details($profileId, "Y")) if (get_horoscope($profileId)) $this->HOROSCOPE = "Y";
		else $this->HOROSCOPE = "N";
		else $this->HOROSCOPE = "Y";
		//If from Incomplete mailer
		//Get Decorated Details End
		//Profile Status
		//~ $this->profilePercent = profile_percent($profileId);
		$mod_date = substr($this->loginProfile->getMOD_DT(), 0, 10);
		$mydateArr = explode("-", $mod_date);
		$this->profileModDate = my_format_date($mydateArr[2], $mydateArr[1], $mydateArr[0], 1);
		include_once (sfConfig::get("sf_web_dir") . "/profile/ntimes_function.php");
		$this->profileViews = ntimes_count($profileId, "SELECT");
		$this->profilechecksum=$request->getAttribute("profilechecksum");
		//Assign banners
		//for showing intermediate layer. If called after login do not open intermediale layer
		if (!$request->getParameter('CALL_ME')) $this->oldFlag = $request->getParameter('oldFlag');
		//If profile get completed after edit call tracking code
			include_once (sfConfig::get("sf_web_dir") . "/profile/functions_edit_profile.php");
		if ($request->getParameter("tracking")) {
			track_completeness($this->loginProfile->getSOURCE(), $profileId, $this->USERNAME,$this->loginProfile->getDecoratedCity(),$this->loginProfile->getDecoratedGender(),$this->loginProfile->getAGE());
		}
		$this->PHOTO_REQUEST_CNT=get_photo_request_count($profileId);
		$this->INCOMPLETE = $this->loginProfile->getINCOMPLETE();
	  	$entry_time=JSstrToTime($this->loginProfile->getENTRY_DT());			
		$now=time();
		$age_of_profile=($now-$entry_time)/(60*60*24);
		//FTO Related communication Start
		$this->FtoState=$this->loginProfile->getProfile_State()->getFTOStates()->getSubState();
		if(!in_array($this->FtoState,array('C1','C2','C3','D1','D2','D3','D4','E4')))
			$this->NO_FTO=1;
		if(!$this->NO_FTO){
		if($this->FtoState=='D1' || $this->FtoState=='D2' || $this->FtoState=='D3' || $this->FtoState=='D4'|| $this->FtoState=='E4'){
			$edit_layer_fto=new EditOnFtoContactConfirmation($this->loginProfile);
			$this->LAYER_URL=$edit_layer_fto->getLinkToShowHref();
			$this->LAYER_TEXT=$edit_layer_fto->getLinkToShowText();
			$profileMemcacheServiceObj=new ProfileMemcacheService($this->loginProfile);
			if($this->FtoState =='D1'){
				$this->INTR_REC=$profileMemcacheServiceObj->get("AWAITING_RESPONSE")+$profileMemcacheServiceObj->get("FILTERED")+$profileMemcacheServiceObj->get("ACC_BY_ME")+$profileMemcacheServiceObj->get("DEC_BY_ME");
			}
			else{
				$this->INTR_SENT=$profileMemcacheServiceObj->get("NOT_REP") + $profileMemcacheServiceObj->get("ACC_ME") + $profileMemcacheServiceObj->get("DEC_ME");
				$this->ACCPT_REC=$profileMemcacheServiceObj->get("ACC_ME");
			}
		}
		}

		//FTO Related communication End
		if($age_of_profile>7)
			$this->OLDER_WITH_NO_PHOTO=1;
		ProfileCommon::old_smarty_assign($this);
	}
	
	
	//echo $this->getLayout();
	//echo $this->getTemplate();die;
	}
	
	function getJPartner() {
		return DetailActionLib::getJPartnerEdit($this);
	}
	
  private function setJspcLayout(){
    
    $this->editApiResponse = $this->getExistingUserData();
    $this->jsonEditResp = json_encode($this->editApiResponse);
    //Name of User
    $nameObj= new NameOfUser;
    $nameData = $nameObj->getNameData($this->loginProfile->getPROFILEID());
    $this->name = null;
    if(!empty($nameData))
        $this->name = $nameData[$this->loginProfile->getPROFILEID()]["NAME"];

    //Call Desktop View 
    $nullMarker = ApiViewConstants::JSPC_NULL_VALUE_MARKER;
    $this->notFilledInText = $nullMarker ;
    $this->loginProfile->setNullValueMarker($nullMarker);
    ApiViewConstants::setUserDefinedNullValueMarker($nullMarker);
    
    $this->profile=$this->loginProfile;
    $jparnterObj = DetailActionLib::getJPartnerEdit($this);
    $this->profile->setJpartner($jparnterObj);
        
    DetailActionLib::GetProfilePicForApi($this);
    $objDetailedDisplay = new desktopView($this);
    $objDetailedDisplay->setResponseForEditView('Y');
    $this->arrOutDisplay = array();
    $this->arrOutDisplay =  $objDetailedDisplay->getResponse();
    $this->arrOutDisplay['pic']['photo_display']=$this->profile->getPHOTO_DISPLAY();
    ApiViewConstants::setUserDefinedNullValueMarker(null);
    $this->loginProfile->setNullValueMarker("-");

    $this->loggedInEmail=$this->profile->getEMAIL();
    $this->username=$this->profile->getUSERNAME();
    $this->js_UniqueID = $this->profile->getPROFILEID();
    list($BIRTH_YR, $BIRTH_MON, $BIRTH_DAY) = explode("-", $this->profile->getDTOFBIRTH());
    $this->BIRTH_YR = $BIRTH_YR;
    $this->BIRTH_DAY = $BIRTH_DAY;
    $this->BIRTH_MON = $BIRTH_MON;
    $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jspcEditProfileUrl);

    $this->setTemplate("_jspcEdit/jspcEditProfile");
  }
  
  /*
  * function to fetch data already filled for a user from API
  * @param - request object
  * @return - returns an array with dpp data
  */
 private function getExistingUserData(){
   ob_start();
   $nullValueMarker = $this->loginProfile->getNullValueMarker();
   $this->loginProfile->setNullValueMarker("");
   $this->request->setParameter("sectionFlag","all");
   $this->request->setParameter("internal",1);
   $fieldValues = sfContext::getInstance()->getController()->getPresentationFor("profile","ApiEditV1");
   $editData = (array)(json_decode(ob_get_contents(),true));
   ob_end_clean();
   $this->loginProfile->setNullValueMarker($nullValueMarker);
   $arrOut = array();
   foreach($editData as $keySection=>$arrSectionArray){
     $arrOut[$keySection] = array();
     if(is_array($arrSectionArray)){
       foreach($arrSectionArray as $key=>$val){
//       if($val['key'] == "HEIGHT"){
//         
//         $val['label_val'] = htmlspecialchars($val['label_val'],ENT_QUOTES,UTF-8,false);
//         $val['label_val'] = str_replace("&#039;","\'",$val['label_val']);
//         $val['label_val'] = str_replace("&quot;",'"',$val['label_val']);
//         $editData[$keySection][$key]['label_val'] = $val['label_val'];
//         
//       }
        $arrOut[$keySection][$val['key']] = $val;
      }
     }
   }
//   $this->jsonEditResp = json_encode($editData);
//   $this->jsonEditResp = str_replace('"',"\'",$this->jsonEditResp);
   unset($editData);   
   return $arrOut;
 }
 
 private function changeEditWhatNew(){
     switch($this->EditWhatNew){
         case "EduOcc":
             $edit = "career";
             break;
         case "FamilyDetails":
             $edit = "family";
             break;
         case "RelEthnic":
             $edit = "RelEthnic";
             break;
         case "AstroData":
             $edit = "horoscope";
             break;
         case "LifeStyle":
             $edit = "lifestyle";
             break;
         case "Interests":
             $edit = "likes";
             break;
         case "PMF":
             $edit = "about";
             break;
         case "Basic":
             $edit = "basic";
             break;
         default:
             $edit = $this->EditWhatNew;
             break;
     }
     return $edit;
 }
}
?>
