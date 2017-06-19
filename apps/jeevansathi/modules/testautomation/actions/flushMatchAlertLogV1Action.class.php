<?php
/**
 * Flush Match Alert Logs
 * This api will be used by the test team to clear the match Alert logs for a particular ProfileID
 * @package    jeevansathi
 * @subpackage api
 * @author     Sanyam Chopra	
 * @date	   14th March 2017
 */
class flushMatchAlertLogV1Action extends sfActions 
{
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{
		$apiResponseHandlerObj=ApiResponseHandler::getInstance();
		$profileId = $request->getParameter("pid");		
		if(JsConstants::$whichMachine == "test")
		{
			JsMemcache::getInstance()->remove($profileId."_MATCHALERTS_LOG_ALL"); //remove key for given profileid.			
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$apiResponseHandlerObj->generateResponse();
			die;
		}
		else
		{
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiResponseHandlerObj->generateResponse();
			die;
		}
	}
}