<?php
/**
 * ApiTrackIdfyProfiles
 * To get the Cal Layer contents 
 * @package    jeevansathi
 * @subpackage api
 * @author     Sanyam Chopra
 * @date	   09th June 2017
 */
class apiTrackIdfyProfilesV1Action extends sfActions
{ 	
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	//Member Functions
	public function execute($request)
	{
		$apiResponseHandlerObj = ApiResponseHandler::getInstance();		
		$loginData=$request->getAttribute("loginData");
		if(is_array($loginData) && !empty($loginData))
		{
			$profileId = $loginData["PROFILEID"];
			$email = $loginData["EMAIL"];
			if($profileId)
			{
				$trackIdfyObj = new PROFILE_TRACKIDFYPROFILES("newjs_masterRep");
				$trackIdfyObj->insertTrackingData($profileId,$email,date("Y-m-d H:i:s"));	
			}			
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			die;
		}
		
	}
}