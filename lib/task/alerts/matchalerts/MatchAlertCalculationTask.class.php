<?php
/**
* This taks will decide correct Strategy for the matchalerts users. 
*/
class MatchAlertCalculationTask extends sfBaseTask
{
	private $limit = 5000;
	private $limitNtRec = 16;
	private $limitTRec = 10;
	const clusterRecordLimit = 10;
        
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

		$flag=1;

		do{
			/**
			* Fetch Ids to be send.
			*/
                        
                        $memObject=JsMemcache::getInstance();
			$matchalerts_MATCHALERTS_TO_BE_SENT = new matchalerts_MATCHALERTS_TO_BE_SENT;
			$arr = $matchalerts_MATCHALERTS_TO_BE_SENT->fetch($totalScripts,$currentScript,$this->limit);
                        //$arr = array(7043932=>1);
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
                                                $returnTotalCountWithCluster = 0;
						if($trends)
						{
							/*
							* Two mails willl be sent to user if has trends
							*/
                                                        // DPP mailer for trends profile (loggedin in 15 days) sort by reverse dpp
                                                        $returnTotalCountWithCluster = 1;
                                                        $StrategyReceiversNT = new DppBasedMatchAlertsStrategy($loggedInProfileObj,$this->limitTRec,MailerConfigVariables::$strategyReceiversTVsNT,MailerConfigVariables::$DppLoggedinWithReverseDppSort);
							$totalResults = $StrategyReceiversNT->getMatches('',$returnTotalCountWithCluster);
                                                        if($totalResults["LOGIN_SCORE"] > self::clusterRecordLimit){
                                                                //if dpp count is greater than 20 send dpp again(loggedin in 15 days) sort by trends score
                                                                $StrategyReceiversNT = new DppBasedMatchAlertsStrategy($loggedInProfileObj,$this->limitTRec,MailerConfigVariables::$strategyReceiversTVsNT,MailerConfigVariables::$DppLoggedinWithTrendsScoreSort);
                                                                $totalResults = $StrategyReceiversNT->getMatches('',$returnTotalCountWithCluster); 
                                                        }else{
                                                                //if dpp count is 0 or less than 20 send match alerts with trends(loggedin in 15 days), sort by trends score
                                                                $StrategyReceiversT = new TrendsBasedMatchAlertsStrategy($loggedInProfileObj, $this->limitTRec,MailerConfigVariables::$TrendsLoggedinWithTrendsScoreSort);       
                                                                $totalResults = $StrategyReceiversT->getMatches();
                                                                if(!$totalResults){ 
                                                                        // send DPP profiles (not loggedin in 15 days) sort by login time
                                                                        $StrategyReceiversNT = new DppBasedMatchAlertsStrategy($loggedInProfileObj,$this->limitTRec,MailerConfigVariables::$strategyReceiversTVsNT,MailerConfigVariables::$DppNotLoggedinWithLoginDateSort);
                                                                        $totalResults = $StrategyReceiversNT->getMatches('',$returnTotalCountWithCluster);
                                                                        if(!$totalResults){
                                                                                // send Trends profiles (not loggedin in 15 days) sort by login time
                                                                                $StrategyReceiversT = new TrendsBasedMatchAlertsStrategy($loggedInProfileObj, $this->limitTRec,MailerConfigVariables::$TrendsNotLoggedinWithLoginDateSort);
                                                                                $totalResults = $StrategyReceiversT->getMatches();
                                                                        }
                                                                }
                                                        }
						}
						else
						{
							/**
							* Matches : Trends are not set, Only one mailer will be sent. 
							*/
							$StrategyReceiversNT = new DppBasedMatchAlertsStrategy($loggedInProfileObj,$this->limitNtRec,MailerConfigVariables::$strategyReceiversNT,MailerConfigVariables::$DppLoggedinWithReverseDppSort);
							$totalResults = $StrategyReceiversNT->getMatches('',$returnTotalCountWithCluster);
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
}
