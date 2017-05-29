<?php

/**
 * profile actions.
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Your name here
 */
class gunascorev1Action extends sfAction
{
	public function execute($request)
	{
		$apiObj=ApiResponseHandler::getInstance();	
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		$loggedInDetails = $loggedInProfileObj->getDetail("","","*");
		$profileId=$loggedInDetails['PROFILEID'];  //getting the profileid
		$gender=$loggedInDetails["GENDER"];
		$caste = $loggedInDetails["CASTE"];

		$oProfile=CommonFunction::getProfileFromChecksum($request->getParameter("oprofile"));
		if($oProfile == $profileId)
		{
			$apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$apiObj->generateResponse();
		}
		if(!MobileCommon::isApp())
		{
			$sameGender = $request->getParameter("sameGender");
		}
		else
		{
			$sameGender = $this->getSameGender($gender,$oProfile);
		}
		
		$parent="";
		if($oProfile && $profileId && !$sameGender)
		{
			$apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$gunaData = $this->getGunaScoreForSearch($profileId,$caste,$oProfile,$gender,1);		
			$apiObj->setResponseBody(array("SCORE"=>$gunaData[$oProfile]));
			$apiObj->generateResponse();
			if($request->getParameter('INTERNAL')==1)
			{
				return sfView::NONE;
			}			
		}
		die;
	}

	//This function calls the gunsScore.class.php and returns $gunaData
	public function getGunaScoreForSearch($profileId,$caste,$otherProfileId,$gender,$shutDownConnections='')
	{	
		$gunaScoreObj = new gunaScore();
		$gunaData = $gunaScoreObj->getGunaScore($profileId,$caste,$otherProfileId,$gender,'',$shutDownConnections);		
		unset($gunaScoreObj);
		return($gunaData);
	}

	public function getSameGender($loggedInGender,$otherProfileId)
	{
		$dbJprofile= new JPROFILE();        
 		$otherRow=$dbJprofile->get($otherProfileId,"PROFILEID","GENDER");
 			if($loggedInGender == $otherRow[GENDER])
 				$sameGender=1;
 			else
 				$sameGender=0;
 		return $sameGender;
	}
} 
