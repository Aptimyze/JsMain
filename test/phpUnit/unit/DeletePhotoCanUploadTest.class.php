<?php
/*tests to be covered for getProfilePic
*/
class DeletePhotoCanUploadTest extends PHPUnit_Framework_TestCase
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
		$this->deletePhotoObj = new deletePhoto(self::$pictureid,self::$profileid);
		$this->picObj  = $this->getMockBuilder('NonScreenedPicture')->disableOriginalConstructor()->getMock();
		$this->pictureServiceObj  = $this->getMockBuilder('PictureService')->disableOriginalConstructor()->getMock();
		$this->pictureObj  = $this->getMockBuilder('NonScreenedPicture')->disableOriginalConstructor()->getMock();
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
	/**
	*function takes input from dataProvider function and and is specified using @  dataProvider annotation 
	*this is the test code executed starting with test*
	**/
        /**
        * @dataProvider dataProvider
        */
	public function testCanDeleteNonZeroOrdering($photoCount,$canDelFlag,$profilePicDelete,$screenedPicDel)
	{
		$this->setOrdering($order = 1);
		$canDelete = $this->deletePhotoObj->canDelete($this->picObj,$photoCount,'');
                $this->assertTrue($canDelete['CAN_DELETE']==$canDelFlag);
                $this->assertTrue($canDelete['PROFILE_PIC_DELETE']==$profilePicDelete);
                $this->assertTrue($canDelete['SCREENED_PROFILE_PIC_DELETE']==$screenedPicDel);
	}
	public function testCanDeleteZeroOrderingZeroPhoto()
	{
		$this->setOrdering($order = 0);
		$canDelete = $this->deletePhotoObj->canDelete($this->picObj,$photoCount=0,'');
                $this->assertTrue($canDelete['CAN_DELETE']==false);
                $this->assertTrue($canDelete['PROFILE_PIC_DELETE']==false);
                $this->assertTrue($canDelete['SCREENED_PROFILE_PIC_DELETE']==false);
	}
	public function testCanDeleteZeroOrderingOnePhoto()
        {
                $this->setOrdering($order = 0);
                $canDelete = $this->deletePhotoObj->canDelete($this->picObj,$photoCount=1,'');
                $this->assertTrue($canDelete['CAN_DELETE']==true);
                $this->assertTrue($canDelete['PROFILE_PIC_DELETE']==true);
                $this->assertTrue($canDelete['SCREENED_PROFILE_PIC_DELETE']==false);
        }
        /**
        * @dataProvider dataProvider1
        */
	public function testCanDeleteZeroOrderMultiPhotos($pictureType,$pictureid,$canDelFlag,$profilePicDelete,$screenedPicDel)
        {
                $this->setOrdering($order = 0);
		$this->setPictureType($pictureType);
		$this->setProfilePicId($pictureid);
		$this->pictureObj->method('getPictureId')->with()->willReturn($pictureid);
                $canDelete = $this->deletePhotoObj->canDelete($this->picObj,$photoCount=2,$this->pictureObj);
                $this->assertTrue($canDelete['CAN_DELETE']==$canDelFlag);
                $this->assertTrue($canDelete['PROFILE_PIC_DELETE']==$profilePicDelete);
                $this->assertTrue($canDelete['SCREENED_PROFILE_PIC_DELETE']==$screenedPicDel);
        }
        /**
        * @dataProvider dataProviderException
        */
	public function testException($pictureType,$pictureid)
	{
		$this->setExpectedException('jsException');
                $this->setOrdering($order = 0);
                $this->setPictureType($pictureType);
                $canDelete = $this->deletePhotoObj->canDelete($this->picObj,$photoCount=2,'');
	}
	/*
	*function: data provider for can delete with zero ordering and 0 and non zero number of photos
	*/
	public function dataProvider()
	{
		$photoCount = array(array(0,false,false,false),array(1,true,false,false),array(2,true,false,false));
		return $photoCount;
		
	}
	public function dataProvider1()
	{
		$array = array(
				array("S",self::$pictureid,false,false,false),
				array("S","12456",true,false,true),
				array("N",self::$pictureid,false,false,false),
				array("N","12345",false,false,false));
		return $array;
	}
	public function dataProviderException()
	{
		$array = array(
				array("S",self::$pictureid),
				array("S","12456"));
		return $array;
		
	}
} 
