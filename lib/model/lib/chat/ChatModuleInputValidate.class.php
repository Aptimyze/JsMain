<?php
/**
 * This class will handle Contact validation result   
 * @author Pankaj
 */
class ChatModuleInputValidate extends ValidationHandler
{
	private $response;
	public function __construct()
	{
	}
	public function getResponse()
	{
		return $this->response;
	}
	/*
	This function validates the POST parameters for /register/staticTablesData url and set the response in the class variable
	@param - sfWebRequest object
	*/
	public function validatePushChat ($request)
	{
		$ip=FetchClientIP();
		if(strstr($ip, ","))    
		{                       
			$ip_new = explode(",",$ip);
			$ip = $ip_new[1];
		}
		$validIp=array("10.10.18.61","10.10.18.62","10.10.18.63","10.10.18.64","10.10.18.65","10.10.18.67","10.10.18.75");
		if(in_array($ip,$validIp) || JsConstants::$whichMachine != 'live')
			$whitelistedIp=true;
		else
			$whitelistedIp=false;
			
		if($request->getParameter("communicationType") && $request->getParameter("message") && $whitelistedIp){
				$this->response = ResponseHandlerConfig::$SUCCESS;
		}
		else
		{
			
			$errorString="--RECEIVER--".$request->getParameter("profilechecksum")."--communicationType--".$request->getParameter("communicationType")."--MSG--".$request->getParameter("message")."--IP--".$ip;
			$errorString    = "Chat Input Validation Failed:" . $errorString;
			$this->response = ResponseHandlerConfig::$POST_PARAM_INVALID;
			ValidationHandler::getValidationHandler("", $errorString);
		}
	}
	
	public function validatePopChat ($request)
	{
		
		if($request->getParameter("communicationType") && $request->getParameter("sender")==$request->getAttribute('profileid'))
			$this->response = ResponseHandlerConfig::$SUCCESS;
		else
		{
			$errorString="--SENDER--".$request->getParameter("sender")."--LOGGEDINPROFILE--".$request->getAttribute("profileid")."--RECEIVER--".$request->getParameter("profilechecksum")."--TYPE--".$request->getParameter("type")."--TYPE--".$request->getParameter("message");
			$errorString    = "Chat Input Validation Failed:" . $errorString;
			$this->response = ResponseHandlerConfig::$POST_PARAM_INVALID;
			ValidationHandler::getValidationHandler("", $errorString);
		}
	}
}
