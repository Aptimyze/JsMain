<?php
/**
 * apiDeepLinkingTracking
 * To track deep linking by using the trackingId. This Api will be called once while sending the header for redirecting the user to app and then by the ios team sending back data to verify that the page was successfully opened.
 * @package    jeevansathi
 * @subpackage api
 * @author     Sanyam Chopra	
 * @date	   15th April 2016
 */
class ApiDeepLinkingTrackingV1Action extends sfActions 
{
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{
		$apiResponseHandlerObj=ApiResponseHandler::getInstance();
		if(!$request->getParameter('profilechecksum'))
		{
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiResponseHandlerObj->generateResponse();
			die;
		}
		//fetch viewer and viewed profileId
		$profileId = JsCommon::getProfileFromChecksum($request->getParameter('profilechecksum'));
		$viewerProfileID = $request->getParameter('loggedInProfileId');

		//this part is called when the call to the api is made internally
		if(!$request->getParameter('trackingId'))
		{
			$trackId=$this->getTrackingId($profileId,$viewerProfileID);
			echo json_encode($trackId);
		}
		//This part is called when the api is hit externally in response to the header sent to them
		else
		{
			$trackedId = $request->getParameter('trackingId');
			$authchecksum = $request->getParameter('authchecksum');
			$authenticateObj = new AppAuthentication();
			$resultArray=$authenticateObj->authenticate($authchecksum);
			$viewerProfileId = $resultArray["PROFILEID"];
			$updatedRows = $this->updateTrackingData($profileId,$trackedId,$viewerProfileId);
			if($updatedRows)
			{
				$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
				$apiResponseHandlerObj->setResponseBody(array("deepLinkingResponse"=>"SUCCESS"));
				$apiResponseHandlerObj->generateResponse();
			}
			
		}
		return SfView::NONE;
	}


	//This function calls the deepLinking Library to fetch the trackingId
	public function getTrackingId($profileid,$viewerProfileID)
	{
		$deepLinkingObj = new deepLinking();
		$trackingId = $deepLinkingObj->trackingUser($profileid,$viewerProfileID);
		unset($deepLinkingObj);
		return $trackingId;
	}

	//This function is called to update the DeepLinking table using the profileId, trackedId and viewerProfileId provided
	public function updateTrackingData($profileId,$trackedId,$viewerProfileId)
	{
		$deepLinkingObj = new deepLinking();
		$updatedRowCount = $deepLinkingObj->verifyTrackingEntry($profileId,$trackedId,$viewerProfileId);
		return $updatedRowCount;
		unset($deepLinkingObj);
	}
}
?>
