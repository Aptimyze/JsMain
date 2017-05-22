<?php
/*tests to be covered for getProfilePic
*/
class UploadPhotoGetActualTypeTest extends PHPUnit_Framework_TestCase
{
	public static $profileid = "123";
	public static $pictureid = "1234";

	/**
	* function setUpBeforeClass
	* call: this function is called before the first test of the test case class is run once
	* use: for intial setup of test environment 
	*/
	public static function setUpBeforeClass()
	{
	}
	
        /**
        * function tearDownAfterClass
        * call: this function is called after the last test of the test case class is run
        * use: for garbage collection/reseting of test environment 
        */
	public static function tearDownAfterClass()
	{
	}
	/**
	*function setUp
	*call: this function is called before every test 
	*use: to create data and set object against which testing is done
	**/
	protected function setUp()
	{
                $this->profileObj = $this->getMockBuilder('LoggedInProfile')->disableOriginalConstructor()->getMock();
                $this->profileObj->method('getPROFILEID')->with()->willReturn(self::$profileid);
	}

	private function setUploadPhotoObj($uploadSource)
	{
                $this->uploadPhotoObj = new UploadPhoto($this->profileObj,$uploadSource);
	}
        /**
        *function tearDown
        *call: this function is called after every test 
        *use: to clear data after each test
        **/
	protected function tearDown()
	{
		unset($this->uploadPhotoObj);
	}
	/*
	*function: data provider for can delete with zero ordering and 0 and non zero number of photos
	*/
	public function dataProviderCheckSourceValue()
	{
		$photoCount = array(
					array("image/jpeg","jpeg"),
					array("image/pjpeg","jpeg"),
					array("image/jpg","jpg"),
					array("image/gif","gif"),
					);
		return $photoCount;
	}
	/**
	*function takes input from dataProvider function and and is specified using @  dataProvider annotation 
	*this is the test code executed starting with test*
	**/
        /**
        * @dataProvider dataProviderCheckSourceValue
        */
	public function testCheckSourceValue($imageType,$actualTypeTest)
	{
		$this->setUploadPhotoObj($uploadSource);
		$actualType = $this->uploadPhotoObj->getActualType($imageType);
                $this->assertTrue($actualType==$actualTypeTest);
	}
} 
