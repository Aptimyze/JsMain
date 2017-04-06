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
		$search_JUST_JOINED_LAST_USED = new search_JUST_JOINED_LAST_USED(SearchConfig::getSearchDb());
		$dt = $search_JUST_JOINED_LAST_USED->ins($this->loggedInProfileObj->getPROFILEID());
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
		$search_JUST_JOINED_LAST_USED = new search_JUST_JOINED_LAST_USED(SearchConfig::getSearchDb());
		$dt = $search_JUST_JOINED_LAST_USED->getDt($this->loggedInProfileObj->getPROFILEID());
		return $dt;
	}
}
?>
