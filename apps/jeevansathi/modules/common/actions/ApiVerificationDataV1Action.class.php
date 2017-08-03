<?php
/**
 * ApiVerificationData
 * To get the documents verified for different profiles 
 * @package    jeevansathi
 * @subpackage api
 * @author     Sanyam Chopra	
 * @date	   4th March 2016
 */

class ApiVerificationDataV1Action extends sfActions 
{
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{	
		//Contains login credentials
		// $this->loginData = $request->getAttribute("loginData");
		// $this->profile=Profile::getInstance("newjs_masterRep");
		$apiResponseHandlerObj=ApiResponseHandler::getInstance();
		if(!$request->getParameter('profilechecksum'))
		{
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiResponseHandlerObj->generateResponse();
			die;
		}
		
		$profileId = JsCommon::getProfileFromChecksum($request->getParameter('profilechecksum'));		
		$verificationDataString=$this->getVerificationData($profileId);
		$documentArr = array("documentsVerified"=>$verificationDataString);
		
		//aadhar data	
		$aadharData = $this->getAadharData($profileId);
		if(is_array($aadharData))
		{
			$documentArr["aadharDetails"] = $aadharData;
		}		
		$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$apiResponseHandlerObj->setResponseBody($documentArr);
		$apiResponseHandlerObj->generateResponse();
		die;
	}

	/*This function uses the profileId extracted from the profilechecksum and fetches the corresponding documents 
	* verified(if any) and accordingly converts it into a string and returns the string
	* @param  int $profileId   id for which the documents are to be fetched
	* @return  string $verifiedDocumentsString   the string of verified documents returned
	*/
	public function getVerificationData($profileId)
	{	
		$profileIdArr = array($profileId);
		$verificationSealObj=new VerificationSealLib($objProfile,'1');
		if($verificationSealObj->getFsoStatus() == 0)
		{
			$verifiedDocumentsString = null; //this is set so as to indicate that the profile was not verified by visit.
		}
		else
		{
			$arrResult=$verificationSealObj->getVerifiedDocumets($profileIdArr);
			$arrTemp = array();
			$arrTemp1[] = $arrResult["VERIFICATION_SEAL"]["Self_Address"];
			$arrTemp1[] = $arrResult["VERIFICATION_SEAL"]["Parents_Address"];

			if(is_array($arrTemp1) && count($arrTemp1))
			{
				$arrTemp = implode(", ",array_unique($arrTemp1));
			}
			unset($arrTemp1);
			unset($verificationSealObj);
			$arrResult["address"]=ltrim(rtrim($arrTemp,", "),",");

			$verifiedDocumentsString="";
			if($arrResult["VERIFICATION_SEAL"]["Date_of_Birth"] != "")
			{
				$verifiedDocumentsString.="Date of Birth (".$arrResult["VERIFICATION_SEAL"]["Date_of_Birth"]."),";
			}
			if($arrResult["VERIFICATION_SEAL"]["Qualification"] != "")
			{
				$verifiedDocumentsString.=" Education (".$arrResult["VERIFICATION_SEAL"]["Qualification"]."),";
			}
			if($arrResult["VERIFICATION_SEAL"]["Income"] != "")
			{
				$verifiedDocumentsString.=" Income (".$arrResult["VERIFICATION_SEAL"]["Income"]."),";
			}
			if($arrResult["address"] != "")
			{
				$verifiedDocumentsString.=" Address (".$arrResult["address"]."),";
			}
			if($arrResult["VERIFICATION_SEAL"]["Divorce"] != "")
			{
				$verifiedDocumentsString.=" Marital Status (".$arrResult["VERIFICATION_SEAL"]["Divorce"]."),";
			}
			if(substr($verifiedDocumentsString,-1) == ',')
			{
				$verifiedDocumentsString=rtrim($verifiedDocumentsString,",");
			}	
		}
		
		return $verifiedDocumentsString;

	}

	// this is used to get aadhar details
	public function getAadharData($profileId)
	{
		$aadharVerificationObj = new aadharVerification();
		$aadharData = $aadharVerificationObj->getAadharDetails($profileId);
		if(is_array($aadharData) && !empty($aadharData))
		{
			//$returnArr["AADHAR_NO"] = $aadharData[$profileId]["AADHAR_NO"]; 
			$returnArr["VERIFY_STATUS"] = $aadharData[$profileId]["VERIFY_STATUS"];
		}
		return $returnArr;

	}
}
