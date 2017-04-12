<?php
/**
 * contacts actions.
 *
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Pankaj Khandelwal
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class postEOIv1Action extends sfAction
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
				$this->loginProfile->getDetail($this->loginData["PROFILEID"], "PROFILEID");
				
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
					if($this->getParameter($request,"page_source"))
						$this->contactHandlerObj->setPageSource($this->getParameter($request,"page_source"));
					else
						$this->contactHandlerObj->setPageSource('App');
					$this->contactEngineObj=ContactFactory::event($this->contactHandlerObj);
					$responseArray           = $this->getContactArray($request);
				}
			}
		}
		if (is_array($responseArray)) {
			$apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$apiObj->setResponseBody($responseArray);
			$apiObj->setResetCache(true);
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
		$this->loginProfile = LoggedInProfile::getInstance();
		$subscription=$this->loginProfile->getSubscription();
		if($profilePicObj)
			$thumbNail = $profilePicObj->getThumbailUrl();
		if(!$thumbNail)
			$thumbNail = null;
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
		$buttonObj = new ButtonResponse($this->loginProfile,$this->Profile,"",$this->contactHandlerObj);
		if($this->getParameter($request,"page_source") == "chat" && $this->getParameter($request,"channel") == "A")
		{
			$androidText = true;
			$responseButtonArray["buttons"][] = $buttonObj->getInitiatedButton($androidText,$privilegeArray);
			$responseButtonArray["cansend"] = true;
			$responseButtonArray["sent"] = true;
		}
		$responseButtonArray["button"] = $buttonObj->getInitiatedButton($androidText,$privilegeArray);
		if($this->contactEngineObj->messageId)
		{
        	if($privilegeArray["0"]["SEND_REMINDER"]["MESSAGE"] == "Y")
			{
				$contactId = $this->contactEngineObj->contactHandler->getContactObj()->getCONTACTID(); 
				$param = "&messageid=".$this->contactEngineObj->messageId."&type=I&contactId=".$contactId;
				$responseArray["writemsgbutton"] = ButtonResponse::getCustomButton("Send","","SEND_MESSAGE",$param,"");
				$responseArray['lastsent'] = LastSentMessage::getLastSentMessage($this->loginProfile->getPROFILEID(),"I");
                                if($request->getParameter('API_APP_VERSION')>=80 && $this->getParameter($request,"page_source") != "chat")
					$responseArray['errmsglabel'] = "Write a personalized message to ".$this->Profile->getUSERNAME()." along with your interest";
		                        $responseArray["headerthumbnailurl"] = $thumbNail;
                        		$responseArray["headerlabel"] = $this->Profile->getUSERNAME();
                        		$responseArray["selfthumbnailurl"] = $ownthumbNail;
			}

                        if($this->getParameter($request,"page_source") == "VDP")
			{
				$redirection = "true";
			}
			else
			{
				$redirection = "false";
			}	
		}
		else
		{
			$errorArr = $this->contactEngineObj->errorHandlerObj->getErrorType();
			if($errorArr["SAMEGENDER"] == 2)
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

			elseif($errorArr["PROFILE_IGNORE"] == 2)
			{
				$responseArray["errmsglabel"] = $this->contactEngineObj->errorHandlerObj->getErrorMessage();
				$responseArray["headerlabel"] = "Blocked Profile";
				$responseArray["errmsgiconid"] = IdToAppImagesMapping::DISABLE_CONTACT;
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
							$strdate = date('F t,Y', strtotime(CommonFunction::getLimitEndingDate($errorArr["LIMIT"])));
							break;
					}
					$responseArray["errmsglabel"]= 'You have exceeded the limit of the number interests you can send for this '.strtolower($errorArr["LIMIT"]).' ending '.$strdate.'.';
					if(!$this->loginProfile->getPROFILE_STATE()->getPaymentStates()->isPAID())
					{
						$responseArray["errmsglabel"]= $responseArray["errmsglabel"].$membershipText;
					}
				}
				$responseArray["errmsgiconid"] = "13";
				$responseArray["headerlabel"] = "Limit Exceeded";
				if(!$this->loginProfile->getPROFILE_STATE()->getPaymentStates()->isPAID())
				{
				if(strpos($request->getParameter("newActions"), "MEMBERSHIP")!== false )
					{
						$memHandlerObj = new MembershipHandler();
						$data2 = $memHandlerObj->fetchHamburgerMessage($request);
						$MembershipMessage = $data2['hamburger_message']['top']; 
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
			}
			elseif($errorArr["UNDERSCREENING"] == 2)
			{
				$responseArray["errmsglabel"] = "Your interest has been saved and will be sent after screening. Content of each profile created on Jeevansathi is manually screened for best experience of our users and may take up to 24 hours.";
				$responseArray["errmsgiconid"] = IdToAppImagesMapping::UNDERSCREENING;
				$responseArray["headerlabel"] = "Profile Under Screening";
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
		$finalresponseArray["buttondetails"] = buttonResponse::buttondetailsMerge($responseButtonArray);
		$finalresponseArray['redirection'] = $redirection;
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

