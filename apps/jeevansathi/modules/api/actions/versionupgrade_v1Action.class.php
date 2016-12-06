<?php

/**
 * api actions.
 * AppRegV1
 * Controller to make user logout
 * @package    jeevansathi
 * @subpackage api
 * @author     Nikhil Dhimn
 */
class versionupgrade_v1Action extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	
	public function execute($request)
	{	
		$responseData = array();
		//$loginData = $request->getAttribute("loginData");
		
		$apiObj=ApiResponseHandler::getInstance();
		$this->apiWebHandler = ApiRequestHandler::getInstance($request);
		$this->defaultArray=$this->apiWebHandler->forceUpgradeCheck($request);
		$this->defaultArray["titleButtonA"]="Update App";
		$this->defaultArray["titleButtonB"]="Skip";
		$this->defaultArray["RATE_US_AUTO"]="true";
		$this->defaultArray["RATE_US_MANUAL"]="true";
		$this->defaultArray["RATE_US_BEHAVIORAL"]="true";
		$apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$apiObj->setResponseBody($this->defaultArray);
		$apiObj->generateResponse();
		die;
	}
}
?>
