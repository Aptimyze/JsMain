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
		$this->aadharID = $dataArr[$this->profileId]["AADHAR_NO"];
		$returnArr = $this->getVerificationStatus($dataArr[$this->profileId]["REQUEST_ID"]);
		unset($aadharVerificationObj);
		$apiResponseHandlerObj->setHttpArray($returnArr["RESPONSE"]);
		$apiResponseHandlerObj->setResponseBody($returnArr);		
		$apiResponseHandlerObj->generateResponse();
		return sfView::NONE;
	}

	public function getVerificationStatus($requestId)
	{
		$urlToHit = aadharVerificationEnums::URLTOHIT."?request_id=".$requestId;
		$headerArr  = aadharVerificationEnums::$headerArrForStatus;
		$output = json_decode(CommonUtility::sendCurlGetRequest($urlToHit,'',$headerArr));
		$finalArr = array();
		if($output[0]->status == aadharVerificationEnums::COMPLETED)
		{
			if($output[0]->source_output[0]->match_result->name == aadharVerificationEnums::EXACTMATCH)
			{
				$aadharVerificationObj = new aadharVerification();
				$aadharVerificationObj->updateVerificationStatus($this->profileId,aadharVerificationEnums::VERIFIED);
				unset($aadharVerificationObj);
				$finalArr["MESSAGE"] = aadharVerificationEnums::AADHARVERIFIED;
				$finalArr["AADHAR_NO"] = $this->aadharID;
				$finalArr["RESPONSE"]  = ResponseHandlerConfig::$SUCCESS;
			}
			else
			{
				$finalArr["MESSAGE"] = aadharVerificationEnums::NOTVERIFIEDMSG;
				$finalArr["RESPONSE"]  = ResponseHandlerConfig::$FAILURE;
			}			
		}
		else
		{
			$finalArr["MESSAGE"] = aadharVerificationEnums::STATUSPENDINGMSG;
			$finalArr["RESPONSE"]  = ResponseHandlerConfig::$FAILURE;
		}

		return $finalArr;
	}
}