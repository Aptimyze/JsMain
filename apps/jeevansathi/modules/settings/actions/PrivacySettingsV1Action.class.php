<?php

/*
 * @package    jeevansathi
 * @subpackage Privacy Settings
 * @author     Sanyam Chopra
*/

class PrivacySettingsV1Action extends sfAction
{
    public function execute($request) 
    {        
        $apiResponseHandlerObj = ApiResponseHandler::getInstance();
        $field = $request->getParameter("field");
        $privacyValue = $request->getParameter("privacy");
        $privacySettingObj = new privacySettings();
        $privacySettingObj->updatePrivacySettings($field,$privacyValue);
        echo("done");die;
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
