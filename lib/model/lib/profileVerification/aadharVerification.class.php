<?php

class aadharVerification
{
	public function callAadharVerificationApi($aadharId,$nameOfUser,$profileId,$username)
	{
		$aadharArr = array();
		$urlToHit = "https://api.idfy.com/v2/tasks";
		$headerArr = array(
    	'apikey:f97b05b2-9360-4706-91e7-6154513200a1',
    	'Content-Type:application/json',
		);

		$aadharArr["tasks"][0]["type"] = aadharVerificationEnums::TYPE;
		$aadharArr["tasks"][0]["group_id"] = aadharVerificationEnums::GROUPID;
		$aadharArr["tasks"][0]["task_id"] = aadharVerificationEnums::TASKID;
		$aadharArr["tasks"][0]["data"]["aadhaar_number"] = $aadharId;
		$aadharArr["tasks"][0]["data"]["aadhaar_name"] = $nameOfUser;
		$aadharArr["tasks"][0]["data"]["aadhaar_consent"] = aadharVerificationEnums::AADHAR_CONSENT;
		
		$response = json_decode(CommonUtility::sendCurlPOSTRequest($urlToHit,json_encode($aadharArr),"",$headerArr));
		$reqId = $response->request_id;
		$date = date("Y-m-d H:i:s");
		$aadharVerificationObj = new PROFILE_VERIFICATION_AADHAR_VERIFICATION("newjs_masterRep");
		$aadharVerificationObj->insertAadharDetails($profileId,$username,$date,$aadharId,$reqId);
		unset($aadharVerificationObj);
	}

	public function getAadharDetails($profileId)
	{
		$aadharVerificationObj = new PROFILE_VERIFICATION_AADHAR_VERIFICATION("newjs_masterRep");
		$aadharDetails = $aadharVerificationObj->getAadharDetails($profileId);
		unset($aadharVerificationObj);
		return $aadharDetails;
	}

	public function updateVerificationStatus($profileId,$verifiedFlag)
	{
		$aadharVerificationObj = new PROFILE_VERIFICATION_AADHAR_VERIFICATION("newjs_masterRep");
		$aadharVerificationObj->updateVerificationStatus($profileId,$verifiedFlag);
		unset($aadharVerificationObj);	
	}

	public function resetAadharDetails($profileId)
	{
		$aadharVerificationObj = new PROFILE_VERIFICATION_AADHAR_VERIFICATION("newjs_masterRep");
		$aadharVerificationObj->resetAadharDetails($profileId);
		unset($aadharVerificationObj);
	}


}