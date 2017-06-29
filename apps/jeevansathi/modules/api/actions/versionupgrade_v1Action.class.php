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
			"See Recent Searches and Profile Views 
			Get FLASH DEALS for Membership upgrades"            
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
		$this->defaultArray["RATE_US_BEHAVIORAL_CAP"]=4;
		$this->defaultArray["updateInfo"]['updateInfoFlag']="true";
		$this->defaultArray["updateInfo"]['playStoreVersion']=  ApiRequestHandler::$ANDROID_PLAYSTORE_APP_VERSION;
		$this->defaultArray["updateInfo"]['optionalUpgradeVersion']=  ApiRequestHandler::$ANDROID_OPTIONAL_UPGRADE_VERSION;
		$this->defaultArray["updateInfo"]['forceUpgradeVersion']=  ApiRequestHandler::$ANDROID_FORCE_UPGRADE_VERSION;
		$this->defaultArray["updateInfo"]['updateFeatures']=self::$updateArray;
                
		$apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$apiObj->setResponseBody($this->defaultArray);
		$apiObj->generateResponse();



		die;
	}
}
?>
