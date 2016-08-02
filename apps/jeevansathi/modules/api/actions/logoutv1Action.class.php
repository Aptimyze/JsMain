<?php

/**
 * api actions.
 * AppRegV1
 * Controller to make user logout
 * @package    jeevansathi
 * @subpackage api
 * @author     Nikhil Dhimn
 */
class logoutv1Action extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{	
		$responseData = array();
		$loginData = $request->getAttribute("loginData");
		$registrationid=$request->getParameter("registrationid");
		if($loginData[PROFILEID])
		{
			$dbObj = new LOG_LOGOUT_HISTORY(JsDbSharding::getShardNo($loginData[PROFILEID]));
			$dbObj->insert($loginData[PROFILEID],CommonFunction::getIP());

			$dbObj=new userplane_recentusers;
                        $dbObj->DeleteRecord($loginData[PROFILEID]);

                        // Remove Online-User
		        $dateTime1 ='11';
        		$dateTime2 ='22';
                	$dateTime =date("H");
                	$redisOnline =true;
                	if(($dateTime>=$dateTime1) && ($dateTime<$dateTime2))
                	        $redisOnline =false;
			if($redisOnline){	
				$pid =$loginData['PROFILEID'];
	                        $jsCommonObj =new JsCommon();
	                        $jsCommonObj->removeOnlineUser($pid);
			}

			$apiObj=ApiResponseHandler::getInstance();
			$apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$done = NotificationFunctions::manageGcmRegistrationid($registrationid)?"1":"0";
			$apiObj->setResponseBody(array("GCM_REGISTER"=>$done));
			$apiObj->setAuthChecksum("");
			$apiObj->generateResponse();
		}
		die;
	}
}
?>
