<?php

/**
 * api actions.
 * AppRegV1
 * Controller to register a new device
 * @package    jeevansathi
 * @subpackage api
 * @author     Nitesh Sethi
 */
class ForgotloginV1Action extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{	
		$responseData = array();
		$email=$request->getParameter("email");
		$apiObj=ApiResponseHandler::getInstance();
		if(!$email)
		{
			$apiObj->setHttpArray(ResponseHandlerConfig::$FLOGIN_EMAIL_ERR);
		}
		else
		{
			$dbJprofile= new JPROFILE();
			$data=$dbJprofile->get($email,"EMAIL","USERNAME,EMAIL,ACTIVATED,PROFILEID");
			if($data[EMAIL])
			{
				if($data[ACTIVATED]!='D')
				{
					include_once(sfConfig::get("sf_web_dir")."/profile/sendForgotPasswordLink.php");
					sendForgotPasswordLink($data);
					$apiObj->setHttpArray(ResponseHandlerConfig::$FLOGIN_EMAIL_SUCCESS);
				}
				else
					$apiObj->setHttpArray(ResponseHandlerConfig::$FLOGIN_EMAIL_DELETED);
			}
			else
				$apiObj->setHttpArray(ResponseHandlerConfig::$FLOGIN_EMAIL_ERR);
			
		}
			$apiObj->generateResponse();
		die;
	}
}
