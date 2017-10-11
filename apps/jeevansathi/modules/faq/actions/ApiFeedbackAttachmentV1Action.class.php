<?php

/**
 * profile actions.
 * ApiEditV1
 * Controller to register a new device
 * @package    jeevansathi
 * @subpackage api
 * @author     Kuna Verma
 * @date       14th Aug 2017
 */
class ApiFeedbackAttachmentV1Action extends sfActions {

  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function execute($request) {

    $loginData = $request->getAttribute("loginData");
    
    $apiResponseHandlerObj = ApiResponseHandler::getInstance();
    if ($loginData[PROFILEID]) {
      $loginProfile = LoggedInProfile::getInstance();
      $loginProfile->getDetail($loginData['PROFILEID'], "PROFILEID");
      $this->USERNAME = $loginData[USERNAME];
    } else {
      $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$LOGOUT_PROFILE);
      $apiResponseHandlerObj->generateResponse();
    }
    
    $arrFeedback = $request->getParameter('feed');
    
    if($request->isMethod('POST')) {
    $feedBackObj = new FAQFeedBack(1);
      $result = $feedBackObj->uploadTempAttachments($request);     
      if($feedBackObj->getIsAttachmentError()) {
        $arrError = array_values($result);
        
        $arrErrorOut["message"] = implode(",", $arrError);
        $arrErrorOut["feed[count]"] = $arrFeedback['count'];
        
        $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$ABUSE_ATTACHMENT_ERROR);
        $apiResponseHandlerObj->setResponseBody($arrErrorOut);
      } else {
        $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
        $apiResponseHandlerObj->setResponseBody( array( "attachment_id" => $result, "feed[count]"=> $arrFeedback['count'] ) );
      }
    } else {
      $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$POST_PARAM_INVALID);
    }
    
    $apiResponseHandlerObj->generateResponse();

    die;
  }

}
