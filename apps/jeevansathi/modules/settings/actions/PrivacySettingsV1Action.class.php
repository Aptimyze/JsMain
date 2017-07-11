<?php

/*
 * @package    jeevansathi
 * @subpackage Privacy Settings
 * @Description: This api fetches field and privacy from the request and updates the same on edit_log and jprofile
 * @author     Sanyam Chopra
*/

class PrivacySettingsV1Action extends sfAction
{
    public function execute($request) 
    {        
        $apiResponseHandlerObj = ApiResponseHandler::getInstance();
        $getDataFlag = $request->getParameter("getData");
        $privacySettingObj = new privacySettings();
        if($getDataFlag)
        {            
            $response = json_encode($privacySettingObj->getPrivacySettingsData());
        }
        else
        {
            $field = $request->getParameter("field");
            $privacyValue = $request->getParameter("privacy");
            $privacySettingObj = new privacySettings();
            $response = $privacySettingObj->updatePrivacySettings($field,$privacyValue);
        }
         unset($privacySettingObj);       
        if($response)
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
