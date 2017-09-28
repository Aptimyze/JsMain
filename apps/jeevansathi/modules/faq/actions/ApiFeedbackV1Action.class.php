<?php

/**
 * profile actions.
 * ApiEditV1
 * Controller to register a new device
 * @package    jeevansathi
 * @subpackage api
 * @author     Nitesh Sethi
 */
class ApiFeedbackV1Action extends sfActions {

  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function execute($request) {
    $loginData = $request->getAttribute("loginData");
    if ($loginData[PROFILEID]) {
      $loginProfile = LoggedInProfile::getInstance();
      $loginProfile->getDetail($loginData['PROFILEID'], "PROFILEID");
      $this->USERNAME = $loginData[USERNAME];
    }

    $arrFeed = $request->getParameter("feed");
    $valResonCode = $this->forwardToReportInvalid($request);

    if (false !== $valResonCode) {
      $val = $valResonCode;

      $request->setParameter("reasonCode", $val);
      $request->setParameter("profilechecksum", $this->getOtherProfileCheckSum($request));
      $request->setParameter("mobile", "Y");
      $request->setParameter("phone", "N");

      $this->forward("phone", "ReportInvalid");
    }

    $apiResponseHandlerObj = ApiResponseHandler::getInstance();

    //if request does not contain user specified message, then prompt an error
    if (false === $this->isMessageAvailable($request)) {
      $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
      $apiResponseHandlerObj->setResponseBody(array("message" => "User specified message is missing."));
      $apiResponseHandlerObj->generateResponse();
      die;
    }

    $feedBackObj = new FAQFeedBack(1);


    $success = false;
    $result = $feedBackObj->ProcessData($request);
    if (is_array($result)) {
      foreach ($result as $key => $val) {
        $error[message] = $val;
        ValidationHandler::getValidationHandler("", $val . "in Report Abuse API");
      }
      $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
      $apiResponseHandlerObj->setResponseBody($error);
    } elseif ($result) {
      if (MobileCommon::isApp() == "I")
        $success[message] = FeedbackEnum::SUCCESS_IOS_MSG;
      else
        $success[message] = FeedbackEnum::SUCCESS_ANDROID_MSG;

      if (strtolower(FeedbackEnum::CAT_ABUSE) == trim(strtolower($feedBackObj->getCategory()))) {
        $success[message] = FeedbackEnum::SUCCESS_ABUSE_MSG;
      }
      $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
      $apiResponseHandlerObj->setResponseBody($success);
    } else {
      $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
      $result = array("INVALID_REP_ABUSE REQUEST" => "not a valid report abuse request");
      $apiResponseHandlerObj->setResponseBody($result);
      ValidationHandler::getValidationHandler("", "not a valid report abuse request");
    }

    $apiResponseHandlerObj->generateResponse();
    //$this->form = $feedBackObj->getForm();
    //$this->tracepath = $feedBackObj->getTracePath();
    die;
  }

  /**
   * 
   * @return boolean
   */
  private function forwardToReportInvalid($request) {

    //For Rest Channel
    $arrAllowedReason = array(
        'user\'s phone is switched off/not reachable' => 1,
        'user is not picking up phone calls' => 4,
    );

    //Please Refer FAQFeedback::$REASON_MAP
    //15 is user is not picking up phone calls
    //17 is user\'s phone is switched off/not reachable
    $arrAllowedAndroidReason = array(15 => 4, 17 => 1);

    $forwardToReportInvalid = false;
    $returnValue = false;

    $androidReasonMap = $request->getParameter('reason_map');
    if (MobileCommon::isAndroidApp() && false == is_null($androidReasonMap) && isset($arrAllowedAndroidReason[$androidReasonMap])) {
      $returnValue = $arrAllowedAndroidReason [$request->getParameter('reason_map')];
    }

    $arrFeed = $request->getParameter("feed");

    if (false === $forwardToReportInvalid && isset($arrAllowedReason [trim(strtolower($arrFeed['mainReason']))])) {
      $returnValue = $arrAllowedReason [trim(strtolower($arrFeed['mainReason']))];
    }

    return $returnValue;
  }

  /**
   * 
   * @param type $request
   * @return type
   */
  private function getOtherProfileCheckSum($request) {

    $otherProfileCheckSum = $request->getParameter("profilechecksum");
    //Awful Check!!
    if (MobileCommon::isIOSApp() && null == $otherProfileCheckSum) {
      $feed = $request->getParameter('feed');
      $reason = $feed['message'];

      $pos = strpos($reason, ':');
      $reasonNew = trim(substr($reason, $pos + 1));

      $pos2 = strpos($reason, 'by');
      $arr2 = split(' ', trim(substr($reason, $pos2 + 2)));

      $otherUsername = trim($arr2[0]);

      $otherProfile = new Profile();
      $otherProfile->getDetail($otherUsername, "USERNAME");

      $otherProfileId = $otherProfile->getPROFILEID();

      $otherProfileCheckSum = JsCommon::createChecksumForProfile($otherProfileId);
    }
    return $otherProfileCheckSum;
  }

  /**
   * 
   * @param type $request
   * @return boolean
   */
  private function isMessageAvailable($request) {
    return true;
    $msg = $request->getParameter("other_reason");
    //In Android Channel, other_reason key have the message specified by user in open text field
    if (MobileCommon::isAndroidApp() && ( is_null($msg) || 0 === strlen($msg))) {
      return false;
    }

    $arrFeed = $request->getParameter("feed");
    //In Other Channels, feed[message] contains the message specified by user in open text field
    if (false === MobileCommon::isAndroidApp() && is_null($arrFeed['message'])) {
      return false;
    }

    return true;
  }

}
