<?php
/*tests to be covered for getProfilePic
*/
class UploadPhotoFacebookTest extends PHPUnit_Framework_TestCase
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

		$this->picObj  = $this->getMockBuilder('NonScreenedPicture')->disableOriginalConstructor()->getMock();

		$this->pictureServiceObj  = $this->getMockBuilder('PictureService')->disableOriginalConstructor()->getMock();

		$this->nonScreenedPicObj  = $this->getMockBuilder('NonScreenedPicture')->disableOriginalConstructor()->getMock();
	}
        /**
        *function tearDown
        *call: this function is called after every test 
        *use: to clear data after each test
        **/
        protected function tearDown()
        {
                unset($this->uploadPhotoObj);
                unset($this->pictureForScreenNewObj);
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

	private function setUploadPhotoObj($uploadSource,$canUploadFunctionWillReturn)
	{
		$this->uploadPhotoObj = $this->getMockBuilder('UploadPhoto')->setConstructorArgs(array($this->profileObj,$uploadSource))->setMethods(array('getFacebookActualType','getUserUploadedPictureCount','getPicCopyData','getPicContent','copypicLinkImage','generateImages','getLockObj','generatePictureArray','setDetail','addPhotos','releaseLockObj','saveImageDeatils','track1stUnscreenedPhoto','importUploadTracking'))->getMock();
		
	}
	private function setStubs($returnSettingData)
	{
		$this->uploadPhotoObj->method('getFacebookActualType')->with()->willReturn($returnSettingData['getFacebookActualType']);
		$this->uploadPhotoObj->method('getUserUploadedPictureCount')->with()->willReturn($returnSettingData['getUserUploadedPictureCount']);
		$this->uploadPhotoObj->method('getPicCopyData')->with()->willReturn($returnSettingData['getPicCopyData']);
		$this->uploadPhotoObj->method('getPicContent')->with()->willReturn($returnSettingData['getPicContent']);
		$this->uploadPhotoObj->method('copypicLinkImage')->with()->willReturn($returnSettingData['copypicLinkImage']);
		$this->uploadPhotoObj->method('generateImages')->with()->willReturn(true);
		$this->uploadPhotoObj->method('getLockObj')->with()->willReturn(true);
		$this->uploadPhotoObj->method('generatePictureArray')->with()->willReturn($returnSettingData['generatePictureArray']);
		$this->uploadPhotoObj->method('setDetail')->with()->willReturn(true);
		$this->uploadPhotoObj->method('addPhotos')->with()->willReturn(true);
		$this->uploadPhotoObj->method('releaseLockObj')->with()->willReturn(true);
		$this->uploadPhotoObj->method('saveImageDeatils')->with()->willReturn(true);
		$this->uploadPhotoObj->method('track1stUnscreenedPhoto')->with()->willReturn(true);
		$this->uploadPhotoObj->method('importUploadTracking')->with()->willReturn(true);
	}
	
        public function dataProviderFacebook()
        {
                $getUserUploadedPictureCountData = array("correct"=>10,"incorrect"=>"20");
                $getPicCopyData = array("correct"=>array("PIC_ID"=>"pic1"),"incorrect"=>false);
                $getFacebookActualTypeData = array("correct1"=>"jpeg","correct2"=>"gif","correct3"=>"jpg","incorrect"=>"png");
		$fileCoppiedData = array("coppied"=>true,"notCoppied"=>false);
		$generatePictureArrayData = array("correct"=>array("ORDERING"=>0),"incorrect"=>array("ORDERING"=>21));

		$facebookData = 
			array
			(
				array(
					array("fileData"=>"sdcsdcsc","importSite"=>"facebook"),
					array('getFacebookActualType'=>$getFacebookActualTypeData['correct1'],
						'getUserUploadedPictureCount'=>$getUserUploadedPictureCountData['correct'],
						'getPicCopyData'=>$getPicCopyData['correct'],
						"getPicContent"=>"sdsds",
						"copypicLinkImage"=>$fileCoppiedData['coppied'],
						"generatePictureArray"=>$generatePictureArrayData['correct'],
					),
					$getPicCopyData['correct'],
				),
                                array(
                                        array("fileData"=>"sdcsdcsc","importSite"=>"facebook"),
                                        array('getFacebookActualType'=>$getFacebookActualTypeData['correct2'],
                                                'getUserUploadedPictureCount'=>$getUserUploadedPictureCountData['correct'],
                                                'getPicCopyData'=>$getPicCopyData['correct'],
                                                "getPicContent"=>"sdsds",
                                                "copypicLinkImage"=>$fileCoppiedData['coppied'],
                                                "generatePictureArray"=>$generatePictureArrayData['correct'],
                                        ),
                                        $getPicCopyData['correct'],
                                ),
                                array(
                                        array("fileData"=>"sdcsdcsc","importSite"=>"facebook"),
                                        array('getFacebookActualType'=>$getFacebookActualTypeData['correct3'],
                                                'getUserUploadedPictureCount'=>$getUserUploadedPictureCountData['correct'],
                                                'getPicCopyData'=>$getPicCopyData['correct'],
                                                "getPicContent"=>"sdsds",
                                                "copypicLinkImage"=>$fileCoppiedData['coppied'],
                                                "generatePictureArray"=>$generatePictureArrayData['correct'],
                                        ),
                                        $getPicCopyData['correct'],
                                ),
                                array(
                                        array("fileData"=>"sdcsdcsc","importSite"=>"facebook"),
                                        array('getFacebookActualType'=>$getFacebookActualTypeData['incorrect'],
                                                'getUserUploadedPictureCount'=>$getUserUploadedPictureCountData['correct'],
                                                'getPicCopyData'=>$getPicCopyData['correct'],
                                                "getPicContent"=>"sdsds",
                                                "copypicLinkImage"=>$fileCoppiedData['coppied'],
                                                "generatePictureArray"=>$generatePictureArrayData['correct'],
                                        ),
                                        $getPicCopyData['incorrect'],
                                ),
                                array(
                                        array("fileData"=>"sdcsdcsc","importSite"=>"facebook"),
                                        array('getFacebookActualType'=>$getFacebookActualTypeData['correct1'],
                                                'getUserUploadedPictureCount'=>$getUserUploadedPictureCountData['correct'],
                                                'getPicCopyData'=>$getPicCopyData['correct'],
                                                "getPicContent"=>"sdsds",
                                                "copypicLinkImage"=>$fileCoppiedData['notCoppied'],
                                                "generatePictureArray"=>$generatePictureArrayData['correct'],
                                        ),
                                        $getPicCopyData['incorrect'],
                                ),
                                array(
                                        array("fileData"=>"sdcsdcsc","importSite"=>"facebook"),
                                        array('getFacebookActualType'=>$getFacebookActualTypeData['correct1'],
                                                'getUserUploadedPictureCount'=>$getUserUploadedPictureCountData['correct'],
                                                'getPicCopyData'=>$getPicCopyData['correct'],
                                                "getPicContent"=>"sdsds",
                                                "copypicLinkImage"=>$fileCoppiedData['coppied'],
                                                "generatePictureArray"=>$generatePictureArrayData['incorrect'],
                                        ),
                                        $getPicCopyData['incorrect'],
                                ),

			);
					
		return $facebookData;
	}
        /**
        *function takes input from dataProvider function and and is specified using @  dataProvider annotation 
        *this is the test code executed starting with test*
        **/
        /**
        * @dataProvider dataProviderFacebook
        */
        public function testCanUpload($uploadData,$returnSettingData,$resultData)
        {
		$this->setUploadPhotoObj("facebook");
		$this->setStubs($returnSettingData);
		$result =  $this->uploadPhotoObj->picLinkUpload('','',$uploadData['fileData'],$uploadData['importSite'],$this->nonScreenedPicObj);
		if(is_array($resultData))
		{
			foreach($result as $k=>$v)
				$this->assertTrue($v==$resultData[$k]);
			foreach($resultData as $k=>$v)
				$this->assertTrue($v==$result[$k]);
		}
		else
		{
			$this->assertTrue($result==$resultData);
		}
        }
} 
