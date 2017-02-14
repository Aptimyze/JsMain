<?php

// field sales login api.
// @package    operations
// @subpackage fieldSales
// @author     Ankita Gupta

class ApiCrmAuthenticateV1Action extends sfActions
{

	/**
	  * Executes backend login action
	  *
	  * @param sfRequest $request A request object
	**/
	function execute($request){
		//get authentication object channel wise
		$loginObj=BackendAuthenticationFactory::getBackendAuthenicationObj();
		$fromInternal = $request->getParameter("fromInternal");
		$apiObj=ApiResponseHandler::getInstance();
		if($loginObj)
		{
			//provide login credentials to authenticate login
			$loginCredentials = array("USERNAME"=>trim($request->getParameter("USERNAME")),"PASSWORD"=>$request->getParameter("PASSWORD"));

			//get login data
			$result=$loginObj->backendLogin($loginCredentials);
			if($result)
			{
				if($result["expired_account"]==1)  //expired account(not logged in within last 15 days)
				{
					$result = "";
					$apiObj->setHttpArray(CrmResponseHandlerConfig::$EXPIRED_AGENT);
				}
				else	
				{
                    
                    $loginLogObj = new jsadmin_AGENTS_LOGIN_LOG();
                    $loginLogObj->insert($loginCredentials['USERNAME']);
					if($result[AUTHCHECKSUM])
						$apiObj->setAuthChecksum($result[AUTHCHECKSUM]);
					//register for fso app notifications if android app
					if(MobileCommon::isCrmApp()=="A")
					{
						$registrationid = $request->getParameter("REGISTRATION_ID");
						$appVersion = $request->getParameter("APP_VERSION");
						$notificationObj = new BrowserNotification();
						if(!empty($registrationid) && isset($registrationid)){
							$notificationObj->manageRegistrationid($registrationid,'',$result["agentid"],"CRM_AND",$appVersion);
						}
						unset($notificationObj);
					}
					$apiObj->setHttpArray(CrmResponseHandlerConfig::$AGENT_LOGIN_SUCCESS); 
					unset($result["expired_account"]);
				}
			}
			else
			{
				$result = "";
				$apiObj->setHttpArray(CrmResponseHandlerConfig::$AGENT_LOGIN_FAILURE);
			}
		}
		else
		{   //invalid channel
			$result = "";
			$apiObj->setHttpArray(CrmResponseHandlerConfig::$INVALID_BACKEND_CHANNEL);
		}

		if($result)
			$apiObj->setResponseBody($result);
		if($apiObj->getResponseBody() || !$fromInternal)
		{
			$apiObj->generateResponse();
		}
		if($fromInternal==1)
		{
			$code = $apiObj->getResponseStatusCode();
			if($code == "2")
			{
				$request->setParameter("EXPIRE","Y");
			}
			else if($code=="1")
			{
				$request->setParameter("INVALID","Y");
			}
			return sfView::NONE;
		}
		die;
	}

}
?>