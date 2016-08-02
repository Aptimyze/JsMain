<?php
/**
* This class perform Sort By date when profile get eligible for search (activeDate+phonrverify)
* @author : Lavesh Rawat
* @package Search
* @subpackage Sort
* @copyright 2014 Lavesh Rawat
* @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
* @since 2014-12-01
*/
class SortByVerifyActivationDateStrategy extends SearchSort implements SortStrategyInterface
{
	/*
	* Constructor function.
	* @constructor
	* @access public
        * @param LoggedInProfile $loggedInProfileObj logged in profile object 
        * @param SearchParamters $SearchParamtersObj
	*/
        public function __construct($SearchParamtersObj,$loggedInProfileObj='')
        {
                $this->SearchParamtersObj = $SearchParamtersObj;
		parent::isPhotoSorting($SearchParamtersObj,$loggedInProfileObj,1);
		parent::isFilterSorting($loggedInProfileObj);
		parent::setReverseDppSorting($loggedInProfileObj, 0); // Called with second parameter as 0 When LAst login score is not required in sort string
        }


	/**
	* @access public
	* This funcion will set the solr post parameters required to fetch the desired results.
	* The bucketing should be as follows:
	* 1. PoGs with Photo (havephoto='Y')
	* 2. PoGs with Photo under screening (havephoto='U')
	* 3. PoGs with Photo visible on accept (PRIVACY='C') // irrespective of havephoto
	* 4. PoGs without Photo (havephoto='N' or '')
	* 5. VERIFY_ACTIVATED_DT Sorting
	*/
	public function getSortString()
	{
		$counter = 0;
	
		/* 
		sorting 1: PoGs with Photo under screening,PoGs with Photo comes at top 
		*/
		$sortString[$counter] = "sum(termfreq(HAVEPHOTO,Y),termfreq(HAVEPHOTO,U))";
		$sortAscOrDesc[$counter] = $this->sortByDesc;
                $counter++;

		/* 
		* sorting 2 : PoGs with Photo visible on accept comes below  visible to all 
		*/
		$photoSort = parent::getPhotoSort();
		if($photoSort)
		{
			//$sortString[$counter] = "if(sum(termfreq(HAVEPHOTO,Y)),if(sum(termfreq(PHOTO_DISPLAY,A)),1,0),0)";
                        $sortString[$counter] = parent::getPhotoSort();
                        $sortAscOrDesc[$counter] = parent::getPhotoSortDesc();
                        $counter++;
		}

		/* 
		* sorting 3 : Points1 and Point2 Sorting
		*/
		$sortString[$counter] = "sum(termfreq(HAVEPHOTO,Y))";
		$sortAscOrDesc[$counter] = $this->sortByDesc;
                $counter++;

                /**
                 * Sorting 4 Sorting on the basis of activated Date
                 */
                $sortString[$counter] = "VERIFY_ACTIVATED_DT_ONLY";
		$sortAscOrDesc[$counter] = $this->sortByDesc;
		$counter++;
                
                /**
                 * Sorting 5 sorting on the basis of reverse Dpp
                 */
                if(parent::getReverseDppSort()){
                        $sortString[$counter] = parent::getReverseDppSort();
                        $sortAscOrDesc[$counter] = $this->sortByDesc;
                        $counter++;
                }
                
                /* sorting 6 on the basis of cerify activated timestamp */
		$sortString[$counter] = "VERIFY_ACTIVATED_DT";
		$sortAscOrDesc[$counter] = $this->sortByDesc;
		$counter++;

		$this->SearchParamtersObj->setSORTING_CRITERIA($sortString);
		$this->SearchParamtersObj->setSORTING_CRITERIA_ASC_OR_DESC($sortAscOrDesc);
	}
}
