<?php

class matchAlertTrackingTask extends sfBaseTask
{
	protected function configure()
  	{
		$this->addArguments(array(
                        new sfCommandArgument('totalScripts', sfCommandArgument::REQUIRED, 'My argument'),
                        new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
        	));

		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
	     
	    $this->namespace        = 'alert';
	    $this->name             = 'matchAlertTracking';
	    $this->briefDescription = '';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony alert:matchAlertTracking] 
EOF;
  	}

  	protected function execute($arguments = array(), $options = array())
	{
		if(!sfContext::hasInstance())
			sfContext::createInstance($this->configuration);

		ini_set('memory_limit','512M');
        $totalScripts = $arguments["totalScripts"]; // total no of scripts
        $currentScript = $arguments["currentScript"]; // current script number

        $logTempObj = new matchalerts_LOG_TEMP();
        $countByLogicArr = $logTempObj->getCountGroupedByLogic();        
        $lowTrendsObj = new matchalerts_LowTrendsMatchalertsCheck();
        $lowTrendsCountArr = $lowTrendsObj->getLowCountGroupedByLogic();
        foreach($countByLogicArr as $key => $val)
        {
        	foreach($val as $k1=>$v1)
        	{
        		if($k1 == "CNT")
        		{
        			$finalCountByLogicArr[$key][$k1] = $v1 + $lowTrendsCountArr[$key]["CNT"];
        		}
        		elseif($k1 == "LOGICLEVEL")
        		{
        			$finalCountByLogicArr[$key][$k1] = $v1;
        		}
        		
        	}
        }        
        $trackingLibObj = new matchAlertMailerDataTracking();
        $trackingLibObj->insertCountDataByLogicLevel($finalCountByLogicArr);
        
        $countByLogicAndRecommendations = $logTempObj->getCountGroupedByLogicAndRecommendation();
        $trackingLibObj->insertCountDataByLogicLevelAndRecommendation($countByLogicAndRecommendations);        
   	}
}