<?php
/**
 *  This task is for populating data in visitoralert.MAILER_VISITORS
 */
class VisitorAlertCalculateTask extends sfBaseTask
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
        
        try 
        {
           $visitoralertMailerVisitors = new visitorAlert_MAILER('shard1_master');

           $profiles = $visitoralertMailerVisitors->getMailerProfiles($totalScripts,$currentScript,"",$sent='U');

           
           if ( is_array($profiles) )
           {
                foreach ($profiles as $key => $profile) 
                {
                    $profileVisitors = array();
                    $profileVisitors = $this->getVisitorProfile($profile['PROFILEID']);
                    $profileVisitorsArray = array();
                    if ( is_array($profileVisitors) )
                    {
                        foreach ($profileVisitors as $key => $value) {
                            $profileVisitorsArray[] = $key;
                        }
                        $count = count($profileVisitorsArray);
                        $profileVisitorsArrayMixed[$profile['PROFILEID']] =   $profileVisitorsArray;
                    }
                    if ( is_array($profileVisitorsArrayMixed))
                    {
                        $visitoralertMailerVisitors->updateReceiverData($profileVisitorsArrayMixed,$count);
                    }
                }
            }
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
		$SearchParamtersObj = new VisitorsSearch($loggedInProfileObj);
		$SearchParamtersObj->getSearchCriteria(VisitorAlertEnums::MATCHED_OR_ALL);
		$SearchParamtersObj->setToSortByPhotoVisitors(1);
		$SearchServiceObj = new SearchService($searchEngine);
		$SearchServiceObj->setSearchSortLogic($SearchParamtersObj,$loggedInProfileObj,"",$sort);
		$responseObj = $SearchServiceObj->performSearch($SearchParamtersObj,'','','','',$loggedInProfileObj);
		$arr['PIDS'] = $responseObj->getsearchResultsPidArr();

		return $arr['PIDS'];
		
	}
}
