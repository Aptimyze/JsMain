<?php
/**
 * This class perform Sort By NTIMES
 * @author : Lavesh Rawat
 * @package Search
 * @subpackage Sort
 * @copyright 2013 Lavesh Rawat
 * @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
 * @since 2013-03-21
*/
class SortByNtimesStrategy extends SearchSort implements SortStrategyInterface
{
        /*
        * @constructor
        * @param SearchParamters $SearchParamtersObj
        * @param LoggedInProfile $loggedInProfileObj logged in profile object 
        */
        public function __construct($SearchParamtersObj,$loggedInProfileObj='')
        {
                $this->SearchParamtersObj = $SearchParamtersObj;
        }

	/**
	* This funcion will set the solr post parameters required to fetch the desired results.
	*/
	public function getSortString()
	{
		$counter = 0;
		
		/* SORT_DT popularity */
		$sortString[$counter] = "NTIMES";
		$sortAscOrDesc[$counter] = $this->sortByDesc;
		$counter++;

		$this->SearchParamtersObj->setSORTING_CRITERIA($sortString);
		$this->SearchParamtersObj->setSORTING_CRITERIA_ASC_OR_DESC($sortAscOrDesc);
	}
}
