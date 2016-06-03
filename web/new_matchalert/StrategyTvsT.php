<?php
include_once(JsConstants::$alertDocRoot."/new_matchalert/StrategyClass.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/FilterBean.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/SortingArray.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/Sender.php");

//class StrategyNTvsT implements StrategyInterface 
class StrategyTvsT extends StrategyClass
{
	//this is for just demo of DPP to DPP
	
	private $receiverObj; 
	private $filterBean;
	private $dbo;
	private $db;
	private $sortingBean;	
	private $profileSet=array();
	private $profileSetTemp=array();
	private $logicLevel=array();//------------
	private $frequency;

	function __construct($receiverObj,$db,$shardArr,$mysqlObj,$frequency) 
	{
		$this->receiverObj=$receiverObj;
		$this->filterBean=new filterBean($db,$shardArr,$mysqlObj,'Y'); 
	       	//$this->setFilter($db);
		$this->filterBean->setForwardCriteria($this->receiverObj);
		$this->isMatchesTrending='Y';
		$this->db=$db;
		$this->frequency = $frequency;
       }
	/*
    	private function setFilter() 
	{
		$this->filterBean->setForwardCriteria($this->receiverObj);
	}*/

	public function processResults($queryLimit)
	{
                $trendsUtility=new TrendsUtility();
		$sortingArrayF=$trendsUtility->calculateForwardScrore($this->receiverObj->getPartnerProfile()->getPROFILEID(),$this->db,$this->profileSetTemp,1)->makeAccessible();
		$sortingArrayR=$trendsUtility->calculateReverseScrore($this->db,$this->profileSetTemp,$this->receiverObj)->makeAccessible();
	
		//print_r($sortingArrayF);
		//print_r($sortingArrayR);

		$cnt=count($sortingArrayF);
                $sortingArray1=new SortingArray();
		for($i=0;$i<$cnt;$i++)
		{
			$totalScore=$sortingArrayF[$i]->getScore()+$sortingArrayR[$i]->getScore();
			$profileId=$sortingArrayF[$i]->getProfileid();
			$send=new Sender($profileId,$totalScore);
                        $sortingArray1->add($send);
		}
		$sortedArry=$sortingArray1->sort();
                $queryLimit=min($queryLimit,count($sortedArry));
                for($i=0;$i<$queryLimit;$i++)
                {
                        if($sortedArry[$i])
                        {
                                $this->profileSet[]=$sortedArry[$i]->getProfileId();
                        }
                }
	}


	//This is the main function
	public function doProcessing()
	{
		global $TvTlevel1,$TvTlevel2;
                $maxLimit=configVariables::$maxLimit;
                $queryLimit=$maxLimit;
                $loginDtRelax1=configVariables::$loginDtRelax1;
                $loginDtRelax2=configVariables::$loginDtRelax2;
                $maxForwardTrendsLimit=configVariables::$maxForwardTrendsLimit;
		$dayOfRelaxation=configVariables::$use60DaysRelaxOnDay;

		$TvTlevel1++;
		$this->profileSetTemp=$this->runDBQuery($this->receiverObj,$this->filterBean,$this->profileSet,$this->db,$this->isMatchesTrending,$loginDtRelax1,1);
		if($this->profileSetTemp)
			$this->processResults($queryLimit);
		unset($this->profileSetTemp);

                //------
                if(count($this->profileSet)>0)
                {
                        $levelCount=count($this->profileSet);
                        while($levelCount>0)
                        {
                                $this->logicLevel[]='41';
                                $levelCount--;
                        }
                }
                //------

		if(count($this->profileSet)<$maxForwardTrendsLimit) //60 days Relax
		{
			$TvTlevel2++;
			$queryLimit=$maxLimit-count($this->profileSet);
			$this->profileSetTemp=$this->runDBQuery($this->receiverObj,$this->filterBean,$this->profileSet,$this->db,$this->isMatchesTrending,$loginDtRelax2,1);
			if($this->profileSetTemp)
			{
				$this->processResults($queryLimit);
			}
			unset($this->profileSetTemp);
			//------
			$levelCount=count($this->profileSet)-count($this->logicLevel);
			while($levelCount>0)
			{
				$this->logicLevel[]='42';
				$levelCount--;
			}
			//------
		}

		//PHASE2
		if(count($this->profileSet)<$maxLimit) //60 days Relax  + no_login_dt +  PHASE2
		{
			$gap=configVariables::getNoOfDays();
			if($gap%7==$dayOfRelaxation)
			{
				$TvTlevel2++;
				$queryLimit=$maxLimit-count($this->profileSet);
				$this->profileSetTemp=$this->runDBQuery($this->receiverObj,$this->filterBean,$this->profileSet,$this->db,$this->isMatchesTrending,'',1);
				if($this->profileSetTemp)
				{
					$this->processResults($queryLimit);
				}
				unset($this->profileSetTemp);
				//------
				$levelCount=count($this->profileSet)-count($this->logicLevel);
				while($levelCount>0)
				{
					$this->logicLevel[]='43';
					$levelCount--;
				}
				//------

			}
			//------
		}
		//PHASE2


		if(count($this->profileSet)<$maxLimit)
		{
			//Tracking
		}
                if(count($this->profileSet))
                        $this->logRecords($this->profileSet,$this->receiverObj->getPartnerProfile()->getProfileId(),$this->db,configVariables::$strategyTVsTLogic,$this->logicLevel,$this->frequency);
                else
		{
                        $gap=configVariables::getNoOfDays();
                        $zeropid=$this->receiverObj->getPartnerProfile()->getProfileId();
                        $sql_y="INSERT INTO matchalerts.ZEROTvT(PROFILEID,DATE) VALUES($zeropid,$gap)";
                        mysql_query($sql_y,$this->db) or logerror1("In matchalert_mailer.php",$sql_y);
                        ;// some tarck for 0 res
		}

	}
}
?>
