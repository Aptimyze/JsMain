<?php
/**
* This file will be handle two way match requirements.
* Two way match is combination of forward(limited params) + reverse (limited params)
* @author Esha Jain
* @package Search
* @subpackage SearchTypes
* @copyright 2016 Esha Jain
* @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
* @since 2017-01-05
*/
class MatchOfDayList extends MatchOfDay
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
//include list to be provided by crm team
		$arr = array("144111","1","3777","28091","8768598","3177497","7630739","7708686","9296553");
                if(is_array($arr))
                        $str = implode(" ",$arr);
		if(!$str)
			$str = '';
                $this->setProfilesToShow($str);
                parent::getSearchCriteria();
	}
}
