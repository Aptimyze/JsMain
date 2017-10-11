<?php
/**
 * contacts actions.
 *
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Pankaj Khandelwal
 * @version    SVN: JSI-1074: PreWriteMessagev2Action.class.php 23810 2009-11-12 11:07:44Z Pankaj Khandelwal $
 */
class PreWriteMessagev2Action extends sfAction
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  
  function execute($request){
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		$apiObj                  = ApiResponseHandler::getInstance();
		if ($request->getParameter("actionName")=="preWriteMessage")
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
					$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,'M',ContactHandler::PRE);
					$this->contactEngineObj=ContactFactory::event($this->contactHandlerObj);
					$responseArray = $this->getContactArray($messageDetailsArr,$request);
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
	
	private function getContactArray($messageDetailsArr,$request)
	{
		$responseButtonArray = array();	
		$privilegeArray = $this->contactEngineObj->contactHandler->getPrivilegeObj()->getPrivilegeArray();
		if($privilegeArray["0"]["COMMUNICATION"]["MESSAGE"] == "Y")
		{
			//$responseArray["headerlabel"] = $this->Profile->getUSERNAME();
			$responseArray["selfthumbnailurl"] = $ownthumbNail;
			$responseArray["writemsgbutton"] = ButtonResponse::getCustomButton("Send","","WRITE_MESSAGE","","");
		}
		else//if($privilegeArray["0"]["COMMUNICATION"]["MESSAGE"] == "N")
		{
			$memHandlerObj = new MembershipHandler();
			$data2 = $memHandlerObj->fetchHamburgerMessage($request);
			$MembershipMessage = $data2['hamburger_message']['top'];              
            $MembershipMessage = $memHandlerObj->modifiedMessage($data2);
			//$MembershipMessage = "get 30% off";
			$errorArr = $this->contactEngineObj->errorHandlerObj->getErrorType();
			if($errorArr["PROFILE_VIEWED_HIDDEN"] == 2)
			{
				$responseArray["errmsglabel"]= $this->contactEngineObj->errorHandlerObj->getErrorMessage();
				$responseArray["errmsgiconid"] = "16";
				$responseArray["headerlabel"] = "Unsupported action";
				$responseButtonArray["button"]["iconid"] = IdToAppImagesMapping::DISABLE_CONTACT;

			}
			else
			{
				$responseArray["errmsglabel"]= "Upgrade membership to Send messages & initiate chat with ".$this->Profile->getUSERNAME();
				$responseArray["footerbutton"]["label"]  = "View Membership Plans";
				$responseArray["footerbutton"]["value"] = "";
				$responseArray["footerbutton"]["action"] = "MEMBERSHIP";
				$responseArray["footerbutton"]["text"] = $MembershipMessage;
			}
			
		}
		$finalresponseArray["actiondetails"] = ButtonResponse::actiondetailsMerge($responseArray);
		$finalresponseArray["buttondetails"] = ButtonResponse::buttondetailsMerge($responseButtonArray);
		return $finalresponseArray;
		
	}
}

