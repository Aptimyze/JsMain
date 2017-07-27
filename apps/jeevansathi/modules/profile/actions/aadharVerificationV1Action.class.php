<?php
/**
 * This api is to send a post request to Idfy with aadhar details to get the request Id in response
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Sanyam Chopra
 * @date	   13th July 2017
 */
class aadharVerificationV1Action extends sfActions 
{	
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{
		$apiResponseHandlerObj=ApiResponseHandler::getInstance();
		$this->loginProfile = LoggedInProfile::getInstance();
		$this->profileId = $this->loginProfile->getPROFILEID();
		$this->username = $this->loginProfile->getUSERNAME();
		$aadharId = $request->getParameter("aid");
		$nameOfUser = $request->getParameter("name");
		if(strlen($aadharId) != aadharVerificationEnums::AADHARLENGTH) //to check the length of aadhar number
		{
			$errorArr["ERROR"] = aadharVerificationEnums::IMPROPERFORMAT;
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiResponseHandlerObj->setResponseBody($errorArr);
		}
		else
		{
			$nameOfUserObj=new NameOfUser();        
			$nameOfUserArr = $nameOfUserObj->getNameData($this->profileId);		
			if (strcasecmp($nameOfUserArr[$this->profileId]["NAME"], $nameOfUser) != 0) //check if the user changed the name on the CAL
			{
				$nameArr["NAME"] = $nameOfUser;
				$nameOfUserObj->updateName($this->profileId,$nameArr);				
			}
			$aadharVerificationObj = new aadharVerification();			
			$response = $aadharVerificationObj->callAadharVerificationApi($aadharId,$nameOfUser,$this->profileId,$this->username);
			unset($aadharVerificationObj);
			unset($nameOfUserObj);
			if($response)
			{
				$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);		
			}			
			else
			{
				$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			}
		}		        
        $apiResponseHandlerObj->generateResponse();
		return sfView::NONE; 
	}
}