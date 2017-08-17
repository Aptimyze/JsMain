<?php
/**
 * contacts actions.
 *
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Pankaj Khandelwal
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class postSendReminderv2Action extends sfAction
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */

	function execute($request){
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		$apiObj                  = ApiResponseHandler::getInstance();

		if ($request->getParameter("actionName")=="reminder" || $request->getParameter("actionName")=="postSendReminder")
		{
			$inputValidateObj->validateContactActionData($request);
			$output = $inputValidateObj->getResponse();
			if($output["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
			{
				$this->loginData    = $request->getAttribute("loginData");
				//Contains logined Profile information;
				$this->loginProfile = LoggedInProfile::getInstance();
		//		$this->loginProfile->getDetail($this->loginData["PROFILEID"], "PROFILEID");
				$this->channel = $request->getParameter("channel");
				$this->source = $request->getParameter("pageSource");
				if ($this->loginProfile->getPROFILEID()) {
					$this->userProfile = $request->getParameter('profilechecksum');
					if ($this->userProfile) {

						$this->Profile = new Profile();
						$profileid     = JsCommon::getProfileFromChecksum($this->userProfile);
						$this->Profile->getDetail($profileid, "PROFILEID");
						$this->contactObj = new Contacts($this->loginProfile, $this->Profile);
					}
					$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,'R',ContactHandler::POST);
					$this->contactHandlerObj->setElement("MESSAGE","");
					$this->contactHandlerObj->setElement("DRAFT_NAME","preset");
					$this->contactHandlerObj->setElement("STATUS","R");
					$this->contactEngineObj=ContactFactory::event($this->contactHandlerObj);
					$responseArray           = $this->getContactArray($request);
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


	private function getContactArray($request)
	{
		$pictureServiceObj=new PictureService($this->Profile);
		$profilePicObj = $pictureServiceObj->getProfilePic();

		if($profilePicObj)
			$thumbNail = $profilePicObj->getThumbailUrl();
		if(!$thumbNail)
			$thumbNail = PictureService::getRequestOrNoPhotoUrl('noPhoto','ThumbailUrl',$this->Profile->getGENDER());
		$thumbNail = PictureFunctions::mapUrlToMessageInfoArr($thumbNail,'ThumbailUrl',1);
		unset($pictureServiceObj);
		unset($profilePicObj);
		$pictureServiceObj=new PictureService($this->loginProfile);
		$profilePicObj = $pictureServiceObj->getProfilePic();
		if($profilePicObj)
			$ownthumbNail = $profilePicObj->getThumbailUrl();
		if(!$ownthumbNail)
			$ownthumbNail = null;
		$ownthumbNail = PictureFunctions::mapUrlToMessageInfoArr($ownthumbNail,'ThumbailUrl',1);
		$ownthumbNail = $ownthumbNail['url'];
		$privilegeArray = $this->contactEngineObj->contactHandler->getPrivilegeObj()->getPrivilegeArray();
		$buttonObj = new ButtonResponse($this->loginProfile,$this->Profile,array("source"=>$this->source),$this->contactHandlerObj);
		$responseButtonArray = $buttonObj->getAfterActionButton(ContactHandler::REMINDER);
		if($this->contactEngineObj->messageId)
		{
			if($privilegeArray["0"]["SEND_REMINDER"]["MESSAGE"] == "Y")
			{
				$responseArray["headerthumbnailurl"] = $thumbNail;;
				$responseArray["headerlabel"] = $this->Profile->getUSERNAME();
				$responseArray["selfthumbnailurl"] = $ownthumbNail;
				$contactId = $this->contactEngineObj->contactHandler->getContactObj()->getCONTACTID();
				$param = "&messageid=".$this->contactEngineObj->messageId."&type=R&receiver=".$this->Profile->getPROFILEID().'&contactId='.$contactId;
				$responseArray["writemsgbutton"] = ButtonResponse::getCustomButton("Send","","SEND_MESSAGE",$param,"");
				$responseArray['draftmessage'] = "Write a personalized message to ".$this->Profile->getUSERNAME()." along with your reminder" ;
				$responseArray['lastsent'] = LastSentMessage::getLastSentMessage($this->loginProfile->getPROFILEID(),"R");


			}
			else
			{
				if(MobileCommon::isDesktop())
				{
					$memHandlerObj = new MembershipHandler();
					$data2 = $memHandlerObj->fetchHamburgerMessage($request);
					$MembershipMessage = $data2['hamburger_message']['top'];
                    $MembershipMessage = $memHandlerObj->modifiedMessage($data2);
					$responseArray["errmsglabel"]= "Reminder sent. Upgrade to send personalized messages or initiate chat";
					$responseArray["footerbutton"]["label"]  = "View Membership Plans";
					$responseArray["footerbutton"]["value"] = "";
					$responseArray["footerbutton"]["action"] = "MEMBERSHIP";
					$responseArray["footerbutton"]["text"] = $MembershipMessage;

				}
			}
			$responseArray["redirect"] = true;
		}
		else
		{
			$errorArr = $this->contactEngineObj->errorHandlerObj->getErrorType();
			if($errorArr["PROFILE_IGNORE"] == 2)
			{
				$responseArray["errmsglabel"] = $this->contactEngineObj->errorHandlerObj->getErrorMessage();
				$responseArray["errmsgiconid"] = IdToAppImagesMapping::DISABLE_CONTACT;
				$responseArray["headerlabel"] = "Blocked Profile";
			}
			elseif($errorArr["SAMEGENDER"] == 2)
			{
				$responseArray["errmsglabel"] = "We do not support same gender marriage it is against the law";
				$responseArray["errmsgiconid"] = IdToAppImagesMapping::SAME_GENDER;
				$responseArray["headerlabel"] = "Same Gender Contact Ristricted";
				$responseButtonArray["button"]["iconid"] = IdToAppImagesMapping::DISABLE_CONTACT;
			}
			elseif($errorArr["DELETED"]==2)
			{
				$responseArray["errmsglabel"] = "This profile has been deleted";
				$responseArray["errmsgiconid"] = "13";
				$responseArray["headerlabel"] = "Deleted Profile";
				$responseButtonArray["button"]["iconid"] = IdToAppImagesMapping::DISABLE_CONTACT;
				}
			elseif($errorArr["PROFILE_HIDDEN"] == 2)
			{
				$responseArray["errmsglabel"] = "This profile is Hidden";
				$responseArray["errmsgiconid"] = "13";
				$responseArray["headerlabel"] = "Hidden Profile";
				$responseButtonArray["button"]["iconid"] = IdToAppImagesMapping::DISABLE_CONTACT;
			}
			elseif($errorArr["PROFILE_VIEWED_HIDDEN"] == 2)
			{
				$responseArray["errmsglabel"]= $this->contactEngineObj->errorHandlerObj->getErrorMessage();
				$responseArray["errmsgiconid"] = "16";
				$responseArray["headerlabel"] = "Unsupported action";
				$responseButtonArray["button"]["iconid"] = IdToAppImagesMapping::DISABLE_CONTACT;
			}
			elseif($errorArr["REMINDER_LIMIT"] == 2)
			{
				if($this->loginProfile->getPROFILE_STATE()->getPaymentStates()->isPAID())
					$responseArray["errmsglabel"]= "You can not send more than two reminders. However, you can talk to this member directly by viewing contact details.";
				else
				{
					$memHandlerObj = new MembershipHandler();
					$data2 = $memHandlerObj->fetchHamburgerMessage($request);
					$MembershipMessage = $data2['hamburger_message']['top'];
                    $MembershipMessage = $memHandlerObj->modifiedMessage($data2);
					$responseArray["errmsglabel"]= "You can not send more than two reminders. Buy paid membership to talk to this member directly.";
					$responseArray["footerbutton"]["label"]  = "View Memebrship Plans";
					$responseArray["footerbutton"]["value"] = "";
					$responseArray["footerbutton"]["action"] = "MEMBERSHIP";
					$responseArray["footerbutton"]["text"] = $MembershipMessage;

				}
				$responseArray["errmsgiconid"] = "13";
				$responseArray["headerlabel"] = "Limit Exceeded";
			}
			elseif($errorArr["PHONE_NOT_VERIFIED"] == 2)
			{
				$responseArray["headerlabel"] = "Phone Verification Complusory";
				$responseArray["errmsglabel"] = "Its is complusory to verify your number on jeevansathi.com or you will not able send expression of interest. \n\n You only have to give the missed call ";
				$responseArray["errmsgiconid"] = IdToAppImagesMapping::PHONE_NOT_VERIFIED;
				$responseArray["footerbutton"]["label"] = "Verify your number";
				$responseArray["footerbutton"]["value"] = "";
				$responseArray["footerbutton"]["action"] = "PHONEVERIFICATION";
			}
			elseif($errorArr["INCOMPLETE"] == 2)
			{
				$responseArray["errmsglabel"] = "Profile is Incomplete Expression of interest will be delivered only on profile is completed and is live. ";
				$responseArray["errmsgiconid"] = IdToAppImagesMapping::INCOMPLETE;
				$responseArray["footerbutton"]["label"] = "complete your profile";
				$responseArray["footerbutton"]["value"] = "";
				$responseArray["footerbutton"]["action"] = "COMPLETEPROFILE";
				$responseArray["headerlabel"] = "Your Profile is Incomplete";
				$responseArray["redirect"] = true;
			}
			elseif($errorArr["UNDERSCREENING"] == 2)
			{
				$responseArray["errmsglabel"] = "Expession of interest will be delivered only when Profile is live";
				$responseArray["errmsgiconid"] = IdToAppImagesMapping::UNDERSCREENING;
				$responseArray["headerlabel"] = "Profile is Underscreening";
				$responseArray["redirect"] = true;
			}
			elseif($errorArr["REMINDER_SENT_BEFORE_TIME"] == 2)
			{
				$responseArray["errmsglabel"] = Messages::getReminderSentBeforeTimeMessage(Messages::REMINDER_SENT_BEFORE_TIME);
				//This is a junk Id which is not in use for IOS but just to make sure that existing architecture is not disturbed, We are sending it. As without this, The output was not visible on IOS.
				$responseArray["errmsgiconid"] = IdToAppImagesMapping::UNDERSCREENING;
				$responseArray["headerlabel"] = "Reminder cannot be sent";
				//$responseArray["redirect"] = true;
			}
			elseif($errorArr["SECOND_REMINDER_BEFORE_TIME"] == 2)
			{
				$responseArray["errmsglabel"] = Messages::getReminderSentBeforeTimeMessage(Messages::SECOND_REMINDER_BEFORE_TIME);
				//This is a junk Id which is not in use for IOS but just to make sure that existing architecture is not disturbed, We are sending it. As without this, The output was not visible on IOS.
				$responseArray["errmsgiconid"] = IdToAppImagesMapping::UNDERSCREENING;
				$responseArray["headerlabel"] = "Second Reminder cannot be sent";
				//$responseArray["redirect"] = true;
			}
			else
			{
				$responseArray["errmsglabel"]= "You cannot perform this action";
				$responseArray["errmsgiconid"] = "16";
				$responseArray["headerlabel"] = "Unsupported action";
				$responseButtonArray["button"]["iconid"] = IdToAppImagesMapping::DISABLE_CONTACT;
			}
		}
		$finalresponseArray["actiondetails"] = ButtonResponse::actiondetailsMerge($responseArray);
		$finalresponseArray["buttondetails"] = buttonResponse::buttondetailsMerge($responseButtonArray);
		if(MobileCommon::isNewMobileSite())
		{
			$finalresponseArray["buttondetails"] = ButtonResponseFinal::getListingButtons("CC","M","S","R");
			$restResponseArray= $buttonObj->jsmsRestButtonsrray();
			$finalresponseArray["buttondetails"]["photo"]=$thumbNail;
			//$finalresponseArray["button_after_action"]["photo"]=$restResponseArray["photo"];
            $finalresponseArray["buttondetails"]["topmsg"]=$restResponseArray["topmsg"];
			//$finalresponseArray["button_after_action"][] =

		}
		else
		{
                $button_after_action = $buttonObj->getButtonArray();
                $finalresponseArray["button_after_action"] = ButtonResponse::buttondetailsMerge($button_after_action);
		}
		return $finalresponseArray;
	}
}
