<?php
/**
* This class perform Sort for NewMatchesMailer
* @author : Kumar Anand
* @package Search
* @subpackage Sort
* @copyright 2014 Kumar Anand
* @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
* @since 2014-03-25
*/
class SortByNewMatchesMailerStrategy extends SearchSort implements SortStrategyInterface
{
	private $pid;
        
	/*
        * @constructor 
        * @access public
        * @param SearchParamters $SearchParamtersObj 
        * @param LoggedInProfile $loggedInProfileObj logged in profile object 
        */
	public function __construct($SearchParamtersObj,$loggedInProfileObj='')
        {
                $this->SearchParamtersObj = $SearchParamtersObj;
		parent::isPhotoSorting($SearchParamtersObj,$loggedInProfileObj);
		parent::isFilterSorting($loggedInProfileObj);
                parent::setReverseDppSorting($loggedInProfileObj, 1);
		if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID())
			$this->pid = $loggedInProfileObj->getPROFILEID();
        }


	/**
	* This funcion will set the solr post parameters required to fetch the desired results.
	* @access public
        * @staticvar SearchConfig::$filteredRemove
	*/
	public function getSortString()
	{
		$counter = 0;
	
		/*Filter sorting to be given max preference*/
                if(SearchConfig::$filteredRemove && parent::getFilterSort())
                {
                        $sortString[$counter] = parent::getFilterSort();
                        $sortAscOrDesc[$counter] = parent::getPhotoSortAsc();
                        $counter++;
                        $this->SearchParamtersObj->setFL_ATTRIBUTE("*,FILTER_SCORE:".parent::getFilterSortScore());
                }
	
		/* When Photos is searched , visible photos will be given more prefernce. */
		$photoSort = $this->getPhotoSorting();
		if($photoSort)
		{
                        $sortString[$counter] = $photoSort;
                        $sortAscOrDesc[$counter] = $this->sortByDesc;
                        $counter++;
		}

		$matchalertSort = $this->getMatchAlertSentProfilesSorting();
		if($matchalertSort)
		{
			$sortString[$counter] = $matchalertSort;
                        $sortAscOrDesc[$counter] = $this->sortByDesc;
                        $counter++;
		}
                
                if(parent::getReverseDppSort()){
                        $sortString[$counter] = parent::getReverseDppSort();
                        $sortAscOrDesc[$counter] = $this->sortByDesc;
                        $counter++;
                }
                
		/* new profiles / new photo profile will be given more prefernce. */
		$sortString[$counter] = "VERIFY_ACTIVATED_DT";
		$sortAscOrDesc[$counter] = $this->sortByDesc;
		$counter++;
                
		$this->SearchParamtersObj->setSORTING_CRITERIA($sortString);
		$this->SearchParamtersObj->setSORTING_CRITERIA_ASC_OR_DESC($sortAscOrDesc);
	}

	/**
        * This funcion will set sent match alert profile sorting string.
        * @access public
	* @return String $sortStr
        */
	public function getMatchAlertSentProfilesSorting()
	{
		if($this->pid)
		{
			$matchAlertObj = new MatchAlerts;
			$output = $matchAlertObj->getProfilesSentInMatchAlerts($this->pid);
                	unset($matchAlertObj);
			if($output && is_array($output))
			{
				$sortStr = "";
				foreach($output as $k=>$v)
				{
					$sortStr = $sortStr."if(tf(id,".$v."),0,1),";
				}
				$sortStr = rtrim($sortStr,",");
				$sortStr = "and(".$sortStr.")";
			}
		}
		return $sortStr;
	}

	public function getPhotoSorting()
	{
		$sortStr = "if(tf(HAVEPHOTO,Y),if(tf(PHOTO_DISPLAY,C),3,4),if(tf(HAVEPHOTO,U),2,1))";
		return $sortStr;
	}
}
