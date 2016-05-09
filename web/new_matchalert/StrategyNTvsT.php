<?php
include_once(JsConstants::$alertDocRoot."/new_matchalert/StrategyClass.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/FilterBean.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/SortingArray.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/Sender.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/TrendsUtility.php");

//class StrategyNTvsT implements StrategyInterface 
class StrategyNTvsT extends StrategyClass
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
	private $communityModelLogic;
	private $frequency;

	function __construct($receiverObj,$db,$shardArr,$mysqlObj,$communityModelLogic,$frequency) 
	{
		$this->receiverObj=$receiverObj;
		$this->filterBean=new filterBean($db,$shardArr,$mysqlObj); 
		$this->filterBean->setForwardCriteria($this->receiverObj);
		$this->isMatchesTrending='Y';
		$this->db=$db;
		$this->communityModelLogic = $communityModelLogic;
		$this->frequency = $frequency;
    	}

	public function processResults($queryLimit)
	{
		$trendsUtility=new TrendsUtility();
		$sortingArray=$trendsUtility->calculateReverseScrore($this->db,$this->profileSetTemp,$this->receiverObj);
		$sortedArry=$sortingArray->sort();
		$queryLimit=min($queryLimit,count($sortedArry));
		for($i=0;$i<$queryLimit;$i++)
		{
			if($sortedArry[$i])
			{
				$this->profileSet[]=$sortedArry[$i]->getProfileId();
			}
		}
		unset($sortedArry);
	}


	//This is the main function
	public function doProcessing()
	{
		global $NTvTlevel1,$NTvTlevel2,$NTvTlevel3,$NTvTlevel4,$NTvTlevel5,$NTvTlevel6;

                $maxLimit=configVariables::$maxLimit;
                $queryLimit=$maxLimit;
                $loginDtRelax1=configVariables::$loginDtRelax1;
                $loginDtRelax2=configVariables::$loginDtRelax2;
		$dayOfRelaxation=configVariables::$use60DaysRelaxOnDay;

                if($this->receiverObj->getSwitchToDpp()==1)
                {
                        $canUseRelaxation=0;
                }
                else
                {
                        $relaxlheight=$this->filterBean->getLheightRelax();
                        $relaxhheight=$this->filterBean->getHheightRelax();
                        $relaxlage=$this->filterBean->getLageRelax();
                        $relaxhage=$this->filterBean->getHageRelax();
                        $canUseRelaxation=$this->filterBean->getCanUseRelaxation();
                }
              	$dppCasteVal = $this->filterBean->getCaste();

		$NTvTlevel1++;
		$this->profileSetTemp=$this->runDBQuery($this->receiverObj,$this->filterBean,$this->profileSet,$this->db,$this->isMatchesTrending,$loginDtRelax1,1,'','','','','','','',$this->communityModelLogic);
		if($this->profileSetTemp && !$this->communityModelLogic)
			$this->processResults($queryLimit);
		unset($this->profileSetTemp);

                //------
                if(count($this->profileSet)>0)
                {
                        $levelCount=count($this->profileSet);
                        while($levelCount>0)
                        {
				if($this->communityModelLogic)
                                	$this->logicLevel[]='211';
				else
                                	$this->logicLevel[]='21';
                                $levelCount--;
                        }
                }
                //------

		if($canUseRelaxation && count($this->profileSet) <$maxLimit)//Relaxed forward only 15 days
		{
			$NTvTlevel5++;
			$queryLimit=$maxLimit-count($this->profileSet);
                        
			$this->filterBean->setLheight(intval($this->filterBean->getLheight())-$relaxlheight);
			$this->filterBean->setHheight(intval($this->filterBean->getHheight())+$relaxhheight);
			$this->filterBean->setLage(intval($this->filterBean->getLAge())-$relaxlage);
			$this->filterBean->setHage(intval($this->filterBean->getHAge())+$relaxhage);
			$this->setCasteRelaxation($this->receiverObj->getRecCaste(),$this->filterBean);

			$this->profileSetTemp=$this->runDBQuery($this->receiverObj,$this->filterBean,$this->profileSet,$this->db,$this->isMatchesTrending,$loginDtRelax1,1,1,$queryLimit,'LAST_LOGIN_DT DESC','','','','',$this->communityModelLogic);//1,1 was 1,0 PHASE2
			if($this->profileSetTemp)
				$this->profileSet=array_merge($this->profileSet,$this->profileSetTemp);
                        //------
                        $levelCount=count($this->profileSet)-count($this->logicLevel);
                        while($levelCount>0)
                        {
				if($this->communityModelLogic)
                                	$this->logicLevel[]='221';
				else
                                	$this->logicLevel[]='22';
                                $levelCount--;
                        }
                        //------
		}

		if(count($this->profileSet) <$maxLimit)//Relaxed forward only 60days
                {
			$NTvTlevel6++;
                        $queryLimit=$maxLimit-count($this->profileSet);
                        $this->profileSetTemp=$this->runDBQuery($this->receiverObj,$this->filterBean,$this->profileSet,$this->db,$this->isMatchesTrending,$loginDtRelax2,1,1,$queryLimit,'LAST_LOGIN_DT DESC','','','','',$this->communityModelLogic);//1,1 was 1,0 PHASE2
                        if($this->profileSetTemp)
                                $this->profileSet=array_merge($this->profileSet,$this->profileSetTemp);
                        //------
                        $levelCount=count($this->profileSet)-count($this->logicLevel);
                        while($levelCount>0)
                        {
				if($this->communityModelLogic)
                                	$this->logicLevel[]='231';
				else
                                	$this->logicLevel[]='23';
                                $levelCount--;
                        }
                        //------

                }

		$gap=configVariables::getNoOfDays();
		if($gap%7==$dayOfRelaxation)
		{
                        if(count($this->profileSet) <$maxLimit)//Relaxed forward only + no_login_dt
                        {
                                $NTvTlevel6++;
                                $queryLimit=$maxLimit-count($this->profileSet);

				if($canUseRelaxation)
                                {
					$this->filterBean->setLheight(intval($this->filterBean->getLheight())-$relaxlheight);
					$this->filterBean->setHheight(intval($this->filterBean->getHheight())+$relaxhheight);
					$this->filterBean->setLage(intval($this->filterBean->getLAge())-$relaxlage);
					$this->filterBean->setHage(intval($this->filterBean->getHAge())+$relaxhage);
					$this->setCasteRelaxation($this->receiverObj->getRecCaste(),$this->filterBean);
				}

                                $this->profileSetTemp=$this->runDBQuery($this->receiverObj,$this->filterBean,$this->profileSet,$this->db,$this->isMatchesTrending,'',1,'',$queryLimit,'LAST_LOGIN_DT DESC','','','','',$this->communityModelLogic);
                                if($this->profileSetTemp)
                                        $this->profileSet=array_merge($this->profileSet,$this->profileSetTemp);
                                //------
                                $levelCount=count($this->profileSet)-count($this->logicLevel);
                                while($levelCount>0)
                                {
					if($this->communityModelLogic)
                                        	$this->logicLevel[]='241';
					else
                                        	$this->logicLevel[]='24';
                                        $levelCount--;
                                }
                                //------
                        }
                }

                if(count($this->profileSet)<$maxLimit)//NT PROFILEIS NOT SENT
                {
			//NEED TO IMPLEMENT LATER
                }
                if(count($this->profileSet)<$maxLimit)
                {
			//TRACKING CODE
                }
                if(count($this->profileSet))
                        $this->logRecords($this->profileSet,$this->receiverObj->getPartnerProfile()->getProfileId(),$this->db,configVariables::$strategyNtVsTLogic,$this->logicLevel,$this->frequency);
                else
		{
                        $gap=configVariables::getNoOfDays();
                        $zeropid=$this->receiverObj->getPartnerProfile()->getProfileId();
                        $sql_y="INSERT INTO matchalerts.ZERONTvT(PROFILEID,DATE) VALUES($zeropid,$gap)";
                        mysql_query($sql_y,$this->db) or logerror1("In matchalert_mailer.php",$sql_y);
                        ;// some tarck for 0 res
		}

	}
}
?>
