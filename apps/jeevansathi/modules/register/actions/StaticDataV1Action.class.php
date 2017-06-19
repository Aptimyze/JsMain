<?php

/**
 * register actions.
 * StaticDataV1
 * Controller to send static table data to app
 * @package    jeevansathi
 * @subpackage register
 * @author     Kumar Anand
 */
class StaticDataV1Action extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		if($request->getParameter("actionName")=="staticTablesData")
                {
			$inputValidateObj->validateStaticTablesData($request);
			$output = $inputValidateObj->getResponse();

			if($output["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
			{
				$param = json_decode($request->getParameter("json"),true);
				$apiCommonObj = new ApiCommon;
				$resp = $apiCommonObj->getStaticTablesData($param);

				if($resp)
				{
					$respObj = ApiResponseHandler::getInstance();
					$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
					$respObj->setResponseBody(array("services"=>$resp));
					$respObj->generateResponse();
				}
				else
				{
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
		die;
    	}
}
