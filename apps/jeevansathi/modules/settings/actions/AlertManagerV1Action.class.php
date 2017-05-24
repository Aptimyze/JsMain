<?php

/*
 * @package    jeevansathi
 * @subpackage Alert Manager
 * @author     Avneet Singh Bindra
*/

class AlertManagerV1Action extends sfAction
{
    function execute($request) {
        
        $apiResponseHandlerObj = ApiResponseHandler::getInstance();
        $alertManagerObj = new AlertManager($request);
        $this->apiResponse = $alertManagerObj->generateResponse($request);
        
        if (!empty($this->apiResponse)) {
            $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
            $apiResponseHandlerObj->setResponseBody($this->apiResponse);
        } 
        else {
            $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
        }
        
        $apiResponseHandlerObj->generateResponse();
        
        if ($request->getParameter('INTERNAL') == 1) {
            return sfView::NONE;
        } 
        else {
            if ($this->apiResponse) {
                die;
            }
        }
    }
}
