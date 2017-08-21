<?php

/**
 * 
 *
 *
 * @package    jeevansathi
 * @subpackage api
 * @author     Kuna Verma
 * @date       21st Aug 2017
 */
class ApiFeedbackDeleteAttachmentV1Action extends sfActions {

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
    
    
   if ($request->isMethod('POST')) {
      $feedBackObj = new FAQFeedBack(1);
      $result = $feedBackObj->deleteTempAttachments($request);
      if(false === $result) {
        $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$ABUSE_ATTACHMENT_DELETE_ERROR);
      } else {
        $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
      }
    } else {
      $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$POST_PARAM_INVALID);
    }
    
    $apiResponseHandlerObj->generateResponse();

    die;
  }

}
