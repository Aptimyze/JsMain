<?php

class TrendsPartnerProfiles extends SearchParamters {
        /**
        * @private LAST_LOGGEDIN [No. of days in which we consider for last logged in matches]
        */
  private $LAST_LOGGEDIN = 15;
  //Jpartner fields to be fetched from table
  private $jpartnerFields = 'PROFILEID,GENDER,LAGE,HAGE,LHEIGHT,HHEIGHT,PARTNER_MSTATUS,PARTNER_MANGLIK,PARTNER_COUNTRYRES,PARTNER_INCOME,PARTNER_ELEVEL_NEW,PARTNER_OCC,PARTNER_CITYRES,PARTNER_CASTE,PARTNER_MTONGUE';
  
//Trends and jpartner table fields mapping
  private $jpartnerTrendsFieldsMapping = array('GENDER'=>'GENDER','LAGE'=>'LAGE','HAGE'=>'HAGE','MSTATUS'=>'MSTATUS','CASTE'=>'CASTE','CITY_RES'=>'CITY_RES','MTONGUE'=>'MTONGUE','INCOME'=>'INCOME');
  private $filterArray = array();
  // Jpartner data
  private $jpartnerData = array();
  private $VERIFIED_CHECK = 2;
  private $LAST_LOGGEDIN_STARTFROM = "1960-01-01 00:00:00";
  /**
   * 
   * @param type $loggedInProfileObj
   */
  public function __construct($loggedInProfileObj) {
    parent::__construct();
    $this->loggedInProfileObj = $loggedInProfileObj;
    $this->table = "twowaymatch.TRENDS";
  }
  /**
   * 
   * @param type $sort sort logic
   * @param type $limit number of records
   */
  public function setSortParam($sort,$limit){
        $this->setSORT_LOGIC($sort);
        $this->setNoOfResults($limit);
        $this->rangeParams .= ",LAST_LOGIN_DT";
        $this->setRangeParams($this->rangeParams);
        // Set login Date condition
        if($sort == SearchSortTypesEnums::SortByTrendsScore){
                $endDate = date("Y-m-d H:i:s", strtotime("now"));
                $startDate = date("Y-m-d 00:00:00", strtotime($endDate) - $this->LAST_LOGGEDIN*24*3600);
                $this->setLLAST_LOGIN_DT($startDate);
                $this->setHLAST_LOGIN_DT($endDate);
        }else{
                $endDate = date("Y-m-d H:i:s", strtotime("now") - $this->LAST_LOGGEDIN*24*3600);
                $this->setLLAST_LOGIN_DT("1960-01-01 00:00:00");
                $this->setHLAST_LOGIN_DT($endDate);
        }
        
        //just joined 2 day check
        $endDate = date("Y-m-d H:i:s", strtotime("now") - $this->VERIFIED_CHECK*24*3600);
        $this->setLVERIFY_ACTIVATED_DT($this->LAST_LOGGEDIN_STARTFROM);
        $this->setHVERIFY_ACTIVATED_DT($endDate);
  }
  /**
   * This function returns table store object
   * @return object default \TWOWAYMATCH_TRENDS object
   */
  public function getTableObj() {
    $mtObj = new TWOWAYMATCH_TRENDS;
    return $mtObj;
  }
  /**
   * function to fetch filter data
   * @return type array filter data
   */
  public function getFilterData() {
    $mtObj = new NEWJS_FILTER();
    return $mtObj->fetchEntry($this->loggedInProfileObj->getPROFILEID());
  }

  public function setMaritalStatus() {
        if($this->filterArray && $this->filterArray["MSTATUS"] == 'Y'){ // if jpartner filter is set, return jpartner condition criteria
                $mstatus = trim($this->jpartnerData[0][$this->jpartnerTrendsFieldsMapping["MSTATUS"]],"'");
                return implode(" ",explode("','",$mstatus));
        }
    if ($this->loggedInProfileObj->getMSTATUS() == 'N') {
      $MSTATUS = "N";
    } else {
      $maritalStatus = FieldMap::getFieldLabel('marital_status', 0, 1);
      unset($maritalStatus['N']);
      $MSTATUS = implode(" ", array_keys($maritalStatus));
    }
    return $MSTATUS;
  }

  public function isManglik() {
          if($this->jpartnerData[0]["MANGLIK"]=="" || $this->jpartnerData[0]["MANGLIK"]=="N"){
                  return false;
          }else{
                  $manglikValues = trim(str_replace("','",' ',$this->jpartnerData[0]["MANGLIK"]),"'");
                  if(strstr($manglikValues,"N")){
                          $manglikValues .= " D";
                  }
                  return $manglikValues;
          }
  }

  public function setCountry($NRI_N_P, $NRI_M_P) {
    if ($NRI_N_P >= 90) {
      return false;
    } elseif ($NRI_M_P >= 90) {
      return true;
    }
  }

  public function setOppositeGENDER($GENDER) {
    if ($GENDER == 'M')
      return 'F';
    else
      return 'M';
  }

  /**
   * 
   * @return type
   */
  public function getDppCriteria($sort, $limit) {
    $this->setSortParam($sort,$limit);
    $mtObj = $this->getTableObj();
    $this->filterArray = $this->getFilterData();
    //if(!empty($this->filterArray)){} // if filter set get jpartner data
    // Get jpartner in each condition
        $memObject=JsMemcache::getInstance();
        $jpartnerData = $memObject->get('SEARCH_JPARTNER_'.$this->loggedInProfileObj->getPROFILEID());

        if(empty($jpartnerData)){
                $dbName = JsDbSharding::getShardNo($this->loggedInProfileObj->getPROFILEID());
                $JPARTNERobj = new newjs_JPARTNER($dbName);
                $fields = SearchConfig::$dppSearchParamters.",MAPPED_TO_DPP";
                $this->jpartnerData = $JPARTNERobj->get(array("PROFILEID"=>$this->loggedInProfileObj->getPROFILEID()),$fields);
                $memObject->set('SEARCH_JPARTNER_'.$this->loggedInProfileObj->getPROFILEID(),serialize($this->jpartnerData),SearchConfig::$matchAlertCacheLifetime);
        }else{
              $this->jpartnerData = unserialize($jpartnerData);
        }
        
    $myrow = $mtObj->getData($this->loggedInProfileObj->getPROFILEID());
    $this->setTRENDS_DATA(serialize($myrow));
    if ($myrow) {
      $forward_filter = $this->setForwardData($myrow);
      $l = 0;
      foreach ($forward_filter as $k => $v) {
        $l = $l + 1;
        if ($k == 'AGE') {
          $temp = $this->getValue($myrow, $k);
          $this->LAGE = $temp[1];
          $this->HAGE = $temp[0];
        } elseif ($k == 'HEIGHT') {
          $temp = $this->getValue($myrow, $k);
          $this->LHEIGHT = $temp[1];
          $this->HHEIGHT = $temp[0];
        } elseif ($k == 'MSTATUS') {
          $this->MSTATUS = $this->setMaritalStatus();
        } elseif ($k == 'MANGLIK') {
          $isManglik = $this->isManglik();
          if ($isManglik){
            $this->MANGLIK = $isManglik;
          }elseif($isManglik === false)
            $this->MANGLIK_IGNORE = "M A";
        } elseif ($k == 'COUNTRYRES') {
          $isNRI = $this->setCountry($myrow["NRI_N_P"], $myrow["NRI_M_P"]);
          if ($isNRI === false)
            $this->COUNTRY_RES = "51";
          elseif($isNRI === true)
            $this->INDIA_NRI = 2;
        } elseif ($k == 'INCOME') {
          if ($myrow["GENDER"] == 'F') {
            $this->INCOME_SORTBY = $this->getValue($myrow, $k);
          }
        } else {
          //if ($l <= 3 || !in_array($k, array('EDU_LEVEL_NEW', 'OCCUPATION', 'CITY_RES')))
          if ($l <= 3 || !in_array($k, array('EDU_LEVEL_NEW', 'OCCUPATION')))
            $this->$k = $this->getValue($myrow, $k);
        }
      }
      $this->GENDER = $this->setOppositeGENDER($myrow["GENDER"]);
      $this->PROFILEID = $myrow["PROFILEID"];
    }else {
      return;
    }
    $this->showFilteredProfiles = 'N';
  }
        
  /**
   * This fucntion sets user forward criteria to be used
   * @param type $myrow user trends data
   * @return array $forward_filter array of predifined forward Criteria
   */
  public function setForwardData($myrow) {
    $forward_filter["CASTE"] = $myrow["W_CASTE"];
    $forward_filter["MTONGUE"] = $myrow["W_MTONGUE"];
    $forward_filter["AGE"] = $myrow["W_AGE"];
    $forward_filter["INCOME"] = $myrow["W_INCOME"];
    $forward_filter["HEIGHT"] = $myrow["W_HEIGHT"];
    $forward_filter["MSTATUS"] = $myrow["W_MSTATUS"];
    $forward_filter["MANGLIK"] = $myrow["W_MANGLIK"];
    $forward_filter["COUNTRYRES"] = $myrow["W_NRI"];

    //--------------only if are in top 2-----------------------
    $forward_filter["EDU_LEVEL_NEW"] = $myrow["W_EDUCATION"];
    $forward_filter["OCCUPATION"] = $myrow["W_OCCUPATION"];
    $forward_filter["CITY_RES"] = $myrow["W_CITY"];
    arsort($forward_filter);
    return $forward_filter;
  }

  /**
   * 
   * @param type $myrow array of user trends data
   * @param type $k key value of user data
   * @return type array in case of range key index else comma separated string of trends value.
   */
  public function getValue($myrow, $k) {
    if ($k == 'CASTE')
      $value = $myrow["CASTE_VALUE_PERCENTILE"];
    elseif ($k == 'MTONGUE')
      $value = $myrow['MTONGUE_VALUE_PERCENTILE'];
    elseif ($k == 'AGE') {
      $value = $myrow['AGE_VALUE_PERCENTILE'];
      $rangeCase = 1;
    } elseif ($k == 'INCOME') {
      $value = $myrow['INCOME_VALUE_PERCENTILE'];
    } elseif ($k == 'HEIGHT') {
      $value = $myrow['HEIGHT_VALUE_PERCENTILE'];
      $rangeCase = 1;
    } elseif ($k == 'EDU_LEVEL_NEW')
      $value = $myrow['EDUCATION_VALUE_PERCENTILE'];
    elseif ($k == 'OCCUPATION')
      $value = $myrow['OCCUPATION_VALUE_PERCENTILE'];
    elseif ($k == 'CITY_RES')
      $value = $myrow['CITY_VALUE_PERCENTILE'];

    if($this->filterArray && $this->filterArray[$k] == 'Y'){ // if jpartner filter is set, return jpartner condition criteria
        if($rangeCase == 1){
                $valueL = $this->jpartnerData[0][$this->jpartnerTrendsFieldsMapping['L'.$k]];
                $valueH = $this->jpartnerData[0][$this->jpartnerTrendsFieldsMapping['H'.$k]];
                $value = array($valueH,$valueL);
        }else{
            $value = $this->jpartnerData[0][$this->jpartnerTrendsFieldsMapping[$k]];
            $value = implode(" ",explode("','",trim($value,"'")));
        }
        return $value;
    }
    $forward_temp = explode("|", $value);
    foreach ($forward_temp as $tempF) {
      if ($tempF) {
        $temparr = explode("#", $tempF);
        if ($temparr[1] > 5) {//2 is cut-off percentage of individual values.
          $forward_temp2[] = $temparr[0];
        }
      }
    } 
    if ($forward_temp2) {
      if ($rangeCase) {
        $str[0] = max($forward_temp2);
        $str[1] = min($forward_temp2);
      } else
        $str = implode(" ", $forward_temp2);
    }
    return $str;
  }
}
?>
