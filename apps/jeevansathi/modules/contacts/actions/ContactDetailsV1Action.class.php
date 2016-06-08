<?php
/**
 * contacts actions.
 *
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ContactDetailsV1Action extends sfAction
{
	/**
	 * Executes index action
	 *
	 * @param sfRequest $request A	 request object
	 */
	function execute($request)
	{
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		$apiObj                  = ApiResponseHandler::getInstance();
		if ($request->getParameter("actionName")=="contactDetails")
		{
			$inputValidateObj->validateContactActionData($request);
			$output = $inputValidateObj->getResponse();
			if($output["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
			{
				$this->loginData    = $request->getAttribute("loginData");
				//Contains logined Profile information;
				$this->loginProfile = LoggedInProfile::getInstance();
				$this->loginProfile->getDetail($this->loginData["PROFILEID"], "PROFILEID");
				
				if ($this->loginProfile->getPROFILEID()) {
					$this->userProfile = $request->getParameter('profilechecksum');
					if ($this->userProfile) {
						
						$this->Profile = new Profile();
						$profileid     = JsCommon::getProfileFromChecksum($this->userProfile);
						$this->Profile->getDetail($profileid, "PROFILEID");
						$this->contactObj = new Contacts($this->loginProfile, $this->Profile);
					}
					$this->contactHandlerObj = new ContactHandler($this->loginProfile, $this->Profile, "INFO", $this->contactObj, 'CONTACT_DETAIL', ContactHandler::PRE);
					$this->contactEngineObj  = ContactFactory::event($this->contactHandlerObj);
					$responseArray           = $this->getContactDetailsArray($request);
				}
			}
		}
		if (is_array($responseArray)) {
			$apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$apiObj->setResponseBody($responseArray);
			$apiObj->generateResponse();
		}
		else
		{
			if(is_array($output))
				$apiObj->setHttpArray($output);
			else
				$apiObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiObj->generateResponse();
		}
		die;
	}
	private function getContactDetailsArray($request)
	{
		$gender        = $this->Profile->getGENDER();
		$heshe         = ($gender == 'M') ? "he" : "she";
		$himher        = ($gender == 'M') ? "him" : "her";
		$hisher        = ($gender == 'M') ? "his" : "her";
		$pictureServiceObj=new PictureService($this->Profile);
		$profilePicObj = $pictureServiceObj->getProfilePic();
		if($profilePicObj)
			$thumbNail = $profilePicObj->getThumbailUrl();
		if(!$thumbNail)
			$thumbNail = PictureService::getRequestOrNoPhotoUrl('noPhoto','ThumbailUrl',$this->Profile->getGENDER());
		$thumbNail = PictureFunctions::mapUrlToMessageInfoArr($thumbNail,'ThumbailUrl',1);
		$priArr = $this->contactEngineObj->contactHandler->getPrivilegeObj()->getPrivilegeArray();
		$memHandlerObj = new MembershipHandler();
		$data2 = $memHandlerObj->fetchHamburgerMessage($request);
		$MembershipMessage = $data2['hamburger_message']['top']; 
		if ($priArr[0]["CONTACT_DETAIL"]["VISIBILITY"] == "Y" && !$this->contactEngineObj->errorHandlerObj->getErrorMessage()) {
			$responseArray                       = $this->getContactDetailsInArray($this->contactEngineObj);
			$source=CommonFunction::getViewContactDetailFlag($this->contactEngineObj->contactHandler);
			if($this->contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus()=="FREE")
			{
				if($this->contactEngineObj->getComponent()->contactDetailsObj->getEvalueLimitUser()==CONTACT_ELEMENTS::EVALUE_STOP)
				{
					unset($responseArray);
					$responseArray["contactdetailmsg"]       = "Upgrade your membership to view phone/email of ".$this->contactHandlerObj->getViewed()->getUSERNAME()." (and other members)";
					$responseArray["footerbutton"]["label"]  = "View Membership Plans";
					$responseArray["footerbutton"]["value"] = "";
					$responseArray["footerbutton"]["action"] = "MEMBERSHIP";
					$responseArray["footerbutton"]["text"] = $MembershipMessage;
					$responseArray["contact1"]["value"]      = "***********";
					$responseArray["contact1"]["label"]      = "Phone No.";
					$responseArray["contact1"]["action"]     = null;
					$responseArray["contact4"]["value"]      = "*******@*****.com";
					$responseArray["contact4"]["label"]      = "Email";
					$responseArray["contact4"]["action"]     = null;
					VCDTracking::insertTracking($this->contactHandlerObj);
				}
				else
				{
					if(MobileCommon::isApp()=="A" && $this->contactEngineObj->getComponent()->contactDetailsObj->getEvalueLimitUser()==CONTACT_ELEMENTS::EVALUE_PCS)
					{
						unset($responseArray);					
						$responseArray["errmsglabel"] 			= $this->contactHandlerObj->getViewed()->getUSERNAME()." has an eValue plan and has made Phone/Email visible.\n\n But to view ".$this->contactHandlerObj->getViewed()->getUSERNAME()."'s Phone/Email, your profile should be at least ".CONTACT_ELEMENTS::PCS_CHECK_VALUE."% complete.\n\n Please add more information to your profile.";
							$responseArray["headerLabel"]            = "Complete your profile";
							$responseArray["errMsgIconId"]           = "21";
						VCDTracking::insertYesNoTracking($this->contactHandlerObj,'N');
						
					}

					else
					{

						$responseArray["footerbutton"]["label"]  = "View Membership Plans";
						$responseArray["footerbutton"]["value"] = "";
						$responseArray["footerbutton"]["action"] = "MEMBERSHIP";
						$responseArray["footerbutton"]["text"] = $MembershipMessage;
						$responseArray["contactdetailmsg"] = $this->contactHandlerObj->getViewed()->getUSERNAME()." has an eValue plan and has made contact details visible. Upgrade to eValue to make your phone/email visible to all matching profiles";
						VCDTracking::insertYesNoTracking($this->contactHandlerObj,'Y');
						
					}
				}
			}
			elseif ($this->contactObj->getTYPE() != "A") {
				if ($this->Profile->getPROFILE_STATE()->getPaymentStates()->getEVALUE()) {
					VCDTracking::insertYesNoTracking($this->contactHandlerObj,'Y');
						
					if ($this->loginProfile->getPROFILE_STATE()->getPaymentStates()->isPAID()) {
						$responseArray["contactdetailmsg"] = "There would be no deduction in number of contacts you can view as " . $this->contactEngineObj->getComponent()->genderPronoun . " is an <b>eValue</b> Member.";
					} else {
						$responseArray["contactdetailmsg"] = "You can View Contact Details as " . $this->contactEngineObj->getComponent()->genderPronoun . " is an <b>eValue</b> Member.";
					}
				}
			}
			$responseArray["headerThumbnailURL"] = $thumbNail;
			$responseArray["headerLabel"]        = $this->contactHandlerObj->getViewed()->getUSERNAME();
		} else {
			if ($this->contactObj->getTYPE() == "C" || $this->contactObj->getTYPE() == "D" || $this->contactObj->getTYPE() == "E")
			{
				$responseArray["headerLabel"] = "Contact Not Visible";
				$responseArray["errMsgIconId"] = IdToAppImagesMapping::NOT_VISIBLE;
				if($this->contactObj->getSenderObj()->getPROFILEID() == $this->loginProfile->getPROFILEID())
				{
					if($this->contactObj->getTYPE() == "E")
						$responseArray["errMsgLabel"]  = "You cannot see contact details as you have cancelled the interest sent to $himher";
					elseif($this->contactObj->getTYPE() == "D")
						$responseArray["errMsgLabel"]  = "You cannot see contact details as $heshe has declined your interest";
					elseif($this->contactObj->getTYPE() == "C")
						$responseArray["errMsgLabel"]  = "You cannot see contact details as you have cancelled the interest sent to $himher";
				}
				else
				{
					if($this->contactObj->getTYPE() == "E")
						$responseArray["errMsgLabel"]  = "You cannot see contact details $heshe cancelled her interest";
					elseif($this->contactObj->getTYPE() == "D")
						$responseArray["errMsgLabel"]  = "You cannot see contact details as you have declined $hisher interest";
					elseif($this->contactObj->getTYPE() == "C")
						$responseArray["errMsgLabel"]  = "You cannot see contact details as you have cancelled the interest sent to $himher";
				}
				VCDTracking::insertYesNoTracking($this->contactHandlerObj,'N');
						
			}
			elseif ($this->contactEngineObj->errorHandlerObj->getErrorMessage()) {
				
				$errorArr = $this->contactEngineObj->errorHandlerObj->getErrorType();
				if ($errorArr["SAMEGENDER"] == 2) {
					$responseArray["errMsgLabel"]  = "You cannot see contact details of a person of the same gender";
					$responseArray["errMsgIconId"] = "17";
					$responseArray["headerLabel"]  = "Gender is incompatible";
					VCDTracking::insertYesNoTracking($this->contactHandlerObj,'N');
						
				}
				elseif ($errorArr["PHONE_NOT_VERIFIED"] == 2) {
					$responseArray["headerLabel"]            = "Phone Verification Complusory";
					$responseArray["errMsgLabel"]            = "It is complusory to verify your number on jeevansathi.com or you will not able to contact others. you only have to give a missed call ";
					$responseArray["errMsgIconId"]           = "15";
					$responseArray["footerButton"]["label"]  = "Verify your number";
					$responseArray["footerButton"]["value"]  = "";
					$responseArray["footerButton"]["action"] = "PHONEVERIFICATION";
					VCDTracking::insertYesNoTracking($this->contactHandlerObj,'N');
						
				}
				 elseif ($errorArr["FILTERED"] == 2) {
					$responseArray["errMsgLabel"]  = "You cannot see the contact details of this profile as the profile has filtered you.";
					$responseArray["errMsgIconId"] = "12";
					$responseArray["headerLabel"]  = "Filtered Member";
					VCDTracking::insertYesNoTracking($this->contactHandlerObj,'N');
						
				} 
				elseif($errorArr["PROFILE_IGNORE"] == 2)
				{
					$responseArray["errmsglabel"] = $this->contactEngineObj->errorHandlerObj->getErrorMessage();
					$responseArray["headerlabel"] = "Blocked Profile";
					$responseArray["errMsgIconId"] = IdToAppImagesMapping::DISABLE_CONTACT;
					VCDTracking::insertYesNoTracking($this->contactHandlerObj,'N');
						
				}
				elseif ($errorArr["VIEW_LIMIT"] == 2) {
					$responseArray["contactdetailmsg"]       = "You have exhausted all your qouta of viewing contact details. ";
					if(strpos($request->getParameter("newActions"), "MEMBERSHIP")!== false )
					{
						$responseArray["footerbutton"]["label"]  = "Renew membership";
						$responseArray["footerbutton"]["value"] = "";
						$responseArray["footerbutton"]["action"] = "MEMBERSHIP";
					}
					else
					{
						$responseArray["footerbutton"]["label"]  = "Call to Renew Membership";
						$responseArray["footerbutton"]["value"]  = "18004196299";
						$responseArray["footerbutton"]["action"] = "CALL";
					}
					$responseArray["contact1"]["value"]      = "***********";
					$responseArray["contact1"]["label"]      = "Phone No.";
					$responseArray["contact1"]["action"]     = null;
					$responseArray["contact4"]["value"]      = "*******@*****.com";
					$responseArray["contact4"]["label"]      = "Email";
					$responseArray["contact4"]["action"]     = null;
					$responseArray["headerThumbnailURL"]     = $thumbNail;
					$responseArray["headerLabel"]            = $this->contactHandlerObj->getViewed()->getUSERNAME();
					VCDTracking::insertTracking($this->contactHandlerObj);
				} elseif ($errorArr["INCOMPLETE"] == 2) {
					$responseArray["headerLabel"]            = "Your Profile is Incomplete";
					$responseArray["errMsgLabel"]            = "Currently your profile is not complete. You can see contact details only when your profile is complete.";
					$responseArray["errMsgIconId"]           = "13";
					$responseArray["footerButton"]["label"]  = "complete your profile";
					$responseArray["footerButton"]["value"]  = "";
					$responseArray["footerButton"]["action"] = "COMPLETEPROFILE";
					VCDTracking::insertYesNoTracking($this->contactHandlerObj,'N');
						
				} elseif ($errorArr["DELETED"] == 2)
				{	
					$responseArray["headerLabel"] = "Deleted Profile";
					$responseArray["errMsgLabel"] = "You can not see the contact details of this profile as this profile is Deleted.";
					$responseArray["errMsgIconId"]           = "13";
					VCDTracking::insertYesNoTracking($this->contactHandlerObj,'N');
						
				}
				elseif ($errorArr["PROFILE_HIDDEN"] == 2)
				{	
					$responseArray["headerLabel"] = "Profile is Hidden";
					$responseArray["errMsgLabel"] = "You can not see the contact details as your profile is hidden. To see contact details unhide your profile.";
					$responseArray["errMsgIconId"]           = "13";
					VCDTracking::insertYesNoTracking($this->contactHandlerObj,'N');
						
				}
				 elseif ($errorArr["UNDERSCREENING"] == 2) {
					$responseArray["errMsgLabel"]  = "Your profile is being screened by our screening team. You can see contact details only after your profile is screened.";
					$responseArray["errMsgIconId"] = "16";
					$responseArray["headerLabel"]  = "Profile is Underscreening";
					VCDTracking::insertYesNoTracking($this->contactHandlerObj,'N');
						
				} elseif ($errorArr["DECLINED"] == 2) {
					$responseArray["errMsgLabel"]  = $this->contactEngineObj->errorHandlerObj->getErrorMessage();
					$responseArray["errMsgIconId"] = "12";
					$responseArray["headerLabel"]  = "Profile not available";
					VCDTracking::insertYesNoTracking($this->contactHandlerObj,'N');
						
				} else {
					$responseArray["contactdetailmsg"]       = "Only paid members can view contact details of other members. You can still send an interest for free.";
					if(strpos($request->getParameter("newActions"), "MEMBERSHIP")!== false )
					{
						$responseArray["footerbutton"]["label"]  = "Buy paid membership";
						$responseArray["footerbutton"]["value"] = "";
						$responseArray["footerbutton"]["action"] = "MEMBERSHIP";
						$responseArray["footerbutton"]["text"] = $MembershipMessage;
					}
					else
					{
						$responseArray["footerbutton"]["label"]  = "Call to Buy Paid Membership";
						$responseArray["footerbutton"]["value"]  = "18004196299";
						$responseArray["footerbutton"]["action"] = "CALL";
					}
					$responseArray["contact1"]["value"]      = "***********";
					$responseArray["contact1"]["label"]      = "Phone No.";
					$responseArray["contact1"]["action"]     = null;
					$responseArray["contact4"]["value"]      = "*******@*****.com";
					$responseArray["contact4"]["label"]      = "Email";
					$responseArray["contact4"]["action"]     = null;
					$responseArray["headerThumbnailURL"]     = $thumbNail;
					$responseArray["headerLabel"]            = $this->contactHandlerObj->getViewed()->getUSERNAME();
					VCDTracking::insertTracking($this->contactHandlerObj);
				}
			} elseif ($priArr[0]["CALL_DIRECT"]["ALLOWED"] == "Y") {
				$this->contactHandlerObj1 = new ContactHandler($this->loginProfile, $this->Profile, "INFO", $this->contactObj, 'CALL_DIRECT', ContactHandler::POST);
				$this->contactHandlerObj1->setElement("ALLOWED", 1);
				$this->contactEngineObj1 = ContactFactory::event($this->contactHandlerObj1);
				$priArr1 = $this->contactEngineObj1->contactHandler->getPrivilegeObj()->getPrivilegeArray();
				if ($priArr1[0]["CONTACT_DETAIL"]["VISIBILITY"] == "Y") {
					$responseArray                     = $this->getContactDetailsInArray($this->contactEngineObj1);
					$leftAlloted                       = $this->contactEngineObj1->getComponent()->contactDetailsObj->getLEFT_ALLOTED();
					$responseArray["contactdetailmsg"] = "You can view " . $leftAlloted . " more contact details.";
					$responseArray["headerThumbnailURL"] = $thumbNail;
		            $responseArray["headerLabel"]        = $this->contactHandlerObj->getViewed()->getUSERNAME();
					VCDTracking::insertYesNoTracking($this->contactHandlerObj,'N');
						
				}
			} 
			else {
				$responseArray["contactdetailmsg"]       = "Upgrade your membership to view phone/email of ".$this->contactHandlerObj->getViewed()->getUSERNAME()." (and other members)";
				if(strpos($request->getParameter("newActions"), "MEMBERSHIP")!== false )
				{
					$responseArray["footerbutton"]["label"]  = "View Membership Plans";
					$responseArray["footerbutton"]["value"] = "";
					$responseArray["footerbutton"]["action"] = "MEMBERSHIP";
					$responseArray["footerbutton"]["text"] = $MembershipMessage;
				}
				else
				{
					$responseArray["footerbutton"]["label"]  = "Call to Buy Paid Membership";
					$responseArray["footerbutton"]["value"]  = "18004196299";
					$responseArray["footerbutton"]["action"] = "CALL";
				}
				$responseArray["contact1"]["value"]      = "***********";
				$responseArray["contact1"]["label"]      = "Phone No.";
				$responseArray["contact1"]["action"]     = null;
				$responseArray["contact4"]["value"]      = "*******@*****.com";
				$responseArray["contact4"]["label"]      = "Email";
				$responseArray["contact4"]["action"]     = null;
				$responseArray["headerThumbnailURL"]     = $thumbNail;
				$responseArray["headerLabel"]            = $this->contactHandlerObj->getViewed()->getUSERNAME();
				VCDTracking::insertTracking($this->contactHandlerObj);
			}
		}
		if (is_array($responseArray["contact1"]))
			$responseArray["contact1"] = ButtonResponse::buttonMerge($responseArray["contact1"]);
		if (is_array($responseArray["contact2"]))
			$responseArray["contact2"] = ButtonResponse::buttonMerge($responseArray["contact2"]);
		if (is_array($responseArray["contact3"]))
			$responseArray["contact3"] = ButtonResponse::buttonMerge($responseArray["contact3"]);
		if (is_array($responseArray["contact4"]))
			$responseArray["contact4"] = ButtonResponse::buttonMerge($responseArray["contact4"]);
		if (is_array($responseArray["footerButton"]))
			$responseArray["footerButton"] = ButtonResponse::buttonMerge($responseArray["footerButton"]);
		$responseArray = array_change_key_case($responseArray,CASE_LOWER);
		$finalresponseArray["actiondetails"] = ButtonResponse::actionDetailsMerge($responseArray);
		$finalresponseArray["buttondetails"] = null;
		return $finalresponseArray;
	}
	public function getContactDetailsInArray($contactEngineObj)
	{
		$contactDetailsArr = $contactEngineObj->getComponent()->contactDetailsObj->getContactDetailArr();
		foreach ($contactDetailsArr as $key => $value) {
			if (strstr($value["LABEL"], "Mobile") && !strstr($value["LABEL"], "Alternate")) {
				$responseArray["contact1"]["value"]  = strstr($value["VALUE"],"+")?$value["VALUE"]:"+".$value["VALUE"];
				$responseArray["contact1"]["label"]  = "Phone No.";
				$responseArray["contact1"]["action"] = "CALL";
				$responseArray["contact1"]["iconid"] = IdToAppImagesMapping::PHONEICON;
			}
			if (strstr($value["LABEL"], "LandLine")) {
				$responseArray["contact2"]["value"]  = strstr($value["VALUE"],"+")?$value["VALUE"]:"+".$value["VALUE"];
				$responseArray["contact2"]["label"]  = "Landline";
				$responseArray["contact2"]["action"] = "CALL";
				$responseArray["contact2"]["iconid"] = IdToAppImagesMapping::PHONEICON;
			}
			if (strstr($value["LABEL"], "Alternate")) {
				$responseArray["contact3"]["value"]  = strstr($value["VALUE"],"+")?$value["VALUE"]:"+".$value["VALUE"];
				$responseArray["contact3"]["label"]  = "Alternate No.";
				$responseArray["contact3"]["action"] = "CALL";
				$responseArray["contact3"]["iconid"] = IdToAppImagesMapping::PHONEICON;
			}
			if (strstr($value["LABEL"], "Email")) {
				$responseArray["contact4"]["value"]  = $value["VALUE"];
				$responseArray["contact4"]["label"]  = "Email";
				$responseArray["contact4"]["action"] = "MAIL";
				$responseArray["contact4"]["iconid"] = IdToAppImagesMapping::MAILICON;
			}
		}
		return $responseArray;
	}
}
