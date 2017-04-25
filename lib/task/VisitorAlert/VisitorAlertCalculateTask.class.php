<?php
/**
 *  This task is for populating data in visitoralert.MAILER_VISITORS
 */
class VisitorAlertCalculateTask extends sfBaseTask
{
private $limit = 1000;
    const VISITORS_MAILER_LIMIT = 10;
  protected function configure()
    {
        $this->addArguments(array(
                        new sfCommandArgument('totalScripts', sfCommandArgument::REQUIRED, 'My argument'),
                        new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
          ));

    $this->addOptions(array(
    new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
       ));
        
        $this->namespace = 'VisitorAlert';
        $this->name = 'VisitorAlertCalculate';
        $this->briefDescription='';
        $this->detailedDescription = <<<EOF
          Call it with:
          [php symfony VisitorAlert:VisitorAlertCalculate totalScripts currentScript]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        if(!sfContext::hasInstance())
            sfContext::createInstance ($this->configuration);
        ini_set('memory_limit','1024M');

        $totalScripts = $arguments["totalScripts"]; // total no of scripts
        $currentScript = $arguments["currentScript"]; // current script number
	if(CommonUtility::hideFeaturesForUptime())
                successfullDie();
        try 
        {
		$flag = 1;
                do{
                        if(CommonUtility::hideFeaturesForUptime())
                            successfullDie();
			//file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/bhavana_1".$currentScript.".txt","p1 ::::::::::".(memory_get_usage(false)/1024/1024)." MiB\n",FILE_APPEND);
		   	$visitoralertMailerVisitors = new visitorAlert_MAILER('shard1_master');
           		$profiles = $visitoralertMailerVisitors->getMailerProfiles($totalScripts,$currentScript,$this->limit,$sent='U');
          		if ( is_array($profiles) )
           		{
                		foreach ($profiles as $key => $profile) 
                		{
					//file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/ba.txt",$c++."\n",FILE_APPEND);
                    			$profileVisitors = $this->getVisitorProfile($profile['PROFILEID']);
			                if ( is_array($profileVisitors) )
                    			{
			                        foreach ($profileVisitors as $key => $value) {
                            				$profileVisitorsArray[] = $value;
                        			}
						unset($profileVisitors);
			                        $count = count($profileVisitorsArray);
			                        $profileVisitorsArrayMixed[$profile['PROFILEID']] =   $profileVisitorsArray;
						unset($profileVisitorsArray);
                    			}
                    			if ( is_array($profileVisitorsArrayMixed))
                    			{
			                        $visitoralertMailerVisitors->updateReceiverData($profileVisitorsArrayMixed,$count);
                    			}
					else{		
			                        $visitoralertMailerVisitors->updateReceiverDataSetX($profile['PROFILEID']);
                    			}
					unset($profileVisitorsArrayMixed);
                		}
            		}else{
				$flag = 0;
			}
			//file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/bhavana_1".$currentScript.".txt","p2 ::::::::::".(memory_get_usage(false)/1024/1024)." MiB\n",FILE_APPEND);

			unset($visitoralertMailerVisitors);
                        unset($profiles);
		}while($flag);
        } 
        catch (PDOException $e)
        {
            throw new jsException($e);
        }
    }
    
    
    
    private function getVisitorProfile($profileId){
		$searchEngine = 'solr';
		$sort = SearchSortTypesEnums::SortByVisitorsTimestamp;
		$loggedInProfileObj = LoggedInProfile::getInstance('',$profileId);
                $loggedInProfileObj->getDetail('','','*');
		$SearchParamtersObj = new VisitorsSearch($loggedInProfileObj);
		$SearchParamtersObj->getSearchCriteria(VisitorAlertEnums::MATCHED_OR_ALL,  VisitorAlertEnums::NO_OF_DAYS_BEFORE_MAILER);
		$SearchParamtersObj->setToSortByPhotoVisitors(1);
                $SearchParamtersObj->setNoOfResults(self::VISITORS_MAILER_LIMIT);
		$SearchServiceObj = new SearchService($searchEngine);
		$SearchServiceObj->setSearchSortLogic($SearchParamtersObj,$loggedInProfileObj,"",$sort);
		$responseObj = $SearchServiceObj->performSearch($SearchParamtersObj,'','','','',$loggedInProfileObj);
		$arr['PIDS'] = $responseObj->getsearchResultsPidArr();
unset($loggedInProfileObj);
unset($SearchParamtersObj);
unset($SearchServiceObj);
unset($responseObj);
		return $arr['PIDS'];
		
	}
}
