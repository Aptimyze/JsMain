<?php

class trendsIntersectionDppProfiles extends PartnerProfile {

  /**
   * 
   * @param type $loggedInProfileObj
   */
  public function __construct($loggedInProfileObj) {
    parent::__construct($loggedInProfileObj);
    $this->valuesForTrends = array("MSTATUS","MANGLIK","COUNTRYRES","INCOME","EDU_LEVEL_NEW","OCCUPATION","CITY_RES","MTONGUE","COUNTRYRES","CASTE");
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
    $getFromTrends = $this->getIntersectionDpp($trendsObj, $this);
    return $getFromTrends;
  }
  
  
  private function getIntersectionDpp($trendsObj,$dppObj) {
      foreach($this->valuesForTrends as $value => $key){
        $matched=0;
        $dppArr = explode(",",$dppObj->$key);
        $trendsVar = explode(" ",$trendsObj->$key);
        if(count($trendsVar) > 0){
            foreach($trendsVar as $key1=>$val){
                if(in_array($val, $dppArr) || count($dppArr) == 0){
                  $dppArr[] = $val;
                  $matched = 1;
                }
            }
        $dppObj->$key = implode(",", $dppArr);
        if($matched = 0)
          return false;
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

