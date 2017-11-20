<?php
/**
* This class handles library related to Just Joined Matches
* @author : Lavesh Rawat
* @package Search
* @subpackage SearchTypes
* @copyright 2014 Lavesh Rawat
* @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
* @since 2014-08-01
* @author Lavesh Rawat
*/
class JustJoinedMatches extends PartnerProfile
{
        /**
        * @const SAFE_TIME this time is to make sure photos are also got screened
        */
	const SAFE_TIME = 6; 

        /**
        * @const DAY_GAP [No. of days in which we consider for justjoined matches]
        */
	const DAY_GAP = 10; // this has to be changed to 10 days 


	/**
	* @const SHOW_FILTERED_PROFILES we dont show filtered profiles at all.
	*
	*/
	const SHOW_FILTERED_PROFILES='N';

        /**
        * @access private
        * @var String $m_sz_callType type of call like countOnly.
        */
	private $m_sz_callType;
	
	private $PARTNER_MTONGUE;
        private $PARTNER_CASTE;
        private $PARTNER_RELIGION;
        private $PARTNER_COUNTRYRES;
        private $PARTNER_BTYPE;
        private $PARTNER_COMP;
        private $PARTNER_ELEVEL_NEW;
        private $PARTNER_INCOME;
        private $PARTNER_OCC;
        private $LPARTNER_LAGE;
        private $HPARTNER_LAGE;
        private $LPARTNER_HAGE;
        private $HPARTNER_HAGE;
        private $LPARTNER_LHEIGHT;
        private $HPARTNER_LHEIGHT;
        private $LPARTNER_HHEIGHT;
        private $HPARTNER_HHEIGHT;
        private $PARTNER_MSTATUS;
        private $PARTNER_CITYRES;
        private $PARTNER_DRINK;
        private $PARTNER_SMOKE;
        private $PARTNER_DIET;
        private $PARTNER_HANDICAPPED;
        private $PARTNER_MANGLIK;

        /**
        * Constructor function.
        * @constructor
        * @access public        
        * @param LoggedInProfile $loggedInProfileObj logged in profile object
        */
        public function __construct($loggedInProfileObj)
        {
		$this->loggedInProfileObj = $loggedInProfileObj;
		$this->SORT_LOGIC = SearchSortTypesEnums::justJoinedSortFlag;
		$this->showFilteredProfiles = self::SHOW_FILTERED_PROFILES;
		parent::__construct($loggedInProfileObj);
        }

        /**
        * @access public        
        * Sets JustJoinedMatches(PartnerProfileObject(SearchParamtersObj)) 
	* @param sz_callType CountOnly indicates actual search is not getting performed which means we dont need to log this time (last search performed time which is used for logic.)
        */
	public function getSearchCriteria($sz_callType='')
	{
		$this->getDppCriteria();
		$this->m_sz_callType = $sz_callType;
		if($this->m_sz_callType!='CountOnly')
			if($this->m_sz_callType!='countAll')
				$this->addJustJoinedDate();
		$this->addSpecificCondition();
		
		//This is AB for  Reverse DPP Match condition for Males onle with profile is mod 101 <=50
		if($this->loggedInProfileObj->getGENDER() == 'M' && $this->loggedInProfileObj->getPROFILEID()%101 <=50){
			$reverseCriteria = PredefinedSearchFactory::getSetterBy('MembersLookingForMe',$this->loggedInProfileObj);
			$reverseCriteria->getSearchCriteria();
			if($this->loggedInProfileObj->getGENDER() == 'F')
	                $reverseParams = SearchConfig::$reverseParamsFemaleLoggedIn;
	            else
	                $reverseParams = SearchConfig::$reverseParamsMaleLoggedIn;
	            
			foreach($reverseParams as $k=>$v)	
			{
				eval('$tempVal = $reverseCriteria->get'.$v.'();');
				if($tempVal)
					eval('$this->set'.$v.'("'.$tempVal.'");');
			}
			$this->setWhereParams(SearchConfig::$searchWhereParameters.",".SearchConfig::$membersLookingForMeWhereParameters);
			$this->setRangeParams(SearchConfig::$searchRangeParameters.",".SearchConfig::$membersLookingForMeRangeParameters);
		}
		
		return $forwardCriteria; //no use now
	}


	/**
        * @access public        
	* Add criteria specific to two way match.
	* This private function will set date related condition(range for just joined) and search type(used for contact behaviour)
	*/
	private function addSpecificCondition()
	{
		$this->justJoinedDateRange();
                $channel =  SearchChannelFactory::getChannel();
		$this->stype =  $channel::getSearchTypeJJMatches();
		
                $this->setSEARCH_TYPE($this->stype);
	}


	/**
        * @access private       
	* This private function will log date, indicates just joined search is being used.
	*/
	private function addJustJoinedDate()
	{
		$this->newTagJustJoinDate = $this->lastUsedJustJoinedSearch();
                if(!$this->newTagJustJoinDate)
                        $this->newTagJustJoinDate="0000:00:00";
                
		$lastSeen = date("Y-m-d h:i:s");
                $producerObj = new Producer();
                if($producerObj->getRabbitMQServerConnected())
                {
                        $updateSeenProfileData = array("process"=>"JUSTJOINED_LAST_SEEN",'data'=>array('body'=>array('seen_date'=>$lastSeen,'profileid'=>$this->loggedInProfileObj->getPROFILEID())));
                        $producerObj->sendMessage($updateSeenProfileData);
                }else{
                        $search_JUST_JOINED_LAST_USED = new search_JUST_JOINED_LAST_USED(SearchConfig::getSearchDb());
                        $dt = $search_JUST_JOINED_LAST_USED->ins($this->loggedInProfileObj->getPROFILEID());
                }
                $dt = $lastSeen;
                JsMemcache::getInstance()->set($this->loggedInProfileObj->getPROFILEID()."_JUSTJOINED_LAST_VISITED",$lastSeen);
                JsMemcache::getInstance()->incrCount("INSERT_COUNTER_JUSTJOINED_LAST_VISITED");
	}


	/**
        * @access public        
	* Sets the date condition for Just Joined Matches.
	* Just joined is dpp + date range (date range is set here)
	*/
	public function justJoinedDateRange()
	{
		/** 
		* stareDate will be calcualted as "profiles which have Registered(VERIFY_ACTIVATED_DT) from the midnight of the day 6 days before current day to current time minus 6(SAFE_TIME) hours. For instance, if now is 2nd July 2014 11:34:59, all "Just Joined Matches" would be profiles which have registered from 26th June 2014 00:00:00 to 2nd July 2014 5:34:59. All timestamps should be considered in IST. 
		*/
		$endDate = date("Y-m-d H:i:s", strtotime("now")); //Safe time was removed as per JIRA (JSM-3062)
		$startDate = date("Y-m-d 00:00:00", strtotime($endDate) - self::DAY_GAP*24*3600); 

		if($this->m_sz_callType=='CountOnly')
		{
			$dt = $this->lastUsedJustJoinedSearch();
			$dt = date("Y-m-d H:i:s", strtotime($dt));  // - self::SAFE_TIME * 3600);
			$startDate = (CommonUtility::dateDiff($startDate,$dt)>1)?$dt:$startDate;
		}
		$this->setLVERIFY_ACTIVATED_DT($startDate);
		$this->setHVERIFY_ACTIVATED_DT($endDate);
	}

	/**
        * @access public        
	* Fetch date when last time just joined search is used.
	*/
	public function lastUsedJustJoinedSearch()
	{
                $lastVisited = JsMemcache::getInstance()->get($this->loggedInProfileObj->getPROFILEID()."_JUSTJOINED_LAST_VISITED");
                if(!$lastVisited){
                    JsMemcache::getInstance()->incrCount("SELECT_COUNTER_JUSTJOINED_LAST_VISITED_BYDB");
                    $search_JUST_JOINED_LAST_USED = new search_JUST_JOINED_LAST_USED(SearchConfig::getSearchDb());
                    $dt = $search_JUST_JOINED_LAST_USED->getDt($this->loggedInProfileObj->getPROFILEID());
                }else{
                    JsMemcache::getInstance()->incrCount("SELECT_COUNTER_JUSTJOINED_LAST_VISITED_BYCACHE");
                    $dt = $lastVisited;
                }
		return $dt;
	}
	
	public function getPARTNER_MTONGUE() { return $this->PARTNER_MTONGUE; }
        public function setPARTNER_MTONGUE($x) { $this->PARTNER_MTONGUE = $x; }
        public function getPARTNER_CASTE() { return $this->PARTNER_CASTE; }
        public function setPARTNER_CASTE($x) { $this->PARTNER_CASTE = $x; }
        public function getPARTNER_RELIGION() { return $this->PARTNER_RELIGION; }
        public function setPARTNER_RELIGION($x) { $this->PARTNER_RELIGION = $x; }
        public function getPARTNER_COUNTRYRES() { return $this->PARTNER_COUNTRYRES; }
        public function setPARTNER_COUNTRYRES($x) { $this->PARTNER_COUNTRYRES = $x; }
        public function getPARTNER_BTYPE() { return $this->PARTNER_BTYPE; }
        public function setPARTNER_BTYPE($x) { $this->PARTNER_BTYPE = $x; }
        public function getPARTNER_COMP() { return $this->PARTNER_COMP; }
        public function setPARTNER_COMP($x) { $this->PARTNER_COMP = $x; }
        public function getPARTNER_ELEVEL_NEW() { return $this->PARTNER_ELEVEL_NEW; }
        public function setPARTNER_ELEVEL_NEW($x) { $this->PARTNER_ELEVEL_NEW = $x; }
        public function getPARTNER_INCOME() { return $this->PARTNER_INCOME; }
        public function setPARTNER_INCOME($x) { $this->PARTNER_INCOME = $x; }
        public function getPARTNER_OCC() { return $this->PARTNER_OCC; }
        public function setPARTNER_OCC($x) { $this->PARTNER_OCC = $x; }
        public function getLPARTNER_LAGE() { return $this->LPARTNER_LAGE; }
        public function setLPARTNER_LAGE($x) { $this->LPARTNER_LAGE = $x; }
        public function getHPARTNER_LAGE() { return $this->HPARTNER_LAGE; }
        public function setHPARTNER_LAGE($x) { $this->HPARTNER_LAGE = $x; }
        public function getLPARTNER_HAGE() { return $this->LPARTNER_HAGE; }
        public function setLPARTNER_HAGE($x) { $this->LPARTNER_HAGE = $x; }
        public function getHPARTNER_HAGE() { return $this->HPARTNER_HAGE; }
        public function setHPARTNER_HAGE($x) { $this->HPARTNER_HAGE = $x; }
	public function getLPARTNER_LHEIGHT() { return $this->LPARTNER_LHEIGHT; }
        public function setLPARTNER_LHEIGHT($x) { $this->LPARTNER_LHEIGHT = $x; }
        public function getHPARTNER_LHEIGHT() { return $this->HPARTNER_LHEIGHT; }
        public function setHPARTNER_LHEIGHT($x) { $this->HPARTNER_LHEIGHT = $x; }
        public function getLPARTNER_HHEIGHT() { return $this->LPARTNER_HHEIGHT; }
        public function setLPARTNER_HHEIGHT($x) { $this->LPARTNER_HHEIGHT = $x; }
        public function getHPARTNER_HHEIGHT() { return $this->HPARTNER_HHEIGHT; }
        public function setHPARTNER_HHEIGHT($x) { $this->HPARTNER_HHEIGHT = $x; }
        public function getPARTNER_MSTATUS() { return $this->PARTNER_MSTATUS; }
        public function setPARTNER_MSTATUS($x) { $this->PARTNER_MSTATUS = $x; }
        public function getPARTNER_CITYRES() { return $this->PARTNER_CITYRES; }
        public function setPARTNER_CITYRES($x) { $this->PARTNER_CITYRES = $x; }
        public function getPARTNER_DRINK() { return $this->PARTNER_DRINK; }
        public function setPARTNER_DRINK($x) { $this->PARTNER_DRINK = $x; }
        public function getPARTNER_SMOKE() { return $this->PARTNER_SMOKE; }
        public function setPARTNER_SMOKE($x) { $this->PARTNER_SMOKE = $x; }
        public function getPARTNER_DIET() { return $this->PARTNER_DIET; }
        public function setPARTNER_DIET($x) { $this->PARTNER_DIET = $x; }
        public function getPARTNER_HANDICAPPED() { return $this->PARTNER_HANDICAPPED; }
        public function setPARTNER_HANDICAPPED($x) { $this->PARTNER_HANDICAPPED = $x; }
        public function getPARTNER_MANGLIK() { return $this->PARTNER_MANGLIK; }
        public function setPARTNER_MANGLIK($x) { $this->PARTNER_MANGLIK = $x; }
}
?>
