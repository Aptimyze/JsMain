<?php
/**
 * This class perform Sort By Popularity
 * @author : Lavesh Rawat
 * @package Search
 * @subpackage Sort
 * @copyright 2012 Lavesh Rawat
 * @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
 * @since 2012-07-27
*/
class SortByPopularStrategy extends SearchSort implements SortStrategyInterface
{
	/*
	* @constructor
        * @param SearchParamters $SearchParamtersObj
        * @param LoggedInProfile $loggedInProfileObj logged in profile object 
	*/
        public function __construct($SearchParamtersObj,$loggedInProfileObj='')
        {
                $this->SearchParamtersObj = $SearchParamtersObj;
		parent::isPhotoSorting($SearchParamtersObj,$loggedInProfileObj);
		parent::isFilterSorting($loggedInProfileObj);
		parent::isPaidSorting($loggedInProfileObj);
        }

	/**
	* This funcion will set the solr post parameters required to fetch the desired results.
	*/
	public function getSortString()
	{
		$counter = 0;
                if(parent::getPaidSorting()){
                        $sortString[$counter] = parent::getPaidSorting();
                        $sortAscOrDesc[$counter] = $this->sortByDesc;
                        $counter++;
                }
		/*Filter sorting to be given max preference*/
                if(SearchConfig::$filteredRemove && parent::getFilterSort())
                {
                        $sortString[$counter] = parent::getFilterSort();
                        $sortAscOrDesc[$counter] = parent::getPhotoSortAsc();
                        $counter++;
                        $this->SearchParamtersObj->setFL_ATTRIBUTE("*,FILTER_SCORE:".parent::getFilterSortScore());
                }
		
                /* When Photos is searched , visible photos will be given more prefernce. */
                $photoSort = parent::getPhotoSort();
                if($photoSort)
                {
                        $sortString[$counter] = parent::getPhotoSort();
                        $sortAscOrDesc[$counter] = parent::getPhotoSortDesc();
                        $counter++;
                }       

		/* SORT_DT popularity */
		$sortString[$counter] = "POPULAR";
		$sortAscOrDesc[$counter] = $this->sortByDesc;
		$counter++;

		/* SORT_DT completeness and freshness*/
		//$sortString[$counter] = "TOTAL_POINTS";
		//$sortAscOrDesc[$counter] = $this->sortByDesc;
		//$counter++;

		$this->SearchParamtersObj->setSORTING_CRITERIA($sortString);
		$this->SearchParamtersObj->setSORTING_CRITERIA_ASC_OR_DESC($sortAscOrDesc);
	}
}
