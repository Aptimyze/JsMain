<?php
/**
 * contacts actions.
 *
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Pankaj Khandelwal
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class postDeclinev1Action extends sfAction
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  
	function execute($request){
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		$apiObj                  = ApiResponseHandler::getInstance();
		if ($request->getParameter("actionName")=="decline")
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
					$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,'D',ContactHandler::POST);
					$this->contactHandlerObj->setElement("STATUS","D");	
					$this->contactHandlerObj->setElement("MESSAGE",PresetMessage::getPresentMessage($this->loginProfile,$this->contactHandlerObj->getToBeType()));	
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
	
	
	private function getContactArray()
	{
		$privilegeArray = $this->contactEngineObj->contactHandler->getPrivilegeObj()->getPrivilegeArray();
		$buttonObj = new ButtonResponse($this->loginProfile,$this->Profile,array("source"=>$this->source),$this->contactHandlerObj);
		if($this->contactEngineObj->messageId)
		{
			$responseButtonArray = $buttonObj->getAfterActionButton(ContactHandler::DECLINE);
		}
		else
		{
			$responseArray["errmsglabel"]= "You cannot perform this action";
			$responseArray["errmsgiconid"] = "16";
			$responseArray["headerlabel"] = "Unsupported action";
		}
		$finalresponseArray["actiondetails"] = ButtonResponse::actiondetailsMerge($responseArray);
		$finalresponseArray["buttondetails"] = buttonResponse::buttondetailsMerge($responseButtonArray);
		return $finalresponseArray;		
	}
}

