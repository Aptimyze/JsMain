<?php

/**
 * jaagterahoo actions.
 *
 * @package    jeevansathi
 * @subpackage jaagterahoo
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class jaagterahooActions extends sfActions
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
  public function executeDuniyaWalo(sfWebRequest $request)
  {
	$this->serverHealthConfig = json_encode(ServerHealthEnums::$config);

	$HaProxy = new HaProxy();
	$this->marGayeServers =  $HaProxy->validate();

	$this->thirdPartyCheckSolr = ThirdPartyService::checkSolr();
	$this->checkGuna = ThirdPartyService::checkGuna();
	$this->checkRedis = ThirdPartyService::checkRedis();
	$this->checkRabbit = ThirdPartyService::checkRabbitMq();
	$this->checkServices  = ThirdPartyService::callJavaServices();
	print_r($this->checkServices); die;

	$url = JsConstants::$chatListingWebServiceUrl["dpp"]."?type=CHATDPP";
	$this->checkDpp = ThirdPartyService::javaService($url,9061321);

	$url = "http://10.10.18.67:8290/profile/v1/presence?pfids=9061321";
	$this->checkPresence67 = ThirdPartyService::javaService($url);

	$url = "http://10.10.18.72:8290/profile/v1/presence?pfids=9061321";
	$this->checkPresence72 = ThirdPartyService::javaService($url);

	$serverStatusObj = new ServerStatus;
	$this->serverstatus = $serverStatusObj->getStatus();
	$mysqlStatusObj = new MysqlStatus;
	$this->mysqlStatus = $mysqlStatusObj->getStatus();
  }
}
