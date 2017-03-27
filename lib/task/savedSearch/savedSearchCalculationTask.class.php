<?php
/**
* This taks will decide correct Strategy for the savedSearch users and fill the data whose *profiles are to be sent for each receiver
*/
class savedSearchCalculationTask extends sfBaseTask
{
	private $limit = 5000;
	protected function configure()
  	{
		$this->addArguments(array(
                        new sfCommandArgument('totalScripts', sfCommandArgument::REQUIRED, 'My argument'),
                        new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
        	));

		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
	     
	    $this->namespace        = 'savedSearch';
	    $this->name             = 'savedSearchCalculation';
	    $this->briefDescription = '';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony savedSearch:savedSearchCalculation totalScripts currentScript] 
EOF;
  	}

  	protected function execute($arguments = array(), $options = array())
   {
      if(!sfContext::hasInstance())
         sfContext::createInstance($this->configuration);

     ini_set('memory_limit','512M');
                $totalScripts = $arguments["totalScripts"]; // total no of scripts
                $currentScript = $arguments["currentScript"]; // current script number

                $flag=1;
                $userArray = savedSearchMailerEnums::$userArray;
                $savedSearchObj = new send_saved_search_mail();

                do
                {
                    $dataArr = $savedSearchObj->fetchReceiverDetails($totalScripts,$currentScript,$this->limit);
                    if(is_array($dataArr))
                    {
                        foreach($dataArr as $key => $value)
                        {
                            try
                            {
                             $profileId = $value["RECEIVER"]; 

                             $loggedInProfileObj = LoggedInProfile::getInstance();
                             $loggedInProfileObj->getDetail($profileId,"PROFILEID","*");
                             $SearchParamtersObj = PredefinedSearchFactory::getSetterBy('mySavedSearch',$loggedInProfileObj);
                             $SearchParamtersObj->setShowFilteredProfiles('X');
                             $SearchParamtersObj->setNoOfResults(10);
                             unset($arr);
                             unset($finalArr);
                             $SearchParamtersObj->getSearchCriteria($value["SEARCH_ID"],1);
                             $SearchParamtersObj->setHVERIFY_ACTIVATED_DT(date("Y-m-d H:i:s"));
                             $SearchParamtersObj->setLVERIFY_ACTIVATED_DT(date("Y-m-d H:i:s", strtotime("-1 week")));

                               //searchEngine = solr
                             //outputFormat = array
                             $SearchServiceObj = new SearchService(savedSearchMailerEnums::$searchEngine,savedSearchMailerEnums::$outputFormat);
                            $SearchServiceObj->setSearchSortLogic($SearchParamtersObj,$loggedInProfileObj,"",SearchSortTypesEnums::justJoinedSortFlag); //since same logic as JustJoined is to be used

                            $SearchUtilityObj =  new SearchUtility;
                            $SearchUtilityObj->removeProfileFromSearch($SearchParamtersObj,'spaceSeperator',$loggedInProfileObj,'',savedSearchMailerEnums::$noAwaitingContacts);
                            $responseObj = $SearchServiceObj->performSearch($SearchParamtersObj,$results_orAnd_cluster,$clustersToShow,$currentPage,'',$loggedInProfileObj);
                            $arr[$value["SEARCH_ID"]] = $responseObj->getsearchResultsPidArr();

                            //Array received in $arr is altered in the required format
                            foreach($arr as $key=>$val1)
                            {
                                if(count($val1)>0)
                                {
                                    foreach($userArray as $k1=>$v1)
                                    {
                                        if(array_key_exists($k1, $val1))
                                        {
                                            $finalArr[$key][$userArray[$k1]] = $val1[$k1];
                                        }
                                        else
                                        {
                                            $finalArr[$key][$userArray[$k1]] = 0;
                                        }
                                    }
                                }
                            }
                            $finalArr[$value["SEARCH_ID"]]["SENT"]=savedSearchMailerEnums::$updateSent;
                            //if finalArr is an array, insert details into the table
                            if(is_array($finalArr))
                            {
                                $savedSearchObj->updateUserMailerData($finalArr);
                            }
                        }
                        catch(jsException $e)
                        {   
                            $updateArr[$value["SEARCH_ID"]]["SENT"] = "I";
                            $savedSearchObj->updateUserMailerData($updateArr);                    
                        } 
                    }
                    
                }
                else
                {
                    $flag=0;
                }
            }while($flag);


    }

}
