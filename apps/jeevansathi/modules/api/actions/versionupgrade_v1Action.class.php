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
	
	private static $updateArray = array(
            "dpp suggestion cal",
            "multi select search form",
            "online chat"
            
        );
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
		$this->defaultArray["updateInfo"]['updateInfoFlag']="true";
		$this->defaultArray["updateInfo"]['updateVersion']="81";
		$this->defaultArray["updateInfo"]['updateFeatures']=$updateArray;

		$apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$apiObj->setResponseBody($this->defaultArray);
		$apiObj->generateResponse();



		die;
	}
}
?>
