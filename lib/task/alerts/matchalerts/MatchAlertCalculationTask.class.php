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
                                                        $profiles = array();
							/*
							* Two mails willl be sent to user if has trends
							*/
                                                        $StrategyReceiversNT = new DppBasedMatchAlertsStrategy($loggedInProfileObj,$this->limitTRec,MailerConfigVariables::$strategyReceiversTVsNT,MailerConfigVariables::$DppLoggedinWithReverseDppSort);
							$totalResults = $StrategyReceiversNT->getMatches('',1);
                                                        
                                                        if($totalResults["LOGIN_SCORE"] > self::clusterRecordLimit){
                                                                $StrategyReceiversNT = new DppBasedMatchAlertsStrategy($loggedInProfileObj,$this->limitTRec,MailerConfigVariables::$strategyReceiversTVsNT,MailerConfigVariables::$DppLoggedinWithTrendsScoreSort);
                                                                $totalResults = $StrategyReceiversNT->getMatches('',0); 
                                                                if($totalResults["profiles"])
                                                                        $profiles = array_merge($profiles,$totalResults["profiles"]);
                                                        }
                                                        
                                                        if(count($profiles)<$this->limitTRec){
                                                                $this->limitTRecTemp = abs($this->limitTRec - count($profiles));
                                                                $StrategyReceiversT = new TrendsBasedMatchAlertsStrategy($loggedInProfileObj, $this->limitTRecTemp,MailerConfigVariables::$TrendsLoggedinWithTrendsScoreSort);   
                                                                $totalResults = $StrategyReceiversT->getMatches($profiles);
                                                                if($totalResults["profiles"])
                                                                        $profiles = array_merge($profiles,$totalResults["profiles"]);
                                                                
                                                                if(count($profiles)<$this->limitTRec){
                                                                        
                                                                        $this->limitTRecTemp = abs($this->limitTRec - count($profiles));
                                                                        $StrategyReceiversNT = new DppBasedMatchAlertsStrategy($loggedInProfileObj,$this->limitTRecTemp,MailerConfigVariables::$strategyReceiversTVsNT,MailerConfigVariables::$DppNotLoggedinWithLoginDateSort);                                                                                                        $totalResults = $StrategyReceiversNT->getMatches('',0,$profiles);
                                                                        if($totalResults["profiles"])
                                                                                $profiles = array_merge($profiles,$totalResults["profiles"]);
                                                                        
                                                                        if(count($profiles)<$this->limitTRec){
                                                                                $this->limitTRecTemp = abs($this->limitTRec - count($profiles));
                                                                                $StrategyReceiversT = new TrendsBasedMatchAlertsStrategy($loggedInProfileObj, $this->limitTRecTemp,MailerConfigVariables::$TrendsNotLoggedinWithLoginDateSort);
                                                                                $totalResults = $StrategyReceiversT->getMatches($profiles);
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
