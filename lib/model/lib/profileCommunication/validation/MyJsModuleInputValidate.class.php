<?php
/**
* This class will handle myjs validation result   
* @author Reshu Rajput
* @dated 2 july 2014
*/
class MyJsModuleInputValidate extends ValidationHandler
{
	private $response;

	public function getResponse()
	{
		return $this->response;
	}

	/**
	* This function will validate search forms of app.
	*/ 
	public function validateRequestMyJsData($request)
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
		
		if($pass)
			$this->response = ResponseHandlerConfig::$SUCCESS;
		else
		{
			$errorString = "MyJs Input Validation Failed:".$errorString;
			$this->response = ResponseHandlerConfig::$POST_PARAM_INVALID;
			ValidationHandler::getValidationHandler("",$errorString);
		}
	}

}
?>
