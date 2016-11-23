<?php
/**
* This taks will decide correct Strategy for the matchalerts users. 
*/
class MatchAlertCalculationTask extends sfBaseTask
{
	private $limit = 5000;
	private $limitNtRec = 16;
	private $limitTRec = 10;
	private $limitTRecTemp = 10;
	private $LowDppCountCachetime = 25200; // 1 week
	private $LowDppLimit = 10;
        private $limitCommunityRec = 10;
	const clusterRecordLimit = 10;
        const _communityModelToggle=1;
        
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
	    $this->name             = 'MatchAlertCalculation';
	    $this->briefDescription = '';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony alert:MatchAlertCalculation] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
	{
		if(!sfContext::hasInstance())
			sfContext::createInstance($this->configuration);

                ini_set('memory_limit','512M');
                $totalScripts = $arguments["totalScripts"]; // total no of scripts
                $currentScript = $arguments["currentScript"]; // current script number
                
                $profilesWithLimitReached=array();
                $lowMatchesCheckObj = new LowDppMatchesCheck();
                $dateToCheck= date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) );
                $profilesWithLimitReached = $lowMatchesCheckObj->getProfilesWithInformLimitReached($dateToCheck,$totalScripts,$currentScript);
                

		$flag=1;

		do{
			/**
			* Fetch Ids to be send.
			*/
                        
                        $memObject=JsMemcache::getInstance();
			$matchalerts_MATCHALERTS_TO_BE_SENT = new matchalerts_MATCHALERTS_TO_BE_SENT;
			$arr = $matchalerts_MATCHALERTS_TO_BE_SENT->fetch($totalScripts,$currentScript,$this->limit);
                        //$arr = array(7043932=>array("HASTRENDS"=>0,"MATCH_LOGIC"=>'N','PERSONAL_MATCHES'=>'A'),144111=>array("HASTRENDS"=>0,"MATCH_LOGIC"=>'N','PERSONAL_MATCHES'=>'A'));
			if(is_array($arr))
			{
				foreach($arr as $profileid=>$v)
				{
                                  if($v["HASTRENDS"] != 1)
                                    $v["HASTRENDS"] = 0;
					/**
					* update flag.
					*/
					$matchalerts_MATCHALERTS_TO_BE_SENT->update($profileid);
					$loggedInProfileObj = LoggedInProfile::getInstance();
					$loggedInProfileObj->getDetail($profileid,"PROFILEID","*");

					$trends = $v["HASTRENDS"];
                                        $matchesSetting = $v["PERSONAL_MATCHES"];
                                        $matchLogic = $v["MATCH_LOGIC"];
					if($loggedInProfileObj->getPROFILEID())
					{
                                                $returnTotalCountWithCluster = 0;
						if($trends)
						{
                                                        $profiles = array();
							/*
							* Two mails willl be sent to user if has trends
							*/

                                                        $StrategyReceiversNT = new DppBasedMatchAlertsStrategy($loggedInProfileObj,$this->limitTRec,MailerConfigVariables::$strategyReceiversTVsNT,MailerConfigVariables::$DppLoggedinWithReverseDppSort);
							$totalResults = $StrategyReceiversNT->getMatches('',1,array(),$matchesSetting);
                                                        if($totalResults["CNT"] == 0 && !in_array($loggedInProfileObj->getPROFILEID(), $profilesWithLimitReached))
                                                                $lowMatchesCheckObj->insertForProfile($loggedInProfileObj->getPROFILEID());
                                                        
                                                        // Set Low Dpp flag
                                            //            $this->setLowDppFlag($memObject,$profileid,$totalResults["CNT"]);                                       
                                                        if($totalResults["LOGIN_SCORE"] > self::clusterRecordLimit){
                                                                $StrategyReceiversNT = new DppBasedMatchAlertsStrategy($loggedInProfileObj,$this->limitTRec,MailerConfigVariables::$strategyReceiversTVsNT,MailerConfigVariables::$DppLoggedinWithTrendsScoreSort);
                                                                $totalResults = $StrategyReceiversNT->getMatches('',0,array(),$matchesSetting); 
                                                                if($totalResults["profiles"])
                                                                        $profiles = array_merge($profiles,$totalResults["profiles"]);
                                                        }
                                                        
                                                        if(count($profiles)<$this->limitTRec){
                                                                $this->limitTRecTemp = abs($this->limitTRec - count($profiles));
                                                                $StrategyReceiversT = new TrendsBasedMatchAlertsStrategy($loggedInProfileObj, $this->limitTRecTemp,MailerConfigVariables::$TrendsLoggedinWithTrendsScoreSort);   
                                                                $totalResults = $StrategyReceiversT->getMatches($profiles,$matchesSetting);
                                                                if($totalResults["profiles"])
                                                                        $profiles = array_merge($profiles,$totalResults["profiles"]);
                                                                
                                                                if(count($profiles)<$this->limitTRec){
                                                                        
                                                                        $this->limitTRecTemp = abs($this->limitTRec - count($profiles));
                                                                        $StrategyReceiversNT = new DppBasedMatchAlertsStrategy($loggedInProfileObj,$this->limitTRecTemp,MailerConfigVariables::$strategyReceiversTVsNT,MailerConfigVariables::$DppNotLoggedinWithLoginDateSort);                                                                                                        $totalResults = $StrategyReceiversNT->getMatches('',0,$profiles,$matchesSetting);
                                                                        if($totalResults["profiles"])
                                                                                $profiles = array_merge($profiles,$totalResults["profiles"]);
                                                                        
                                                                        if(count($profiles)<$this->limitTRec){
                                                                                $this->limitTRecTemp = abs($this->limitTRec - count($profiles));
                                                                                $StrategyReceiversT = new TrendsBasedMatchAlertsStrategy($loggedInProfileObj, $this->limitTRecTemp,MailerConfigVariables::$TrendsNotLoggedinWithLoginDateSort);
                                                                                $totalResults = $StrategyReceiversT->getMatches($profiles,$matchesSetting);
                                                                        }
                                                                }
                                                        }
						}
						else
						{
							/**
							* Matches : Trends are not set, Only one mailer will be sent. 
							*/
                                                        $includeDppCnt = 1;
                                                        if($this->checkForCommunityModel($loggedInProfileObj->getPROFILEID(),$matchLogic))
                                                            $this->limitNtRec=10;
							$StrategyReceiversNT = new DppBasedMatchAlertsStrategy($loggedInProfileObj,$this->limitNtRec,MailerConfigVariables::$strategyReceiversNT,MailerConfigVariables::$DppLoggedinWithReverseDppSort);
							$totalResults = $StrategyReceiversNT->getMatches($includeDppCnt,$returnTotalCountWithCluster,array(),$matchesSetting);
                                                        if($totalResults["CNT"] == 0 && !in_array($loggedInProfileObj->getPROFILEID(), $profilesWithLimitReached))
                                                                $lowMatchesCheckObj->insertForProfile($loggedInProfileObj->getPROFILEID());
                                                        if($this->checkForCommunityModel($loggedInProfileObj->getPROFILEID(),$matchLogic)){
                                                            $communityModelNT = new CommunityModelMatchAlertsStrategy($loggedInProfileObj,$this->limitCommunityRec,MailerConfigVariables::$communityModelNT);
                                                            $communityModelNT->getMatches($matchesSetting);
                                                        }
                                                        // Set Low Dpp flag
                                                   //     $this->setLowDppFlag($memObject,$profileid,$totalResults["CNT"]);
						}
                                                $memObject->remove('SEARCH_JPARTNER_'.$profileid);
                                                $memObject->remove('SEARCH_MA_IGNOREPROFILE_'.$profileid);

					}
				}
			
			}
			else
				$flag=0;
		}while($flag);
	}
        /**
         * This function sets low dpp cache flag.
         * @param type $memObject Cahce Object
         * @param type $profileid // profile id
         */
        private function setLowDppFlag($memObject,$profileid,$dppCount){
                if($dppCount < $this->LowDppLimit){
                        $memObject->set('MA_LOWDPP_FLAG_'.$profileid,1,$this->LowDppCountCachetime);
                }else{
                        $memObject->remove('MA_LOWDPP_FLAG_'.$profileid);
                }       
        }
        
        /**
         * This function returns whether to use community model.
         */
        private function checkForCommunityModel($profileId,$oldNewLogic){
                if($profileId%11<1 && self::_communityModelToggle && $oldNewLogic=='N'){
                    return true;
                }
                else
                    return false;
        }
}
