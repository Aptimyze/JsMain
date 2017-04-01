<?php

/**
 * profile actions.
 * ApiEditV1
 * Controller to register a new device
 * @package    jeevansathi
 * @subpackage api
 * @author     Nitesh Sethi
 */
class ApiFeedbackV1Action extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{
		
		$loginData=$request->getAttribute("loginData");
		if($loginData[PROFILEID])
		{
			$loginProfile=LoggedInProfile::getInstance();
			$loginProfile->getDetail($loginData['PROFILEID'],"PROFILEID");
			$this->USERNAME=$loginData[USERNAME];
		}
		$feedBackObj = new FAQFeedBack(1);
		$apiResponseHandlerObj=ApiResponseHandler::getInstance();

		$success=false;
		$result=$feedBackObj->ProcessData($request);
		if(is_array($result))
		{
			foreach($result as $key=>$val)
			{
				$error[message]=$val;
				ValidationHandler::getValidationHandler("",$val."in Report Abuse API");
			}
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiResponseHandlerObj->setResponseBody($error);
			
		}
		elseif($result)
		{
			if(MobileCommon::isApp()=="I")
				$success[message]= FeedbackEnum::SUCCESS_IOS_MSG;
			else
				$success[message]= FeedbackEnum::SUCCESS_ANDROID_MSG;
      
      if(strtolower(FeedbackEnum::CAT_ABUSE) == trim(strtolower($feedBackObj->getCategory())))
      {
        $success[message] = FeedbackEnum::SUCCESS_ABUSE_MSG;
      }
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$apiResponseHandlerObj->setResponseBody($success);
		}
		else
		{
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$result=array("INVALID_REP_ABUSE REQUEST"=>"not a valid report abuse request");
			$apiResponseHandlerObj->setResponseBody($result);
			ValidationHandler::getValidationHandler("","not a valid report abuse request");
		}
		
		$apiResponseHandlerObj->generateResponse();
		//$this->form = $feedBackObj->getForm();
		//$this->tracepath = $feedBackObj->getTracePath();
		die;
	}

}
