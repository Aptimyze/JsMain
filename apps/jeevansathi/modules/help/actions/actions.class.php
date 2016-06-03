<?php

/**
 * help actions.
 *
 * @package    jeevansathi
 * @subpackage help
 * @author     Nitish
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class helpActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }
  
  /*
   * @desc: API to get all questions
   * @param $request
   * @return none
   */
  public function executeHelpQuestionsV1(sfWebRequest $request)
  {
      $apiObj = ApiResponseHandler::getInstance();
      $apiObj->setAuthChecksum($request->getAttribute("AUTHCHECKSUM"));
      $helpQuestionSlaveObj = new jsadmin_HELP_QUESTIONS("newjs_slave");
      list($allQuestions) = $helpQuestionSlaveObj->getAll();
      $result =array("Response"=>$allQuestions);      
      $apiObj->setHttpArray(CrmResponseHandlerConfig::$INVALID_USERNAME);
      $apiObj->setResponseBody($result);
      $apiObj->generateResponse();
      die;
  }
}
