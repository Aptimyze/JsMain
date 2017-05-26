<?php

/* tests to be covered for TrendsPartnerProfiles Control
 * Call with phpunit --bootstrap test/phpUnit/bootstrap.php test/phpUnit/unit/TrendsPartnerProfilesControlTest.class.php
 */

class TrendsPartnerProfilesControlTest extends PHPUnit_Framework_TestCase {

  public $limit = 10;
  public $sort = 'D';
  public $mtObj;
  public $profileID = 11;
  public $forward_array = array(
      "GENDER" => 'M',
      "W_CASTE" => "0.38", "CASTE_VALUE_PERCENTILE" => "|48#32|41#21|66#10|35#8|25#7|65#6|89#5|32#3|78#3|30#2|242#1|94#1|",
      "W_MTONGUE" => "0.19", "MTONGUE_VALUE_PERCENTILE" => "|16#73|31#8|3#6|34#5|20#4|27#2|10#2|",
      "W_AGE" => "0.11", "AGE_VALUE_PERCENTILE" => "|31#27|33#24|29#15|30#11|32#10|34#4|27#4|28#2|26#7|",
      "W_INCOME" => "0.01", "INCOME_VALUE_PERCENTILE" => "|6#23|5#18|9#18|0#14|7#14|2#10|4#4|",
      "W_HEIGHT" => "0.04", "HEIGHT_VALUE_PERCENTILE" => "|17#31|18#18|20#12|16#12|21#11|15#9|19#5|14#2|",
      "W_EDUCATION" => "0.11", "EDUCATION_VALUE_PERCENTILE" => "|13#35|19#20|3#14|14#11|22#7|16#6|17#3|12#2|15#2|2#1|",
      "W_OCCUPATION" => "0.44", "OCCUPATION_VALUE_PERCENTILE" => "|20#26|24#19|6#13|27#13|13#8|21#8|7#3|19#3|1#3|41#2|31#2|43#0|",
      "W_CITY" => "0.43", "CITY_VALUE_PERCENTILE" => "|5#26|30#21|KA02#21|KA09#10|PU09#10|HA03#6|MH04#4|MH08#3|",
      "W_MSTATUS" => "0", "MSTATUS_N_P" => "0", "MSTATUS_M_P" => "0",
      "W_MANGLIK" => "0.09", "MANGLIK_M_P" => "98", "MANGLIK_N_P" => "2",
      "W_NRI" => "0.06", "NRI_M_P" => "82", "NRI_N_P" => "18"
  );

  public static function setUpBeforeClass() {
    
  }

  public static function tearDownAfterClass() {
    
  }

  protected function tearDown() {
    
  }

  protected function setUp() {
    $this->profileObj = $this->getMockBuilder('LoggedInProfile')->disableOriginalConstructor()->getMock();
    $this->trendsBasedMatchAlertsObj = $this->getMockBuilder('TrendsPartnerProfiles')->setConstructorArgs(array($this->profileObj))->setMethods(array('getTableObj','setSortParam'))->getMock();
  }

  public function setgetTableObj($dataArray) {
    $this->mtObj = $this->getMockBuilder('TWOWAYMATCH_TRENDS')->setConstructorArgs(array())->setMethods(array('getData'))->getMock();
    $this->mtObj->method('getData')->with($this->profileID)->willReturn($dataArray);
    $this->trendsBasedMatchAlertsObj->method('getTableObj')->with()->willReturn($this->mtObj);
  }
  public function setgetProfileid() {
    $this->profileObj->method('getPROFILEID')->with()->willReturn($this->profileID);
  }
  
  public function setgetMSTATUS($MSTATUS) {
    $this->profileObj->method('getMSTATUS')->with()->willReturn($MSTATUS);
  }
  public function setSortParam() {
    $this->trendsBasedMatchAlertsObj->method('setSortParam')->with()->willReturn();
  }
  public function dataProviderMaritalStatus() {
    $dataArray = array();
    //condition 1
    $dataArray[] = array('M', array(), array(), array(),NULL);
    
    // condition 2
    $verifyArray = array('LAGE'=>26,'HAGE'=>33,'MSTATUS'=>'N','MANGLIK'=>'M A',"OCCUPATION"=>'20 24 6 27 13 21',"CITY_RES"=>"5 30 KA02 KA09 PU09 HA03");
    $notSetArray = array('MANGLIK_IGNORE',"COUNTRY_RES","INDIA_NRI","INCOME_SORTBY","EDU_LEVEL_NEW");
    $dataArray[] = array('N', $this->forward_array,$verifyArray,$notSetArray,1);
    
    // condition 3
    $data = $this->forward_array;
    $data['W_EDUCATION'] = '0.9';
    $verifyArray = array('LAGE'=>26,'HAGE'=>33,'MSTATUS'=>'N','MANGLIK'=>'M A',"OCCUPATION"=>'20 24 6 27 13 21',"CITY_RES"=>"5 30 KA02 KA09 PU09 HA03","EDU_LEVEL_NEW"=>"13 19 3 14 22 16");
    $notSetArray = array('MANGLIK_IGNORE',"COUNTRY_RES","INDIA_NRI","INCOME_SORTBY");
    $dataArray[] = array('N', $data,$verifyArray,$notSetArray,1);
    
    //condition 4
    $d1 = $this->forward_array;
    $d1['GENDER'] = 'F';
    $d1['W_OCCUPATION'] = '0.01';
    $d1['W_EDUCATION'] = '0.01';
    $d1['W_CITY'] = '0.01';
    $d1['NRI_M_P'] = '91';
    $d1['MANGLIK_M_P'] = '1';
    $d1['MANGLIK_N_P'] = '99';
    $verifyArray = array('LAGE'=>26,'HAGE'=>33,'MSTATUS'=>'N','MANGLIK_IGNORE'=>'M A',"INCOME_SORTBY"=>"6 5 9 0 7 2","INDIA_NRI"=>2,"CASTE"=>"48 41 66 35 25 65","MTONGUE"=>"16 31 3", 'LHEIGHT'=>15,'HHEIGHT'=>21);
    $notSetArray = array('MANGLIK',"COUNTRY_RES","EDU_LEVEL_NEW","OCCUPATION","CITY_RES");
    $dataArray[] = array('N', $d1,$verifyArray,$notSetArray,1);
    
    
    return $dataArray;
  }

  /**
   * @dataProvider dataProviderMaritalStatus
   */
  public function testMaritalStatus($MSTATUS,$trendsDataArray,$verifyArray, $notSetArray, $hasErrorTest) {
    $this->setSortParam();
    $this->setgetTableObj($trendsDataArray);
    $this->setgetProfileid();
    $this->setgetMSTATUS($MSTATUS);
    $m_status = $this->trendsBasedMatchAlertsObj->getDppCriteria($this->sort, $this->limit);
    if($hasErrorTest === NULL){
      $this->assertTrue($m_status === $hasErrorTest);
    }else{
      foreach($verifyArray as $key=>$field){
        $this->assertTrue(eval('return ($this->trendsBasedMatchAlertsObj->get'.$key.'() == $field);'));
      }
      foreach($notSetArray as $key=>$field){
        $this->assertTrue(eval('return ($this->trendsBasedMatchAlertsObj->get'.$field.'() == NULL);'));
      }
    }
  }

}
