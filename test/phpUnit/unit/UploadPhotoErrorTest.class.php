<?php
/*tests to be covered for getProfilePic
*/
class UploadPhotoErrorTest extends PHPUnit_Framework_TestCase
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
	{/*
		$this->deletePhotoObj = new deletePhoto(self::$pictureid,self::$profileid);
		$this->picObj  = $this->getMockBuilder('NonScreenedPicture')->disableOriginalConstructor()->getMock();
		$this->pictureServiceObj  = $this->getMockBuilder('PictureService')->disableOriginalConstructor()->getMock();
		$this->pictureObj  = $this->getMockBuilder('Picture')->disableOriginalConstructor()->getMock();
*/
	}
	public function setPictureType($pictureType)
	{
		$this->picObj->method('getPictureType')->with()->willReturn($pictureType);
	}
	public function setProfilePicId($pictureid)
	{
		$this->pictureObj->method('getPictureId')->with()->willReturn($pictureid);
	}
	private function setOrdering($ordering)
	{
		$this->picObj->method('getORDERING')->with()->willReturn($ordering);
	}
        /**
        *function tearDown
        *call: this function is called after every test 
        *use: to clear data after each test
        **/
	protected function tearDown()
	{
		unset($this->deletePhotoObj);
	}
	/*
	*function: data provider for can delete with zero ordering and 0 and non zero number of photos
	*/
	public function dataProviderPicError()
	{
		$photoCount = array(
					array(array("error"=>0),false),
					array(array("error"=>1),true)
					);
		return $photoCount;
	}
	/**
	*function takes input from dataProvider function and and is specified using @  dataProvider annotation 
	*this is the test code executed starting with test*
	**/
        /**
        * @dataProvider dataProviderPicError
        */
	public function testPicError($image,$hasErrorTest)
	{
		$hasError = UploadPhoto::picError($image);
                $this->assertTrue($hasError==$hasErrorTest);
	}
        /*
        *function: data provider for can delete with zero ordering and 0 and non zero number of photos
        */
        public function dataProviderUnexpectedFormat()
        {
                $photoCount = array(
					array(IMAGETYPE_GIF,false),
					array(IMAGETYPE_JPEG,false),
					array(IMAGETYPE_PNG,true),
					array(IMAGETYPE_SWF,true),
					array(IMAGETYPE_PSD,true),
					array(IMAGETYPE_BMP,true),
					array(IMAGETYPE_TIFF_II,true),
					array(IMAGETYPE_TIFF_MM,true),
					array(IMAGETYPE_JPC,true),
					array(IMAGETYPE_JP2,true),
					array(IMAGETYPE_JPX,true),
					array(IMAGETYPE_JB2,true),
					array(IMAGETYPE_SWC,true),
					array(IMAGETYPE_IFF,true),
					array(IMAGETYPE_WBMP,true),
					array(IMAGETYPE_XBM,true),
					array(IMAGETYPE_ICO,true),
				);
                return $photoCount;
        }
        /**
        *function takes input from dataProvider function and and is specified using @  dataProvider annotation 
        *this is the test code executed starting with test*
        **/
        /**
        * @dataProvider dataProviderUnexpectedFormat
        */
        public function testUnexpectedFormat($typeOfImage,$hasErrorTest)
        {
                $hasError = UploadPhoto::unexpectedFormat($typeOfImage);
                $this->assertTrue($hasError==$hasErrorTest);
        }

        /*
        *function: data provider for can delete with zero ordering and 0 and non zero number of photos
        */
        public function dataProviderSizeLimitExceeded()
        {
                $photoCount = array(
                                        array(array("size"=>0),false),
                                        array(array("size"=>1),false),
                                        array(array("size"=>5291455),false),
                                        array(array("size"=>6291455),false),
                                        array(array("size"=>6291456),false),
                                        array(array("size"=>6291457),true),
                                        array(array("size"=>6291458),true),
                                        array(array("size"=>1111291458),true),
                                        );
                return $photoCount;
        }
        /**
        *function takes input from dataProvider function and and is specified using @  dataProvider annotation 
        *this is the test code executed starting with test*
        **/
        /**
        * @dataProvider dataProviderSizeLimitExceeded
        */
        public function testSizeLimitExceeded($image,$hasErrorTest)
        {
                $hasError = UploadPhoto::sizeLimitExceeded($image);
                $this->assertTrue($hasError==$hasErrorTest);
        }
        /*
        *function: data provider for can delete with zero ordering and 0 and non zero number of photos
        */
        public function dataProviderNumberOfPhotosInLimit()
        {
                $photoCount = array(
                                        array(1,true),
                                        array(2,true),
                                        array(18,true),
                                        array(19,true),
                                        array(20,false),
                                        array(21,false),
                                        );
                return $photoCount;
        }
        /**
        *function takes input from dataProvider function and and is specified using @  dataProvider annotation 
        *this is the test code executed starting with test*
        **/
        /**
        * @dataProvider dataProviderNumberOfPhotosInLimit
        */
        public function testNumberOfPhotosInLimit($picsInDb,$hasErrorTest)
        {
                $hasError = UploadPhoto::numberOfPhotosInLimit($picsInDb);
                $this->assertTrue($hasError==$hasErrorTest);
        }
	
} 
