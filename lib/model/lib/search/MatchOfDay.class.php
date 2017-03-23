<?php
/**
* This file will be handle two way match requirements.
* Two way match is combination of forward(limited params) + reverse (limited params)
* @author Esha Jain
* @package Search
* @subpackage SearchTypes
* @copyright 2016 Esha Jain
* @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
* @since 2016-10-18
*/
class MatchOfDay extends TwoWayMatch
{
	/**
	* Constructor.
	*/
        public function __construct($loggedInProfileObj)
        {
		parent::__construct($loggedInProfileObj);
        }

	/*
	* This function will set the criteria for forward and reverse search.
	*/
	public function getSearchCriteria($searchId='')
	{
		$this->LAST_LOGGEDIN=7;
                parent::getSearchCriteria();
                $subscription = '';
                foreach(SearchConfig::$jsBoostSubscription as $modSubs){
                        $subscription .= '"'.$modSubs.'" ';
                }
                $this->setSUBSCRIPTION($subscription);
                $this->setPHOTO_VISIBILITY_LOGGEDIN(2);
                $this->setHAVEPHOTO('Y');
                $this->setPRIVACY('"A"');
		$this->rangeParams .= ",LAST_LOGIN_DT";
		$this->setRangeParams($this->rangeParams);
		$endDate = date("Y-m-d H:i:s", strtotime("now"));
		$startDate = date("Y-m-d 00:00:00", strtotime($endDate) - $this->LAST_LOGGEDIN*24*3600);
		$this->setLLAST_LOGIN_DT($startDate);
		$this->setHLAST_LOGIN_DT($endDate);
                $channel =  SearchChannelFactory::getChannel();
                $this->stype =  $channel::getSearchTypeMatchOfDay();
		$this->setSEARCH_TYPE($this->stype);
	}
}
?>
