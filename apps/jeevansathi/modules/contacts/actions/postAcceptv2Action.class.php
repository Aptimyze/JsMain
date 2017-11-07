<?php
/**
 * contacts actions.
 *
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Pankaj Khandelwal
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class postAcceptv2Action extends sfAction
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */

	function execute($request){
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		$apiObj                  = ApiResponseHandler::getInstance();
		if ($request->getParameter("actionName")=="accept" || $request->getParameter("actionName")=="postAccept")
		{
			$inputValidateObj->validateContactActionData($request);
			$output = $inputValidateObj->getResponse();
			if($output["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
			{
				$this->loginData    = $request->getAttribute("loginData");
				//Contains logined Profile information;
				$this->loginProfile = LoggedInProfile::getInstance();
			//	$this->loginProfile->getDetail($this->loginData["PROFILEID"], "PROFILEID");
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
					$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,'A',ContactHandler::POST);
					$this->contactHandlerObj->setElement("STATUS","A");
					$this->contactHandlerObj->setElement("MESSAGE","");
					$this->contactHandlerObj->setElement("DRAFT_NAME","preset");
					$this->contactHandlerObj->setElement("RESPONSETRACKING",$request->getParameter('responseTracking'));
					$this->contactEngineObj=ContactFactory::event($this->contactHandlerObj);
					$responseArray           = $this->getContactArray();
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
                if($request->getParameter("internal") == 1)
                return sfView::NONE;
		die;
	}


	private function getContactArray()
	{
		$request=sfContext::getInstance()->getRequest();
		$privilegeArray = $this->contactEngineObj->contactHandler->getPrivilegeObj()->getPrivilegeArray();
		$buttonObj = new ButtonResponse($this->loginProfile,$this->Profile,array("source"=>$this->source),$this->contactHandlerObj);
		$pictureServiceObj=new PictureService($this->Profile);
		$profilePicObj = $pictureServiceObj->getProfilePic();
		if($profilePicObj)
			$thumbNail = $profilePicObj->getThumbailUrl();
		if(!$thumbNail)
			$thumbNail = null;
		$thumbNail = PictureFunctions::mapUrlToMessageInfoArr($thumbNail,'ThumbailUrl',1);
		unset($pictureServiceObj);
		unset($profilePicObj);
		if($this->contactEngineObj->messageId)
		{
			$responseButtonArray = $buttonObj->getAfterActionButton(ContactHandler::ACCEPT);
			if($privilegeArray["0"]["COMMUNICATION"]["MESSAGE"] != "Y" && MobileCommon::isDesktop())
			{
				if(!$request->getParameter("myjs")){
					$memHandlerObj = new MembershipHandler();
					$data2 = $memHandlerObj->fetchHamburgerMessage($request);
					$MembershipMessage = $data2['hamburger_message']['top'];
                    $MembershipMessage = $memHandlerObj->modifiedMessage($data2);
					$responseArray["errmsglabel"]= "Upgrade your membership to send personalized messages or initiate chat";
					$responseArray["footerbutton"]["label"]  = "Upgrade";
					$responseArray["footerbutton"]["value"] = "";
					$responseArray["footerbutton"]["action"] = "MEMBERSHIP";
					$responseArray["footerbutton"]["text"] = $MembershipMessage;
				}
			}
			else
			{
					$responseArray["notused"]= "true";
			}
			$responseArray["redirect"]= true;
		}
		else
		{
			$errorArr = $this->contactEngineObj->errorHandlerObj->getErrorType();
			$responseArray["redirect"]= false;
			if($errorArr["PROFILE_IGNORE"] == 2)
			{
				$responseArray["errmsglabel"] = $this->contactEngineObj->errorHandlerObj->getErrorMessage();
				$responseArray["errmsgiconid"] = IdToAppImagesMapping::DISABLE_CONTACT;
				$responseArray["headerlabel"] = "Blocked Profile";
			}
			elseif($errorArr["DELETED"]==2)
			{
				$responseArray["errmsglabel"] = "This profile has been deleted";
				$responseArray["errmsgiconid"] = "13";
				$responseArray["headerlabel"] = "Deleted Profile";
			}
			elseif($errorArr["PROFILE_HIDDEN"] == 2)
			{
				$responseArray["errmsglabel"] = "This profile is Hidden";
				$responseArray["errmsgiconid"] = "13";
				$responseArray["headerlabel"] = "Hidden Profile";
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
				$responseArray["errmsglabel"]= 'You have exceeded limit of expresssion of interest for this '.$errorArr["LIMIT"];
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
				$responseArray["redirect"]= true;				
			}
			elseif($errorArr["UNDERSCREENING"] == 2)
			{
				$responseArray["errmsglabel"] = "Expession of interest will be delivered only when Profile is live";
				$responseArray["errmsgiconid"] = IdToAppImagesMapping::UNDERSCREENING;
				$responseArray["headerlabel"] = "Profile is Underscreening";
				$responseArray["redirect"]= true;
			}
			else
			{
				$responseArray["errmsglabel"]= "You cannot perform this action";
				$responseArray["errmsgiconid"] = "16";
				$responseArray["headerlabel"] = "Unsupported action";
			}
		}
		$finalresponseArray["actiondetails"] = ButtonResponse::actiondetailsMerge($responseArray);
		$finalresponseArray["buttondetails"] = buttonResponse::buttondetailsMerge($responseButtonArray);
		if(MobileCommon::isNewMobileSite())
		{

      if(sfContext::getInstance()->getRequest()->getParameter('fromSPA')!='1')
      {
    			if($this->contactObj->getsenderObj()->getPROFILEID() == $this->contactHandlerObj->getViewer()->getPROFILEID())
    			$finalresponseArray["button_after_action"] = ButtonResponseFinal::getListingButtons("CC","M","S","A");
    			else
    			$finalresponseArray["button_after_action"] = ButtonResponseFinal::getListingButtons("CC","M","R","A");

    			$restResponseArray= $buttonObj->jsmsRestButtonsrray();
    			$finalresponseArray["button_after_action"]["photo"]=$thumbNail;
                $finalresponseArray["button_after_action"]["topmsg"]=$restResponseArray["topmsg"];
			//$finalresponseArray["button_after_action"][] =
    }
    else
    {
      $restResponseArray= $buttonObj->jsmsRestButtonsrrayNew();
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
}
