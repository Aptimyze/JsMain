<?php

/* tests to be covered for getProfilePic
 */

class SetProfilePicTest extends PHPUnit_Framework_TestCase {

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
    $this->setProfilePicObj = new SetProfilePic($this->newPicObj, self::$profileid, $this->profileObj, $this->source);
    $this->setProfilePicId();
  }

  public function setNonScreenedMockClass() {
    $this->PICTURE_FOR_SCREEN_NEW = $this->getMockBuilder('NonScreenedPicture')->disableOriginalConstructor()->getMock();
  }

  public function setProfilePicId() {
    $this->currentProfilePicObj->method('getPICTUREID')->with()->willReturn(self::$pictureid);
  }

  public function setProfilePicIdNew($pictureId) {
    $this->newPicObj->method('getPICTUREID')->with()->willReturn($pictureId);
  }

  public function setPictureType($pictureType) {
    $this->newPicObj->method('getPictureType')->with()->willReturn($pictureType);
  }

  public function setGetArrayData($pictureArray) {
    $this->PICTURE_FOR_SCREEN_NEW->method('get')->with()->willReturn($pictureArray);
  }

  public function dataProviderSamePic() {
    $dataArray = array(
        array('', false),
        array(12345, false),
        array(1234, true)
    );
    return $dataArray;
  }

  /**
   * @dataProvider dataProviderSamePic
   */
  public function testcheckSamePic($pictureId, $hasErrorTest) {
    $this->setProfilePicIdNew($pictureId);
    $hasError = $this->setProfilePicObj->checkSamePic($this->currentProfilePicObj);
    $this->assertTrue($hasError == $hasErrorTest);
  }

  public function dataProviderNewPicScreenStatus() {
    $dataArray = array(
        array('N', 0),
        array('S', 1)
    );
    return $dataArray;
  }

  /**
   * @dataProvider dataProviderNewPicScreenStatus
   */
  public function testgetNewPicScreenStatus($pictureType, $hasErrorTest) {
    $this->setPictureType($pictureType);
    $hasError = $this->setProfilePicObj->getNewPicScreenStatus();
    $this->assertTrue($hasError == $hasErrorTest);
  }

  public function dataPictureExistInNonScreen() {
    $dataArray = array(
        array(array(), 1),
        array(array(0 => ''), 1),
        array(array(0 => array()), 0)
    );
    return $dataArray;
  }

  /**
   * @dataProvider dataPictureExistInNonScreen
   */
  public function testcheckPictureExistInNonScreen($getArray, $hasErrorTest) {
    $this->setNonScreenedMockClass();
    $this->setGetArrayData($getArray);
    $hasError = $this->setProfilePicObj->checkPictureExistInNonScreen(self::$pictureid, self::$profileid, $this->PICTURE_FOR_SCREEN_NEW);
    $this->assertTrue($hasError == $hasErrorTest);
  }

  public function dataCurrentProfilePicScreenStatus() {
    $dataArray = array(
        array(array(), 1),
        array(array(0 => ''), 1),
        array(array(0 => array()), 0)
    );
    return $dataArray;
  }

  /**
   * @dataProvider dataCurrentProfilePicScreenStatus
   */
  public function testgetCurrentProfilePicScreenStatus($getArray, $hasErrorTest) {
    $this->setNonScreenedMockClass();
    $this->setGetArrayData($getArray);
    $hasError = $this->setProfilePicObj->getCurrentProfilePicScreenStatus(self::$pictureid, $this->PICTURE_FOR_SCREEN_NEW);
    $this->assertTrue($hasError == $hasErrorTest);
  }

  public function setArrayData($dataArray) {
    $this->newPicObj->method('getMainPicUrl')->with(1)->willReturn($dataArray['MainPicUrl']);
    $this->newPicObj->method('getOriginalPicUrl')->with(1)->willReturn($dataArray['OriginalPicUrl']);
    $this->newPicObj->method('getProfilePic120Url')->with(1)->willReturn($dataArray['ProfilePic120Url']);
    $this->newPicObj->method('getProfilePic235Url')->with(1)->willReturn($dataArray['ProfilePic235Url']);
    $this->newPicObj->method('getProfilePicUrl')->with(1)->willReturn($dataArray['ProfilePicUrl']);
    $this->newPicObj->method('getProfilePic450Url')->with(1)->willReturn($dataArray['ProfilePic450Url']);
    $this->newPicObj->method('getMobileAppPicUrl')->with(1)->willReturn($dataArray['MobileAppPicUrl']);
    $this->newPicObj->method('getThumbail96Url')->with(1)->willReturn($dataArray['Thumbail96Url']);
    $this->newPicObj->method('getTITLE')->with(1)->willReturn($dataArray['TITLE']);
    $this->newPicObj->method('getKEYWORD')->with(1)->willReturn($dataArray['KEYWORD']);
    $this->newPicObj->method('getPICTUREID')->with(1)->willReturn($dataArray['PICTUREID']);
    $this->newPicObj->method('getORDERING')->with(1)->willReturn($dataArray['ORDERING']);
    $this->newPicObj->method('getPROFILEID')->with(1)->willReturn($dataArray['PROFILEID']);
    $this->newPicObj->method('getPICFORMAT')->with(1)->willReturn($dataArray['PICFORMAT']);
  }

  public function dataGetNonScreenedObjectArray() {
    $dataArray = array(array(array("MainPicUrl" => "Main URL", "OriginalPicUrl" => "Original URL", "ProfilePic120Url" => "Profile Pic 120 URL",
                "ProfilePic235Url" => "Profile Pic 235 Url", "ProfilePicUrl" => "Profile Pic Url", "ProfilePic450Url" => "Profile Pic 450 Url",
                "MobileAppPicUrl" => "Mobile App Pic Url", "Thumbail96Url" => "Thumbail 96 Url", "TITLE" => "TITLE", "KEYWORD" => "KEYWORD",
                "PICTUREID" => "PICTUREID", "ORDERING" => "ORDERING", "PROFILEID" => "PROFILEID", "PICFORMAT" => "PICFORMAT")));
    return $dataArray;
  }

  /**
   * @dataProvider dataGetNonScreenedObjectArray
   */
  public function testgetNonScreenedObjectArray($dataFields) {    
    $this->setArrayData($dataFields);
    $hasError = $this->setProfilePicObj->getNonScreenedObjectArray();
    $arrayDiff = array_diff($dataFields, $hasError);
    $this->assertTrue(count($arrayDiff)<=0);
  }
}
