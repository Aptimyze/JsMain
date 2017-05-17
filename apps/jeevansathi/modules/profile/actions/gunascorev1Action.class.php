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
		$sameGender = $request->getParameter("sameGender");
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		$loggedInDetails = $loggedInProfileObj->getDetail("","","*");
		$profileId=$loggedInDetails['PROFILEID'];  //getting the profileid
		$gender=$loggedInDetails["GENDER"];
		$caste = $loggedInDetails["CASTE"];

		$oProfile=CommonFunction::getProfileFromChecksum($request->getParameter("oprofile"));
		
		//$oProfile=intval($request->getParameter("oprofile"));
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
			else
			{
				die;
			}
		}
	}

	//This function calls the gunsScore.class.php and returns $gunaData
	public function getGunaScoreForSearch($profileId,$caste,$otherProfileId,$gender,$shutDownConnections='')
	{	
		$gunaScoreObj = new gunaScore();
		$gunaData = $gunaScoreObj->getGunaScore($profileId,$caste,$otherProfileId,$gender,'',$shutDownConnections);		
		unset($gunaScoreObj);
		return($gunaData);
	}
} 
