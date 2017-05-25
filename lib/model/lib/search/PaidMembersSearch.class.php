<?php
/**
* This file will be handle members get pain in last week list.
* @author Bhavana Kadwal
* @package Search
* @subpackage SearchTypes
* @copyright 2017 Bhavana Kadwal
* @since 2017-04-19
*/
class PaidMembersSearch extends PartnerProfile
{
        private $PAID_TIME_RANGE = 7;
        private $stype = SearchTypesEnums::PAID_MEMBERS;
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
	public function getSearchCriteria($limit)
	{
                parent::getDppCriteria();
                
                $this->setNoOfResults($limit);
                
		$this->rangeParams .= ",PAID_ON";
		$this->setRangeParams($this->rangeParams);
                
		$endDate = date("Y-m-d H:i:s", strtotime("now"));
		$startDate = date("Y-m-d 00:00:00", strtotime($endDate) - $this->PAID_TIME_RANGE*24*3600);
		$this->setLPAID_ON($startDate);
		$this->setHPAID_ON($endDate);
                $this->setShowFilteredProfiles('N');
		$this->setSEARCH_TYPE($this->stype);
	}
}
?>
