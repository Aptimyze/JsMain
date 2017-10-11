<?php
/*tests to be covered for getProfilePic
*/
class PictureArrayTest extends PHPUnit_Framework_TestCase
{

	public $other =null;

	public $loggedIn=null;

	public static $profiles = array("3894","3777");

	public static $gender = array("3894"=>"Male","3777"=>"Female");
	
	/**
	* function setUpBeforeClass
	* call: this function is called before the first test of the test case class is run once
	* use: for intial setup of test environment 
	*/
	public static function setUpBeforeClass()
	{
		$sqlArr = MakeTestData::firstTimeQueries(self::$profiles);
		MakeTestData::executeQueries($sqlArr);
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
	}
        /**
        *function tearDown
        *call: this function is called after every test 
        *use: to clear data after each test
        **/
	protected function tearDown()
	{
	}
	/**
	*function takes input from dataProvider function and and is specified using @  dataProvider annotation 
	*this is the test code executed starting with test*
	**/
	/**
	* @dataProvider dataProvider
	*/
	public function testGetProfilePic($row)
	{
		$this->loggedIn = $row['loggedIn'];
		$this->other = $row['other'];
		MakeTestData::createGetProfilePicTestData($row,$this->loggedIn,$this->other);
		$result = MakeTestData::getFunctionPreVariablesValues($row);

		$pictureArrayObj = new PictureArray($result['viewedObjArray']);

		$photo = $pictureArrayObj->getProfilePhoto($photoType = 'N', $result['skipProfilePrivacy'],$viewedDppArr='',$result['viewerObj'],$skipContacts='',$result['contactsBetweenViewedAndViewer']);

		$photoObj = $photo[$this->other];

		$match = $this->checkCorrectPhoto($photoObj,$row);
		$this->assertTrue($match);
	}
	/*
	*function: returns array to for  iteration step on test method
	*/
	public function dataProvider()
	{
		$result = MakeTestData::getPHOTO_DISPLAY_LOGIC_testData(self::$profiles[1],self::$profiles[0]);
		return $result;
	}
	private function checkCorrectPhoto($photoObj,$row)
	{
                if($row[IS_PHOTO_SHOWN]=="yes")
                {
                        $x= (($photoObj->getMainPicUrl()==JsConstants::$siteUrl."/spic1.jpg")&&
                                ($photoObj->getSearchPicUrl()==JsConstants::$siteUrl."/spic5.jpg") &&
                                ($photoObj->getProfilePicUrl()==JsConstants::$siteUrl."/spic2.jpg") &&
                                ($photoObj->getThumbailUrl()==JsConstants::$siteUrl."/spic3.jpg") &&
                                ($photoObj->getThumbail96Url()==JsConstants::$siteUrl."/spic4.jpg") &&
                                ($photoObj->getMobileAppPicUrl()==JsConstants::$siteUrl."/spic6.jpg") &&
                                ($photoObj->getProfilePic120Url()==JsConstants::$siteUrl."/spic7.jpg") &&
                                ($photoObj->getProfilePic235Url()==JsConstants::$siteUrl."/spic8.jpg") &&
                                ($photoObj->getProfilePic450Url()==JsConstants::$siteUrl."/spic9.jpg") &&
                                ($photoObj->getOriginalPicUrl()==JsConstants::$siteUrl."/spic10.jpg"));
                }
                elseif($row[IS_PHOTO_SHOWN]=="requestPhoto")
                {
                        $x = ($photoObj==NULL);
                }
                else
                {
                        $gender = self::$gender[$row['other']];
                        $x= (($photoObj->getSearchPicUrl()==JsConstants::$imgUrl.constant('StaticPhotoUrls::'.$row[IS_PHOTO_SHOWN].$gender.'SearchPicUrl')) &&
                                ($photoObj->getMainPicUrl()==JsConstants::$imgUrl.constant('StaticPhotoUrls::'.$row[IS_PHOTO_SHOWN].$gender.'ProfilePicUrl')) &&
                                ($photoObj->getProfilePicUrl()==JsConstants::$imgUrl.constant('StaticPhotoUrls::'.$row[IS_PHOTO_SHOWN].$gender.'ProfilePicUrl')) &&
                                ($photoObj->getThumbailUrl()==JsConstants::$imgUrl.constant('StaticPhotoUrls::'.$row[IS_PHOTO_SHOWN].$gender.'ThumbailUrl')) &&
                                ($photoObj->getMobileAppPicUrl()==JsConstants::$imgUrl.constant('StaticPhotoUrls::'.$row[IS_PHOTO_SHOWN].$gender.'MobileAppPicUrl')) &&
                                ($photoObj->getProfilePic120Url()==JsConstants::$imgUrl.constant('StaticPhotoUrls::'.$row[IS_PHOTO_SHOWN].$gender.'ProfilePic120Url')) &&
                                ($photoObj->getProfilePic235Url()==JsConstants::$imgUrl.constant('StaticPhotoUrls::'.$row[IS_PHOTO_SHOWN].$gender.'ProfilePic235Url')) &&
                                ($photoObj->getProfilePic450Url()==JsConstants::$imgUrl.constant('StaticPhotoUrls::'.$row[IS_PHOTO_SHOWN].$gender.'ProfilePic450Url')) &&
                                ($photoObj->getOriginalPicUrl()==JsConstants::$imgUrl.constant('StaticPhotoUrls::'.$row[IS_PHOTO_SHOWN].$gender.'ProfilePicUrl')));
                }
		return $x;
	}
} 
