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
        $response = $privacySettingObj->updatePrivacySettings($field,$privacyValue);        
        if ($response)
        {
            $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
            $apiResponseHandlerObj->setResponseBody(array('responseVal'=>$response));            
        } 
        else
        {
            $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
        }
        
        $apiResponseHandlerObj->generateResponse();
        return sfView::NONE;
    }
}
