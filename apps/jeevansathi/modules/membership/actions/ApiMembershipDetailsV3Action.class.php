<?php

/*
 * @package    jeevansathi
 * @subpackage membership
 * @author     Avneet Singh Bindra
 */

class ApiMembershipDetailsV3Action extends sfAction
{
    
    function execute($request) {
        $memResponseHandlerObj = new MembershipAPIResponseHandler();
        $this->apiParams = $memResponseHandlerObj->initializeAPI($request);
        $apiResponseHandlerObj = ApiResponseHandler::getInstance();
        if($this->apiParams->processPayment){
            $this->apiParams = $memResponseHandlerObj->processPaymentAndRedirect($request,$this->apiParams);
            //print_r($this->apiParams); die;
        }
        else{
            $this->apiResponse = $memResponseHandlerObj->generateResponseData($request);
        }
        if ($this->apiResponse || $this->apiParams->processPayment) {
            if ($this->apiParams->device == "Android_app") {
                $this->apiParams->device = "JSAA_mobile_website";
            }
            if ($this->apiParams->processPayment) {
                switch ($this->apiParams->pageRedirectTo) { 
                    case 'ccavenue':
                        JeevansathiGatewayManager::setCCAVENUEParams($this,$this->apiParams);
                        break;

                    case 'payu':
                        JeevansathiGatewayManager::setPayUParams($this,$this->apiParams);
                        break;

                    case 'paytm':
                        JeevansathiGatewayManager::setPayTMParams($this,$this->apiParams);
                        break;

                    case 'paypal':
                        JeevansathiGatewayManager::setPaypalParams($this,$this->apiParams);
                        break;

                    default:
                        $this->setTemplate("apiPaymentRedirect");
                        break;
                }
                JeevansathiGatewayManager::reAuthenticateUser($this);
            } 
            elseif ($this->apiResponse == "logout_case") {
                $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$LOGOUT_PROFILE);
            } 
            else {
                
                $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
                $apiResponseHandlerObj->setResponseBody($this->apiResponse);
            }
        } 
        else {
            $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
        }
        if ($this->apiResponse) {
            $apiResponseHandlerObj->generateResponse();
        }
        if ($request->getParameter('INTERNAL') == 1 && empty($this->apiParams->processCallback)) {
            return sfView::NONE;
        } 
        else {
            if ($this->apiResponse) {
                die;
            }
        }
    }
}
