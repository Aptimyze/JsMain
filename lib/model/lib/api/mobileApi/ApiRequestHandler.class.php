<?php

//This class is to handle the API request by validating the url, performing authentication and providing the forwarding module and action name

class ApiRequestHandler
{
	public static $ANDROID_OPTIONAL_UPGRADE_VERSION = 90;
	public static $ANDROID_PLAYSTORE_APP_VERSION = 94;
	public static $ANDROID_FORCE_UPGRADE_VERSION = 40;
	private static $apiRequestHandlerObj = null;
	private $responseFlag = false;
	private $response;
	private $app = array();
	private $forceUpgrade = false;

	public function __construct($request)
	{
		$this->validateRequest($request);
		if ($this->responseFlag) {
			$this->AuthenticateDevice($request);
		}
		$this->app = array("android" => array('APILEVEL' => '14', "CURRENT_VERSION" => "2.3", "API_APP_VERSION" => self::$ANDROID_OPTIONAL_UPGRADE_VERSION, "FORCE_API_APP_VERSION" => self::$ANDROID_FORCE_UPGRADE_VERSION), "ios" => array("APILEVEL" => "1", "CURRENT_VERSION" => "5", "API_APP_VERSION" => 1));
	}

	/*
	This function return the current working object instance
	*/

	public function validateRequest($request)
	{
		$pattern = "/^v[0-9]+$/";
		if (!preg_match($pattern, $request->getParameter("version"))) {
			$this->responseFlag = false;
			$this->response = ResponseHandlerConfig::$INVALID_URL;
			ValidationHandler::getValidationHandler("", "Invalid API URL ");
		} else {
			$this->responseFlag = true;
		}

		if ($this->responseFlag) {
			$this->responseFlag = false;
			foreach (RequestHandlerConfig::$moduleActionVersionArray as $k => $v) {
				if ($k == $request->getParameter("moduleName")) {
					$this->responseFlag = true;
					break;
				}
			}

			if ($this->responseFlag) {
				$this->responseFlag = false;
				foreach (RequestHandlerConfig::$moduleActionVersionArray[$request->getParameter("moduleName")] as $k => $v) {
					if ($k == $request->getParameter("actionName")) {
						$this->responseFlag = true;
						break;
					}
				}
			}
		}

		if ($this->responseFlag)
			$this->response = ResponseHandlerConfig::$SUCCESS;
		else {
			$this->response = ResponseHandlerConfig::$INVALID_URL;
			ValidationHandler::getValidationHandler("", "Invalid Api URL");
		}
	}

	/**
	 *
	 * Function for Validate and authenicate the request
	 * @param sfRequest $request A request object
	 * @return void
	 */

	public function AuthenticateDevice($request)
	{
		if (!($request->getParameter("moduleName") == "api" && $request->getParameter("actionName") == "index") && $request->getParameter("actionName") != "appReg" && $request->getParameter("actionName") != "staticTablesData" && $request->getParameter("actionName") != "searchFormData") {
			$this->responseFlag = false;
			if ($request->getParameter('authToken')) {
				$authToken = $request->getParameter('authToken');
				$encryptDecrypt = new Encrypt_Decrypt();
				$requestData = $encryptDecrypt->decrypt($authToken);
				$requestDataArray = explode('|', $requestData);
				$authkey = $requestDataArray[1];
				if ($requestDataArray[0] == strrev($requestDataArray[2])) {
					$mobileApiClientInfo = new AppClientInfo($authkey);
					if (!$mobileApiClientInfo->getAPPID()) {
						$this->response = ResponseHandlerConfig::$SUCCESS;
						//$this->response =ResponseHandlerConfig::$INVALID_AUTHTOKEN_KEY;
						//ValidationHandler::getValidationHandler("","Invalid Authkey generated from Authtoken sent $authToken");
					} elseif ($mobileApiClientInfo->getSTATUS() == RequestHandlerConfig::$ENABLED) {
						/*if ($mobileApiClientInfo->getIP_COUNT() > RequestHandlerConfig::$AUTH_IP_COUNT)
							$this->response =ResponseHandlerConfig::$IP_NOT_VALID;
						else
						{
							$current_ip=CommonFunction::getIP();
							if($mobileApiClientInfo->getCURRENT_IP() == $current_ip)
								$mobileApiClientInfo->setIP_COUNT($mobileApiClientInfo->getIP_COUNT()+1);
							else
								$mobileApiClientInfo->setIP_COUNT(0);
							$mobileApiClientInfo->setCURRENT_IP($current_ip);

							$update=$mobileApiClientInfo->updateClientInfo();
							if (!$update)
								$this->response =ResponseHandlerConfig::$SERVICE_UNAVAILABLE;
							else
							{*/
						$this->response = ResponseHandlerConfig::$SUCCESS;
						$this->responseFlag = true;
						//if(!isset($_COOKIE['dev_info']))
						//$_COOKIE['dev_info'] = 1;
						//}
						//}
					} else {
						//$this->response =ResponseHandlerConfig::$DEVICE_DISABLED;
						$this->response = ResponseHandlerConfig::$SUCCESS;
						//	ValidationHandler::getValidationHandler("","Device is is disabled state with app id:".$mobileApiClientInfo->getAPPID());
					}
				} else {
					//$this->response =ResponseHandlerConfig::$INVALID_API_KEY_REVERSE;
					$this->response = ResponseHandlerConfig::$SUCCESS;
					//	ValidationHandler::getValidationHandler("","String Append to Api Key is wrong after reversal");
				}
			} else {
				$this->response = ResponseHandlerConfig::$SUCCESS;
				//$this->response =ResponseHandlerConfig::$BLANK_AUTHOKEN;
				//	ValidationHandler::getValidationHandler("","authtoken is blank");
			}
		}
	}

	/*
	This function validates the url to check if the correct version number format, module name format and action name format is present in the url
	*/

	public static function getInstance($request)
	{
		if (ApiRequestHandler::$apiRequestHandlerObj) {
			return ApiRequestHandler::$apiRequestHandlerObj;
		} else {
			ApiRequestHandler::$apiRequestHandlerObj = new ApiRequestHandler($request);
			return ApiRequestHandler::$apiRequestHandlerObj;

		}
	}

	public function getResponse()
	{
		return $this->response;
	}


	/*
	This function return the module name and action name to which the controller should be forwarded.
	@return - array with module name and action name
	*/

	public function getModuleAndActionName($request)
	{
		$output["moduleName"] = $request->getParameter("moduleName");
		$output["actionName"] = RequestHandlerConfig::$moduleActionVersionArray[$request->getParameter("moduleName")][$request->getParameter("actionName")][$request->getParameter("version")];
		$profileid = $request->getAttribute('profileid');
		$loginData = $request->getAttribute('loginData');
		if ($profileid) {
			if ($request->getParameter("actionName") == "staticTablesData" || $request->getParameter("actionName") == "searchFormData") {
			}
			if (($loginData[INCOMPLETE] == "Y") && ($output["actionName"] != "ApiEditSubmitV1" && $request->getAttribute("incomplete") != "Y") && ($output["moduleName"] != "register") && ($output["actionName"] != "AlertManagerV1") && ($output["actionName"] != "logoutv1" && $output["moduleName"] != "api")) {
				$request->setParameter('sectionFlag', "incomplete");
				$output["moduleName"] = "profile";
				$output["actionName"] = RequestHandlerConfig::$moduleActionVersionArray[$output["moduleName"]]["editprofile"][$request->getParameter("version")];
			} elseif ($output["actionName"] != "ApiEditSubmitV1" && $request->getAttribute("incomplete") != "Y" && ($output["actionName"] != "logoutv1" && $output["moduleName"] != "api") && ($output["actionName"] != "AlertManagerV1")) {
				$verifyPhoneForRequest = JsCommon::verifyPhoneForRequest($profileid, $output['moduleName'], $output['actionName']);
				if ($verifyPhoneForRequest) {
					$output["moduleName"] = "phone";
					$output["actionName"] = RequestHandlerConfig::$moduleActionVersionArray[$output["moduleName"]]["display"][$request->getParameter("version")];
				}
			}

		}


		return $output;
	}

	public function validateAuthChecksum($authChecksum, $gcm = 0)
	{
		//$authChecksum=$request->getParameter('AUTHCHECKSUM');
		if ($authChecksum) {
			$mobileApiLoginObj = AuthenticationFactory::getAuthenicationObj();
			$responseData = $mobileApiLoginObj->authenticate($authChecksum, $gcm);
		}

		return $responseData;
	}

	public function forceUpgradeCheck($request, $calledFrom = "")
	{

		$defaultArray = array("FORCEUPGRADE" => "N", "UPGRADE" => "N");
		$apiLevel = $request->getParameter(APILEVEL);
		$currentOSversion = $request->getParameter(CURRENT_VERSION);
		$apiappVersion = intval($request->getParameter(API_APP_VERSION));
		$Device = $this->app[$request->getParameter(KEY)];
		if ($Device) {
			if ($apiappVersion < $Device[API_APP_VERSION]) {
				if ($calledFrom == "apiAction") {
					if ($this->forceUpgrade || ($apiappVersion < $Device[FORCE_API_APP_VERSION]))
						$defaultArray[FORCEUPGRADE] = "Y";
					else
						$defaultArray[FORCEUPGRADE] = "N";
				} else {
                                        if($apiLevel >= $Device[APILEVEL]  && ($request->getParameter(KEY)!='android' || $this->checkForRandomNess()))
                                            $defaultArray[UPGRADE] = "Y";
					if ($this->forceUpgrade || ($apiappVersion < $Device[FORCE_API_APP_VERSION]))
						$defaultArray[FORCEUPGRADE] = "Y";
				}
			} else if ($calledFrom == "apiAction") {
				$defaultArray[FORCEUPGRADE] = "N";
			}

		} else if ($calledFrom == "apiAction")
			unset($defaultArray);
		if ($calledFrom == "apiAction") {
			unset($defaultArray[UPGRADE]);
			if ($defaultArray[FORCEUPGRADE] == "Y")
				$defaultArray["forceupgrade_message"] = "This version of your Jeevansathi App has expired please upgrade";
		} else
			$defaultArray["message"] = "This version of your Jeevansathi App has expired. Please upgrade";
		return $defaultArray;
	}
// if for 50% set divisor =2, 25 % set to 4
        
        public function  checkForRandomNess(){
            $Divisor = 10;
            $randNum = rand(1,$Divisor);
            if($randNum % $Divisor  == 0 )return true;
            else return false;
            
        }
}

?>
