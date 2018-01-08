<?php
/**
* This class perform Sort By Photo for AP cron
* @author : Ankit Shukla
* @package Search
* @subpackage Sort
* @copyright 2013 Ankit Shukla
* @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
* @since 2017-11-30
*/
class SortByPhotoForAP extends SearchSort implements SortStrategyInterface
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
      parent::isHavePhotoSorting($SearchParamtersObj, $loggedInProfileObj);
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
        /* When Photos is searched , visible photos will be given more prefernce. */
        $photoSort = parent::getHavePhotoSort();
        if ($photoSort) {
          $sortString[$counter] = parent::getHavePhotoSort();
          $sortAscOrDesc[$counter] = parent::getPhotoSortDesc();
          $counter++;
        }

        /* Filter sorting to be given max preference */
        if (SearchConfig::$filteredRemove && parent::getFilterSort()) {
          $sortString[$counter] = parent::getFilterSort();
          $sortAscOrDesc[$counter] = parent::getPhotoSortAsc();
          $counter++;
        }

        /* new profiles / new photo profile will be given more prefernce. */

        if($this->sortDpp == 1){
          $sortString[$counter] = parent::getReverseDppSort();
          $sortAscOrDesc[$counter] = $this->sortByDesc;
          $counter++;
        }

        $sortString[$counter] = "SORT_DT";
        //$sortString[$counter] = "LAST_LOGIN_DT";
        $sortAscOrDesc[$counter] = $this->sortByAsc;
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
