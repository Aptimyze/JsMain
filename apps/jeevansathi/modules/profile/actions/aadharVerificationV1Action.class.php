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

	const AADHAR_CONSENT = "I, the holder of Aadhaar number, hereby give my consent to Baldor Technologies Private Limited, to obtain my Aadhaar number, name, date of birth, address and demographic data for authentication with UIDAI. Baldor Technologies Private Limited has informed me that my identity information would only be used for a background check or a verification of my identity and has also informed me that my biometrics will not be stored/ shared and will be submitted to CIDR only for the purpose of authentication. I have no objection if reports generated from such background check are shared with relevant third parties.";

	const TYPE = "aadhaar_verification";
	const GROUPID = "20f46a37-e9d3-4d02-82f5-fce23abbf12d";
	const TASKID = "438d9786-2762-43f8-961f-4bd499d783d7";
	
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
		if(strlen($aadharId) != 12)
		{
			$errorArr["ERROR"] = "Aadhar Id is not in proper format";
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiResponseHandlerObj->setResponseBody($errorArr);
		}
		else
		{
			$nameOfUserObj=new NameOfUser();        
			$nameOfUserArr = $nameOfUserObj->getNameData($this->profileId);
			if (strcasecmp($nameOfUserArr[$this->profileId]["NAME"], $nameOfUser) != 0)
			{
				$nameArr["NAME"] = $nameOfUser;
				$nameOfUserObj->updateName($this->profileId,$nameArr);				
			}
			$this->callAadharVerificationApi($aadharId,$nameOfUser);			
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);	
		}		        
        $apiResponseHandlerObj->generateResponse();
		return sfView::NONE; 
	}

	public function callAadharVerificationApi($aadharId,$nameOfUser)
	{
		$aadharArr = array();
		$urlToHit = "https://api.idfy.com/v2/tasks";
		$headerArr = array(
    	'apikey:f97b05b2-9360-4706-91e7-6154513200a1',
    	'Content-Type:application/json',
		);

		$aadharArr["tasks"][0]["type"] = self::TYPE;
		$aadharArr["tasks"][0]["group_id"] = self::GROUPID;
		$aadharArr["tasks"][0]["task_id"] = self::TASKID;
		$aadharArr["tasks"][0]["data"]["aadhaar_number"] = $aadharId;
		$aadharArr["tasks"][0]["data"]["aadhaar_name"] = $nameOfUser;
		$aadharArr["tasks"][0]["data"]["aadhaar_consent"] = self::AADHAR_CONSENT;
		
		$response = json_decode(CommonUtility::sendCurlPOSTRequest($urlToHit,json_encode($aadharArr),"",$headerArr));
		$reqId = $response->request_id;
		$date = date("Y-m-d H:i:s");
		$aadharVerificationObj = new PROFILE_VERIFICATION_AADHAR_VERIFICATION("newjs_masterRep");
		$aadharVerificationObj->insertAadharDetails($this->profileId,$this->username,$date,$aadharId,$reqId);
	}
}