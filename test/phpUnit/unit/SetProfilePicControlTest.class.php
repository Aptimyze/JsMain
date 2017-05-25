<?php

/* tests to be covered for getProfilePic
 */

class SetProfilePicControlTest extends PHPUnit_Framework_TestCase {

  public static $profileid = "123";
  public static $pictureid = "1234";
  public $source = '';

  public static function setUpBeforeClass() {
    
  }

  public static function tearDownAfterClass() {
    
  }

  protected function tearDown() {
    
  }

  protected function setUp() {
    $this->newPicObj = $this->getMockBuilder('ScreenedPicture')->disableOriginalConstructor()->getMock();
    $this->profileObj = $this->getMockBuilder('LoggedInProfile')->disableOriginalConstructor()->getMock();
    $this->currentProfilePicObj = $this->getMockBuilder('NonScreenedPicture')->disableOriginalConstructor()->getMock();
    $this->setProfilePicObj = $this->getMockBuilder('SetProfilePic')->setConstructorArgs(array($this->newPicObj, self::$profileid, $this->profileObj, $this->source))->setMethods(array('getNonScreenedProfilePicObj','checkSamePic','getCurrentProfilePicScreenStatus','getNewPicScreenStatus','nonScreenedToNonScreened','nonScreenedToScreened','screenedToNonScreened','screenedToScreened'))->getMock();
  }
  public function setNonScreenedProfilePicObj() {
    $this->setProfilePicObj->method('getNonScreenedProfilePicObj')->with()->willReturn($this->currentProfilePicObj);
  }
  public function setcheckSamePic($isSamePic) {
    $this->setProfilePicObj->method('checkSamePic')->with()->willReturn($isSamePic);
  }
  public function setCurrentProfilePicScreenStatus($currentValue) {
    $this->setProfilePicObj->method('getCurrentProfilePicScreenStatus')->with()->willReturn($currentValue);
  }
  public function setNewPicScreenStatus($newPicValue) {
    $this->setProfilePicObj->method('getNewPicScreenStatus')->with()->willReturn($newPicValue);
  }
  public function setCaseFunctions(){
    $this->setProfilePicObj->method('nonScreenedToNonScreened')->with()->willReturn(1);
    $this->setProfilePicObj->method('screenedToNonScreened')->with()->willReturn(1);
    $this->setProfilePicObj->method('screenedToScreened')->with()->willReturn(1);
    $this->setProfilePicObj->method('nonScreenedToScreened')->with()->willReturn(1);
    
  }
  public function dataProviderProfilePicControl() {
    $dataArray = array(
        array(true,1,1,true),
        array(false,0,0,true),
        array(false,0,1,true),
        array(false,1,0,true),
        array(false,1,1,true)
    );
    return $dataArray;
  }

  /**
   * @dataProvider dataProviderProfilePicControl
   */
  public function testsetProfilePicControl($isSamePic, $currentValue, $newPicValue, $hasErrorTest) {
    $this->setNonScreenedProfilePicObj();
    $this->setCaseFunctions();
    $this->setcheckSamePic($isSamePic);
    $this->setCurrentProfilePicScreenStatus($currentValue);
    $this->setNewPicScreenStatus($newPicValue);
    $hasError = $this->setProfilePicObj->setProfilePicControl();
    $this->assertTrue($hasError == $hasErrorTest);
  }
}
