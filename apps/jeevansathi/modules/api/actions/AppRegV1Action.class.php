<?php

/**
 * api actions.
 * AppRegV1
 * Controller to register a new device
 * @package    jeevansathi
 * @subpackage api
 * @author     Nitesh Sethi
 */
class AppRegV1Action extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{	
        $responseData = array();
        // ?? will implement if required (Lakshay)
        //$etag = md5($request->getParameter('uri'));
		/*if (false){//$request->ifNoneMatch($etag)) {
			$status = ResponseHandlerConfig::$NOTMODIFIED;
		}
		else {*/
			//------------$response->addEtag($etag);??
			$deviceRegistrationObj = new DeviceRegistration();
			if ($request->getParameter('identifier')) {
				$responseData = $deviceRegistrationObj->generateUID($request->getParameter('identifier'));
				if(is_array($responseData))
					$status = ResponseHandlerConfig::$AUTH_KEY_GENERATED;
				else{				
					$status = ResponseHandlerConfig::$UNIDENTIFIED_IDENTIFIER;
					ValidationHandler::getValidationHandler("","Invalid Identifier");
				}
			}
			elseif($request->getParameter('authKey')) {
				$responseData = $deviceRegistrationObj->confirmRegistration($request->getParameter('authKey'));
				if($responseData)
					$status = ResponseHandlerConfig::$APP_REG_VERIFIED;
				else{
					$status= ResponseHandlerConfig::$APP_REG_FAILED;
					ValidationHandler::getValidationHandler("","App Registration Failed");
				}
				}
			else{
				$status= ResponseHandlerConfig::$UNKNOWN_REG_REQUEST;
				ValidationHandler::getValidationHandler("","Unknown Reg Request");
			}

			$respObj = ApiResponseHandler::getInstance();
			
			$respObj->setHttpArray($status);
			
			if(is_array($responseData))
				$respObj->setResponseBody($responseData);
			$respObj->generateResponse();
			
		//}
           die();
    }
}
