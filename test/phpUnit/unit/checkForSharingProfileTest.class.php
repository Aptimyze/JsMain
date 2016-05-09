<?php

/*tests to be covered for canSendEmail
*/
class checkForSharingProfileTest extends PHPUnit_Framework_TestCase
{
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
		$this->checkShareProfileObj = new checkForSharingProfile();
                $this->shareProfileMock = $this->getMockBuilder('PROFILE_SHARE_PROFILE')->getMock();
	}
        public function setShareProfileStoreSelect($data,$profileId)
	{
		$this->shareProfileMock->method('selectData')->with($profileId)->willReturn($data);
	}
        /**
        *function tearDown
        *call: this function is called after every test 
        *use: to clear data after each test
        **/
	protected function tearDown()
	{
		unset($this->checkShareProfileObj);
	}
        /*
	*function: data provider for can delete with zero ordering and 0 and non zero number of photos
	*/
	public function dataProviderGetMailerCriteria()
	{
                $date = date('Y-m-d H:i:s');
                $oneDayBefore = date('Y-m-d H:i:s' ,strtotime ( '-1 day' , strtotime ( $date ) ));
                $twoDayBefore = date('Y-m-d H:i:s' ,strtotime ( '-2 day' , strtotime ( $date ) ));
                $onehourBefore = date('Y-m-d H:i:s' ,strtotime ( '-1 hour' , strtotime ( $date ) ));
                $dataArray = array(array('112122','YES',array('TIME' => $onehourBefore,'COUNT' => 16)),array('323223','YES',array('TIME' => $oneDayBefore,'COUNT' => 19)),array('232323','YES',array('TIME' => $twoDayBefore,'COUNT' => 26)),array('232323','NO',array('TIME' => $onehourBefore,'COUNT' => 20)),array('232323','YES',array('TIME' => $onehourBefore,'COUNT' => 19)),array('232323','YES',array()));
		return $dataArray;
		
	}
        /**
	*function takes input from dataProvider function and and is specified using @  dataProvider annotation 
	*this is the test code executed starting with test*
	**/
        /**
        * @dataProvider dataProviderGetMailerCriteria
        */
	public function testgetsendMailCriteria($profileId,$sendMailVal,$dataToSet)
	{
                $this->setShareProfileStoreSelect($dataToSet,$profileId);
		$sendMailCriteria = $this->checkShareProfileObj->getsendMailCriteria($profileId,$this->shareProfileMock);
                $this->assertEquals($sendMailCriteria["RESPONSE"],$sendMailVal);
	}
} 
