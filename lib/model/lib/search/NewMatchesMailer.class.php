<?php
//This class is for the new matches mailers. It is used in the matches generation logic and also on landing on the search page through "See New Matches" link in the mailer

class NewMatchesMailer extends SearchParamters
{
	private $pid;
	private $loggedInProfileObj;
	private $daysOldProfiles = 7;
        private $daysOldProfiles_3 = 3;
        private $daysOldProfiles_4 = 4;
	private $educationRelaxation = "E";
	private $occupationRelaxation = "O";
	private $casteRelaxation = "C";
	private $ageRelaxation = "A";
	private $heightRelaxation = "H";
	private $cityRelaxation = "R";
	
	public function __construct($loggedInProfileObj)
	{
		if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID())
			$this->pid = $loggedInProfileObj->getPROFILEID();
		$this->loggedInProfileObj = $loggedInProfileObj;
		parent::__construct();
	}
	
	/*
	This function sets the DPP criteria in the search object either from JPARTNER or TRENDS
	@param - array where index is the parameter name and its value is present in corresponding value
	*/
	public function getSearchCriteria($paramArr)
	{
		$pattern = "/^([A-Z0-9,])+$/";				//White Listing starts
		$logic_used = $paramArr["logic_used"];
		if($logic_used && !is_numeric($logic_used))
			$logic_used = "";
		$relax_value = $paramArr["relax"];
		if(!preg_match($pattern,$relax_value))
			$relax_value = "";
		$sent_date = $paramArr["sent_date"];
		if($sent_date && !is_numeric($sent_date))
                	$sent_date = "";				//White Listing ends
                
                
		if($logic_used==6)					//For Trends profile
		{
                        $searchCObj = new NewMatchesTrendsMailer($this->loggedInProfileObj);
                        
			$searchCObj->setSearchCriteria($this->pid);
                        
                        foreach($searchCObj->forwardCriteria as $k=>$v)	
			{
                                if(array_key_exists($v,$searchCObj->incomeStrings))
                                        $tempVal = $searchCObj->incomeStrings[$v];
                                else
                                        eval('$tempVal = $searchCObj->get'.$v.'();');
                                
				if($tempVal)
					eval('$this->set'.$v.'("'.$tempVal.'");');
			}
                        
                        foreach($searchCObj->trendsSearchReverseForwardCriteria as $k=>$v)	
			{
				$lincome = 100;
				$hincome = -1;
				$lincome_dol = 100;
				$hincome_dol = -1;
				$incomeStr = "";
				foreach($incomeFinalArr as $k=>$v)
				{
					if($v["SORTBY"]!=0)
					{
						if($v["TYPE"]=="RUPEES")
						{
							if($lincome>$v["MIN_VALUE"])
								$lincome = $v["MIN_VALUE"];
							if($hincome<$v["MAX_VALUE"])
								$hincome = $v["MAX_VALUE"];
						}
						elseif($v["TYPE"]=="DOLLARS")
						{
							if($lincome_dol>$v["MIN_VALUE"])
                                                                $lincome_dol = $v["MIN_VALUE"];
                                                        if($hincome_dol<$v["MAX_VALUE"])
                                                                $hincome_dol = $v["MAX_VALUE"];
						}
					}
					$incomeStr = $incomeStr.$v["VALUE"].",";
				}
				$incomeStr = rtrim($incomeStr,",");
				if($lincome==100)
					$lincome = "";
				if($hincome==-1)
					$hincome = "";
				if($lincome_dol==100)
					$lincome_dol = "";
				if($hincome_dol == -1)
					$hincome_dol = "";
			}						//Get LINCOME,HINCOME,LINCOME_DOL,HINCOME_DOL values from income ends
			unset($incomeFinalArr);

                        $this->setINCOME($incomeStr);
			$this->setLINCOME($lincome);
			$this->setHINCOME($hincome);
			$this->setLINCOME_DOL($lincome_dol);
			$this->setHINCOME_DOL($hincome_dol);

			$this->setMSTATUS_IGNORE(str_replace("'","",$obj->getPARTNER_MSTATUS_IGNORE()));
			$this->setMANGLIK_IGNORE(str_replace("'","",$obj->getPARTNER_MANGLIK_IGNORE()));
		}
		else				//For Non Trends profile
		{
			$obj = new PartnerProfile($this->loggedInProfileObj);
			$obj->getDppCriteria();
			$this->setGENDER($obj->getGENDER());
			$this->setLAGE($obj->getLAGE());
			$this->setHAGE($obj->getHAGE());
			$this->setLHEIGHT($obj->getLHEIGHT());
			$this->setHHEIGHT($obj->getHHEIGHT());
			$this->setMSTATUS($obj->getMSTATUS());
			$this->setRELIGION($obj->getRELIGION());
			$this->setMTONGUE($obj->getMTONGUE());
			$this->setCASTE($obj->getCASTE());
			$this->setEDU_LEVEL_NEW($obj->getEDU_LEVEL_NEW());
			$this->setOCCUPATION($obj->getOCCUPATION());
			$this->setCITY_RES($obj->getCITY_RES());
			$this->setCITY_INDIA($obj->getCITY_INDIA());
			$this->setINCOME($obj->getINCOME());
			$this->setLINCOME($obj->getLINCOME());
			$this->setHINCOME($obj->getHINCOME());
			$this->setLINCOME_DOL($obj->getLINCOME_DOL());
			$this->setHINCOME_DOL($obj->getHINCOME_DOL());
		}
		unset($obj);
		
		if($sent_date)		//If mailer sent date is available then find a 7 days before date from the sent date
		{
			$zero=mktime(0,0,0,01,01,2006); //timestamp for 1 Jan 2006
                	$sent_date = ($sent_date*24*60*60);
                	$sent_date = $sent_date + $zero;
			$sent_date = $sent_date - ($this->daysOldProfiles*24*60*60);
                	$dateString = date("Y-m-d",$sent_date);
		}
		else{			//If mailer sent date is not available then find a 7 days before date from the today's date
                        $day_of_week = date("D");
                        if($day_of_week == "Sat" || $day_of_week == "Sun") 
                            $dateString = date("Y-m-d",strtotime("-".$this->daysOldProfiles_4." days"));
                        else
                            $dateString = date("Y-m-d",strtotime("-".$this->daysOldProfiles_3." days"));
                }

		$this->setLAST_LOGIN_DT($dateString);
		$this->setSORT_LOGIC(SearchSortTypesEnums::newMatchesMailer);
		$this->setSEARCH_TYPE(SearchTypesEnums::NewMatchesMailer);

		if($this->loggedInProfileObj->getHIV()!="Y")		//not HIV
			$this->setHIV_IGNORE("Y");

		if($this->loggedInProfileObj->getHANDICAPPED()=='N' || $this->loggedInProfileObj->getHANDICAPPED()=='')		//not handicapped
			$recHandicapped='N';
		elseif(in_array($this->loggedInProfileObj->getHANDICAPPED(),array(1,2)))		//Physically handicapped
			$recHandicapped="A";
		elseif(in_array($this->loggedInProfileObj->getHANDICAPPED(),array(3,4)))		//Mentally handicapped
			$recHandicapped="B";
	
		if($recHandicapped=='N')
			$this->setHANDICAPPED_IGNORE("1,2,3,4");
		elseif($recHandicapped=='A')
			$this->setHANDICAPPED_IGNORE("3,4");
		elseif($recHandicapped=='B')
			$this->setHANDICAPPED_IGNORE("1,2");

		if($relax_value)			//If relax value is present then perform relaxation
			$this->performRelaxation($relax_value);
	}

	/*
	This function is used to perform relaxation
	@param - string with capital alphabets separated by comma where alphabets denotethe type of relaxation
	*/
	public function performRelaxation($relax_value)
	{
		if($relax_value)
		{
			$relaxArr = explode(",",$relax_value);
			foreach($relaxArr as $k=>$v)
			{
				if($v==$this->educationRelaxation)		//Education relaxation
				{
					$this->setEDU_LEVEL_NEW("");
				}
				elseif($v==$this->occupationRelaxation)		//Occupation relaxation
				{
					$this->setOCCUPATION("");
				}
				elseif($v==$this->cityRelaxation)		//City relaxation
				{
					$this->setCITY_RES("");
					$this->setCITY_INDIA("");
				}
				elseif($v==$this->casteRelaxation)		//Caste relaxation
				{
					if($this->loggedInProfileObj->getCASTE())
					{
						$crObj = new CasteRelaxation;
						$output = $crObj->getRelaxedCasteList($this->loggedInProfileObj->getCASTE());
						if($output && is_array($output))
						{
							$dppCaste = $this->getCASTE();
							if($dppCaste)
							{
								$tempArr = explode(",",$dppCaste);
								foreach($output as $k=>$v)
									$tempArr[] = $v;
								$tempArr = array_unique($tempArr);
								$casteStr = implode(",",$tempArr);
								unset($tempArr);
								unset($dppCaste);
							}
							else
							{
								$casteStr = implode(",",$output);
							}
							unset($output);
							$this->setCASTE($casteStr);
							unset($casteStr);
						}
						unset($crObj);
					}
				}
				elseif($v==$this->ageRelaxation)			//Age relaxation
				{
					$maxDiff = MailerConfigVariables::$maxAgeDiff;
					if($this->getGENDER()=='M')
					{
						$relaxValue = $this->getLAGE() - $this->loggedInProfileObj->getAGE();
						if($relaxValue>1)
							$relaxValue=2;
						elseif($relaxValue==1)
							$relaxValue=1;
						else
							$relaxValue=0;
					}
					else
					{
						$relaxValue = $this->loggedInProfileObj->getAGE() - $this->getLAGE();
						if(($maxDiff-$relaxValue)>1)
							$relaxValue=2;
						elseif(($maxDiff-$relaxValue)>0)
							$relaxValue=1;
						else
							$relaxValue=0;
					}
					$this->setLAGE($this->getLAGE()-$relaxValue);

					if($this->getGENDER()=='F')
					{
						$relaxValue = $this->loggedInProfileObj->getAGE() - $this->getHAGE();
						if($relaxValue>1)
							$relaxValue=2;
						elseif($relaxValue==1)
							$relaxValue=1;
						else
							$relaxValue=0;
					}
					else
					{
						$relaxValue = $this->getHAGE() - $this->loggedInProfileObj->getAGE();
						if(($maxDiff-$relaxValue)>1)
							$relaxValue=2;
						elseif(($maxDiff-$relaxValue)>0)
							$relaxValue=1;
						else
							$relaxValue=0;
					}
                        		$this->setHAGE($this->getHAGE()+$relaxValue);
				}
				elseif($v==$this->heightRelaxation)			//Height relaxation
				{
					$maxDiff = MailerConfigVariables::$maxHeightDiff;
                                        if($this->getGENDER()=='M')
                                        {
                                                $relaxValue = $this->getLHEIGHT() - $this->loggedInProfileObj->getHEIGHT();
                                                if($relaxValue>1)
                                                        $relaxValue=2;
                                                elseif($relaxValue==1)
                                                        $relaxValue=1;
                                                else
                                                        $relaxValue=0;
                                        }
                                        else
                                        {
                                                $relaxValue = $this->loggedInProfileObj->getHEIGHT() - $this->getLHEIGHT();
                                                if(($maxDiff-$relaxValue)>1)
                                                        $relaxValue=2;
                                                elseif(($maxDiff-$relaxValue)>0)
                                                        $relaxValue=1;
                                                else
                                                        $relaxValue=0;
                                        }
					$this->setLHEIGHT($this->getLHEIGHT()-$relaxValue);

					if($this->getGENDER()=='F')
                                        {
                                                $relaxValue = $this->loggedInProfileObj->getHEIGHT() - $this->getHHEIGHT();
                                                if($relaxValue>1)
                                                        $relaxValue=2;
                                                elseif($relaxValue==1)
                                                        $relaxValue=1;
                                                else
                                                        $relaxValue=0;
                                        }
                                        else
                                        {
                                                $relaxValue = $this->getHHEIGHT() - $this->loggedInProfileObj->getHEIGHT();
                                                if(($maxDiff-$relaxValue)>1)
                                                        $relaxValue=2;
                                                elseif(($maxDiff-$relaxValue)>0)
                                                        $relaxValue=1;
                                                else
                                                        $relaxValue=0;
                                        }
                        		$this->setHHEIGHT($this->getHHEIGHT()+$relaxValue);
				}
			}
		}
	}
}
?>
