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
        $pObj = LoggedInProfile::getInstance("newjs_master",'');
        if($pObj->getPROFILEID()!="9061321")
                die("wrong url");
	$this->serverHealthConfig = json_encode(ServerHealthEnums::$config);

	$HaProxy = new HaProxy();
	$this->marGayeServers =  $HaProxy->validate();

	$this->thirdPartyCheckSolr = ThirdPartyService::checkSolr();
	$this->checkGuna = ThirdPartyService::checkGuna();
	$this->checkRedis = ThirdPartyService::checkRedis();
	$this->checkRabbit = ThirdPartyService::checkRabbitMq();
	$this->checkServices  = ThirdPartyService::callJavaServices();

	$serverStatusObj = new ServerStatus;
	$this->serverstatus = $serverStatusObj->getStatus();

	$mysqlStatusObj = new MysqlStatus;
	$this->mysqlStatus = $mysqlStatusObj->getStatus();
  }
}
