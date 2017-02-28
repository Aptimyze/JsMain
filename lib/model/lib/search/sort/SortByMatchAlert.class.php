<?php
/**
* This class perform Sort By MatchAlert Sent date then photo status 
* @author : Lavesh Rawat/Akash Kumar
* @package Search
* @subpackage Sort
* @copyright 2013 Lavesh Rawat
* @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
* @since 2014-09-26
 */
class SortByMatchAlert extends SearchSort implements SortStrategyInterface
{
	private $usePhotoSorting=1;

	/*
	* constructor class 
	* @access public
	* @param SearchParamters $SearchParamtersObj 
	* @param LoggedInProfile $loggedInProfileObj logged in profile object 
	*/
        public function __construct($SearchParamtersObj,$loggedInProfileObj='')
        { 
                $this->SearchParamtersObj = $SearchParamtersObj;
		parent::isPhotoSorting($SearchParamtersObj,$loggedInProfileObj,$this->usePhotoSorting);
		parent::isFilterSorting($loggedInProfileObj);
                parent::isJsBoostSorting($loggedInProfileObj);
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
                        if($this->SearchParamtersObj->getNoOfResults()==5000)
                                $this->SearchParamtersObj->setFL_ATTRIBUTE("id,FILTER_SCORE:".parent::getFilterSortScore());
                        else
                                $this->SearchParamtersObj->setFL_ATTRIBUTE("*,FILTER_SCORE:".parent::getFilterSortScore());
                } 
                $count=0;
                $pObj = LoggedInProfile::getInstance();

		$tempArr = $this->SearchParamtersObj->getAlertsDateConditionArr();
                $sortString[$counter] = "sum(";

		if(is_array($tempArr))		
		foreach($tempArr as $k=>$v)
                {       
                        $sortString[$counter] .= 'if(and(tf(id,"'.$k.'"),1),'.$v.',0)';
                        $count++;
                        $sortString[$counter] .= ",";
		}
                $sortString[$counter] .= ")";
                $sortAscOrDesc[$counter] = $this->sortByDesc;
		$counter++;
                
		if(parent::getJsBoostSorting()){
                        $sortString[$counter] =  parent::getJsBoostSorting();
                        $sortAscOrDesc[$counter] = $this->sortByDesc;
                        $counter++;
                }
                
                /* Photo Sortng */
		if($this->usePhotoSorting==1)
		{
			$sortString[$counter] = "HAVEPHOTO";
			$sortAscOrDesc[$counter] = parent::getPhotoSortDesc();
			$counter++;
                	$photoSort = parent::getPhotoSort();
	                if($photoSort)
        	        {
                	        $sortString[$counter] = parent::getPhotoSort();
                        	$sortAscOrDesc[$counter] = parent::getPhotoSortDesc();
	                        $counter++;
                	}       
		}
		$this->SearchParamtersObj->setSORTING_CRITERIA($sortString);
		$this->SearchParamtersObj->setSORTING_CRITERIA_ASC_OR_DESC($sortAscOrDesc);
	}
}
