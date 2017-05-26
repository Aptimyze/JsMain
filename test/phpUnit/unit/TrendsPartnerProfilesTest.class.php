<?php

/* tests to be covered for TrendsPartnerProfiles
 * CAll by Command phpunit --bootstrap test/phpUnit/bootstrap.php test/phpUnit/unit/TrendsPartnerProfilesTest.class.php
 */

class TrendsPartnerProfilesTest extends PHPUnit_Framework_TestCase {
  public $limit = 10;

  public static function setUpBeforeClass() {
    
  }

  public static function tearDownAfterClass() {
    
  }

  protected function tearDown() {
    
  }

  protected function setUp() {
    $this->profileObj = $this->getMockBuilder('LoggedInProfile')->disableOriginalConstructor()->getMock();
    $this->trendsBasedMatchAlertsObj = new TrendsPartnerProfiles($this->profileObj);
  }
  
  public function setgetMSTATUS($MSTATUS) {
    $this->profileObj->method('getMSTATUS')->with()->willReturn($MSTATUS);
  }

  public function dataProviderMaritalStatus() {
    $dataArray = array(
        array('M', false),
        array('N', 0),
        array('D', false),
        array('S', false)
    );
    return $dataArray;
  }

  /**
   * This function test SetMaritalStatus function that returns N if logged in user is never married and every other marital status except.
   * In this Test case we tested the position of character N i.e zero if present and false if not present in return string.
   * @param type $MSTATUS
   * @param type $hasErrorTest
   */
  /**
   * @dataProvider dataProviderMaritalStatus
   */
  public function testMaritalStatus($MSTATUS, $hasErrorTest) {
    $this->setgetMSTATUS($MSTATUS);
    $m_status = $this->trendsBasedMatchAlertsObj->setMaritalStatus();
    $this->assertTrue(strpos($m_status,'N') === $hasErrorTest);
  }
  
  public function dataProviderisManglik() {
    $dataArray = array(
        array(91, 10, true),
        array(10, 92, false),
        array(92, 91, true),
        array(50, 50, NULL),
    );
    return $dataArray;
  }

  /**
   * @dataProvider dataProviderisManglik
   */
  public function testisManglik($MANGLIK_M_P, $MANGLIK_N_P, $hasErrorTest) {
    $manglik = $this->trendsBasedMatchAlertsObj->isManglik($MANGLIK_M_P, $MANGLIK_N_P);
    $this->assertTrue($manglik === $hasErrorTest);
  }
  
  public function dataProvidersetCountry() {
    $dataArray = array(
        array(91, 10, false),
        array(91, 92, false),
        array(10, 92, true),
        array(50, 50, NULL),
    );
    return $dataArray;
  }

  /**
   * @dataProvider dataProvidersetCountry
   */
  public function testSetCountry($NRI_M_P, $NRI_N_P, $hasErrorTest) {
    $NRI = $this->trendsBasedMatchAlertsObj->setCountry($NRI_M_P, $NRI_N_P);
    $this->assertTrue($NRI === $hasErrorTest);
  }
  
  public function dataProviderOppositeGENDER() {
    $dataArray = array(
        array('M', 'F'),
        array('F', 'M')
    );
    return $dataArray;
  }
  /**
   * @dataProvider dataProviderOppositeGENDER
   */
  public function testsetOppositeGENDER($gender, $hasErrorTest) {
    $genderPOG = $this->trendsBasedMatchAlertsObj->setOppositeGENDER($gender);
    $this->assertTrue($genderPOG === $hasErrorTest);
  }
  
  public function dataProviderGetValue() {
    $dataArray = array(
        array(array(),'CASTE', NULL),
        array(array('CASTE_VALUE_PERCENTILE'=>''),'CASTE', NULL),
        array(array('CASTE_VALUE_PERCENTILE'=>'|10#4|20#6|31#19|49#5|'),'CASTE', '20 31'),
        array(array(),'MTONGUE', NULL),
        array(array('MTONGUE_VALUE_PERCENTILE'=>''),'MTONGUE', NULL),
        array(array('MTONGUE_VALUE_PERCENTILE'=>'|10#9|20#6|31#19|49#5|'),'MTONGUE', '10 20 31'),
        array(array(),'AGE', NULL),
        array(array('AGE_VALUE_PERCENTILE'=>''),'AGE', NULL),
        array(array('AGE_VALUE_PERCENTILE'=>'|22#9|20#6|31#19|49#5|71#2'),'AGE', array('31','20')),
        array(array(),'INCOME', NULL),
        array(array('INCOME_VALUE_PERCENTILE'=>''),'INCOME', NULL),
        array(array('INCOME_VALUE_PERCENTILE'=>'|10#9|20#6|31#19|49#5|'),'INCOME', '10 20 31'),
        array(array(),'HEIGHT', NULL),
        array(array('HEIGHT_VALUE_PERCENTILE'=>''),'HEIGHT', NULL),
        array(array('HEIGHT_VALUE_PERCENTILE'=>'|22#9|20#6|31#19|49#5|71#2'),'HEIGHT', array('31','20')),
        array(array(),'EDU_LEVEL_NEW', NULL),
        array(array('EDUCATION_VALUE_PERCENTILE'=>''),'EDU_LEVEL_NEW', NULL),
        array(array('EDUCATION_VALUE_PERCENTILE'=>'|10#9|20#6|31#19|49#5|'),'EDU_LEVEL_NEW', '10 20 31'),
        array(array(),'OCCUPATION', NULL),
        array(array('OCCUPATION_VALUE_PERCENTILE'=>''),'OCCUPATION', NULL),
        array(array('OCCUPATION_VALUE_PERCENTILE'=>'|10#9|20#6|31#19|49#5|'),'OCCUPATION', '10 20 31'),
        array(array(),'MTONGUE', NULL),
        array(array('CITY_VALUE_PERCENTILE'=>''),'CITY_RES', NULL),
        array(array('CITY_VALUE_PERCENTILE'=>'|10#9|20#6|31#19|49#6|'),'CITY_RES', '10 20 31 49'),
    );
    return $dataArray;
  }
  /**
   * @dataProvider dataProviderGetValue
   */
  public function testGetValue($dataArray,$key, $output) {
    $searchStr = $this->trendsBasedMatchAlertsObj->getValue($dataArray,$key);
    $this->assertTrue($searchStr === $output);
  }
  
  public function dataProviderSetForwardData() {
    $dataArray = array(
        array(array("W_CASTE"=>0.1,"W_MTONGUE"=>0.3,"W_AGE"=>0.1,"W_INCOME"=>0.3,"W_HEIGHT"=>0.09,"W_MSTATUS"=>0.01,"W_MANGLIK"=>0.05,"W_NRI"=>0.08,"W_EDUCATION"=>0.4,"W_OCCUPATION"=>0.6,"W_CITY"=>0.1), 'OCCUPATION')
    );
    return $dataArray;
  }
  /**
   * @dataProvider dataProvidersetForwardData
   */
  public function testSetForwardData($dataArray, $firstKey) {
    $resultArray = $this->trendsBasedMatchAlertsObj->setForwardData($dataArray);
    $this->assertTrue(key($resultArray) === $firstKey);
  }

}
