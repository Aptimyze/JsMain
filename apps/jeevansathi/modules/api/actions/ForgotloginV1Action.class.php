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
		$flag=$request->getParameter("flag");
		$apiObj=ApiResponseHandler::getInstance();
		if(!$email)
		{
			$apiObj->setHttpArray(ResponseHandlerConfig::$FLOGIN_EMAIL_ERR);
		}
		else
		{
			$dbJprofile= new JPROFILE();
			if(!$flag || $flag == 'E')
			{
				$data=$dbJprofile->get($email,"EMAIL","USERNAME,EMAIL,ACTIVATED,PROFILEID");
			}
			else if($flag == 'M')
			{
				$phone = $request->getParameter("phone");
				$isd = $request->getParameter("isd");
				$arr=array('PHONE_MOB'=>"'$phone'",'ISD'=>"'$isd'");
				$excludeArr=array('ACTIVATED'=>"'D'");
				$data=$dbJprofile->getArray($arr,$excludeArr,'',"EMAIL,USERNAME,EMAIL,ACTIVATED,PROFILEID");
				var_dump($arr);
				var_dump($data);
				// die;
				if(count($data) == 1)
				{
					$x = newjs_SMS_DETAIL()->getCount("FORGOT_PASSWORD", $data[0]['PROFILEID'], $isd.$phone);
					//  1 unique profile found
					include_once(sfConfig::get("sf_web_dir")."/profile/sendForgotPasswordLink.php");
					sendForgotPasswordLink($data);
					$apiObj->setHttpArray(ResponseHandlerConfig::$FLOGIN_EMAIL_SUCCESS);
				}

			}
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
