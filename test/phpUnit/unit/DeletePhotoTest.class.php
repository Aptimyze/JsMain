<?php
/*tests to be covered for getProfilePic
*/
class DeletePhotoTest extends PHPUnit_Framework_TestCase
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
		$this->deletePhotoObj = $this->getMockBuilder('deletePhoto')->setConstructorArgs(array(self::$pictureid,self::$profileid))->setMethods(array('getPicDetails'))->getMock();
                $this->picObj  = $this->getMockBuilder('NonScreenedPicture')->disableOriginalConstructor()->getMock();
                $this->pictureServiceObj  = $this->getMockBuilder('PictureService')->disableOriginalConstructor()->getMock();
                $this->pictureObj  = $this->getMockBuilder('NonScreenedPicture')->disableOriginalConstructor()->getMock();
	}
        public function setPictureType($pictureType)
        {
                $this->picObj->method('getPictureType')->with()->willReturn($pictureType);
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
        */
	public function testGetWhereCondition()
	{
		$whereCondition = $this->deletePhotoObj->getWhereCondition();
                $this->assertTrue($whereCondition['PICTUREID']==self::$pictureid);
                $this->assertTrue($whereCondition['PROFILEID']==self::$profileid);
	}
	public function testGetPictureObject()
	{
                $this->deletePhotoObj->method('getPicDetails')->with()->willReturn(array("e1"));
		$picObj = $this->deletePhotoObj->getPictureObject();
		$this->assertTrue($picObj =="e1");
	}
        /*
        *function: data provider for can delete with zero ordering and 0 and non zero number of photos
        */
        public function dataProvider()
        {
                $photoCount = array(
					array("N",array("photoObj"=>"NonScreenedPicture","otherPhotoObj"=>"ScreenedPicture")),
					array("S",array("photoObj"=>"ScreenedPicture","otherPhotoObj"=>"NonScreenedPicture")),
					);
                return $photoCount;

        }
        /**
        * @dataProvider dataProvider
        */
	public function testGetPhotoObjects($picObjData,$resultData)
	{
                $this->picObj->method('getPictureType')->with()->willReturn($picObjData);
		$photoObjs = $this->deletePhotoObj->getPhotoObjects($this->picObj);
		$this->assertTrue($resultData['photoObj']==get_class($photoObjs['photoObj']));
		$this->assertTrue($resultData['otherPhotoObj']==get_class($photoObjs['otherPhotoObj']));
	}

        /*
        *function: data provider for can delete with zero ordering and 0 and non zero number of photos
        */
        public function dataProvider1()
        {
                $photoCount = array(
                                        array(array(),true),
                                        array(false,true),
                                        );
                return $photoCount;

        }
        /**
        * @dataProvider dataProvider1
        */
        public function testTrackDeletePictureDetails($photoObjData,$resultData)
        {
                $this->picObj->method('get')->with()->willReturn($photoObjData);
                $result = $this->deletePhotoObj->trackDeletePictureDetails($this->picObj);
                $this->assertTrue($result == $resultData);
        }
        public function testTrackDeletePhoto()
        {
                $result = $this->deletePhotoObj->trackDeletePhoto("a");
                $this->assertTrue($result == true);
        }  
        /*
        *function: data provider for can delete with zero ordering and 0 and non zero number of photos
        */
        public function dataProvider2()
        {
                $photoCount = array(
                                        array(false,false,false),
                                        array(false,true,true),
                                        array(true,false,true),
                                        array(true,true,true),
                                        );
                return $photoCount;

        }
        /**
        * @dataProvider dataProvider2
        */

	public function testDelete($status,$otherStatus,$resultData)
	{
		$this->picObj->method('del')->with()->willReturn($status);
		$this->pictureObj->method('del')->with()->willReturn($otherStatus);
		$result = $this->deletePhotoObj->delete($this->picObj,$this->pictureObj);
                $this->assertTrue($result == $resultData);
	}
} 
