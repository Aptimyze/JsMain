<?php
/**
 * contacts actions.
 *
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ContactDetailsV2Action extends sfAction
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
		//		$this->loginProfile->getDetail($this->loginData["PROFILEID"], "PROFILEID");
				
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
		{
			$thumbNail = $profilePicObj->getThumbailUrl();
		}
		if(!$thumbNail)
		{
			$thumbNail = PictureService::getRequestOrNoPhotoUrl('noPhoto','ThumbailUrl',$this->Profile->getGENDER());
		}
		
		$thumbNail = PictureFunctions::mapUrlToMessageInfoArr($thumbNail,'ThumbailUrl',1);
		
		$priArr = $this->contactEngineObj->contactHandler->getPrivilegeObj()->getPrivilegeArray();
		if ($priArr[0]["CONTACT_DETAIL"]["VISIBILITY"] == "Y" && !$this->contactEngineObj->errorHandlerObj->getErrorMessage()) {
			$responseArray                       = $this->getContactDetailsInArray($this->contactEngineObj);
			$source=CommonFunction::getViewContactDetailFlag($this->contactEngineObj->contactHandler);
			if($this->contactEngineObj->contactHandler->getViewer()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus()=="FREE")
			{
				
				if($this->contactEngineObj->getComponent()->contactDetailsObj->getEvalueLimitUser()==CONTACT_ELEMENTS::EVALUE_STOP)
				{
					$memHandlerObj = new MembershipHandler();
					$data2 = $memHandlerObj->fetchHamburgerMessage($request);
					$MembershipMessage = $data2['hamburger_message']['top']; 
                    $MembershipMessage = $memHandlerObj->modifiedMessage($data2);
					$dataPlan = $data2["startingPlan"];
					unset($responseArray);
					$responseArray["errmsglabel"] 			= "Upgrade your membership to view phone/email of ".$this->contactHandlerObj->getViewed()->getUSERNAME()." (and other members)";
					$responseArray["contactdetailmsg"]       = "Upgrade your membership to view phone/email of ".$this->contactHandlerObj->getViewed()->getUSERNAME()." (and other members)";
					$responseArray["footerbutton"]["label"]  = "View Membership Plans";
					$responseArray["footerbutton"]["value"] = "";
					$responseArray["footerbutton"]["action"] = "MEMBERSHIP";
					$responseArray["footerbutton"]["text"] = $MembershipMessage;
					$responseArray["footerbutton"]["enable"] = true;
					$responseArray["contact1"]["value"]      = "blur";
					$responseArray["contact1"]["label"]      = "Phone No.";
					$responseArray["contact1"]["action"]     = null;
					$responseArray["contact4"]["value"]      = "blur";
					$responseArray["contact4"]["label"]      = "Email";
					$responseArray["contact4"]["action"]     = null;
					$responseArray["newerrmsglabel"] = "As a Free Member you cannot see contact details of other users";
					$responseArray["newcontactdetailmsg"] = "As a Free Member you can only send an interest for free";
					$responseArray["membershipmsgheading"] = "BUY PAID MEMBERSHIP TO";
					$responseArray["membershipmsg"]["subheading1"] = "View Contact details of the members";
					$responseArray["membershipmsg"]["subheading2"] = "Send personalized messages to members you like";
					$responseArray["membershipmsg"]["subheading3"] = "Show your contact details to other members";

					$responseArray["footerbutton"]["newlabel"]  = "Explore Plans";
					if($dataPlan)
					{
						$responseArray["membershipOfferCurrency"] = $dataPlan["membershipDisplayCurrency"];
						if($dataPlan["origStartingPrice"] == $dataPlan["discountedStartingPrice"])
						{
							$responseArray["discountedPrice"] = $dataPlan["discountedStartingPrice"];
						}
						else
						{
							$responseArray["strikedPrice"] = $dataPlan["origStartingPrice"];
							$responseArray["discountedPrice"] = $dataPlan["discountedStartingPrice"];
						}
					}

					if($MembershipMessage)
					{
						$responseArray["offer"]["membershipOfferMsg1"] = "Exclusive Offer For You!";
						$responseArray["offer"]["membershipOfferMsg2"] = $MembershipMessage;
					}
					else if($dataPlan)
					{
						// in case of no offer
						$responseArray["lowestOffer"] = "Lowest Membership plan starts @ ".$responseArray["membershipOfferCurrency"]." ".$responseArray["discountedPrice"];
					}

					VCDTracking::insertTracking($this->contactHandlerObj);
                    
                    //Generate Event
                    $iPgID = $this->contactHandlerObj->getViewer()->getPROFILEID();
                    GenerateOutboundEvent::getInstance()->generate(OutBoundEventEnums::VIEW_CONTACT, $iPgID);
				}
				else
				{
					
					if($this->contactEngineObj->getComponent()->contactDetailsObj->getEvalueLimitUser()==CONTACT_ELEMENTS::EVALUE_PCS)
					{
						if(MobileCommon::isApp())
						{
							unset($responseArray);
							$responseArray["errmsglabel"] 			= $this->contactHandlerObj->getViewed()->getUSERNAME()." has an ".$this->contactHandlerObj->getViewed()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatusText()." plan and has made Phone/Email visible.\n\n But to view ".$this->contactHandlerObj->getViewed()->getUSERNAME()."'s Phone/Email, your profile should be at least ".CONTACT_ELEMENTS::PCS_CHECK_VALUE."% complete.\n\n Please add more information to your profile.";
							$responseArray["headerLabel"]            = "Complete your profile";
							$responseArray["errMsgIconId"]           = "13";
						}
						else
						{
							unset($responseArray);
							if(MobileCommon::isNewMobileSite())
							{
								$responseArray["errmsglabel"] 			= "<BR>".$this->contactHandlerObj->getViewed()->getUSERNAME()." has an ".$this->contactHandlerObj->getViewed()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatusText()." plan and has made Phone/Email visible.<br> But to view ".$this->contactHandlerObj->getViewed()->getUSERNAME()."'s Phone/Email, your profile should be at least ".CONTACT_ELEMENTS::PCS_CHECK_VALUE."% complete.<br><br> Please add more information to your profile.";
								$responseArray["footerbutton"]["label"]  = "Complete Profile";
								$responseArray["footerbutton"]["value"] = "";
								$responseArray["footerbutton"]["action"] = "EDITPROFILE";
								$responseArray["footerbutton"]["text"] = "";
								$responseArray["footerbutton"]["enable"] = true;
								$responseArray["contact1"]["value"]      = "blur";
								$responseArray["contact1"]["label"]      = "Phone No.";
								$responseArray["contact1"]["action"]     = null;
								$responseArray["contact4"]["value"]      = "blur";
								$responseArray["contact4"]["label"]      = "Email";
								$responseArray["contact4"]["action"]     = null;
							}
							else
								$responseArray["errmsglabel"] 			= "<BR>".$this->contactHandlerObj->getViewed()->getUSERNAME()." has an ".$this->contactHandlerObj->getViewed()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatusText()." plan and has made Phone/Email visible.<br> But to view ".$this->contactHandlerObj->getViewed()->getUSERNAME()."'s Phone/Email, your profile should be at least ".CONTACT_ELEMENTS::PCS_CHECK_VALUE."% complete.<br><br> Please add more information to your profile.<a href='/profile/viewprofile.php?ownview=1' class='colr5'> Update Profile";
						}
						VCDTracking::insertYesNoTracking($this->contactHandlerObj,'N');
					}
					else
					{
						$memHandlerObj = new MembershipHandler();
						$data2 = $memHandlerObj->fetchHamburgerMessage($request);
						$MembershipMessage = $data2['hamburger_message']['top'];
                        $MembershipMessage = $memHandlerObj->modifiedMessage($data2);
				
						$responseArray["bottommsg2"]       = "Upgrade to ".$this->contactHandlerObj->getViewed()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatusText()." to make your phone/email visible to all matching profiles";
						$responseArray["bottommsg"]       = "View Membership Plans";
						$responseArray["membershipOfferMsg"] = $MembershipMessage;
						$responseArray["bottomMsgUrl"]       = "/profile/mem_comparison.php";
						$responseArray["contactdetailmsg"] = $this->contactHandlerObj->getViewed()->getUSERNAME()." has an ".$this->contactHandlerObj->getViewed()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatusText()." plan and has made contact details visible";
						$responseArray["topmsg"] = $this->contactHandlerObj->getViewed()->getUSERNAME()." has an ".$this->contactHandlerObj->getViewed()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatusText()." plan and has made contact details visible";
						$responseArray['membership']['label'] = "Upgrade to ".$this->contactHandlerObj->getViewed()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatusText();
						$responseArray['membership']['url'] = "/profile/mem_comparison.php";
						VCDTracking::insertYesNoTracking($this->contactHandlerObj,'Y');
					
					}
				
				

				}

			}

			else{
				if ($this->contactObj->getTYPE() != "A") {


				if ($this->Profile->getPROFILE_STATE()->getPaymentStates()->getEVALUE() ||$this->Profile->getPROFILE_STATE()->getPaymentStates()->getJSEXCLUSIVE() ) {
					if ($this->loginProfile->getPROFILE_STATE()->getPaymentStates()->isPAID()) {
						//$responseArray["contactdetailmsg"] = "There would be no deduction in number of contacts you can view as " . $this->contactEngineObj->getComponent()->genderPronoun . " is an <b>eValue</b> Member.";
						$responseArray["bottommsg"] = "No reduction in quota as they have an <b>".$this->contactHandlerObj->getViewed()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatusText()."</b> membership";
						$responseArray["topmsg"] = "No reduction in quota as they have an <b>".$this->contactHandlerObj->getViewed()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatusText()."</b> membership";
					} else {
						//$responseArray["contactdetailmsg"] = "You can View Contact Details as " . $this->contactEngineObj->getComponent()->genderPronoun . " is an <b>eValue</b> Member.";
						$responseArray["bottommsg"] = "Contact visible as they have an <b>".$this->contactHandlerObj->getViewed()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatusText()."</b> membership";
						$responseArray["topmsg"] = "Contact visible as they have an <b>".$this->contactHandlerObj->getViewed()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatusText()."</b> membership";
						$responseArray['membership']['label'] = "Upgrade to ".$this->contactHandlerObj->getViewed()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatusText();
						$responseArray['membership']['url'] = "/profile/mem_comparison.php";
					}
				}
			}
					
		VCDTracking::insertYesNoTracking($this->contactHandlerObj,'Y');

		}
		
			$responseArray["headerThumbnailURL"] = $thumbNail;
			$responseArray["headerLabel"]        = $this->contactHandlerObj->getViewed()->getUSERNAME();
		} else {
			if ($this->contactObj->getTYPE() == "C" || $this->contactObj->getTYPE() == "D" || $this->contactObj->getTYPE() == "E")
			{
				VCDTracking::insertYesNoTracking($this->contactHandlerObj,'N');
				$responseArray["headerLabel"] = "Contact Not Visible";
				$responseArray["errMsgIconId"] = IdToAppImagesMapping::NOT_VISIBLE;
				if($this->contactObj->getSenderObj()->getPROFILEID() == $this->loginProfile->getPROFILEID())
				{
					if($this->contactObj->getTYPE() == "E")
						$responseArray["errMsgLabel"]  = "You cannot see contact details as you have cancelled the interest sent to them";
					elseif($this->contactObj->getTYPE() == "D")
						$responseArray["errMsgLabel"]  = "You cannot see contact details as they have declined your interest";
					elseif($this->contactObj->getTYPE() == "C")
						$responseArray["errMsgLabel"]  = "You cannot see contact details as you have cancelled the interest sent to them";
				}
				else
				{
					if($this->contactObj->getTYPE() == "E")
						$responseArray["errMsgLabel"]  = "You cannot see contact details they cancelled their interest";
					elseif($this->contactObj->getTYPE() == "D")
						$responseArray["errMsgLabel"]  = "You cannot see contact details as you have declined their interest";
					elseif($this->contactObj->getTYPE() == "C")
						$responseArray["errMsgLabel"]  = "You cannot see contact details as you have cancelled the interest sent to them";
				}
				
			}
			elseif ($this->contactEngineObj->errorHandlerObj->getErrorMessage()) {
				
				$errorArr = $this->contactEngineObj->errorHandlerObj->getErrorType();
				if($errorArr["PROFILE_IGNORE"] == 2)
				{
					$responseArray["errmsglabel"] = $this->contactEngineObj->errorHandlerObj->getErrorMessage();
					$responseArray["errmsgiconid"] = IdToAppImagesMapping::DISABLE_CONTACT;
					$responseArray["headerlabel"] = "Blocked Profile";
					VCDTracking::insertYesNoTracking($this->contactHandlerObj,'N');

				}
			elseif ($errorArr["SAMEGENDER"] == 2) {
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
				elseif($errorArr["PROFILE_VIEWED_HIDDEN"] == 2) {
					$responseArray["errmsglabel"]= $this->contactEngineObj->errorHandlerObj->getErrorMessage();
					$responseArray["errmsgiconid"] = "16";
					$responseArray["headerlabel"] = "Unsupported action";
        				$responseButtonArray["button"]["iconid"] = IdToAppImagesMapping::DISABLE_CONTACT;
					VCDTracking::insertYesNoTracking($this->contactHandlerObj,'N');
				}
				 elseif ($errorArr["FILTERED"] == 2) {
					 if($this->contactEngineObj->errorHandlerObj->getErrorMessage())
						$responseArray["errMsgLabel"]  = $this->contactEngineObj->errorHandlerObj->getErrorMessage();
					else
						$responseArray["errMsgLabel"]  = "You cannot see the contact details of this profile as the profile has filtered you.";
					$responseArray["errMsgIconId"] = "12";
					$responseArray["headerLabel"]  = "Filtered Member";
					VCDTracking::insertYesNoTracking($this->contactHandlerObj,'N');

				} elseif ($errorArr["VIEW_LIMIT"] == 2) {
					$responseArray["contactdetailmsg"]       = "You have exhausted all your quota of viewing contact details. ";
					$responseArray["errMsgLabel"] = "You have exhausted all your quota of viewing contact details. ";
					$responseArray["footerbutton"]["label"]  = "Renew membership";
					$responseArray["footerbutton"]["value"] = "";
					$responseArray["footerbutton"]["action"] = "MEMBERSHIP";
					$responseArray["footerbutton"]["enable"] = true;
					$responseArray["contact1"]["value"]      = "blur";
					$responseArray["contact1"]["label"]      = "Phone No.";
					$responseArray["contact1"]["action"]     = null;
					$responseArray["contact4"]["value"]      = "blur";
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
					$responseArray["footerbutton"]["enable"] = true;
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

				}

				elseif ($errorArr["PAID_FILTERED_INTEREST_NOT_SENT"] == 2) { 
					$responseArray["errMsgLabel"]  = $this->contactEngineObj->errorHandlerObj->getErrorMessage();;
					$responseArray["errMsgIconId"] = "12";
					$responseArray["headerLabel"]  = "Filtered Member";
					VCDTracking::insertYesNoTracking($this->contactHandlerObj,'N');
				}

					elseif ($errorArr["PAID_FILTERED_INTEREST_SENT"] == 2) { 
					$responseArray["errMsgLabel"]  = $this->contactEngineObj->errorHandlerObj->getErrorMessage();;
					$responseArray["errMsgIconId"] = "12";
					$responseArray["headerLabel"]  = "Filtered Member";
					VCDTracking::insertYesNoTracking($this->contactHandlerObj,'N');
				}

				 else {

					$responseArray["contactdetailmsg"]       = "Become a paid member to view <br> contact details";
					$responseArray["footerbutton"]["label"]  = "View Membership Plans";
					$responseArray["footerbutton"]["value"] = "";
					$responseArray["footerbutton"]["action"] = "MEMBERSHIP";
					$responseArray["footerbutton"]["enable"] = true;
					$responseArray["contact1"]["value"]      = "blur";
					$responseArray["contact1"]["label"]      = "Phone No.";
					$responseArray["contact1"]["action"]     = null;
					$responseArray["contact4"]["value"]      = "blur";
					$responseArray["contact4"]["label"]      = "Email";
					$responseArray["contact4"]["action"]     = null;
					$responseArray["headerThumbnailURL"]     = $thumbNail;
					$responseArray["headerLabel"]            = $this->contactHandlerObj->getViewed()->getUSERNAME();
					VCDTracking::insertTracking($this->contactHandlerObj);
				}
			}
			 elseif ($priArr[0]["CALL_DIRECT"]["ALLOWED"] == "Y") {
			 	if(MobileCommon::isNewMobileSite() || MobileCommon::isDesktop() || (MobileCommon::isApp()=='I' && ($request->getParameter('API_APP_VERSION')>=5.1)))
			 	{
			 		$DeskMob=1;
			 	}
			 	else
			 		$DeskMob=0;
			 
			if($request->getParameter("VIEWCONTACT") == 1 || $DeskMob==0)
					{

					VCDTracking::insertYesNoTracking($this->contactHandlerObj,'Y');

					$this->contactHandlerObj1 = new ContactHandler($this->loginProfile, $this->Profile, "INFO", $this->contactObj, 'CALL_DIRECT', ContactHandler::POST);
					$this->contactHandlerObj1->setElement("ALLOWED", 1);
					$this->contactEngineObj1 = ContactFactory::event($this->contactHandlerObj1);
					$priArr1 = $this->contactEngineObj1->contactHandler->getPrivilegeObj()->getPrivilegeArray();
					if ($priArr1[0]["CONTACT_DETAIL"]["VISIBILITY"] == "Y") {
						$responseArray                     = $this->getContactDetailsInArray($this->contactEngineObj1);
						$leftAlloted                       = $this->contactEngineObj1->getComponent()->contactDetailsObj->getLEFT_ALLOTED();
						//$responseArray["contactdetailmsg"] = "You can view " . $leftAlloted . " more contact details.";
						$responseArray["headerThumbnailURL"] = $thumbNail;
                        $responseArray["headerLabel"]        = $this->contactHandlerObj->getViewed()->getUSERNAME();
                        $responseArray["bottommsg"]          = "Contacts Left To View <b>".$leftAlloted."</b>";
                        $responseArray["leftviewlabel"]		 = "Contacts left to view";
                        $responseArray['leftviewvalue']      = $leftAlloted;
					}
				}
				else
				{
					
					$leftAlloted                       = $this->contactEngineObj->getComponent()->contactDetailsObj->getLEFT_ALLOTED();
					$responseArray["headerThumbnailURL"] = $thumbNail;
                    $responseArray["headerLabel"]        = $this->contactHandlerObj->getViewed()->getUSERNAME();
                    $responseArray["infomsglabel"]          = "<b>".$leftAlloted."</b> contacts left to view";
                    if($this->contactEngineObj->getComponent()->contactDetailsObj->getHiddenPhoneMsg() == "Y")
                    {
                    	$responseArray['errmsglabel'] = "This member has choosen to hide phone number. Only email is available but no phone number";
                    }
                    else
                    {
                    	$responseArray['errMsgLabel'] = null;
                    }
                    $responseArray["footerbutton"]["label"]  = "View Contact Details";
					$responseArray["footerbutton"]["value"] = "";
					$responseArray["footerbutton"]["params"] = "&VIEWCONTACT=1";
					$responseArray["footerbutton"]["action"] = "CONTACT_DETAIL";
					$responseArray["footerbutton"]["enable"] = true;

				}
			}
			else {
				$memHandlerObj = new MembershipHandler();
				$data2 = $memHandlerObj->fetchHamburgerMessage($request);
				$dataPlan = $data2["startingPlan"];
				$MembershipMessage = $data2['hamburger_message']['top'];
                $MembershipMessage = $memHandlerObj->modifiedMessage($data2);
				$responseArray["errmsglabel"]     = "Upgrade your membership to view phone/email of ".$this->contactHandlerObj->getViewed()->getUSERNAME()." (and other members)";
				$responseArray["contactdetailmsg"]       = "Upgrade your membership to view phone/email of ".$this->contactHandlerObj->getViewed()->getUSERNAME()." (and other members)";
				$responseArray["footerbutton"]["label"]  = "View Membership Plans";
				$responseArray["footerbutton"]["value"] = "";
				$responseArray["footerbutton"]["action"] = "MEMBERSHIP";
				$responseArray["footerbutton"]["enable"] = true;
				$responseArray["footerbutton"]["text"] = $MembershipMessage;
				$responseArray["contact1"]["value"]      = "blur";
				$responseArray["contact1"]["label"]      = "Phone No.";
				$responseArray["contact1"]["action"]     = null;
				$responseArray["contact4"]["value"]      = "blur";
				$responseArray["contact4"]["label"]      = "Email";
				$responseArray["contact4"]["action"]     = null;
				$responseArray["headerThumbnailURL"]     = $thumbNail;
				$responseArray["headerLabel"]            = $this->contactHandlerObj->getViewed()->getUSERNAME();

				$responseArray["newerrmsglabel"] = "As a Free Member you cannot see contact details of other users";
				$responseArray["newcontactdetailmsg"] = "As a Free Member you can only send an interest for free";
				$responseArray["membershipmsgheading"] = "BUY PAID MEMBERSHIP TO";
				$responseArray["membershipmsg"]["subheading1"] = "View Contact details of the members";
				$responseArray["membershipmsg"]["subheading2"] = "Send personalized messages to members you like";
				$responseArray["membershipmsg"]["subheading3"] = "Show your contact details to other members";

				$responseArray["footerbutton"]["newlabel"]  = "Explore Plans";
				if($dataPlan)
				{
					$responseArray["membershipOfferCurrency"] = $dataPlan["membershipDisplayCurrency"];
					if($dataPlan["origStartingPrice"] == $dataPlan["discountedStartingPrice"])
					{
						$responseArray["discountedPrice"] = $dataPlan["discountedStartingPrice"];
					}
					else
					{
						$responseArray["strikedPrice"] = $dataPlan["origStartingPrice"];
						$responseArray["discountedPrice"] = $dataPlan["discountedStartingPrice"];
					}
				}

				if($MembershipMessage)
				{
					$responseArray["offer"]["membershipOfferMsg1"] = "Exclusive Offer For You!";
					$responseArray["offer"]["membershipOfferMsg2"] = $MembershipMessage;
				}
				else if($dataPlan)
				{
					// in case of no offer
					$responseArray["lowestOffer"] = "Lowest Membership plan starts @ ".$responseArray["membershipOfferCurrency"]." ".$responseArray["discountedPrice"];
				}

				VCDTracking::insertTracking($this->contactHandlerObj);
                
                //Generate Event
                $iPgID = $this->contactHandlerObj->getViewer()->getPROFILEID();
                GenerateOutboundEvent::getInstance()->generate(OutBoundEventEnums::VIEW_CONTACT, $iPgID);
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

//  set the hidden message for contacts hidden or visible on accept
		if($this->contactEngineObj->getComponent()->contactDetailsObj){
		        if(!$responseArray['contact1']){
                    $responseArray['contact1_message']=$this->contactEngineObj->getComponent()->contactDetailsObj->getPrimaryMobileHiddenMessage();
                }
                if(!$responseArray['contact2']){
                    $responseArray['contact2_message']=$this->contactEngineObj->getComponent()->contactDetailsObj->getLandlMobileHiddenMessage();
                }
                if(!$responseArray['contact3']){
                    $responseArray['contact3_message']=$this->contactEngineObj->getComponent()->contactDetailsObj->getAltMobileHiddenMessage();
                }
            }
		$responseArray = array_change_key_case($responseArray,CASE_LOWER);
		$finalresponseArray["actiondetails"] = ButtonResponse::actionDetailsMerge($responseArray);
		$finalresponseArray["buttondetails"] = null;
        if(MobileCommon::isApp()=="I")
        {
                $pictureServiceObj=new PictureService($this->contactHandlerObj->getViewed());
                $profilePicObj = $pictureServiceObj->getProfilePic();
                if($profilePicObj)
                        $thumbNail = $profilePicObj->getThumbailUrl();
                $iphoto = PictureFunctions::mapUrlToMessageInfoArr($thumbNail,"ThumbailUrl","",$this->contactHandlerObj->getViewed()->getGENDER())['url'];
                $button_after_action['photo'] = ButtonResponse::getPhotoDetail($iphoto);
                $finalresponseArray["button_after_action"] = ButtonResponse::buttondetailsMerge($button_after_action);
        }
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
			if (strstr($value["LABEL"], "Suitable")) {
				$responseArray["contact5"]["value"]  = $value["VALUE"];
				$responseArray["contact5"]["label"]  = "Suitable time";
				$responseArray["contact5"]["action"] = "CALL";
				$responseArray["contact5"]["iconid"] = IdToAppImagesMapping::PHONEICON;
			}
			if (strstr($value["LABEL"], "posted")) {
				$responseArray["contact6"]["value"]  = $value["VALUE"];
				$responseArray["contact6"]["label"]  = "Profile managed by";
				$responseArray["contact6"]["action"] = "MAIL";
				$responseArray["contact6"]["iconid"] = IdToAppImagesMapping::PHONEICON;
			}
			if (strstr($value['LABEL'],"Address")){
				$responseArray["contact7"]["value"]  = $value["VALUE"];
				$responseArray["contact7"]["label"]  = "Address";
				$responseArray["contact7"]["action"] = "MAIL";
				$responseArray["contact7"]["iconid"] = IdToAppImagesMapping::PHONEICON;
			}
			if (strstr($value['LABEL'],"Parent's address")){
				$responseArray["contact8"]["value"]  = $value["VALUE"];
				$responseArray["contact8"]["label"]  = "Parent's Address";
				$responseArray["contact8"]["action"] = "MAIL";
				$responseArray["contact8"]["iconid"] = IdToAppImagesMapping::PHONEICON;	
			}
						if (strstr($value["LABEL"], "Relationship manager")) {
				$responseArray["contact9"]["value"]  = $value["VALUE"];
				$responseArray["contact9"]["label"]  = $value['LABEL'];
				$responseArray["contact9"]["action"] = "CALL";
				$responseArray["contact9"]["iconid"] = IdToAppImagesMapping::PHONEICON;
			}


		}
		return $responseArray;
	}
}
