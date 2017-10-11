<?php

/**
 * search actions.
 * SearchFormDataV1
 * Controller to send search form data to app
 * @package    jeevansathi
 * @subpackage search
 * @author     Kumar Anand
 */
class SearchFormDataV1Action extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{ 
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		if($request->getParameter("actionName")=="searchFormData")
    {
			$inputValidateObj->validateSearchFormData($request);
			$output = $inputValidateObj->getResponse();
			if($output["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
			{
				$param = json_decode($request->getParameter("json"),true);
				$apiCommonObj = new ApiCommon;
				$this->isApp= MobileCommon::isApp();
				$needMobileFormat=true;
				if($this->isApp=="A" || $this->isApp == "I")
					$needMobileFormat=false;
				$resp = $apiCommonObj->getSearchFormData($param,$needMobileFormat,$request->getParameter('forClusters'));

				if($resp)
				{
					$respObj = ApiResponseHandler::getInstance();
					$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
					$respObj->setResponseBody(array("services"=>$resp));
					$respObj->generateResponse();
				}
				else
				{
	                        	$errorString = "search/actions/SearchFormDataV1Action.class.php : Reason (no response)";
	                	        ValidationHandler::getValidationHandler("",$errorString);

					$respObj = ApiResponseHandler::getInstance();
                                	$respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
                                	$respObj->generateResponse();
				}
				unset($resp);
			}
			else
			{
				$respObj = ApiResponseHandler::getInstance();
                                $respObj->setHttpArray($output);
                                $respObj->generateResponse();
			}
			unset($output);
		}
		unset($inputValidateObj);
		  return sfView::NONE;
		die;
    	}
}
