<?php
/*tests to be covered for getProfilePic
*/
class UploadPhotoChannelUploadTest extends PHPUnit_Framework_TestCase
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
		$this->uploadPhotoObj = $this->getMockBuilder('UploadPhoto')->setConstructorArgs(array($this->profileObj,$uploadSource))->setMethods(array('canUpload','copyPic','generatePictureArray','importUploadTracking','addPhotos','getUserUploadedPictureCount','generateImages','saveImageDeatils','track1stUnscreenedPhoto','oldMobileDataConversion'))->getMock();
		
	}
	private function setStubs($returnSettingData)
	{
		$this->uploadPhotoObj->method('canUpload')->with()->willReturn($returnSettingData['canUpload']);
		$this->uploadPhotoObj->method('copyPic')->with()->willReturn($returnSettingData['copyPic']);
		$this->uploadPhotoObj->method('generatePictureArray')->with()->willReturn($returnSettingData['generatePictureArray']);
		$this->uploadPhotoObj->method('importUploadTracking')->with()->willReturn(true);
		$this->uploadPhotoObj->method('addPhotos')->with()->willReturn($returnSettingData['addPhotos']);
		$this->uploadPhotoObj->method('getUserUploadedPictureCount')->with()->willReturn($returnSettingData['getUserUploadedPictureCount']);
		$this->uploadPhotoObj->method('generateImages')->with()->willReturn(true);
		$this->uploadPhotoObj->method('saveImageDeatils')->with()->willReturn(true);
		$this->uploadPhotoObj->method('track1stUnscreenedPhoto')->with()->willReturn(true);
		$this->uploadPhotoObj->method('oldMobileDataConversion')->with()->willReturn($returnSettingData['oldMobileDataConversion']);
		$this->nonScreenedPicObj->method('setDetail')->with()->willReturn(true);
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
	
        /*
        *function: data provider for can delete with zero ordering and 0 and non zero number of photos
        */
        public function dataProviderCanUpload()
        {
		$imageArray = array("correct"=>array(array("name"=>"sdcknsdk")),"incorrectName"=>array(array("name"=>"")));
		$copyPicData = array("correct"=>true,"incorrect"=>array('ErrorCounter'=>1));
		$generatePictureArrayData = array("correct"=>"","incorrect"=>"");
		$addPhotosData = array("correct"=>true,"incorrect"=>false);
		$errorData = array(
			"incorrectName"=>array("ErrorCounter"=>0,"SizeErrorCounter"=>0,"FormatErrorCounter"=>0,"ActualFiles"=>0,"MaxCountError"=>false),
			"incorrectMaxCount"=>array("ErrorCounter"=>0,"SizeErrorCounter"=>0,"FormatErrorCounter"=>0,"ActualFiles"=>0,"MaxCountError"=>true),
			"incorrectSize"=>array("ErrorCounter"=>1,"SizeErrorCounter"=>1,"FormatErrorCounter"=>0,"ActualFiles"=>1,"MaxCountError"=>false),
			"incorrectFormat"=>array("ErrorCounter"=>1,"SizeErrorCounter"=>0,"FormatErrorCounter"=>1,"ActualFiles"=>1,"MaxCountError"=>false),
			"incorrectPicError"=>array("ErrorCounter"=>1,"SizeErrorCounter"=>0,"FormatErrorCounter"=>0,"ActualFiles"=>1,"MaxCountError"=>false),
			"correct"=>array("ErrorCounter"=>0,"SizeErrorCounter"=>0,"FormatErrorCounter"=>0,"ActualFiles"=>1,"MaxCountError"=>false),
			);
		$canUploadData = array(
			"incorrectName"=>array("ErrorCounter"=>0,"SizeErrorCounter"=>0,"FormatErrorCounter"=>0,"ActualFiles"=>0,"MaxCountError"=>false),
			"incorrectSize"=>array("ErrorCounter"=>1,"SizeErrorCounter"=>1,"FormatErrorCounter"=>0,"ActualFiles"=>0,"MaxCountError"=>false),
			"incorrectFormat"=>array("ErrorCounter"=>1,"SizeErrorCounter"=>0,"FormatErrorCounter"=>1,"ActualFiles"=>0,"MaxCountError"=>false),
			"incorrectPicError"=>array("ErrorCounter"=>1,"SizeErrorCounter"=>0,"FormatErrorCounter"=>0,"ActualFiles"=>0,"MaxCountError"=>false),
			"correct"=>array("ErrorCounter"=>0,"SizeErrorCounter"=>0,"FormatErrorCounter"=>0,"ActualFiles"=>1,"MaxCountError"=>false),
				);
		$getUserUploadedPictureCountData = array("correct"=>10,"incorrect"=>"20");
		$oldMobileDataConversionData = array("correct"=>$imageArray['correct'],'incorrectName'=>$imageArray['incorrectName']);
		$uploadSource = array("computer_noFlash","appPicsCamera","iOSPicsCamera","iOSPicsGallery","mobPicsGallery","appPicsGallery","appPicsUpload",'mobileUpload');
		foreach($uploadSource as $x=>$y)
		{
			$uploadSource = $y;
                $photoCount = array(
                                array(
                                        array("UPLOAD_SOURCE"=>$y,"IMAGE"=> $imageArray['incorrectName'],"HAVEPHOTO_BEFORE_UPLOAD"=>'',"FILEDATA"=>"","IMPORT_SITE"=>''),
                                        array("canUpload"=>true,'copyPic'=>$copyPicData['correct'],'generatePictureArray'=>$generatePictureArrayData['correct'],'addPhotos'=>$addPhotosData['correct'],'getUserUploadedPictureCount'=>$getUserUploadedPictureCountData['correct'],'oldMobileDataConversion'=>$oldMobileDataConversionData['incorrectName']),
                                        $errorData['incorrectName'],
                                        ),
                                array(
                                        array("UPLOAD_SOURCE"=>$y,"IMAGE"=> $imageArray['correct'],"HAVEPHOTO_BEFORE_UPLOAD"=>'',"FILEDATA"=>"","IMPORT_SITE"=>''),
                                        array("canUpload"=>true,'copyPic'=>$copyPicData['correct'],'generatePictureArray'=>$generatePictureArrayData['correct'],'addPhotos'=>$addPhotosData['correct'],'getUserUploadedPictureCount'=>$getUserUploadedPictureCountData['incorrect'],'oldMobileDataConversion'=>$oldMobileDataConversionData['correct']),
                                        $errorData['incorrectMaxCount'],
					),
                                array(
                                        array("UPLOAD_SOURCE"=>$y,"IMAGE"=> $imageArray['correct'],"HAVEPHOTO_BEFORE_UPLOAD"=>'',"FILEDATA"=>"","IMPORT_SITE"=>''),
                                        array("canUpload"=>$canUploadData['incorrectSize'],'copyPic'=>$copyPicData['correct'],'generatePictureArray'=>$generatePictureArrayData['correct'],'addPhotos'=>$addPhotosData['correct'],'getUserUploadedPictureCount'=>$getUserUploadedPictureCountData['correct'],'oldMobileDataConversion'=>$oldMobileDataConversionData['correct']),
                                        $errorData['incorrectSize'],

                                        ),
                                array(
                                        array("UPLOAD_SOURCE"=>$y,"IMAGE"=> $imageArray['correct'],"HAVEPHOTO_BEFORE_UPLOAD"=>'',"FILEDATA"=>"","IMPORT_SITE"=>''),
                                        array("canUpload"=>$canUploadData['incorrectFormat'],'copyPic'=>$copyPicData['correct'],'generatePictureArray'=>$generatePictureArrayData['correct'],'addPhotos'=>$addPhotosData['correct'],'getUserUploadedPictureCount'=>$getUserUploadedPictureCountData['correct'],'oldMobileDataConversion'=>$oldMobileDataConversionData['correct']),
                                        $errorData['incorrectFormat'],

                                        ),
                                array(
                                        array("UPLOAD_SOURCE"=>$y,"IMAGE"=> $imageArray['correct'],"HAVEPHOTO_BEFORE_UPLOAD"=>'',"FILEDATA"=>"","IMPORT_SITE"=>''),
                                        array("canUpload"=>$canUploadData['incorrectPicError'],'copyPic'=>$copyPicData['correct'],'generatePictureArray'=>$generatePictureArrayData['correct'],'addPhotos'=>$addPhotosData['correct'],'getUserUploadedPictureCount'=>$getUserUploadedPictureCountData['correct'],'oldMobileDataConversion'=>$oldMobileDataConversionData['correct']),
                                        $errorData['incorrectPicError'],

                                        ),
				array(
					array("UPLOAD_SOURCE"=>$y,"IMAGE"=> $imageArray['correct'],"HAVEPHOTO_BEFORE_UPLOAD"=>'',"FILEDATA"=>"","IMPORT_SITE"=>''),
					array("canUpload"=>true,'copyPic'=>$copyPicData['incorrect'],'generatePictureArray'=>$generatePictureArrayData['correct'],'addPhotos'=>$addPhotosData['correct'],'oldMobileDataConversion'=>$oldMobileDataConversionData['correct']),
					$errorData['incorrectPicError'],
					),
                                array(
                                        array("UPLOAD_SOURCE"=>$y,"IMAGE"=> $imageArray['correct'],"HAVEPHOTO_BEFORE_UPLOAD"=>'',"FILEDATA"=>"","IMPORT_SITE"=>''),
                                        array("canUpload"=>true,'copyPic'=>$copyPicData['correct'],'generatePictureArray'=>$generatePictureArrayData['correct'],'addPhotos'=>$addPhotosData['incorrect'],'oldMobileDataConversion'=>$oldMobileDataConversionData['correct']),
                                        $errorData['incorrectPicError'],
                                        ),
                                array(
                                        array("UPLOAD_SOURCE"=>$y,"IMAGE"=> $imageArray['correct'],"HAVEPHOTO_BEFORE_UPLOAD"=>'',"FILEDATA"=>"","IMPORT_SITE"=>''),
                                        array("canUpload"=>true,'copyPic'=>$copyPicData['correct'],'generatePictureArray'=>$generatePictureArrayData['correct'],'addPhotos'=>$addPhotosData['correct'],'oldMobileDataConversion'=>$oldMobileDataConversionData['correct']),
                                        $errorData['correct'],
                                        ),
/*
/*
*/
				);
		if(!is_array($finalPhoto))
			$finalPhoto = $photoCount;
		else
			$finalPhoto = array_merge($finalPhoto,$photoCount);
		}

                return $finalPhoto;
        }
        /**
        *function takes input from dataProvider function and and is specified using @  dataProvider annotation 
        *this is the test code executed starting with test*
        **/
        /**
        * @dataProvider dataProviderCanUpload
        */
        public function testCanUpload($uploadData,$returnSettingData,$resultData)
        {
		$this->setUploadPhotoObj($uploadData['UPLOAD_SOURCE']);
		$this->setStubs($returnSettingData);
		$result =  $this->uploadPhotoObj->channelUpload($uploadData['IMAGE'],$uploadData['HAVEPHOTO_BEFORE_UPLOAD'],$uploadData['FILEDATA'],$uploadData['IMPORT_SITE'],$this->nonScreenedPicObj);

		if(!is_array($resultData))
		{
			$this->assertTrue($result==$resultData);
		}
		else
		{
			$this->assertTrue($result['ErrorCounter']==$resultData['ErrorCounter']);
			$this->assertTrue($result['SizeErrorCounter']==$resultData['SizeErrorCounter']);
			$this->assertTrue($result['FormatErrorCounter']==$resultData['FormatErrorCounter']);
			$this->assertTrue($result['ActualFiles']==$resultData['ActualFiles']);
			$this->assertTrue($result['MaxCountError']==$resultData['MaxCountError']);
		}
        }
} 
