<?php

class matchAlertTrackingTask extends sfBaseTask
{
	protected function configure()
  	{		

		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
	     
	    $this->namespace        = 'alert';
	    $this->name             = 'matchAlertTracking';
	    $this->briefDescription = 'This cron is run daily after Match Alert Calculation task and is used to log counts of recommendations based on different criterias into different tables';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony alert:matchAlertTracking] 
EOF;
  	}

  	protected function execute($arguments = array(), $options = array())
	{
		if(!sfContext::hasInstance())
			sfContext::createInstance($this->configuration);

		ini_set('memory_limit','1024M');
        
        $trackingLibObj = new matchAlertMailerDataTracking();
        $todayDate = MailerConfigVariables::getNoOfDays(); //To get the current date
        $logTempObj = new matchalerts_LOG_TEMP();
        $countByLogicArr = $logTempObj->getCountGroupedByLogic(); //To get count of profiles grouped by Logic
        $dateInLogTemp = $logTempObj->getDate(); //Get date from LOG_TEMP
        $date = date("Y-m-d");
        
        if($todayDate > $dateInLogTemp) //To check if the date in LOG_TEMP is the current date
        {
        	$date = date('Y-m-d',strtotime("-1 day"));
        }        
        $lowTrendsObj = new matchalerts_LowTrendsMatchalertsCheck();
        $lowTrendsCountArr = $lowTrendsObj->getLowCountGroupedByLogic($date); //Get count where count is ZERO       // To get ZERO count for each logic level               
        
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

        $trackingLibObj->insertCountDataByLogicLevel($finalCountByLogicArr,$date);
        
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

        $trackingLibObj->insertCountDataByLogicLevelAndRecommendation($countByLogicAndRecommendations,$date);                           
        $trackingLibObj->insertTotalCountGroupedByLogicAndReceiver($date);        
        unset($trackingLibObj);
   		unset($logTempObj);
   		unset($lowTrendsObj);
   	}
}