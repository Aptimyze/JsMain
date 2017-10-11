<?php
/**
* This class perform Sort By Feature Profile expression
* @author : Lavesh Rawat
* @package Search
* @subpackage Sort
* @copyright 2013 Lavesh Rawat
* @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
* @since 2013-12-01
*/
class SortByFeatureProfileStrategy extends SearchSort implements SortStrategyInterface
{
	/**
	* constructor class 
	* @access public
	* @param SearchParamters $SearchParamtersObj
        * @param LoggedInProfile $loggedInProfileObj logged in profile object
	*/
        public function __construct($SearchParamtersObj,$loggedInProfileObj='')
        {
                $this->SearchParamtersObj = $SearchParamtersObj;
		parent::isPhotoSorting($SearchParamtersObj,$loggedInProfileObj);
		parent::isFilterSorting($loggedInProfileObj);
        }


	/**
	* This funcion will set the solr post parameters required to fetch the desired results.
	* @access public
	* @staticvar SearchConfig::$filteredRemove
	*/
	public function getSortString()
	{
		$counter = 0;

		/*Filter sorting to be given max preference if reverse criteria is being avoided*/
                if(SearchConfig::$filteredRemove && parent::getFilterSort() && $this->SearchParamtersObj->getAvoidRevereseCriteria())
                {
                        $sortString[$counter] = parent::getFilterSort();
                        $sortAscOrDesc[$counter] = parent::getPhotoSortAsc();
                        $counter++;
                        //$this->SearchParamtersObj->setFL_ATTRIBUTE("*,FILTER_SCORE:".parent::getFilterSortScore());

			/* When Photos is searched , visible photos will be given more prefernce if reverse criteria is avoided */
			$photoSort = parent::getPhotoSort();
			if($photoSort)
			{
				$sortString[$counter] = parent::getPhotoSort();
				$sortAscOrDesc[$counter] = parent::getPhotoSortDesc();
				$counter++;
			}
                }

		
		$sortString[$counter] = "FEATURE_PROFILE_SCORE";
		$sortAscOrDesc[$counter] = $this->sortByAsc;
		$counter++;

		$this->SearchParamtersObj->setSORTING_CRITERIA($sortString);
		$this->SearchParamtersObj->setSORTING_CRITERIA_ASC_OR_DESC($sortAscOrDesc);
	}
}
