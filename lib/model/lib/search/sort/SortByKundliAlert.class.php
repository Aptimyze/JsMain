<?php
/**
 * This class perform Sort By SortByKundliAlert Sent date then photo status 
 * @author Lavesh Rawat
 * @package Search
 * @subpackage Sort
 * @copyright 2014 Lavesh Rawat
 * @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
 * @since 2014-09-26
 */
class SortByKundliAlert extends SearchSort implements SortStrategyInterface
{
	private $usePhotoSorting=1;

        /**
	* Constructor function
        * @constructor
        * @param SearchParamters $SearchParamtersObj
        * @param LoggedInProfile $loggedInProfileObj logged in profile object 
        */
        public function __construct($SearchParamtersObj,$loggedInProfileObj='')
        { 
                $this->SearchParamtersObj = $SearchParamtersObj;
		parent::isPhotoSorting($SearchParamtersObj,$loggedInProfileObj,$this->usePhotoSorting);
		parent::isFilterSorting($loggedInProfileObj);
        }

	/**
	* This funcion will set the solr post parameters required to fetch the desired results.
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
                $count=0;
                $pObj = LoggedInProfile::getInstance();

		/** date sorting **/
		$tempArr = $this->SearchParamtersObj->getAlertsDateConditionArr();
                $sortString[$counter] = "sum(";
		foreach($tempArr as $k=>$v)
                {       
                        $sortString[$counter] .= 'if(and(tf(id,"'.$k.'"),1),'.$v.',0)';
                        $count++;
                        $sortString[$counter] .= ",";
		}
                $sortString[$counter] .= ")";
                $sortAscOrDesc[$counter] = $this->sortByDesc;
		$counter++;
		
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
