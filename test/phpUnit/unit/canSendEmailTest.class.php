<?php

/*tests to be covered for canSendEmail
*/
class canSendEmailTest extends PHPUnit_Framework_TestCase
{
        public static $emailArr = array("EMAIL"=>"test@gmail.com","EMAIL_TYPE"=>"MATCHALERT");
        public static $emailArr2 = array("EMAIL"=>"test@gmail.com","EMAIL_TYPE"=>"");
        public static $emailArr3 = array("EMAIL"=>"","EMAIL_TYPE"=>"MATCHALERT");
        public static $emailArr4= array("EMAIL"=>"test@gmail.com","EMAIL_TYPE"=>'11');
        public static $profileId = '7043932';
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
                $this->JprofileMock = $this->getMockBuilder('JPROFILE')->getMock();
                $this->bouncedMailsMock = $this->getMockBuilder('bounces_BOUNCED_MAILS')->getMock();
		$this->canSendEmailObj1 = new canSendEmail(self::$emailArr,self::$profileId,$this->JprofileMock,$this->bouncedMailsMock);
                $this->canSendEmailObj2 = new canSendEmail(self::$emailArr2,self::$profileId,$this->JprofileMock,$this->bouncedMailsMock);
                $this->canSendEmailObj3 = new canSendEmail(self::$emailArr3,self::$profileId,$this->JprofileMock,$this->bouncedMailsMock);
                $this->canSendEmailObj4 = new canSendEmail(self::$emailArr4,self::$profileId,$this->JprofileMock,$this->bouncedMailsMock);
	}
	public function setSubscriptionValue($subscriptionValue)
	{
		$this->JprofileMock->method('getSubscriptions')->with()->willReturn($subscriptionValue);
	}
        public function setCheckInBounceEmail($status)
	{
		$this->bouncedMailsMock->method('checkEntry')->with("test@gmail.com")->willReturn($status);
	}
        /**
        *function tearDown
        *call: this function is called after every test 
        *use: to clear data after each test
        **/
	protected function tearDown()
	{
		unset($this->canSendEmailObj1);
                unset($this->canSendEmailObj2);
	}
        /*
	*function: data provider for can send It
	*/
	public function dataProviderCanSendIt()
	{
                $callHistory = array(array(true,0,'A','Y',false,'I'),array(false,1,'A','B',false,'I'),array(false,0,'U','U',false,'I'),array(false,1,'U','B',false,'I'),array(false,1,'','B',false,'I'));
		return $callHistory;
		
	}
        /**
	*function takes input from dataProvider function and and is specified using @  dataProvider annotation 
	*this is the test code executed starting with test*
	**/
        /**
        * @dataProvider dataProviderCanSendIt
        */
	public function testcanSendIt($sendStatus,$entryInBounce,$subscriptionValue,$setDeliveryStatus,$sendStatus2,$setDeliveryStatus2)
	{
                $this->setCheckInBounceEmail($entryInBounce);
                $this->setSubscriptionValue($subscriptionValue);
		$canSendStatus = $this->canSendEmailObj1->canSendIt();
                $deliveryStatus = $this->canSendEmailObj1->getDeliveryStatus();
                $this->assertEquals($sendStatus,$canSendStatus);
                $this->assertEquals($setDeliveryStatus,$deliveryStatus);
                $canSendStatus = $this->canSendEmailObj2->canSendIt();
                $deliveryStatus = $this->canSendEmailObj2->getDeliveryStatus();
                $this->assertEquals($sendStatus2,$canSendStatus);
                $this->assertEquals($setDeliveryStatus2,$deliveryStatus);
                $canSendStatus = $this->canSendEmailObj3->canSendIt();
                $deliveryStatus = $this->canSendEmailObj3->getDeliveryStatus();
                $this->assertEquals($sendStatus2,$canSendStatus);
                $this->assertEquals($setDeliveryStatus2,$deliveryStatus);
                $canSendStatus = $this->canSendEmailObj4->canSendIt();
                $deliveryStatus = $this->canSendEmailObj4->getDeliveryStatus();
                $this->assertEquals($sendStatus,$canSendStatus);
                $this->assertEquals($setDeliveryStatus,$deliveryStatus);
	}
} 

