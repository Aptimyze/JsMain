<?php
/**
 * This class will handle Contact validation result   
 * @author Pankaj
 */
class ContactModuleInputValidate extends ValidationHandler
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
	public function validateContactActionData($request)
	{
		$errorString = "";
		$pass        = TRUE;
		if (!ValidationHandler::validateProfileChecksum($request->getParameter("profilechecksum"))) {
			$pass        = false;
			$errorString = "Checksum " . $request->getParameter("profilechecksum");
		} //!self::validateProfileChecksum($request->getParameter("profliechecksum"))
		if (!self::validateStype($request->getParameter("stype"))) {
			$pass        = false;
			$errorString = "stype " . $request->getParameter("stype");
		} //!self::validateProfileChecksum($request->getParameter("stype"))
		if (!self::validateResponseTracking($request->getParameter("responseTracking"))) {
			$pass        = false;
			$errorString = "responseTracking " . $request->getParameter("responseTracking");
		} //!self::validateProfileChecksum($request->getParameter("responseTracking"))
		if ($pass)
			$this->response = ResponseHandlerConfig::$SUCCESS;
		else {
			$errorString    = "Search Input Validation Failed:" . $errorString;
			$this->response = ResponseHandlerConfig::$POST_PARAM_INVALID;
			ValidationHandler::getValidationHandler("", $errorString);
		}
	}
	
	public function validateMessageActionData($request)
	{
		$errorString = "";
		$pass        = TRUE;
		if (!self::validateMessageId($request->getParameter("messsageid"))) {
			$pass        = false;
			$errorString = "messageid " . $request->getParameter("messsageid");
		} //!self::validateProfileChecksum($request->getParameter("messsageid"))
		if (!self::validateDraft($request->getParameter("draft"))) {
			$pass        = false;
			$errorString = "draft " . $request->getParameter("draft");
		} //!self::validateProfileChecksum($request->getParameter("draft"))
		if ($pass)
			$this->response = ResponseHandlerConfig::$SUCCESS;
		else {
			$errorString    = "Search Input Validation Failed:" . $errorString;
			$this->response = ResponseHandlerConfig::$POST_PARAM_INVALID;
			ValidationHandler::getValidationHandler("", $errorString);
		}
	}
	
	public static function validateStype ($value)
	{
		if(!$value)
			return true;
		if(preg_match("/^([a-zA-Z0-9])+$/",$value))
			return true;
	}
	public static function validateResponseTracking ($value)
	{
		if(!$value)
			return true;
		if(preg_match("/^(\d*)(-\d*)*$/",$value))
			return true;
	}
	public static function validateMessageId ($value)
	{
		if(!$value)
			return false;
		if(preg_match("/^([a-zA-Z])+$/",$value))
			return true;
	}
	public static function validateDraft ($value)
	{
		if(!$value)
			return false;
		if(preg_match("/^([a-zA-Z])+$/",$value))
			return true;
	}
	
}
