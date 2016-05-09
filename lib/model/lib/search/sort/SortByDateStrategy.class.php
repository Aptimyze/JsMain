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
    parent::isPhotoSorting($SearchParamtersObj, $loggedInProfileObj);
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
      $sortString[$counter] = $this->getReverseDppSort();
      $sortAscOrDesc[$counter] = $this->sortByDesc;
      $counter++;
    }

    $sortString[$counter] = "LAST_LOGIN_DT";
    $sortAscOrDesc[$counter] = $this->sortByDesc;
    $counter++;
    if (SearchConfig::$filteredRemove && parent::getFilterSort())
    	$this->SearchParamtersObj->setFL_ATTRIBUTE("*,FILTER_SCORE:" . parent::getFilterSortScore());
    $this->SearchParamtersObj->setSORTING_CRITERIA($sortString);
    $this->SearchParamtersObj->setSORTING_CRITERIA_ASC_OR_DESC($sortAscOrDesc);
  }
  /*
   * This function check gor logged in profile and create sort string for new freshness sort logic
   */
  public function getReverseDppSort(){
    $loggedInProfileObj = LoggedInProfile::getInstance();
    $sortArray = array();
    // Login time condition
    $sortLogin = "LAST_LOGIN_SCORE"; // User who have logged in in last 15 days will be given 100 score
    $doesntMatterValue = 99999;
    
    if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID()!='')
		{
      if($loggedInProfileObj->getCASTE())
			{
        $sortArray[] = "or(tf(PARTNER_CASTE,".$loggedInProfileObj->getCASTE()."),tf(PARTNER_CASTE,".$doesntMatterValue."))";
      }
      if($loggedInProfileObj->getAGE())
			{
				$sortArray[] = "and(if(abs(sub(min(PARTNER_LAGE,".$loggedInProfileObj->getAGE()."),PARTNER_LAGE)),0,1),if(abs(sub(max(PARTNER_HAGE,".$loggedInProfileObj->getAGE()."),PARTNER_HAGE)),0,1))";
			}
      if($loggedInProfileObj->getMSTATUS())
			{
				$sortArray[] = "or(tf(PARTNER_MSTATUS,".$loggedInProfileObj->getMSTATUS()."),tf(PARTNER_MSTATUS,".$doesntMatterValue."))";
			}
      if($loggedInProfileObj->getRELIGION())
			{
        $sortArray[] = "or(tf(PARTNER_RELIGION,".$loggedInProfileObj->getRELIGION()."),tf(PARTNER_RELIGION,".$doesntMatterValue."))";
			}
      if($loggedInProfileObj->getCOUNTRY_RES())
			{
        $sortArray[] = "or(tf(PARTNER_COUNTRYRES,".$loggedInProfileObj->getCOUNTRY_RES()."),tf(PARTNER_COUNTRYRES,".$doesntMatterValue."))";
			}
      if($loggedInProfileObj->getCITY_RES())
			{
        $sortArray[] = "or(tf(PARTNER_CITYRES,".$loggedInProfileObj->getCITY_RES()."),tf(PARTNER_CITYRES,".$doesntMatterValue."))";
			}
      if($loggedInProfileObj->getMTONGUE())
			{
        $sortArray[] = "or(tf(PARTNER_MTONGUE,".$loggedInProfileObj->getMTONGUE()."),tf(PARTNER_MTONGUE,".$doesntMatterValue."))";
			}
      if($loggedInProfileObj->getGENDER()=='F')
      {
        if($loggedInProfileObj->getEDU_LEVEL_NEW())
        {
          $sortArray[] = "or(tf(PARTNER_ELEVEL_NEW,".$loggedInProfileObj->getEDU_LEVEL_NEW()."),tf(PARTNER_ELEVEL_NEW,".$doesntMatterValue."))";
        }
      }else{
        if($loggedInProfileObj->getINCOME())
        {
          $sortArray[] = "or(tf(PARTNER_INCOME_FILTER,".$loggedInProfileObj->getINCOME()."),tf(PARTNER_INCOME_FILTER,".$doesntMatterValue."))";
        }
      }
    }
    
    if(!empty($sortArray)){
      $brace = '';
      $strCondition = '';
      foreach($sortArray as $arr){
        $strCondition .= "if(".$arr.",";
        $brace .= ",0)";
      }
      $strCondition .= "1".$brace;
      $strCondition = "sum(".$sortLogin.",".$strCondition.")";
    }else{
      $strCondition = $sortLogin;
    }
    return $strCondition;
  }
  /*
   * This function set SortDpp variable to 1 for advanced search,quick search, saved search, Online search and DPP
   */
  public function setReverseDPPSort() {
        $this->sortDpp = 1;
  }
}
