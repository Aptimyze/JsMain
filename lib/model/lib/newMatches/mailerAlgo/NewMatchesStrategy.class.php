<?php
/**
* New Match Alerts Base class strategy.
*/
abstract class NewMatchesStrategy
{
	protected $removeMatchAlerts = 1;
        protected $relaxCriteria = '';
        protected $canUseRelaxation = '1';
        protected $sameGenderError;
        protected $isPartnerProfileExist;
	protected $profileSet=array();
        private $counter = 1;
	private $logicLevel;
        private $ageHeightCasteRelaxation = "A,H,C";
	private $educationOccupationCityRelaxation = "E,O,R";
        
	abstract function getMatches();

	public function getAgeHeightCasteRelaxation(){return $this->ageHeightCasteRelaxation;}
	public function getEducationOccupationCityRelaxation(){return $this->educationOccupationCityRelaxation;}
        
        public function logRecords($receiverId,$profileIds,$logicLevel,$limit, $totalCount,$dailyCron=0){
                $profileIds = array_slice($profileIds,0,$limit);

                if($totalCount>$limit)
                        $is_more_link_required = "Y";
                else
                        $is_more_link_required = "N";
                
                $newMatchesMailerObj = new new_matches_emails_MAILER();
                $newMatchesMailerObj->insertLogRecords($receiverId, $profileIds, $logicLevel,$is_more_link_required,$this->relaxCriteria,$dailyCron);
                unset($newMatchesMailerObj);

                $matchalertLogObj = new new_matches_emails_LOG_TEMP();
                $matchalertLogObj->insertLogRecords($receiverId, $profileIds, $this->logicLevel,$dailyCron);
                unset($matchalertLogObj);
        }
        public function canPerformRelaxation($dppSwitch){
                if($dppSwitch == 1)
                        $this->canUseRelaxation = 0;
        }
        
        /**
         * 
         * @param type $SearchServiceObj Object of search service class
         * @param type $SearchUtilityObj object of Search Utility Class
         * @return type array, array of user profile Ids 
         */
        protected function getSearchResult($SearchServiceObj, $SearchUtilityObj,$searchObject, $returnTotalCount = '',$ignoredProfile = 0) {
                if($ignoredProfile == 1){
                        if($this->profileSet)
                        {
                                if($searchObject->getIgnoreProfiles()){
                                        $searchObject->setIgnoreProfiles($searchObject->getIgnoreProfiles()." ".implode(" ",$this->profileSet));
                                }else{
                                        $searchObject->setIgnoreProfiles(implode(" ",$this->profileSet));
                                }
                        }
                        $resultOnly = "onlyResults";
                }else{
                        
                        $SearchServiceObj->setSearchSortLogic($searchObject, $this->loggedInProfileObj, "", "");
                        $SearchUtilityObj->removeProfileFromSearch($searchObject, 'spaceSeperator', $this->loggedInProfileObj, '', "", $this->removeMatchAlerts);
                        $resultOnly = "";
                }
                $responseObj = $SearchServiceObj->performSearch($searchObject, $resultOnly, "", "", '', $this->loggedInProfileObj);
                $PIDS = $responseObj->getsearchResultsPidArr();
                if ($returnTotalCount) {
                        $PidsAndCount['PIDS'] = $PIDS;
                        $PidsAndCount['CNT'] = $responseObj->getTotalResults();
                        return $PidsAndCount;
                }
                return $PIDS;
        }
        public function setLogicLevelArray($profileIds){
                if($profileIds['PIDS'])
		{	
			$this->profileSet=array_merge($this->profileSet,$profileIds['PIDS']);
			$levelCount=count($profileIds['PIDS']);
			if($levelCount>0)
                        {
                              	$this->logicLevel = $this->logicUsed.$this->counter;
                                $levelCount--;
                        }
		}
                $this->counter++;
        }
        public function educationRelaxation($searchObject){
                if(count($this->profileSet)<$this->limit && $this->canUseRelaxation){
                        $this->relaxCriteria = $this->getEducationOccupationCityRelaxation();
                        $queryLimit = $this->limit-count($this->profileSet);
                        $searchObject->setNoOfResults($queryLimit);
                        $searchObject->performRelaxation($this->getEducationOccupationCityRelaxation());
                }
        }
        public function ageHeightCasteRelaxation($searchObject){
                if(count($this->profileSet)<$this->limit && $this->canUseRelaxation){
                        $this->relaxCriteria .= ",".$this->getAgeHeightCasteRelaxation();
			$queryLimit = $this->limit-count($this->profileSet);
			$searchObject->setNoOfResults($queryLimit);
			$searchObject->performRelaxation($this->getAgeHeightCasteRelaxation());
                }
        }
        public function insertZeroRecordEntries($profileId){
                $mysqlObj = new Mysql;
                $db=$mysqlObj->connect("alerts");
                $gap=MailerConfigVariables::getNoOfDays();
                if($this->logicUsed == MailerConfigVariables::$strategyTvsNewLogic)
                        $sql_y="INSERT INTO new_matches_emails.ZEROTvNEW(PROFILEID,DATE) VALUES($profileId,$gap)";
                else
                        $sql_y="INSERT INTO new_matches_emails.ZERONTvNEW(PROFILEID,DATE) VALUES($profileId,$gap)";
                
                mysql_query($sql_y,$db) or logerror1("In matchalert_mailer.php",$sql_y);   
        }
        protected function setSameGenderAndDppExistsError($hasTrends,$loggedInProfileObj){
                if($hasTrends == 1)
		{
			$jpartnerObj = new TrendsPartnerProfile();
			$jpartnerObj->setPartnerDetails($loggedInProfileObj->getPROFILEID());
			if($jpartnerObj && $jpartnerObj->getPROFILEID())
				$this->isPartnerProfileExist="Y";
			else
				$this->isPartnerProfileExist="N";
		}else{
			$jpartnerObj = new PartnerProfile($loggedInProfileObj);
			$jpartnerObj->getDppCriteria("","MAILER");
                        if($jpartnerObj && $jpartnerObj->getIsDppExist())
				 $this->isPartnerProfileExist="Y";
                	else
                        	$this->isPartnerProfileExist="N";
		}
		if($loggedInProfileObj->getGENDER()==$jpartnerObj->getGENDER())
                	$this->sameGenderError='Y';
		else
                	$this->sameGenderError='N';
        }
}
?>
