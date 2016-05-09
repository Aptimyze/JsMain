<?php
include_once(JsConstants::$alertDocRoot."/new_matchalert/StrategyClass.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/FilterBean.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/SortingArray.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/Sender.php");

class StrategyDvD extends StrategyClass
{
	//this is for just demo of DPP to DPP
	
	private $receiverObj; 
	private $filterBean;
	private $dbo;
	private $db;
	private $sortingBean;	
	private $isMatchesTrending;
	private $profileSet=array();
	private $logicLevel=array();//------------

	function __construct($receiverObj,$db,$shardArr,$mysqlObj) 
	{
		$this->receiverObj=$receiverObj;
		$this->filterBean=new filterBean($db,$shardArr,$mysqlObj,'D'); 
		$this->filterBean->setForwardCriteria($this->receiverObj);
		$this->isMatchesTrending='D';//for dvd
		$this->db=$db;
    	}

	//This is the main function
	public function doProcessing()
	{
		$maxLimit=configVariables::$maxLimitDvD;
		$queryLimit=$maxLimit;

		$this->profileSetTemp=$this->runDBQuery($this->receiverObj,$this->filterBean,$this->profileSet,$this->db,$this->isMatchesTrending,'','','',$queryLimit,' HAVEPHOTO DESC , PROFILE_SCORE DESC , LAST_LOGIN_DT DESC');
		if($this->profileSetTemp)
			$this->profileSet=array_merge($this->profileSet,$this->profileSetTemp);
//print_r($this->profileSet);
		if(count($this->profileSetTemp)<$maxLimit)
		{
			$queryLimit=$maxLimit-count($this->profileSet);
                        $this->filterBean->setLheight(intval($this->filterBean->getLheight())-2);
                        $this->filterBean->setHheight(intval($this->filterBean->getHheight())+2);
			$this->profileSetTemp=$this->runDBQuery($this->receiverObj,$this->filterBean,$this->profileSet,$this->db,$this->isMatchesTrending,'','','',$queryLimit,' HAVEPHOTO DESC , PROFILE_SCORE DESC , LAST_LOGIN_DT DESC');
			if($this->profileSetTemp)
				$this->profileSet=array_merge($this->profileSet,$this->profileSetTemp);
//print_r($this->profileSet);
		}
                if(count($this->profileSet))
                {
                        $this->logRecordsDvD($this->profileSet,$this->receiverObj->getPartnerProfile()->getProfileId(),$this->db);
                }
                else
                {
			$zeropid=$this->receiverObj->getPartnerProfile()->getProfileId();
                        $sql_y="INSERT INTO matchalerts.ZERODVD(RECEIVER,DATE) VALUES($zeropid,now())";
                        mysql_query($sql_y,$this->db) or logerror1("In matchalert_mailer.php",$sql_y);
                }

	}
}
?>
