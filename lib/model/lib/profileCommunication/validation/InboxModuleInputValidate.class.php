<?php
/**
* This class will handle inox validation result   
* @author Nitesh Sethi
* @dated 1 july 2015
*/
class InboxModuleInputValidate extends ValidationHandler
{
	private $response;

	public function getResponse()
	{
		return $this->response;
	}

	/**
	* This function will validate search forms of app.
	*/ 
	public function validateRequestInboxData($request)
	{
		$errorString="";
		$pass=TRUE;
		if($request->getParameter('infoTypeId') AND !is_numeric($request->getParameter('infoTypeId')))
		{
			$errorString.=" InfoTypeId ".$request->getParameter('infoTypeId');
			$pass=FALSE;
		}
		if($request->getParameter('pageNo') AND !is_numeric($request->getParameter('pageNo')))
                {
                        $errorString.=" pageNo ".$request->getParameter('pageNo');
                        $pass=FALSE;
		}
                $pattern = "/^([a-zA-Z0-9,])+$/";
		if($request->getParameter('profileList') && !preg_match($pattern,$request->getParameter('profileList')))
		{
			$errorString.=" profile list ".$request->getParameter('profileList');
			$pass=FALSE;
		}

		if($request->getParameter('searchId') AND !is_numeric($request->getParameter('searchId')))
                {
                        $errorString.=" searchId ".$request->getParameter('searchId');
                        $pass=FALSE;
		}
		if($request->getParameter('currentPage') AND !is_numeric($request->getParameter('currentPage')))
                {
                        $errorString.=" currentPage ".$request->getParameter('currentPage');
                        $pass=FALSE;
		}
		if($pass)
			$this->response = ResponseHandlerConfig::$SUCCESS;
		else
		{
			$errorString = "Inbox Input Validation Failed:".$errorString;
			$this->response = ResponseHandlerConfig::$POST_PARAM_INVALID;
			ValidationHandler::getValidationHandler("",$errorString);
		}
	}

}
?>
