<?php
/**
* This class perform Sort By Date expression
* @author : Lavesh Rawat
* @package Search
* @subpackage Sort
* @copyright 2013 Lavesh Rawat
* @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
* @since 2013-12-01
*/
class SortByDateStrategy extends SearchSort implements SortStrategyInterface
{
  /*
   * perform sort or not
   */
  Private $sortDpp = 0;
	/**
	* constructor class
	* @access public
	* @param SearchParamters $SearchParamtersObj
	* @param LoggedInProfile $loggedInProfileObj
	*/

  public function __construct($SearchParamtersObj, $loggedInProfileObj = '') {
    $this->SearchParamtersObj = $SearchParamtersObj;
    $this->setReverseDPPSort(); // to set reverse DPP sorting
    if($this->SearchParamtersObj->getIS_APCron())
        $usePhotoSorting = 1;
    parent::isPhotoSorting($SearchParamtersObj, $loggedInProfileObj,$usePhotoSorting);
    parent::isFilterSorting($loggedInProfileObj);
    
    parent::setReverseDppSorting($loggedInProfileObj, 1);
  }

  /**
	* This funcion will set the solr post parameters required to fetch the desired results.
	* @access public
	* @staticvar SearchConfig::$filteredRemove
	*/
	public function getSortString()
	{
		$counter = 0;

    /* Filter sorting to be given max preference */
    if (SearchConfig::$filteredRemove && parent::getFilterSort()) {
      $sortString[$counter] = parent::getFilterSort();
      $sortAscOrDesc[$counter] = parent::getPhotoSortAsc();
      $counter++;
    }

    /* When Photos is searched , visible photos will be given more prefernce. */
    $photoSort = parent::getPhotoSort();
    if ($photoSort) {
      $sortString[$counter] = parent::getPhotoSort();
      $sortAscOrDesc[$counter] = parent::getPhotoSortDesc();
      $counter++;
    }

    /* new profiles / new photo profile will be given more prefernce. */
    
    if($this->sortDpp == 1){
      $sortString[$counter] = parent::getReverseDppSort();
      $sortAscOrDesc[$counter] = $this->sortByDesc;
      $counter++;
    }

    //$sortString[$counter] = "SORT_DT";
    $sortString[$counter] = "LAST_LOGIN_DT";
    $sortAscOrDesc[$counter] = $this->sortByDesc;
    $counter++;
    if (SearchConfig::$filteredRemove && parent::getFilterSort())
    	$this->SearchParamtersObj->setFL_ATTRIBUTE("*,FILTER_SCORE:" . parent::getFilterSortScore());
    $this->SearchParamtersObj->setSORTING_CRITERIA($sortString);
    $this->SearchParamtersObj->setSORTING_CRITERIA_ASC_OR_DESC($sortAscOrDesc);
  }
  
  /*
   * This function set SortDpp variable to 1 for advanced search,quick search, saved search, Online search and DPP
   */
  public function setReverseDPPSort() {
        $this->sortDpp = 1;
  }
}
