<?php

/**
 * This taks will decide correct Strategy for the community matchalerts users. 
 */
class MatchAlertCommunityTask extends sfBaseTask {

        private $limit = 5000;
        private $limitCommunityRec = 16;

        protected function configure() {
                $this->addArguments(array(
                    new sfCommandArgument('totalScripts', sfCommandArgument::REQUIRED, 'My argument'),
                    new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
                    new sfCommandArgument('fromReg', sfCommandArgument::OPTIONAL, 'My argument', 0),
                ));

                $this->addOptions(array(
                    new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
                ));

                $this->namespace = 'alert';
                $this->name = 'MatchAlertCommunity';
                $this->briefDescription = '';
                $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony alert:MatchAlertCommunity] 
EOF;
        }

        protected function execute($arguments = array(), $options = array()) {
                if (!sfContext::hasInstance())
                        sfContext::createInstance($this->configuration);

                ini_set('memory_limit', '512M');
                $totalScripts = $arguments["totalScripts"]; // total no of scripts
                $currentScript = $arguments["currentScript"]; // current script number
                $fromReg = $arguments["fromReg"]; // registered data flag
                $todayDate = date("Y-m-d H:i:s");
                $flag = 1;
                $emptyFlag = 0;
                $lowTrendsObj = new matchalerts_LowTrendsMatchalertsCheck();

                do {
                        /**
                         * Fetch Ids to be send.
                         */
                        $memObject = JsMemcache::getInstance();
                        $matchalerts_MATCHALERTS_TO_BE_SENT = new matchalerts_MATCHALERTS_TO_BE_SENT;
                        $arr = $matchalerts_MATCHALERTS_TO_BE_SENT->fetchCommunityProfiles($totalScripts, $currentScript, $this->limit);
                        if (is_array($arr)) {
                                $emptyFlag = 0;
                                foreach ($arr as $profileid => $v) {
                                        $matchalerts_MATCHALERTS_TO_BE_SENT->updateCommunity($profileid, "Y");
                                        $loggedInProfileObj = LoggedInProfile::getInstance();
                                        $loggedInProfileObj->getDetail($profileid, "PROFILEID", "*");
                                        if ($loggedInProfileObj->getPROFILEID()) {
                                                $communityModelNT = new CommunityModelMatchAlertsStrategy($loggedInProfileObj, $this->limitCommunityRec, MailerConfigVariables::$communityModelNT);
                                                $totalResults = $communityModelNT->getMatches();
                                                if ($totalResults["CNT"] == 0) {
                                                        $lowTrendsObj->insertForProfile($profileid, $todayDate, MailerConfigVariables::$communityModelNT);
                                                }
                                        }
                                }
                        } else {
                                $emptyFlag++;
                                if ($emptyFlag >= 5) {
                                        $flag = 0;
                                } else {
                                        sleep(600);
                                }
                        }
                } while ($flag);
        }

}
