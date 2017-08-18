<?php

/*
 * @package    jeevansathi
 * @subpackage membership
 * @author     Avneet Singh Bindra
 */

class ApiMembershipDetailsV3Action extends sfAction
{

    public function execute($request)
    {
        $memResponseHandlerObj = new MembershipAPIResponseHandler();
        $this->apiParams       = $memResponseHandlerObj->initializeAPI($request);
        $apiResponseHandlerObj = ApiResponseHandler::getInstance();
        if ($this->apiParams->processPayment) {
            $this->apiParams = $memResponseHandlerObj->processPaymentAndRedirect($request, $this->apiParams);
        } else {
            $this->apiResponse = $memResponseHandlerObj->generateResponseData($request);
        }
        if ($this->apiResponse || $this->apiParams->processPayment) {
            if ($this->apiParams->device == "Android_app") {
                $this->apiParams->device = "JSAA_mobile_website";
            }
            //print_r($this->apiParams);die;
            if ($this->apiParams->processPayment) {
                if (empty($this->apiParams->profileid) || !is_numeric($this->apiParams->profileid)) {
                    $this->setTemplate("paramsError");
                } else {
                    try {
                        
                        switch ($this->apiParams->pageRedirectTo) {
                            case 'ccavenue':
                                JeevansathiGatewayManager::setCCAVENUEParams($this, $this->apiParams);
                                break;

                            case 'payu':
                                JeevansathiGatewayManager::setPayUParams($this, $this->apiParams);
                                break;

                            case 'paytm':
                                JeevansathiGatewayManager::setPayTMParams($this, $this->apiParams);
                                break;

                            case 'paypal':
                                JeevansathiGatewayManager::setPaypalParams($this, $this->apiParams);
                                break;

                            default:
                                $this->setTemplate("apiPaymentRedirect");
                                break;

                        }
                    } catch (Exception $e) {
                        $serverStr = "\n<br>\n<br>\n<br>" . json_encode($_SERVER);
                        if (JsConstants::$whichMachine == 'prod') {
                            SendMail::send_email('vibhor.garg@jeevansathi.com,manoj.rana@naukri.com,ankita.g@jeevansathi.com,nitish.sharma@jeevansathi.com', $e . $serverStr, 'Failure in Setting Gateway Parameters', 'js-sums@jeevansathi.com', '', '', '', '', '', '', '', '', 'Membership Alerts');
                        }
                    }
                    try {
                        JeevansathiGatewayManager::reAuthenticateUser($this);
                    } catch (Exception $e) {
                        $serverStr = "\n<br>\n<br>\n<br>" . json_encode($_SERVER);
                        if (JsConstants::$whichMachine == 'prod') {
                            SendMail::send_email('vibhor.garg@jeevansathi.com,manoj.rana@naukri.com,ankita.g@jeevansathi.com,nitish.sharma@jeevansathi.com', $e . $serverStr, 'Failure in User Re-Authentication Function', 'js-sums@jeevansathi.com', '', '', '', '', '', '', '', '', 'Membership Alerts');
                        }
                    }
                }
                return;
            } elseif ($this->apiResponse == "logout_case") {
                $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$LOGOUT_PROFILE);
            } else {
                $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
                $apiResponseHandlerObj->setResponseBody($this->apiResponse);
            }
        } else {
            $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
        }
        if ($this->apiResponse) {
            $apiResponseHandlerObj->generateResponse();
        }
        if ($request->getParameter('INTERNAL') == 1 && empty($this->apiParams->processCallback)) {
            return sfView::NONE;
        } else {
            if ($this->apiResponse) {
                die;
            } else {
                $allParams    = $request->getParameterHolder()->getAll();
                $stringParams = json_encode($allParams);
                $serverStr    = "\n<br>\n<br>\n<br>" . json_encode($_SERVER);
                if (JsConstants::$whichMachine == 'prod') {
                    SendMail::send_email('avneet.bindra@jeevansathi.com, vibhor.garg@jeevansathi.com', $stringParams . $serverStr, 'Failure in Unknown Case', 'js-sums@jeevansathi.com', 'avneetbindra180691@gmail.com', '', '', '', '', '', '', '', 'Membership Alerts');
                }
                return sfView::NONE;
                die;
            }
        }
    }
}
