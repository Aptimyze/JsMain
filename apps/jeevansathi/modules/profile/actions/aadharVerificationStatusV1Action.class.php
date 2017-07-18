<?php
/**
 * This api is to send a get request to Idfy with requestId to get aadhar verification status
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Sanyam Chopra
 * @date	   13th July 2017
 */
class aadharVerificationStatusV1Action extends sfActions 
{
	CONST COMPLETED = "completed";
	CONST EXACTMATCH = "exact";
	CONST VERIFIED = "Y";
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
		$aadharVerificationObj = new aadharVerification();
		$dataArr = $aadharVerificationObj->getAadharDetails($this->profileId);
		$returnVal = $this->getVerificationStatus($dataArr[$this->profileId]["REQUEST_ID"]);
		
		if($returnVal == 0)
		{
			$finalArr["MESSAGE"] = "Aadhar Not Verified";
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiResponseHandlerObj->setResponseBody($finalArr);
		}
		elseif($returnVal == 2)
		{	
			$finalArr["MESSAGE"] = "Status Pending";
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiResponseHandlerObj->setResponseBody($finalArr);
		}
		else
		{
			$aadharVerificationObj->updateVerificationStatus($this->profileId,self::VERIFIED);
			$finalArr["MESSAGE"] = "Aadhar Verified";
			$finalArr["AADHAR_NO"]	= $dataArr[$this->profileId]["AADHAR_NO"];
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$apiResponseHandlerObj->setResponseBody($finalArr);
		}
		$apiResponseHandlerObj->generateResponse();
		return sfView::NONE;
	}

	public function getVerificationStatus($requestId)
	{
		$urlToHit = "https://api.idfy.com/v2/tasks?request_id=".$requestId;
		$headerArr  =array('apiKey:f97b05b2-9360-4706-91e7-6154513200a1');
		$output = json_decode(CommonUtility::sendCurlGetRequest($urlToHit,'',$headerArr));
		if($output[0]->status == self::COMPLETED)
		{
			if($output[0]->source_output[0]->match_result->name == self::EXACTMATCH)
			{
				return 1;
			}
			else
			{
				return 0;
			}			
		}
		else
		{
			return 2;
		}
	}
}