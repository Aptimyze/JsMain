<?php
/*tests to be covered for getIntroCallHistory
*/
class introCallHistoryTest extends PHPUnit_Framework_TestCase
{
	public static $viewerId = "7043932";
	public static $viewedId = "924";

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
		$this->introCallObj = new getIntroCallHistory();
                $this->introCallmock = $this->getMockBuilder('getIntroCallHistory')->setMethods(array('getHistoryOfIntroCalls'))->getMock();
                $this->membershipObj = $this->getMockBuilder('MembershipHandler')->disableOriginalConstructor()->getMock();
                $this->callHistoryObj = $this->getMockBuilder('ASSISTED_PRODUCT_AP_CALL_HISTORY')->disableOriginalConstructor()->getMock();
	}
	public function setAllCount($introCallArr)
	{
		$this->membershipObj->method('getAllCount')->with()->willReturn($introCallArr);
	}
        public function setCallHistory($callHistoryArr)
	{
		$this->callHistoryObj->method('getCallHistory')->with()->willReturn($callHistoryArr);
	}
        public function setHistoryOfIntroCalls($introCallArr)
	{
		$this->introCallmock->method('getHistoryOfIntroCalls')->with()->willReturn($introCallArr);
	}
        /**
        *function tearDown
        *call: this function is called after every test 
        *use: to clear data after each test
        **/
	protected function tearDown()
	{
		unset($this->introCallObj);
	}
        /*
	*function: data provider for getHistory of intro calls
	*/
	public function dataProvidergetHistoryOfIntroCalls()
	{
		$callHistory = array(array(array(   
    0 => array('MATCH_ID' => 8767533,'CALL_STATUS' => N),                  
    1 => array('MATCH_ID' => 8767868,'CALL_STATUS' => C),                  
    2 => array('MATCH_ID' => 8767657,'CALL_STATUS' => N),                 
    3 => array('MATCH_ID' => 8767457,'CALL_STATUS' => C),                  
    4 => array('MATCH_ID' => 4567533,'CALL_STATUS' => Y),                  
    5 => array('MATCH_ID' => 8567533,'CALL_STATUS' => Y),
    6 => array('MATCH_ID' => 8766733,'CALL_STATUS' => Y),
    7 => array('MATCH_ID' => 8756733,'CALL_STATUS' => N),
    8 => array('MATCH_ID' => 8566533,'CALL_STATUS' => N),
    9 => array('MATCH_ID' => 8566533,'CALL_STATUS' => C)                      
                  ),3,4,7,10),
                                    array(array(   
    0 => array('MATCH_ID' => 8767533,'CALL_STATUS' => N),                  
    1 => array('MATCH_ID' => 8721133,'CALL_STATUS' => Y),                  
    2 => array('MATCH_ID' => 8734333,'CALL_STATUS' => Y),                 
    3 => array('MATCH_ID' => 8767533,'CALL_STATUS' => C),                  
    4 => array('MATCH_ID' => 8767533,'CALL_STATUS' => C),                  
    5 => array('MATCH_ID' => 3167533,'CALL_STATUS' => Y),
    6 => array('MATCH_ID' => 5567533,'CALL_STATUS' => Y),
    7 => array('MATCH_ID' => 8767533,'CALL_STATUS' => Y),
    8 => array('MATCH_ID' => 8437533,'CALL_STATUS' => C)
                  ),5,1,6,9),
                                    array(array(   
    0 => array('MATCH_ID' => 8767533,'CALL_STATUS' => C),                  
    1 => array('MATCH_ID' => 8763433,'CALL_STATUS' => N),                  
    2 => array('MATCH_ID' => 8761233,'CALL_STATUS' => C),                 
    3 => array('MATCH_ID' => 8767523,'CALL_STATUS' => C),                  
    4 => array('MATCH_ID' => 8765543,'CALL_STATUS' => N),                  
    5 => array('MATCH_ID' => 8544553,'CALL_STATUS' => Y),
    6 => array('MATCH_ID' => 8767983,'CALL_STATUS' => Y),
    7 => array('MATCH_ID' => 8767343,'CALL_STATUS' => C),
    8 => array('MATCH_ID' => 8237863,'CALL_STATUS' => N),
    9 => array('MATCH_ID' => 8566533,'CALL_STATUS' => N),                                      
    10 => array('MATCH_ID' => 8566533,'CALL_STATUS' => Y),                                      
                  ),3,4,7,11),
                  array(array(   
    0 => array('MATCH_ID' => 8767533,'CALL_STATUS' => N),                  
    1 => array('MATCH_ID' => 8763433,'CALL_STATUS' => N),                  
    2 => array('MATCH_ID' => 8761233,'CALL_STATUS' => N),                 
    3 => array('MATCH_ID' => 8767523,'CALL_STATUS' => N),                  
    4 => array('MATCH_ID' => 8765543,'CALL_STATUS' => N),                  
    5 => array('MATCH_ID' => 8544553,'CALL_STATUS' => N),
    6 => array('MATCH_ID' => 8767983,'CALL_STATUS' => N),
    7 => array('MATCH_ID' => 8767343,'CALL_STATUS' => N),
    8 => array('MATCH_ID' => 8237863,'CALL_STATUS' => N),
    9 => array('MATCH_ID' => 8566533,'CALL_STATUS' => N),                                      
    10 => array('MATCH_ID' => 8566533,'CALL_STATUS' => N),                                      
                  ),0,11,11,11),
                  array(array(   
    0 => array('MATCH_ID' => 8767533,'CALL_STATUS' => N),                  
    1 => array('MATCH_ID' => 8763433,'CALL_STATUS' => N),                  
    2 => array('MATCH_ID' => 8761233,'CALL_STATUS' => N),
    3 => array('MATCH_ID' => 8767323,'CALL_STATUS' => N),
    4 => array('MATCH_ID' => 8765543,'CALL_STATUS' => N),                  
    5 => array('MATCH_ID' => 8544553,'CALL_STATUS' => N),
    6 => array('MATCH_ID' => 8767983,'CALL_STATUS' => N),
    7 => array('MATCH_ID' => 8767343,'CALL_STATUS' => N),
    8 => array('MATCH_ID' => 8237863,'CALL_STATUS' => N),
    9 => array('MATCH_ID' => 8566533,'CALL_STATUS' => N),                                      
    10 => array('MATCH_ID' => 924,'CALL_STATUS' => N),                                      
                  ),0,11,11,11));
		return $callHistory;
		
	}
        /**
	*function takes input from dataProvider function and and is specified using @  dataProvider annotation 
	*this is the test code executed starting with test*
	**/
        /**
        * @dataProvider dataProvidergetHistoryOfIntroCalls
        */
	public function testgetHistoryOfIntroCalls($callHistoryArr,$calledCount,$notCalled,$calledAndNotCalled,$totalCalls)
	{
                $this->setCallHistory($callHistoryArr);
		$callHistory = $this->introCallObj->getHistoryOfIntroCalls($viewerId,'','','',$this->callHistoryObj);
                $this->assertTrue($callHistory['calledCount']==$calledCount);
                $this->assertTrue($callHistory['notCalledCount']==$notCalled);
                $this->assertTrue($callHistory['TOTAL']==$calledAndNotCalled);
                $this->assertTrue($callHistory['TOTAL_COUNT']==$totalCalls);
	}
        public function dataProviderTestOffCallHistory()
	{
		$array = array(
				array(array('AVAIL' => 16,'TOTAL' => 25, 'USED' => 9 ),array ( 'profile' => array ( 0 => '8529589', 1 => '9536' ,2 => '9495744' ,3 => '96525203' ,4 => '96525159' ,5 => '8512820' ,6 => '9064887', 7 => '9651639', 8 => '8767533', 9 => '9612'), 'CS' => array ( '8529589'=> N, '9536' => C, '9495744' => N, '96525203' => C, '96525159' => Y, '8512820' => Y, '9064887' => Y, '9651639' => N ,'8767533' => N ,'9612' => C),'calledCount' => 3 ,'notCalledCount' => 4 ,'TOTAL' => 7, 'TOTAL_COUNT' => 10 ),25,8,3,17,15,1),
                                array(array('AVAIL' => 1,'TOTAL' => 10, 'USED' => 9 ),array ( 'profile' => array ( 0 => '8529589', 1 => '9536' ,2 => '9495744' ,3 => '96525203' ,4 => '96525159' ,5 => '8512820' ,6 => '9064887', 7 => '9651639', 8 => '8767533'), 'CS' => array ( '8529589'=> N, '9536' => Y, '9495744' => Y, '96525203' => C, '96525159' => C, '8512820' => Y, '9064887' => Y, '9651639' => Y ,'8767533' => C) ,'calledCount' => 5 ,'notCalledCount' => 1 ,'TOTAL' => 6, 'TOTAL_COUNT' => 9 ),10,7,5,3,1,1),
                                array(array('AVAIL' => 38,'TOTAL' => 50, 'USED' => 12 ),array ( 'profile' => array ( 0 => '8529589', 1 => '9536' ,2 => '9495744' ,3 => '96525203' ,4 => '96525159' ,5 => '8512820' ,6 => '9064887', 7 => '9651639', 8 => '8767533', 9 => '9612', 10 => '9479166' ,11=>'9479143'  ), 'CS' => array ( '8529589'=> C, '9536' => N, '9495744' => C, '96525203' => C, '96525159' => N, '8512820' => Y, '9064887' => Y, '9651639' => C ,'8767533' => N ,'9612' => N, '9479166' => N ,'9479143' => Y) ,'calledCount' => 3 ,'notCalledCount' => 4 ,'TOTAL' => 7, 'TOTAL_COUNT' => 11 ),50,8,3,42,39,1),
                                array(array('AVAIL' => 38,'TOTAL' => 40, 'USED' => 2 ),array ( 'profile' => array ( 0 => '8529589', 1 => '9536' ,2 => '9495744' ,3 => '96525203' ,4 => '96525159' ,5 => '8512820' ,6 => '9064887', 7 => '9651639', 8 => '8767533', 9 => '9612', 10 => '9479166' ,11=>'9479143'  ), 'CS' => array ( '8529589'=> N, '9536' => N, '9495744' => N, '96525203' => N, '96525159' => N, '8512820' => N, '9064887' => N, '9651639' => N ,'8767533' => N ,'9612' => N, '9479166' => N ,'9479143' => N) ,'calledCount' => 0 ,'notCalledCount' => 11 ,'TOTAL' =>11, 'TOTAL_COUNT' => 11 ),40,12,0,28,29,1),
                                array(array('AVAIL' => 38,'TOTAL' => 40, 'USED' => 2 ),array ( 'profile' => array ( 0 => '8529589', 1 => '9536' ,2 => '9495744' ,3 => '924' ,4 => '96525159' ,5 => '8512820' ,6 => '9064887', 7 => '9651639', 8 => '8767533', 9 => '9612', 10 => '9479166' ,11=>'9479143'  ), 'CS' => array ( '8529589'=> C, '9536' => N, '9495744' => C, '96525203' => C, '96525159' => N, '8512820' => Y, '9064887' => Y, '9651639' => C ,'8767533' => N ,'9612' => N, '9479166' => N ,'924' => Y) ,'calledCount' => 0 ,'notCalledCount' => 11 ,'TOTAL' =>11, 'TOTAL_COUNT' => 11 ),40,12,0,28,29,'',1));		
		return $array;
	}
	/**
	*function takes input from dataProvider function and and is specified using @  dataProvider annotation 
	*this is the test code executed starting with test*
	**/
        /**
        * @dataProvider dataProviderTestOffCallHistory
        */
	public function testoffCallHistory($introCallArr,$introCallHistoryArr,$purchased,$toBeCalled,$called,$toBeAdded,$toBeAddedCount,$alreadyAdded,$canBeAdded)
	{
                $this->setAllCount($introCallArr);
                $this->setHistoryOfIntroCalls($introCallHistoryArr);
		$callHistory = $this->introCallmock->offCallHistory(self::$viewerId,self::$viewedId,$this->membershipObj,$this->callHistoryObj);
                $this->assertTrue($callHistory['introCallDetail']['PURCHASED']==$purchased);
                $this->assertTrue($callHistory['introCallDetail']['TO_BE_CALLED']==$toBeCalled);
                $this->assertTrue($callHistory['introCallDetail']['CALLED']==$called);
                $this->assertTrue($callHistory['introCallDetail']['TO_BE_ADDED']==$toBeAdded);
                $this->assertTrue($callHistory['introCallDetail']['TO_BE_ADDED_CNT']==$toBeAddedCount);
                if($alreadyAdded)
                    $this->assertTrue($callHistory['OFFLINE_ASSISTANT_ADD']==$alreadyAdded);
                if($canBeAdded)
                    $this->assertTrue($callHistory['OFFLINE_ASSISTANT_REM']==$canBeAdded);
	}
        /**
        * @dataProvider dataProviderException
        */
//	public function testException()
//	{
//		$this->setExpectedException('jsException');
//	}
//	public function dataProviderException()
//	{
//		$array = array(
//				array("S",self::$pictureid),
//				array("S","12456"));
//		return $array;
//		
//	}
} 
