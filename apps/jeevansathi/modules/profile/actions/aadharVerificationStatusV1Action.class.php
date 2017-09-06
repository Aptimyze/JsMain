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
		$this->nameOfUser = $request->getParameter("name");
		$aadharVerificationObj = new aadharVerification();
		$dataArr = $aadharVerificationObj->getAadharDetails($this->profileId);
		$this->aadharID = $dataArr[$this->profileId]["AADHAR_NO"];
		$returnArr = $this->getVerificationStatus($dataArr[$this->profileId]["REQUEST_ID"]);
		unset($aadharVerificationObj);
		$apiResponseHandlerObj->setHttpArray($returnArr["RESPONSE"]);
		$apiResponseHandlerObj->setResponseBody($returnArr);		
		$apiResponseHandlerObj->generateResponse();
		die();
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
				$nameOfUserObj=new NameOfUser();        
				$nameOfUserArr = $nameOfUserObj->getNameData($this->profileId);		
				if (strcasecmp($nameOfUserArr[$this->profileId]["NAME"], $this->nameOfUser) != 0) //check if the user changed the name on the CAL
				{
					$nameArr["NAME"] = $this->nameOfUser;
					$nameOfUserObj->updateName($this->profileId,$nameArr);				
				}
				$aadharVerificationObj = new aadharVerification();
				$aadharVerificationObj->updateVerificationStatus($this->profileId,aadharVerificationEnums::VERIFIED);
				unset($aadharVerificationObj);
				$finalArr["MESSAGE"] = aadharVerificationEnums::AADHARVERIFIED;
				$finalArr["AADHAR_NO"] = $this->aadharID;
				$finalArr["VERIFIED"] = "Y";
				$finalArr["RESPONSE"]  = ResponseHandlerConfig::$SUCCESS;
			}
			else
			{
				$finalArr["MESSAGE"] = aadharVerificationEnums::NOTVERIFIEDMSG;
				$finalArr["VERIFIED"] = "N";
				$finalArr["RESPONSE"]  = ResponseHandlerConfig::$SUCCESS;
			}			
		}
		else
		{
			$finalArr["MESSAGE"] = aadharVerificationEnums::STATUSPENDINGMSG;
			$finalArr["VERIFIED"] = "P";
			$finalArr["RESPONSE"]  = ResponseHandlerConfig::$FAILURE;
		}

		return $finalArr;
	}
}