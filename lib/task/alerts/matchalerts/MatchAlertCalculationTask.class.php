<?php
/**
* This taks will decide correct Strategy for the matchalerts users. 
*/
class MatchAlertCalculationTask extends sfBaseTask
{
	private $limit = 5000;
	private $limitNtRec = 16;
	private $limitTRec = 10;
        private $minDppIntersectionCnt = 50;
        private $idealTrendsResultsNo=10;
        private $idealNonTrendsResultsNo=16;
        
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
			$matchalerts_MATCHALERTS_TO_BE_SENT = new matchalerts_MATCHALERTS_TO_BE_SENT;
			$arr = $matchalerts_MATCHALERTS_TO_BE_SENT->fetch($totalScripts,$currentScript,$this->limit);
                        //$arr = array(7043932=>1,144111=>1);
			if(is_array($arr))
			{
				foreach($arr as $profileid=>$v)
				{
                                  if($v != 1)
                                    $v = 0;
					/**
					* update flag.
					*/
					$matchalerts_MATCHALERTS_TO_BE_SENT->update($profileid);

					$loggedInProfileObj = LoggedInProfile::getInstance();
					$loggedInProfileObj->getDetail($profileid,"PROFILEID","*");

					$trends = $v;
					if($loggedInProfileObj->getPROFILEID())
					{
						if($trends)
						{
                                                        $includeDppCnt = 1;
                                                        $sendIntersectionMatches=1;
                                                        $toSendFromIntersection = 1;
							/*
							* Two mails willl be snet to user if has trends
							*/
                                                        $StrategyReceiversNT = new DppBasedMatchAlertsStrategy($loggedInProfileObj,$this->limitTRec,MailerConfigVariables::$strategyReceiversTVsNT);
							$totalResults = $StrategyReceiversNT->getMatches($includeDppCnt);
                                                        if($totalResults == 0 && !in_array($loggedInProfileObj->getPROFILEID(), $profilesWithLimitReached))
                                                               $lowMatchesCheckObj->insertForProfile($loggedInProfileObj->getPROFILEID());
                                                        $profileId = $loggedInProfileObj->getPROFILEID();
                                                        $isProfileEligible = logAndFetchProfilesForZeroMatches::checkIfProfileIsEligible($profileId);
                                                        if($totalResults >= $this->minDppIntersectionCnt && $isProfileEligible)
                                                         {
                                                            $StrategyReceiversT = new TrendsBasedMatchAlertsStrategy($loggedInProfileObj, $this->limitTRec);   
                                                            $toSendFromIntersection = $StrategyReceiversT->getMatches($sendIntersectionMatches);
                                                            if(!$toSendFromIntersection){
                                                                $StrategyReceiversT->getMatches();
                                                                logAndFetchProfilesForZeroMatches::insertEntry($profileId);
                                                            }
                                                        }
                                                        else{
                                                            $StrategyReceiversT = new TrendsBasedMatchAlertsStrategy($loggedInProfileObj, $this->limitTRec);   
                                                            $StrategyReceiversT->getMatches();
                                                        }
                                                            
						}
						else
						{
							/**
							* Matches : Trends are not set, Only one mailer will be sent. 
							*/
                                                        $includeDppCnt = 1;
							$StrategyReceiversNT = new DppBasedMatchAlertsStrategy($loggedInProfileObj,$this->limitNtRec,MailerConfigVariables::$strategyReceiversNT);
							$totalResults = $StrategyReceiversNT->getMatches($includeDppCnt);
                                                        if($totalResults == 0 && !in_array($loggedInProfileObj->getPROFILEID(), $profilesWithLimitReached))
                                                                $lowMatchesCheckObj->insertForProfile($loggedInProfileObj->getPROFILEID());
						}	
					}
				}
			
			}
			else
				$flag=0;
		}while($flag);
	}
}
