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
		$aadharVerificationObj = new aadharVerification();
		$this->profileId = $this->loginProfile->getPROFILEID();
		$this->username = $this->loginProfile->getUSERNAME();
		$aadharId = $request->getParameter("aid");
		$nameOfUser = $request->getParameter("name");
		$returnId = $aadharVerificationObj->preVerification($aadharId,$this->profileId);
		if(strlen($aadharId) != aadharVerificationEnums::AADHARLENGTH) //to check the length of aadhar number
		{
			$errorArr["ERROR"] = aadharVerificationEnums::IMPROPERFORMAT;
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiResponseHandlerObj->setResponseBody($errorArr);
		}
		elseif($returnId) //aadhar already entered and verified
		{
			if($returnId == $this->profileId)
			{
				$errorArr["ERROR"] = aadharVerificationEnums::AADHARVERIFIED;
				$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$AADHAR_ALREADY_VERIFIED_BY_SAME);
				$apiResponseHandlerObj->setResponseBody($errorArr);
			}
			else
			{
				$errorArr["ERROR"] = aadharVerificationEnums::ALREADYVERIFIED;
				$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$AADHAR_ALREADY_VERIFIED);
				$apiResponseHandlerObj->setResponseBody($errorArr);
			}
			
		} 
		else
		{		
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