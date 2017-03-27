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
      $loginData=$request->getAttribute("loginData");
      $this->username = $loginData["USERNAME"];
      $this->email = $loginData["EMAIL"];
      $ios = $request->getParameter('iosWebView');
      if(MobileCommon::isNewMobileSite())
        {
            if ($ios == 1) {
              $this->iosWebView = 1;
            }
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'JsAndWeb') > 1) {
              $this->andWebView = 1;
            }
            if ($this->iosWebView == 1 || $this->andWebView == 1) {
              $this->webView = 1;
            } else {
              $this->webView = 0;
            }
            $this->setTemplate("JSMSHelp");
        }
        else
        {
            $this->setTemplate("JSPCHelp");
        }
  }
  
  public function executeSubmitQueryV1(sfWebRequest $request){
      $apiObj = ApiResponseHandler::getInstance();
      $apiObj->setAuthChecksum($request->getAttribute("AUTHCHECKSUM"));
      unset($paramsArr);
      $paramsArr['email'] = $request->getParameter("email");
      $paramsArr['username'] = $request->getParameter("username");
      $paramsArr['query'] = $request->getParameter("query");
      $paramsArr['category'] = $request->getParameter("category");
      $paramsArr['channel'] = MobileCommon::isMobile()?"M":"D";
      
      $helpQueries = new jsadmin_HELP_USER_QUERIES();
      $helpQueries->insert($paramsArr);
      
      $apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
      $apiObj->setResponseBody($result);
      $apiObj->generateResponse();
      die;
  }
  
  public function executeGetPublicQuestionsV1(sfWebRequest $request){
      $apiObj = ApiResponseHandler::getInstance();
      $apiObj->setAuthChecksum($request->getAttribute("AUTHCHECKSUM"));
      $helpQuestionSlaveObj = new jsadmin_HELP_QUESTIONS("newjs_slave");
      list($allQuestions) = $helpQuestionSlaveObj->getAll("","1");
      $result =array("Response"=>$allQuestions);      
      $apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
      $apiObj->setResponseBody($result);
      $apiObj->generateResponse();
      die;
  }
  
  public function executeJSMSPostQuery(sfWebRequest $request){
      $loginData=$request->getAttribute("loginData");
      $this->username = $loginData["USERNAME"];
      $this->email = $loginData["EMAIL"];
      $this->iosWebView = $request->getParameter('iosWebView');
  }
}
