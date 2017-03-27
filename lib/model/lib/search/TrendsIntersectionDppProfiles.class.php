<?php

class TrendsIntersectionDppProfiles extends PartnerProfile {

  /**
   * 
   * @param type $loggedInProfileObj
   */
  public function __construct($loggedInProfileObj) {
    parent::__construct($loggedInProfileObj);
    $this->valuesForTrends = array("MSTATUS","MANGLIK","INCOME","EDU_LEVEL_NEW","OCCUPATION","CITY_RES","MTONGUE","CASTE","MANGLIK_IGNORE");
  }
  

  /**
   * 
   * @return type
   */
  public function getDppTrendsCriteria($sort, $limit) {
    parent::getDppCriteria();
    $this->setSortParam($sort,$limit);
    $trendsObj = new TrendsPartnerProfiles($this->loggedInProfileObj);
    $trendsObj->getDppCriteria($sort, $limit);
    if($this->LAGE && $trendsObj->LAGE >= $this->LAGE)
        $this->LAGE = $trendsObj->LAGE;
    if($trendsObj->HAGE && $trendsObj->HAGE <= $this->HAGE)
        $this->HAGE = $trendsObj->HAGE;
    if($this->LHEIGHT && $trendsObj->LHEIGHT >= $this->LHEIGHT)
        $this->LHEIGHT = $trendsObj->LHEIGHT;
    if($trendsObj->HHEIGHT && $trendsObj->HHEIGHT <= $this->HHEIGHT)
        $this->HHEIGHT = $trendsObj->HHEIGHT;
    
    $this->showFilteredProfiles = 'N';
    
    //get all fields from intersection
    $getFromTrends = $this->getIntersectionDpp($trendsObj);
    //check for special cases
    if(($this->MANGLIK != '' && $this->MANGLIK_IGNORE != '') || ($this->LAGE > $this->HAGE || $this->LHEIGHT > $this->HHEIGHT))
        $getFromTrends = false;
    
    if($this->COUNTRY_RES == ''){
        if($trendsObj->INDIA_NRI != '')
            $this->INDIA_NRI = $trendsObj->INDIA_NRI;
        else
            $this->COUNTRY_RES = $trendsObj->COUNTRY_RES;
    }
         
    
    //check for income sortby
    if($this->INCOME == '' && $trendsObj->INCOME_SORTBY)
        $this->INCOME_SORTBY = $trendsObj->INCOME_SORTBY;
    //print_r($this);die;
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

