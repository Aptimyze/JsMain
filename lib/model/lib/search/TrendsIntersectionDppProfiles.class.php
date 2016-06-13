<?php

class TrendsIntersectionDppProfiles extends PartnerProfile {

  /**
   * 
   * @param type $loggedInProfileObj
   */
  public function __construct($loggedInProfileObj) {
    parent::__construct($loggedInProfileObj);
    $this->valuesForTrends = array("MSTATUS","MANGLIK","COUNTRY_RES","INCOME_SORTBY","EDU_LEVEL_NEW","OCCUPATION","CITY_RES","MTONGUE","CASTE","INDIA_NRI","MANGLIK_IGNORE");
  }
  

  /**
   * 
   * @return type
   */
  public function getDppTrendsCriteria($sort, $limit) {
    Parent::getDppCriteria();
    $this->setSortParam($sort,$limit);
    $trendsObj = new TrendsPartnerProfiles($this->loggedInProfileObj);
    $trendsObj->getDppCriteria($sort, $limit);
    if($trendsObj->LAGE >= $this->LAGE)
        $this->LAGE = $trendsObj->LAGE;
    if($trendsObj->HAGE <= $this->HAGE)
        $this->HAGE = $trendsObj->HAGE;
    if($trendsObj->LHEIGHT >= $this->LHEIGHT)
        $this->LHEIGHT = $trendsObj->LHEIGHT;
    if($trendsObj->HHEIGHT <= $this->HHEIGHT)
        $this->HHEIGHT = $trendsObj->HHEIGHT;
    $this->showFilteredProfiles = 'N';
    $getFromTrends = $this->getIntersectionDpp($trendsObj);
    if(($this->COUNTRY_RES != '' && $this->INDIA_NRI != '') || ($this->MANGLIK != '' && $this->MANGLIK_IGNORE != ''))
       $getFromTrends = false;
    return $getFromTrends;
  }
  
  
  private function getIntersectionDpp($trendsObj) {
      $dppObj = $this;
      foreach($this->valuesForTrends as $value => $key){
        $matched=0;
        $dppArr = '';
        $trendsVar='';
        $newDppArr = '';
        if($dppObj->$key)
            $dppArr = explode(",",$dppObj->$key);
        if($trendsObj->$key)
            $trendsVar = explode(" ",$trendsObj->$key);
        if($trendsVar){
            foreach($trendsVar as $key1=>$val){
                if(!$dppArr || in_array($val, $dppArr)){
                  $newDppArr[] = $val;
                }
            }
            if($newDppArr)
                $dppObj->$key = implode(",", $newDppArr);
            else {
                return false;     
            }
        }
      }
      return true;
  }
  public function setSortParam($sort,$limit){
    $this->setSORT_LOGIC($sort);
    $this->setNoOfResults($limit);
  }
}
?>

