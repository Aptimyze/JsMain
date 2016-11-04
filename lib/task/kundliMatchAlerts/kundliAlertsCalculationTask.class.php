<?php

class kundliAlertsCalculationTask extends sfBaseTask
{
    private $limit = 1000;
    protected function configure()
    {
        $this->addArguments(array(
                        new sfCommandArgument('totalScripts', sfCommandArgument::REQUIRED, 'My argument'),
                        new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
            ));

        $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
         ));
         
        $this->namespace = 'kundliMatchAlerts';
        $this->name = 'kundliAlertsCalculation';
        $this->briefDescription='';
        $this->detailedDescription = <<<EOF
          Call it with:
          [php symfony kundliMatchAlerts:kundliAlertsCalculation totalScripts currentScript]
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
        $dateInLog = $this->getNoOfDays();        
        $requiredDate = $dateInLog - kundliMatchAlertMailerEnums::$dateValue;
        $this->userArray = kundliMatchAlertMailerEnums::$userArray;
        $this->gunaUserArray = kundliMatchAlertMailerEnums::$gunaUserArray;
        $kundliMailerObj = new KUNDLI_ALERT_KUNDLI_MATCHES_MAILER();
        $kundliLogObj = new kundli_alert_LOG(); //matchalerts_slave
        do
        {
            $dataArr = $kundliMailerObj->fetchReceiverDetails($totalScripts,$currentScript,$this->limit);
            if(is_array($dataArr))
            {
                foreach($dataArr as $key => $value)
                {
                    try
                    {
                       $profileId = $value["RECEIVER"];

                       //This call fetches the data that is stored in the log table so that they can be put in the NOT IN array in solr search
                       $deDupingArray = $kundliLogObj->getDeDupedProfiles($profileId,$requiredDate);
                       $spaceSeperatedDeDupingString = "";
                       //array is imploded since space seperated string is to be sent in NOT IN array
                       if(is_array($deDupingArray))
                       {
                            $spaceSeperatedDeDupingString = implode(" ", $deDupingArray);
                       }
                       
                       
                       $loggedInProfileObj = LoggedInProfile::getInstance();
                       $loggedInProfileObj->getDetail($profileId,"PROFILEID","*");
                       $SearchParamtersObj = PredefinedSearchFactory::getSetterBy('KundliAlertsSearch',$loggedInProfileObj);

                       //This allows guna score to be shown
                       $SearchParamtersObj->setSHOW_RESULT_FOR_SELF('ISKUNDLIMATCHES');

                        $SearchParamtersObj->setNoOfResults(75);
                        unset($arr);
                        unset($finalArr);
                        
                         //This search criteria has been added after removing the parameter as per kundliAlertsSearch
                        $SearchParamtersObj->getSearchCriteria();                

                        //searchEngine = solr
                        //outputFormat = array
                        $SearchServiceObj = new SearchService(kundliMatchAlertMailerEnums::$searchEngine,kundliMatchAlertMailerEnums::$outputFormat);
                        $SearchServiceObj->setSearchSortLogic($SearchParamtersObj,$loggedInProfileObj,"",SearchSortTypesEnums::kundliAlertFlag); //since kundli alert is to be calculated

                        $SearchUtilityObj =  new SearchUtility;
                        $SearchUtilityObj->removeProfileFromSearch($SearchParamtersObj,'spaceSeperator',$loggedInProfileObj,'',kundliMatchAlertMailerEnums::$noAwaitingContacts,"",$spaceSeperatedDeDupingString);
                        $responseObj = $SearchServiceObj->performSearch($SearchParamtersObj,$results_orAnd_cluster,$clustersToShow,$currentPage,'',$loggedInProfileObj);
                        $arr[$value["RECEIVER"]] = $responseObj->getsearchResultsPidArr();
                        $resultArr = $responseObj->getresultsArr();
                        if(is_array($resultArr))
                        {
                            foreach($resultArr as $key=>$value)
                            {
                                if(is_array($value))
                                {
                                    foreach($value as $key1=>$v1)
                                    {
                                        if($key1 == "GUNASCORE")
                                        {
                                            $arr["GUNASCORE"][] = $v1;
                                        }
                                    }
                                }                                
                            }
                        }
                        
                        //Calls function to get array in desired format
                        $finalArr = $this->getFinalArr($arr);            
                        $finalArr[$profileId]["SENT"]=kundliMatchAlertMailerEnums::$updateSent;
                            //if finalArr is an array, insert details into the table
                        if(is_array($finalArr))
                        {
                            $kundliMailerObj->updateUserMailerData($finalArr);
                            $kundliLogObj->insertDataInLogs($finalArr[$profileId],$dateInLog,$profileId); 
                        }
                    }
                    catch(jsException $e)
                    {    
                        $updateArr[$profileId]["SENT"] = "I";
                        $kundliMailerObj->updateUserMailerData($updateArr);
                        SendMail::send_email("sanyam1204@gmail.com,reshu.rajput@jeevansathi.com","error in kundliAlertsCalculation for profileId".$profileId,"kundliMatchAlerts:kundliAlertsCalculation");   
                    } 
                }
                    
            }
            else
            {
                $flag=0;
            }
        }while($flag);
        
    }

    public function getNoOfDays()
    {
            $today=mktime(0,0,0,date("m"),date("d"),date("Y"));
            $zero=mktime(0,0,0,01,01,2005);
            $gap=($today-$zero)/(24*60*60);
            return $gap;
    }

    //Array received in $arr is altered in the required format
    public function getFinalArr($arr)
    {
        if(is_array($arr))
        {
            foreach($arr as $key=>$val1)
            {
                if($key != "GUNASCORE")
                {
                    if(count($val1)>0)
                    {
                        foreach($this->userArray as $k1=>$v1)
                        {
                            if(array_key_exists($k1, $val1))
                            {
                                $finalArr[$key][$this->userArray[$k1]] = $val1[$k1];
                            }
                            else
                            {
                                $finalArr[$key][$this->userArray[$k1]] = 0;
                            }
                        }
                    }
                }
                else
                {
                    if(count($val1)>0)
                    {
                        foreach($this->gunaUserArray as $k1=>$v1)
                        {
                            if(array_key_exists($k1, $val1))
                            {
                                $finalArr[$key][$this->gunaUserArray[$k1]] = $val1[$k1];
                            }
                            else
                            {
                                $finalArr[$key][$this->gunaUserArray[$k1]] = 0;
                            }
                        }
                    }
                }
            }
        }
        
        return $finalArr;
    }

}