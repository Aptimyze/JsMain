<?php
include_once(JsConstants::$alertDocRoot."/new_matchalert/StrategyClass.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/FilterBean.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/SortingArray.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/Sender.php");

include_once(JsConstants::$alertDocRoot."/new_matchalert/TrendsUtility.php");
//class StrategyNTvsT implements StrategyInterface 
class StrategyTvsNT extends StrategyClass
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
		$this->filterBean=new filterBean($db,$shardArr,$mysqlObj); 
		$this->filterBean->setForwardCriteria($this->receiverObj);
		$this->isMatchesTrending='N';
		$this->db=$db;
		$this->frequency = $frequency;
    	}

	public function processResults($queryLimit)
	{
		$trendsUtility=new TrendsUtility();
		$sortingArray=$trendsUtility->calculateForwardScrore($this->receiverObj->getPartnerProfile()->getPROFILEID(),$this->db,$this->profileSetTemp);
		$sortedArry=$sortingArray->sort();
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
		global $TvNTlevel1,$TvNTlevel2;
                $maxLimit=configVariables::$maxLimit;
                $queryLimit=$maxLimit;
                $loginDtRelax1=configVariables::$loginDtRelax1;
                $loginDtRelax2=configVariables::$loginDtRelax2;
                $maxForwardTrendsLimit=configVariables::$maxForwardTrendsLimit;
		$dayOfRelaxation=configVariables::$use60DaysRelaxOnDay;

		//temp
		//$maxLimit=7777777777;
		//$queryLimit=7;
		//temp

		$TvNTlevel1++;	
		$this->profileSetTemp=$this->runDBQuery($this->receiverObj,$this->filterBean,$this->profileSet,$this->db,$this->isMatchesTrending,$loginDtRelax1);
		if($this->profileSetTemp)
			$this->processResults($queryLimit);
		unset($this->profileSetTemp);
		
		//------
		if(count($this->profileSet)>0)
		{
                        $levelCount=count($this->profileSet);
                        while($levelCount>0)
                        {
                                $this->logicLevel[]='31';
                                $levelCount--;
                        }
		}
		//------
		
		if(count($this->profileSet)<$maxForwardTrendsLimit)
		//when no of result with min score <30% fall below 3, we will go for fall back.
		{
			if(count($this->profileSet)<$maxLimit) //60 days Relax
			{
				$TvNTlevel2++;
				$queryLimit=$maxLimit-count($this->profileSet);
				$this->profileSetTemp=$this->runDBQuery($this->receiverObj,$this->filterBean,$this->profileSet,$this->db,$this->isMatchesTrending,$loginDtRelax2);
				if($this->profileSetTemp)
				{
					$this->processResults($queryLimit);
				}
				unset($this->profileSetTemp);

				//------
				$levelCount=count($this->profileSet)-count($this->logicLevel);
				while($levelCount>0)
				{
					$this->logicLevel[]='32';
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
					$TvNTlevel3++;
					$queryLimit=$maxLimit-count($this->profileSet);
					$this->profileSetTemp=$this->runDBQuery($this->receiverObj,$this->filterBean,$this->profileSet,$this->db,$this->isMatchesTrending,'');
					if($this->profileSetTemp)
					{
						$this->processResults($queryLimit);
					}
					unset($this->profileSetTemp);

					//------
					$levelCount=count($this->profileSet)-count($this->logicLevel);
					while($levelCount>0)
					{
						$this->logicLevel[]='33';
						$levelCount--;
					}
				}
				//------
			}
			//PHASE2
		}

                if(count($this->profileSet))
                        $this->logRecords($this->profileSet,$this->receiverObj->getPartnerProfile()->getProfileId(),$this->db,configVariables::$strategyTVsNtLogic,$this->logicLevel,$this->frequency);
                else
		{
                        $gap=configVariables::getNoOfDays();
                        $zeropid=$this->receiverObj->getPartnerProfile()->getProfileId();
                        $sql_y="INSERT INTO matchalerts.ZEROTvNT(PROFILEID,DATE) VALUES($zeropid,$gap)";
                        mysql_query($sql_y,$this->db) or logerror1("In matchalert_mailer.php",$sql_y);
                        ;// some tarck for 0 res
		}


	}
}
?>
