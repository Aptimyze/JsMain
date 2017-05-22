<?php
/**
 * contacts actions.
 *
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Pankaj Khandelwal
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class postAcceptv1Action extends sfAction
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  
	function execute($request){
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		$apiObj                  = ApiResponseHandler::getInstance();
		if ($request->getParameter("actionName")=="accept")
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
					$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,'A',ContactHandler::POST);
					$this->contactHandlerObj->setElement("STATUS","A");
					$this->contactHandlerObj->setElement("MESSAGE","");
					$this->contactHandlerObj->setElement("DRAFT_NAME","preset");
					$this->contactHandlerObj->setElement("RESPONSETRACKING",$request->getParameter('responseTracking'));
					$this->contactEngineObj=ContactFactory::event($this->contactHandlerObj);
					$responseArray           = $this->getContactArray($request);
				}
			}
		}
		if (is_array($responseArray)) {
			$apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$apiObj->setResponseBody($responseArray);
			$apiObj->setResetCache(true);
			$apiObj->setUserActionState(2);
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
		$privilegeArray = $this->contactEngineObj->contactHandler->getPrivilegeObj()->getPrivilegeArray();
		$buttonObj = new ButtonResponse($this->loginProfile,$this->Profile,"",$this->contactHandlerObj);
		if($request->getParameter("page_source") == "chat" && $request->getParameter("channel") == "A")
		{
			$actionType = "CHATACCEPT";
		}
		else{
			$actionType = ContactHandler::ACCEPT;
		}
		
		if($this->contactEngineObj->messageId)
		{
			$responseButtonArray = $buttonObj->getAfterActionButton($actionType);
		}
		else
		{
			$errorArr = $this->contactEngineObj->errorHandlerObj->getErrorType();
			if($errorArr["DELETED"]==2)
			{
				$responseArray["errmsglabel"] = "This profile has been deleted";
				$responseArray["errmsgiconid"] = "13";
				$responseArray["headerlabel"] = "Deleted Profile";
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
			elseif($errorArr["PROFILE_IGNORE"] == 2)
			{
				$responseArray["errmsglabel"] = $this->contactEngineObj->errorHandlerObj->getErrorMessage();
				$responseArray["headerlabel"] = "blocked Profile";
				$responseArray["errmsgiconid"] = IdToAppImagesMapping::DISABLE_CONTACT;
			}
			elseif($errorArr["INCOMPLETE"] == 2)
			{
				$responseArray["errmsglabel"] = "Profile is Incomplete Expression of interest will be delivered only on profile is completed and is live. ";
				$responseArray["errmsgiconid"] = IdToAppImagesMapping::INCOMPLETE;
				$responseArray["footerbutton"]["label"] = "complete your profile";
				$responseArray["footerbutton"]["value"] = "";
				$responseArray["footerbutton"]["action"] = "COMPLETEPROFILE";
				$responseArray["headerlabel"] = "Your Profile is Incomplete";					
			}
			elseif($errorArr["UNDERSCREENING"] == 2)
			{
				$responseArray["errmsglabel"] = "Expession of interest will be delivered only when Profile is live";
				$responseArray["errmsgiconid"] = IdToAppImagesMapping::UNDERSCREENING;
				$responseArray["headerlabel"] = "Profile is Underscreening";
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
		return $finalresponseArray;
	}
}

