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
	$this->getParticularData = $request->getParameter('getParticularData');
	$this->serverHealthConfig = json_encode(ServerHealthEnums::$config);
	$this->onlyIssues = $request->getParameter('onlyIssues');
	if($this->getParticularData ==''|| $this->getParticularData=="HAPROXY" )
	{
		$HaProxy = new HaProxy();
		$this->marGayeServers =  json_encode($HaProxy->validate());
		if($this->getParticularData!='')
		{
			echo $this->marGayeServers;die;
		}
	}
	if($this->getParticularData ==''|| $this->getParticularData=="SOLR")
	{
		$this->thirdPartyCheckSolr = json_encode(ThirdPartyService::checkSolr());
                if($this->getParticularData!='')
		{
			echo $this->thirdPartyCheckSolr;die;
		}
	}
	if($this->getParticularData ==''|| $this->getParticularData=="THIRD_SERVICES" )
	{
		$this->checkGuna = ThirdPartyService::checkGuna();
		$this->checkRedis = ThirdPartyService::checkRedis();
		$this->checkRabbit = ThirdPartyService::checkRabbitMq();
		$this->checkServices  = ThirdPartyService::callJavaServices();
		$this->checkServices['Guna'] = $this->checkGuna;
		$this->checkServices['Redis'] = $this->checkRedis;
		$this->checkServices['Rabbit'] = $this->checkRabbit;
		foreach($this->checkServices as $k=>$v)
		{
			$this->checkServices[$k]['responseTime']=round($this->checkServices[$k]['responseTime'],3);
		}
		$this->checkServices = json_encode($this->checkServices);
		if($this->getParticularData!='')
		{
			echo $this->checkServices;die;
		}
	}
	if($this->getParticularData ==''|| $this->getParticularData =='SERVER_STATUS')
	{
		$serverStatusObj = new ServerStatus;
		$this->serverstatus = json_encode($serverStatusObj->getStatus());
		if($this->getParticularData!='')
		{
			echo $this->serverstatus;die;
		}
	}
	if($this->getParticularData ==''|| $this->getParticularData =='MYSQL_STATUS')
	{
		$mysqlStatusObj = new MysqlStatus;
		$this->mysqlStatus = json_encode($mysqlStatusObj->getStatus());
		if($this->getParticularData!='')
		{
			echo $this->mysqlStatus;die;
		}
	}
  }
}
