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
                try {
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
	            } catch (Exception $e) {
	            	SendMail::send_email('avneet.bindra@jeevansathi.com, vibhor.garg@jeevansathi.com', $e, 'Failure in Setting Gateway Parameters', 'js-sums@jeevansathi.com', 'avneetbindra180691@gmail.com', '', '', '', '', '', '', '', 'Membership Alerts');
	            }
	            try {
                	JeevansathiGatewayManager::reAuthenticateUser($this);
	            } catch (Exception $e) {
	            	SendMail::send_email('avneet.bindra@jeevansathi.com, vibhor.garg@jeevansathi.com', $e, 'Failure in User Re-Authentication Function', 'js-sums@jeevansathi.com', 'avneetbindra180691@gmail.com', '', '', '', '', '', '', '', 'Membership Alerts');
	            }
	            return;
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
            } else {
            	$allParams = $request->getParameterHolder()->getAll();
            	$stringParams = json_encode($allParams);
            	SendMail::send_email('avneet.bindra@jeevansathi.com, vibhor.garg@jeevansathi.com', $stringParams, 'Failure in Unknown Case', 'js-sums@jeevansathi.com', 'avneetbindra180691@gmail.com', '', '', '', '', '', '', '', 'Membership Alerts');
            	return sfView::NONE;
            	die;
            }
        }
    }
}
