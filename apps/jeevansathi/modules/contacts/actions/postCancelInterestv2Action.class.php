<?php
/**
 * contacts actions.
 *
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Pankaj Khandelwal
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class postCancelInterestv2Action extends sfAction
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */

  function execute($request){
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		$apiObj                  = ApiResponseHandler::getInstance();
		if ($request->getParameter("actionName")=="cancel" || $request->getParameter("actionName")=="postCancelInterest")
		{
			if($request->getParameter("actionName")=="postCancelInterest")
			{
				$this->tobetype = ContactHandler::CANCEL_CONTACT;

			}
			elseif($request->getParameter("actionName")=="cancel")
			{
				$this->tobetype = ContactHandler::CANCEL;
			}
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
						if($this->contactObj->getType() == ContactHandler::ACCEPT)
							$this->tobetype = ContactHandler::CANCEL;
					}
					$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,$this->tobetype,ContactHandler::POST);
					$this->contactHandlerObj->setElement("STATUS",$this->tobetype);
					$this->contactHandlerObj->setElement("MESSAGE","");
					$this->contactHandlerObj->setElement("DRAFT_NAME","preset");
					$this->contactEngineObj=ContactFactory::event($this->contactHandlerObj);
					$responseArray           = $this->getContactArray();
				}
			}
		}
		if (is_array($responseArray)) {
			//CommonFunction::removeCanChat($this->loginProfile->getPROFILEID(),$this->Profile->getPROFILEID());
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


	private function getContactArray()
	{
		$privilegeArray = $this->contactEngineObj->contactHandler->getPrivilegeObj()->getPrivilegeArray();
		$buttonObj = new ButtonResponse($this->loginProfile,$this->Profile,array("source"=>$this->source),$this->contactHandlerObj);
		$responseButtonArray = array();
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
			$responseButtonArray = $buttonObj->getAfterActionButton($this->tobetype);
			$responseArray["notused"] = true;
		}
		else
		{
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
        if(sfContext::getInstance()->getRequest()->getParameter('pageSource')!='VDP')
        {
          $finalresponseArray["button_after_action"] = ButtonResponseFinal::getListingButtons("CC","M","S","C");
    			$restResponseArray= $buttonObj->jsmsRestButtonsrray();
    			$finalresponseArray["button_after_action"]["photo"]=$thumbNail;
          $finalresponseArray["button_after_action"]["topmsg"]=$restResponseArray["topmsg"];

        }
        else
        {
          $restResponseArray= $buttonObj->jsmsRestButtonsrray();
          $finalresponseArray["buttondetails"]["topmsg"]=$restResponseArray["topmsg"];
          $finalresponseArray["button_after_action"]["photo"]=$thumbNail;

        }

			//$finalresponseArray["button_after_action"][] =

		}

		else
		{
                $button_after_action = $buttonObj->getButtonArray();
                $finalresponseArray["button_after_action"] = ButtonResponse::buttondetailsMerge($button_after_action);
		}
		//print_r($finalresponseArray["button_after_action"]);die;
		return $finalresponseArray;
	}
}
