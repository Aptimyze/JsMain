<?php
include_once(JsConstants::$alertDocRoot."/new_matchalert/StrategyClass.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/FilterBean.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/SortingArray.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/Sender.php");

class StrategyNTvsNT extends StrategyClass
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
	private $communityModelLogic;
	private $frequency;

	function __construct($receiverObj,$db,$shardArr,$mysqlObj,$communityModelLogic,$frequency) 
	{
		$this->receiverObj=$receiverObj;
		$this->filterBean=new filterBean($db,$shardArr,$mysqlObj); 
		$this->filterBean->setForwardCriteria($this->receiverObj);
		$this->isMatchesTrending='N';
		$this->db=$db;
		$this->communityModelLogic = $communityModelLogic;
		$this->frequency = $frequency;
    	}

	//This is the main function
	public function doProcessing()
	{
		global $NTvNTlevel1,$NTvNTlevel2,$NTvNTlevel3,$NTvNTlevel4,$NTvNTlevel5,$NTvNTlevel6;

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

		$NTvNTlevel1++;
		$levelCount=0;//----------
		$this->profileSetTemp=$this->runDBQuery($this->receiverObj,$this->filterBean,$this->profileSet,$this->db,$this->isMatchesTrending,$loginDtRelax1,'','',$queryLimit,'ENTRY_DT DESC','','','','',$this->communityModelLogic);
		
		if($this->profileSetTemp)
		{
			//-----------
			$levelCount=count($this->profileSetTemp);
			while($levelCount>0)
			{
				if($this->communityModelLogic)
					$this->logicLevel[]='111';
				else
					$this->logicLevel[]='11';
				$levelCount--;
			}
			//-----------
                        $this->profileSet=array_merge($this->profileSet,$this->profileSetTemp);
		}

		if(count($this->profileSet) <$maxLimit)//Relaxed forward only 15 days
		{
			$NTvNTlevel4++;
			$queryLimit=$maxLimit-count($this->profileSet);

			if($canUseRelaxation)
			{
				$this->filterBean->setLheight(intval($this->filterBean->getLheight())-$relaxlheight);
				$this->filterBean->setHheight(intval($this->filterBean->getHheight())+$relaxhheight);
				$this->filterBean->setLage(intval($this->filterBean->getLAge())-$relaxlage);
				$this->filterBean->setHage(intval($this->filterBean->getHAge())+$relaxhage);
				$this->setCasteRelaxation($this->receiverObj->getRecCaste(),$this->filterBean); 
			}

			$this->profileSetTemp=$this->runDBQuery($this->receiverObj,$this->filterBean,$this->profileSet,$this->db,$this->isMatchesTrending,$loginDtRelax1,1,1,$queryLimit,'ENTRY_DT DESC','','','','',$this->communityModelLogic);
			if($this->profileSetTemp)
			{
				//-----------
				$levelCount=count($this->profileSetTemp);
				while($levelCount>0)
				{
					if($this->communityModelLogic)
						$this->logicLevel[]='121';
					else
						$this->logicLevel[]='12';
					$levelCount--;
				}
				//-----------
                                $this->profileSet=array_merge($this->profileSet,$this->profileSetTemp);
			}
		}

		if(count($this->profileSet) <$maxLimit)//Relaxed forward only 60 days
                {
			$NTvNTlevel6++;
                        $queryLimit=$maxLimit-count($this->profileSet);
			if($canUseRelaxation)			
			{
				$this->filterBean->setLheight(intval($this->filterBean->getLheight())-$relaxlheight);
				$this->filterBean->setHheight(intval($this->filterBean->getHheight())+$relaxhheight);
				$this->filterBean->setLage(intval($this->filterBean->getLAge())-$relaxlage);
				$this->filterBean->setHage(intval($this->filterBean->getHAge())+$relaxhage);
				$this->setCasteRelaxation($this->receiverObj->getRecCaste(),$this->filterBean); 
			}

			$this->profileSetTemp=$this->runDBQuery($this->receiverObj,$this->filterBean,$this->profileSet,$this->db,$this->isMatchesTrending,$loginDtRelax2,1,1,$queryLimit,'LAST_LOGIN_DT DESC','','','','',$this->communityModelLogic);
			if($this->profileSetTemp)
			{
				//-----------
				$levelCount=count($this->profileSetTemp);
				while($levelCount>0)
				{
					if($this->communityModelLogic)
						$this->logicLevel[]='131';
					else
						$this->logicLevel[]='13';
					$levelCount--;
				}
				//-----------
                                $this->profileSet=array_merge($this->profileSet,$this->profileSetTemp);
			}	
                }

		$gap=configVariables::getNoOfDays();
		if($gap%7==$dayOfRelaxation)
		{
                        if(count($this->profileSet) <$maxLimit)//Relaxed Forward only + no_login_dt
                        {
                                $NTvNTlevel6++;
                                $queryLimit=$maxLimit-count($this->profileSet);
                                
				if($canUseRelaxation)			
				{
	                                $this->filterBean->setLheight(intval($this->filterBean->getLheight())-$relaxlheight);
        	                        $this->filterBean->setHheight(intval($this->filterBean->getHheight())+$relaxhheight);
                	                $this->filterBean->setLage(intval($this->filterBean->getLAge())-$relaxlage);
                        	        $this->filterBean->setHage(intval($this->filterBean->getHAge())+$relaxhage);
					$this->setCasteRelaxation($this->receiverObj->getRecCaste(),$this->filterBean); 
				}

                                $this->profileSetTemp=$this->runDBQuery($this->receiverObj,$this->filterBean,$this->profileSet,$this->db,$this->isMatchesTrending,'',1,1,$queryLimit,'LAST_LOGIN_DT DESC','','','','',$this->communityModelLogic);
                                if($this->profileSetTemp)
                                {
                                        $levelCount=count($this->profileSetTemp);
                                        while($levelCount>0)
                                        {
						if($this->communityModelLogic)
                                                	$this->logicLevel[]='141';
						else
                                                	$this->logicLevel[]='14';
                                                $levelCount--;
                                        }
                                        $this->profileSet=array_merge($this->profileSet,$this->profileSetTemp);
                                }
                        }

                }
		
                if(count($this->profileSet) <$maxLimit)
		{
			//Some Tracking Code
		}
		if(count($this->profileSet))
		{
			$this->logRecords($this->profileSet,$this->receiverObj->getPartnerProfile()->getProfileId(),$this->db,configVariables::$strategyNtVsNtLogic,$this->logicLevel,$this->frequency);
		}
		else
		{
			$levelreached=8;
			$gap=configVariables::getNoOfDays();
			$zeropid=$this->receiverObj->getPartnerProfile()->getProfileId();
			$sql_y="INSERT INTO matchalerts.ZERONTvNT(PROFILEID,DATE) VALUES($zeropid,$gap)";
                        mysql_query($sql_y,$this->db) or logerror1("In matchalert_mailer.php",$sql_y);
		}
	}
}
?>
