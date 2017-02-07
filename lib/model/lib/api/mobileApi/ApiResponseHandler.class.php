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
	private $androidChatflag ;
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
		return JsConstants::$androidChat["flag"];
	}
	public function setAndroidChatFlag(){
		$this->androidChatflag = JsConstants::$androidChat["flag"];
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
		$output["xmppLoginOn"] = $this->getAndroidChatFlag();
		$output["flagForAppRatingControl"]=$this->androidFlagForRatingLogic;
		if(isset($this->upgradeDetails)){
			$output["FORCEUPGRADE"]=$this->upgradeDetails[FORCEUPGRADE];
			if(isset($this->upgradeDetails[forceupgrade_message]))
				$output["forceupgrade_message"]=$this->upgradeDetails[forceupgrade_message];
		}

		$output["phoneDetails"]=$this->phoneDetails;
		$loggedIn=LoggedInProfile::getInstance();
		if(MobileCommon::isApp() && $loggedIn && $loggedIn->getPROFILEID())
			$output["userReligion"] = $loggedIn->getRELIGION();
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
