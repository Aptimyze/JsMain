<?php
/* cron to update last login date from memcache into PSWRDS table and set the ACTIVE  status 'N'  in PSWRDS table of those agents those have not login
	from more than last 50 days */

class cronUpdateAgentsLoginHistoryTask extends sfBaseTask
{
	/**
	   * 
	   * Configuration details for CRM:cronUpdateAgentsLoginHistory
	   * 
	   * @access protected
	   * @param none
  	*/
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'CRM';
		$this->name             = 'cronUpdateAgentsLoginHistory';
		$this->briefDescription = 'cron to update last login date from memcache into PSWRDS table and set the ACTIVE  status N  in PSWRDS table of those agents those have not login from more than last 50 days';
		$this->detailedDescription = <<<EOF
		The [cronUpdateAgentsLoginHistory|INFO] task does things.
		Call it with:
		[php symfony CRM:cronUpdateAgentsLoginHistory|INFO]
EOF;
	}

	/**
	   * 
	   * Function for executing cron. 
	   * 
	   * @access protected
	   * @param $arguments,$options
   */
	protected function execute($arguments = array(), $options = array())
	{	 
		if(!sfContext::hasInstance())
		{
			sfContext::createInstance($this->configuration);
		}
		$pswrdsObj = new jsadmin_PSWRDS();

		//populate agent login history from memcache into PSWRDS table
		if(crmCommonConfig::$useCrmMemcache==true)
		{
			$backendLibObj = new backendActionsLib("",true);
			$crmMemcachedData = $backendLibObj->getMemcachedCrmData();
			if(is_array($crmMemcachedData["AGENTS_PSWRDS"]) && $crmMemcachedData["AGENTS_PSWRDS"])
			{	
				foreach ($crmMemcachedData["AGENTS_PSWRDS"] as $agentid => $value) 
				{
					if($value["LAST_LOGIN_DT"])
						$pswrdsObj->edit(array("LAST_LOGIN_DT"=>$value["LAST_LOGIN_DT"]),$agentid);
				}
				unset($crmMemcachedData);
				$backendLibObj->removeCrmMemcachedData("CRM_MEMCACHED_DATA");
			}
			unset($backendLibObj);
			unset($crmMemcachedData);
		}

		//deactivate agents' crm account who have not logged in since past 15 days
		$days15_before=date("Y-m-d H:i:s",time()-15*86400);
		$pswrdsObj->updateActiveStatus($days15_before,'N');
		unset($pswrdsObj);
	}
}
?>