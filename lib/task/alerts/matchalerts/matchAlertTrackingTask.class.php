<?php

class matchAlertTrackingTask extends sfBaseTask
{
	protected function configure()
  	{
		/*$this->addArguments(array(
                        new sfCommandArgument('totalScripts', sfCommandArgument::REQUIRED, 'My argument'),
                        new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
        	));*/

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

        $trackingLibObj = new matchAlertMailerDataTracking();
        $todayDate = $trackingLibObj->getNoOfDays();
        $logTempObj = new matchalerts_LOG_TEMP();
        $countByLogicArr = $logTempObj->getCountGroupedByLogic();
        $dateInLogTemp = $logTempObj->getDate();
        $date = date("Y-m-d");
        if($todayDate > $dateInLogTemp)
        {
        	$date = date('Y-m-d',strtotime("-1 day"));
        }        
        $lowTrendsObj = new matchalerts_LowTrendsMatchalertsCheck();
        $lowTrendsCountArr = $lowTrendsObj->getLowCountGroupedByLogic($date);        
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
        
        $trackingLibObj->insertCountDataByLogicLevel($finalCountByLogicArr);
        
        $countByLogicAndRecommendations = $logTempObj->getCountGroupedByLogicAndRecommendation();        
        
        foreach($lowTrendsCountArr as $key=>$val)
        {
        	foreach($val as $k1=>$v1)
        	{
        		if($k1=="CNT")
        		{
        			$lowCountFinalArr[$key]["PeopleCount"] = $v1;
        		}
        		if($k1=="LOGICLEVEL")
        		{
        			$lowCountFinalArr[$key]["LOGICLEVEL"] = $v1;
        			$lowCountFinalArr[$key]["RecCount"] = 0;
        		}        		
        	}
        }
        $countByLogicAndRecommendations = array_merge($countByLogicAndRecommendations,$lowCountFinalArr);        
        $trackingLibObj->insertCountDataByLogicLevelAndRecommendation($countByLogicAndRecommendations);        
   	}
}