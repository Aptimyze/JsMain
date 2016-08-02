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
		if($request->getParameter("communicationType") && $request->getParameter("message"))
			$this->response = ResponseHandlerConfig::$SUCCESS;
		else
		{
			$errorString="--RECEIVER--".$request->getParameter("profilechecksum")."--TYPE--".$request->getParameter("type")."--TYPE--".$request->getParameter("message");
			$errorString    = "Chat Input Validation Failed:" . $errorString;
			$this->response = ResponseHandlerConfig::$POST_PARAM_INVALID;
			ValidationHandler::getValidationHandler("", $errorString);
		}
	}
	
	public function validatePopChat ($request)
	{
		if($request->getParameter("communicationType"))
			$this->response = ResponseHandlerConfig::$SUCCESS;
		else
		{
			$errorString="--RECEIVER--".$request->getParameter("profilechecksum")."--TYPE--".$request->getParameter("type")."--TYPE--".$request->getParameter("message");
			$errorString    = "Chat Input Validation Failed:" . $errorString;
			$this->response = ResponseHandlerConfig::$POST_PARAM_INVALID;
			ValidationHandler::getValidationHandler("", $errorString);
		}
	}
}
