<?php
/**
 * contacts actions.
 *
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Tanu Gupta
 * @version    SVN: $Id: actions.class.php 23810 2012-12-03 11:07:44Z Kris.Wallsmith $
 */
class postEOIv2Action extends sfAction
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */

	function execute($request){
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		$apiObj                  = ApiResponseHandler::getInstance();

		if ($request->getParameter("actionName")=="postEOI")
		{
			$inputValidateObj->validateContactActionData($request);
			$output = $inputValidateObj->getResponse();
			if($output["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
			{
				$this->loginData    = $request->getAttribute("loginData");
				//Contains logined Profile information;
				$this->loginProfile = LoggedInProfile::getInstance();
			//	$this->loginProfile->getDetail($this->loginData["PROFILEID"], "PROFILEID");

				if ($this->loginProfile->getPROFILEID()) {
					$this->userProfile = $request->getParameter('profilechecksum');
					if ($this->userProfile) {

						$this->Profile = new Profile();
						$profileid     = JsCommon::getProfileFromChecksum($this->userProfile);
						$this->Profile->getDetail($profileid, "PROFILEID");


						$this->contactObj = new Contacts($this->loginProfile, $this->Profile);
					}
					$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,'I',ContactHandler::POST);
                    if($request->getParameter('chatMessage'))
                        $this->contactHandlerObj->setElement("MESSAGE",$request->getParameter('chatMessage'));
                    else
          				$this->contactHandlerObj->setElement("MESSAGE","");
					$this->contactHandlerObj->setElement("DRAFT_NAME","preset");
					$this->contactHandlerObj->setElement("STATUS","I");
					$this->contactHandlerObj->setElement("STYPE",$this->getParameter($request,"stype"));
					$this->contactHandlerObj->setElement("PROFILECHECKSUM",$this->getParameter($request,"profilechecksum"));
					if($this->getParameter($request,"page_source") || $this->getParameter($request,"pageSource"))
						$this->contactHandlerObj->setPageSource($this->getParameter($request,"page_source")?$this->getParameter($request,"page_source"):$this->getParameter($request,"pageSource"));
					else
						$this->contactHandlerObj->setPageSource('JSMS');
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
		$internal = $request->getParameter("internal");
		if($internal == 1){
			return sfView::NONE;
		} else {
			die;
		}
	}


	private function getContactArray($request)
	{
		$pictureServiceObj=new PictureService($this->Profile);
		$profilePicObj = $pictureServiceObj->getProfilePic();
		$this->loginProfile = LoggedInProfile::getInstance();
		$subscription=$this->loginProfile->getSubscription();
		if($profilePicObj)
			$thumbNail = $profilePicObj->getThumbailUrl();
		if(!$thumbNail)
			$thumbNail = null;
		$thumbNail = PictureFunctions::mapUrlToMessageInfoArr($thumbNail,'ProfilePic120Url',1,$this->Profile->getGENDER());
		unset($pictureServiceObj);
		unset($profilePicObj);
		$pictureServiceObj=new PictureService($this->loginProfile);
		$profilePicObj = $pictureServiceObj->getProfilePic();
		if($profilePicObj)
			$ownthumbNail = $profilePicObj->getThumbailUrl();
		if(!$ownthumbNail)
			$ownthumbNail = null;
		$ownthumbNail = PictureFunctions::mapUrlToMessageInfoArr($ownthumbNail,'ProfilePic120Url',1,$this->loginProfile->getGENDER());
		$ownthumbNail = $ownthumbNail['url'];
		$privilegeArray = $this->contactEngineObj->contactHandler->getPrivilegeObj()->getPrivilegeArray();
		$buttonObj = new ButtonResponse($this->loginProfile,$this->Profile,"",$this->contactHandlerObj);
		$errorArr = $this->contactEngineObj->errorHandlerObj->getErrorType();
		$onlyUnderScreenError = 0; // No errors
		if($errorArr["UNDERSCREENING"] == 2 && $this->contactEngineObj->getComponent())
		{
			$onlyUnderScreenError = 1;
		}
		if($onlyUnderScreenError == 1 || $onlyUnderScreenError == 0)
		{
			$responseButtonArray["button"] = $buttonObj->getInitiatedButton();
		}


		if($this->contactEngineObj->messageId)
		{
			if($privilegeArray["0"]["SEND_REMINDER"]["MESSAGE"] == "Y")
			{
				$responseArray["headerthumbnailurl"] = $thumbNail;
				$responseArray["headerlabel"] = $this->Profile->getUSERNAME();
				$responseArray["selfthumbnailurl"] = $ownthumbNail;
				$contactId = $this->contactEngineObj->contactHandler->getContactObj()->getCONTACTID();
				$param = "&messageid=".$this->contactEngineObj->messageId."&type=I&contactId=".$contactId;
				$responseArray["writemsgbutton"] = ButtonResponse::getCustomButton("Send","","SEND_MESSAGE",$param,"");
				$responseArray['draftmessage'] = "Interest sent. You may send a personalized message with the interest.";
				$responseArray['lastsent'] = LastSentMessage::getLastSentMessage($this->loginProfile->getPROFILEID(),"I");


			}
			else
			{
				if(MobileCommon::isDesktop() && sfContext::getInstance()->getRequest()->getParameter("myjs")!=1)
				{
					$memHandlerObj = new MembershipHandler();
					$data2 = $memHandlerObj->fetchHamburgerMessage($request);
					$MembershipMessage = $data2['hamburger_message']['top'];
                    $MembershipMessage = $memHandlerObj->modifiedMessage($data2);
					if($this->contactHandlerObj->getPageSource()=="search")
					{
						$responseArray["errmsglabel"]= "Upgrade to send personalized messages or initiate chat";
						$responseArray["viewSimilarUsername"]= $this->Profile->getUSERNAME();
						$responseArray["headerlabel_viewSimilar"]= "Interest sent to ".$this->Profile->getUSERNAME();
					}
					else{
						$responseArray["errmsglabel"]= "Interest sent. Upgrade to send personalized messages or initiate chat";
					}


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
			$responseArray["redirect"] = false;
			if($errorArr["PROFILE_IGNORE"] == 2)
			{
				$responseArray["errmsglabel"] = $this->contactEngineObj->errorHandlerObj->getErrorMessage();
				$responseArray["errmsgiconid"] = IdToAppImagesMapping::DISABLE_CONTACT;
				$responseArray["headerlabel"] = "Blocked Profile";
			}
			elseif($errorArr["SAMEGENDER"] == 2)
			{
				$responseArray["errmsglabel"] = "You cannot send interest to a person of the same gender";
				$responseArray["errmsgiconid"] = IdToAppImagesMapping::SAME_GENDER;
				$responseArray["headerlabel"] = "Gender is incompatible";
				$responseButtonArray["button"]["iconid"] = IdToAppImagesMapping::DISABLE_CONTACT;
			}
			elseif($errorArr["PHONE_NOT_VERIFIED"] == 2)
			{
				$responseArray["headerlabel"] = "Phone Verification Complusory";
				$responseArray["errmsglabel"] = "Its is complusory to verify your number on jeevansathi.com or you will not able send interest. \n\n You only have to give the missed call ";
				$responseArray["errmsgiconid"] = IdToAppImagesMapping::PHONE_NOT_VERIFIED;
				$responseArray["footerbutton"]["label"] = "Verify your number";
				$responseArray["footerbutton"]["value"] = "";
				$responseArray["footerbutton"]["action"] = "PHONEVERIFICATION";
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
				$responseArray["errmsglabel"] = "You cannot express interest as your profile is hidden";
				$responseArray["errmsgiconid"] = "13";
				$responseArray["headerlabel"] = "Your Profile is Hidden";
				$responseButtonArray["button"]["iconid"] = IdToAppImagesMapping::DISABLE_CONTACT;
			}
			elseif($errorArr["PROFILE_VIEWED_HIDDEN"] == 2)
			{
				$responseArray["errmsglabel"]= $this->contactEngineObj->errorHandlerObj->getErrorMessage();
				$responseArray["errmsgiconid"] = "16";
				$responseArray["headerlabel"] = "Unsupported action";
                                $responseButtonArray["button"]["iconid"] = IdToAppImagesMapping::DISABLE_CONTACT;

			}
			elseif($errorArr["EOI_CONTACT_LIMIT"] == 2)
			{
				$membershipText = " Become a paid member to send more interests";
				if($errorArr["LIMIT"] == "TOTAL")
				{
					$responseArray["errmsglabel"]= 'You have exceeded the limit of the number of interests you can send.';
					if(!$this->loginProfile->getPROFILE_STATE()->getPaymentStates()->isPAID())
					{
						$responseArray["errmsglabel"] = $responseArray["errmsglabel"].$membershipText;
					}
				}
				else
				{
					switch ($errorArr["LIMIT"]){
						case "DAY":
							$strdate = date( 'F j, Y');
							break;
						case "WEEK":
							$strdate = date('F j,Y', strtotime(CommonFunction::getLimitEndingDate($errorArr["LIMIT"])));
						    break;
						case "MONTH":
							$strdate = date('F j,Y', strtotime(CommonFunction::getLimitEndingDate($errorArr["LIMIT"])));
							break;
					}
					$responseArray["errmsglabel"]= 'You have exceeded the limit of the number interests you can send for the '.strtolower($errorArr["LIMIT"]).' ending '.$strdate.'.';
					if(!$this->loginProfile->getPROFILE_STATE()->getPaymentStates()->isPAID())
					{
						$responseArray["errmsglabel"]= $responseArray["errmsglabel"].$membershipText;
					}
				}
				$responseArray["errmsgiconid"] = "13";
				$responseArray["headerlabel"] = "Limit Exceeded";
				if(!$this->loginProfile->getPROFILE_STATE()->getPaymentStates()->isPAID() && $errorArr["EOI_CONTACT_LIMIT"] == 2)
				{
					$memHandlerObj = new MembershipHandler();
					$data2 = $memHandlerObj->fetchHamburgerMessage($request);
					$MembershipMessage = $data2['hamburger_message']['top'];
                    $MembershipMessage = $memHandlerObj->modifiedMessage($data2);
					$responseArray["footerbutton"]["label"]  = "View Membership Plans";
					$responseArray["footerbutton"]["value"] = "";
					$responseArray["footerbutton"]["action"] = "MEMBERSHIP";
					$responseArray["footerbutton"]["text"] = $MembershipMessage;
				}

			}

			elseif($errorArr["INCOMPLETE"] == 2)
			{
				$responseArray["errmsglabel"] = "Currently your profile is not complete. Your interest would be delivered only when your profile is complete.";
				$responseArray["errmsgiconid"] = IdToAppImagesMapping::INCOMPLETE;
				$responseArray["footerbutton"]["label"] = "complete your profile";
				$responseArray["footerbutton"]["value"] = "";
				$responseArray["footerbutton"]["action"] = "COMPLETEPROFILE";
				$responseArray["headerlabel"] = "Your Profile is Incomplete";
				$responseArray["redirect"] = true;
			}
			elseif($errorArr["UNDERSCREENING"] == 2)
			{
				$responseArray["topMsg2"] = "Interest will be delivered once your profile is screened";
				$responseArray["errmsglabel"] = "Your interest has been saved and will be sent after screening. Content of each profile created on Jeevansathi is manually screened for best experience of our users and may take up to 24 hours.";
				$responseArray["errmsgiconid"] = IdToAppImagesMapping::UNDERSCREENING;
				$responseArray["remove3Dots_UnderScreen"] = true;
				$responseArray["headerlabel"] = "Profile Under Screening";
				$responseArray["redirect"] = true;
			}
			elseif($errorArr["DECLINED"] == 2)
			{
				$responseArray["errmsglabel"]  = $this->contactEngineObj->errorHandlerObj->getErrorMessage();
				$responseArray["errmsgiconid"] = IdToAppImagesMapping::UNDERSCREENING;
				$responseArray["headerlabel"]  = "Profile not available";
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
		$finalresponseArray["buttondetails"] = ButtonResponse::buttondetailsMerge($responseButtonArray);
		if($request->getParameter("pageSource") == "chat" && $request->getParameter("channel") == "pc" && $request->getParameter("setFirstEoiMsgFlag") == true)
        {
        	if($this->contactEngineObj->messageId){
				$finalresponseArray["eoi_sent"] = true;
				$finalresponseArray["cansend"] = true;
            	$finalresponseArray["sent"] = true;
			}
			else{
				$finalresponseArray["eoi_sent"] = false;
				$finalresponseArray["cansend"] = false;
            	$finalresponseArray["sent"] = false;
        	}
		}
		if(MobileCommon::isNewMobileSite()  )
		{

      if(sfContext::getInstance()->getRequest()->getParameter('pageSource')!='VDP')
      {
        $finalresponseArray["button_after_action"] = ButtonResponseFinal::getListingButtons("CC","M","S","I");
        $restResponseArray= $buttonObj->jsmsRestButtonsrray();
        $finalresponseArray["button_after_action"]["photo"]=$thumbNail;
        //$finalresponseArray["button_after_action"]["photo"]["url"]=$thumbnail;
              $finalresponseArray["button_after_action"]["topmsg"]=$restResponseArray["topmsg"];
        //$finalresponseArray["button_after_action"][] =

      }
      else
      {
        if($errorArr["UNDERSCREENING"]!=2)
          $finalresponseArray["buttondetails"] = ButtonResponseFinal::getListingButtons("CE_PD","M","S","I");
        $restResponseArray= $buttonObj->jsmsRestButtonsrray();
        $finalresponseArray["buttondetails"]["photo"]=$thumbNail;
        $finalresponseArray["buttondetails"]["topmsg"]=$restResponseArray["topmsg"];
      }
		}
		else
		{
		$button_after_action = $buttonObj->getButtonArray();
		$finalresponseArray["button_after_action"] = ButtonResponse::buttondetailsMerge($button_after_action);
		}
		return $finalresponseArray;
	}
	private function getParameter($request,$type)
	{
		if($request->getParameter($type))
				return $request->getParameter($type);
		elseif($request->getParameter(strtoupper($type)))
				return $request->getParameter(strtoupper($type));
		else
				return $request->getParameter(strtolower($type));

	}
}
