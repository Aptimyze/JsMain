<?php
//This class handles the API responses to be sent back to the app. Its a singleton class

class ApiResponseHandler
{
	//Class Variables
	private $responseMessage;
	private $responseStatusCode;
	private $responseBody;
	private $responseContentType = "application/json";
	private static $apiResponseHandlerObj = null;
	private $authChecksum;
	private $hamburgerDetails = null;
	private $imageCopyServer = null;
	private $phoneDetails = null;
	private $cache_flag=true;
	private $cache_interval=120000; //in milisecond should be integer always 
	private $resetCache=false;
	private $androidFlagForRatingLogic=true;
	private $androidChat;
	private $membershipSubscription;
	private $webserviceCachingCap;
	private $androidChatLocalStorage;
	//Constructor
	private function __construct()
	{
	}

	//Getters and Setters
	public function setResponseMessage($responseMessage){$this->responseMessage = $responseMessage;}
	public function getResponseMessage(){return $this->responseMessage;}
	public function setResponseStatusCode($responseStatusCode){$this->responseStatusCode = $responseStatusCode;}
	public function getResponseStatusCode(){return $this->responseStatusCode;}
	public function setResponseBody($responseBody){$this->responseBody = $responseBody;}
	public function getResponseBody(){return $this->responseBody;}
	public function setResponseContentType($responseContentType){$this->responseContentType = $responseContentType;}
	public function getResponseContentType(){return $this->responseContentType;}
	public function setAuthChecksum($checksum){$this->authChecksum = $checksum;}
	public function getAuthChecksum(){return $this->authChecksum;}
	public function setHamburgerDetails($hamburgerDetails){$this->hamburgerDetails = $hamburgerDetails;}
	public function getHamburgerDetails(){return $this->hamburgerDetails;}
	public function setUpgradeDetails($upgradeDetails){$this->upgradeDetails = $upgradeDetails;}
	public function getUpgradeDetails(){return $this->upgradeDetails;}
	public function setPhoneDetails($phoneDetails){$this->phoneDetails = $phoneDetails;}
	public function getPhoneDetails(){return $this->phoneDetails;}
	public function getImageCopyServer(){return $this->imageCopyServer;}
	public function setImageCopyServer($pid)
	{
		$this->imageCopyServer = IMAGE_SERVER_ENUM::getImageServerEnum($pid);
	}
	public function getAndroidChatFlag(){
		$this->androidChat = JsConstants::$androidChatNew["chatOn"];
		return $this->androidChat;
	}

	//getter for androidChatLocalStorage flag
	public function getAndroidChatLocalStorageFlag(){
		$this->androidChatLocalStorage = JsConstants::$androidChatNew["flushLocalStorage"];
		return $this->androidChatLocalStorage;
	}

	//getter for webserviceCachingGap based on subscription of logged in user
	public function getWebserviceCachingCap($subscription="Free"){
		$this->webserviceCachingCap = 600000;
		if(is_array(JsConstants::$nonRosterRefreshUpdateNew) && $subscription!=""){
			$this->webserviceCachingCap = JsConstants::$nonRosterRefreshUpdateNew["dpp"][$subscription];
		}
		return $this->webserviceCachingCap;
	}

	//setter for membershipSubscription of logged in user for android app
	public function setSelfSubscription(){
		$this->membershipSubscription = "Free";
		$profileObj=LoggedInProfile::getInstance('newjs_master');
		$pid=$profileObj->getPROFILEID();
		unset($profileObj);
		if($pid && !empty($pid)){
			$this->membershipSubscription = CommonFunction::getMembershipName($pid);
			if($this->membershipSubscription && $this->membershipSubscription!= "Free"){
		        $this->membershipSubscription = "Paid";
		    }
		    else{
		        $this->membershipSubscription = "Free";
		    }
		}
	}

	public function setResetCache($resetCache){$this->resetCache = $resetCache;}
	public function getResetCache(){return $this->resetCache;}
	public function setHttpArray($httpArray)
	{
		if(is_array($httpArray))
		{
			$this->setResponseMessage($httpArray["message"]);
			if(isset($httpArray["statusCode"]))
				$this->responseStatusCode=$httpArray["statusCode"];
		}
	}
	public function getHttpArray()
	{
		$httpArray["message"] = $this->getResponseMessage();
		$httpArray["statusCode"]=$this->getResponseStatusCode();
		return $httpArray;
	}

	/*
	This function return the current working object instance
	*/
	public static function getInstance()
	{
		if(ApiResponseHandler::$apiResponseHandlerObj)
		{
			return ApiResponseHandler::$apiResponseHandlerObj;
		}
		else
		{
			ApiResponseHandler::$apiResponseHandlerObj = new ApiResponseHandler;
			return ApiResponseHandler::$apiResponseHandlerObj;

		}
	}

	/*
	This function generates the final response to be sent to the app
	*/
	public function generateResponse()
	{
		if(isset($this->responseStatusCode) && isset($this->responseMessage))
		{
			if($this->responseBody)
			{
				if(is_array($this->responseBody))
				{
					$output = $this->responseBody;
				}
				else
				{
					$output[] = $this->responseBody;
				}
			}
		}
		else
		{
			$errorArr = ResponseHandlerConfig::$HTTP_CODE_MESSAGE_NOT_SET;
			$this->setHttpArray($errorArr);
		}

		$output["responseStatusCode"] = $this->responseStatusCode;
		$output["responseMessage"] = $this->responseMessage;
		$output["AUTHCHECKSUM"]=$this->authChecksum;
		$output["hamburgerDetails"]=$this->hamburgerDetails;
		$output["imageCopyServer"]=$this->imageCopyServer;
		$output["imageCopyServer"]=$this->imageCopyServer;
		$output["cache_flag"]=$this->cache_flag;
		$output["cache_interval"]=$this->cache_interval;
		$output["resetCache"]=$this->resetCache;

		//android chat on/off flag
		$output["androidChat"] = $this->getAndroidChatFlag();
		$output["flagForAppRatingControl"]=$this->androidFlagForRatingLogic;

		//flag for android chat localstorage flushing
		$output["androidChatLocalStorage"] = $this->getAndroidChatLocalStorageFlag();

		//set membershipSubscription
		$this->setSelfSubscription();

		//set webservice caching flag for android
		$output["webserviceCachingCap"] = $this->getWebserviceCachingCap($this->membershipSubscription);

		if(isset($this->upgradeDetails)){
			$output["FORCEUPGRADE"]=$this->upgradeDetails[FORCEUPGRADE];
			if(isset($this->upgradeDetails[forceupgrade_message]))
				$output["forceupgrade_message"]=$this->upgradeDetails[forceupgrade_message];
		}

		$output["phoneDetails"]=$this->phoneDetails;
		$loggedIn=LoggedInProfile::getInstance();
		if(MobileCommon::isApp() && $loggedIn && $loggedIn->getPROFILEID())
		{
			$output["userReligion"] = $loggedIn->getRELIGION();
			$output["userActivation"] = $loggedIn->getACTIVATED();
		}


		// set the content type
		header('Content-type: ' . $this->responseContentType);

		if($this->responseContentType == "application/json")
		{
			//echo ApiResponseHandler::errorReplacement(json_encode($output));
			echo json_encode($output);
		}
		else
		{
			print_r($output);
		}
	}

	/*
	This function returns the HTTP status message corresponding to the HTTP status code
	@param - HTTP status code
	*/
	public static function getStatusCodeMessage($status)
	{
		$codes = Array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => '(Unused)',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported'
		);

		return (isset($codes[$status])) ? $codes[$status] : '';
	}
	public static function errorReplacement($jsonString)
	{
		$string=str_replace('\n','',$jsonString);
		$string=str_replace('\r','',$string);
		return $string;
	}
}
?>
