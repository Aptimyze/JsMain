<?php
include_once(JsConstants::$alertDocRoot."/newMatches/StrategyClass.php");

class StrategyNTvsNEW extends StrategyClass
{
	private $receiverObj; 
	private $db;
	private $profileSet=array();
	private $logicLevel=array();

	function __construct($receiverObj,$db) 
	{
		$this->receiverObj=$receiverObj;
		$this->db=$db;
    	}

	//This is the main function
	public function doProcessing()
	{
                $maxLimit=MailerConfigVariables::$maxLimitNewMatchesMails;
                $queryLimit=$maxLimit;

		$SearchServiceObj = new SearchService;
		$loggedInProfileObj = $this->receiverObj->getProfileObj();
		$paramArr["logic_used"] = MailerConfigVariables::$strategyNTvsNewLogic;
		$searchObj = new NewMatchesMailer($loggedInProfileObj);
		$searchObj->getSearchCriteria($paramArr);
		$SearchUtilityObj =  new SearchUtility;
		$noAwaitingContacts = 1;
		$SearchUtilityObj->removeProfileFromSearch($searchObj,'spaceSeperator',$loggedInProfileObj,'',$noAwaitingContacts);
		unset($SearchUtilityObj);
		$SearchServiceObj->callSortEngine($searchObj,'',$loggedInProfileObj);

		if($this->receiverObj->getSwitchToDpp()==1)
                        $canUseRelaxation=0;
                else
                        $canUseRelaxation=1;

		$searchObj->setNoOfResults($queryLimit);
		$respObj = $SearchServiceObj->performSearch($searchObj,"onlyResults",'','','',$loggedInProfileObj);
		if($respObj->getSearchResultsPidArr() && is_array($respObj->getSearchResultsPidArr()))
		{
			$this->profileSetTemp = $respObj->getSearchResultsPidArr();
			$totalResultsCount = $respObj->getTotalResults();
		}
		unset($respObj);

		if($this->profileSetTemp)
		{	
			$this->profileSet=array_merge($this->profileSet,$this->profileSetTemp);
			$levelCount=count($this->profileSetTemp);
			while($levelCount>0)
                        {
                              	$this->logicLevel[]='51';
                                $levelCount--;
                        }
		}
		unset($this->profileSetTemp);
		
		if(count($this->profileSet)<$maxLimit && $canUseRelaxation)	//RELAX EDUCATION AND OCCUPATION
		{
			$relaxCriteria = $this->getEducationOccupationCityRelaxation();

			$queryLimit = $maxLimit-count($this->profileSet);

			$searchObj->setNoOfResults($queryLimit);
			$searchObj->performRelaxation($this->getEducationOccupationCityRelaxation());
			if($this->profileSet)
			{
				if($searchObj->getIgnoreProfiles())
					$searchObj->setIgnoreProfiles($searchObj->getIgnoreProfiles()." ".implode(" ",$this->profileSet));
				else
					$searchObj->setIgnoreProfiles(implode(" ",$this->profileSet));
			}
			$respObj = $SearchServiceObj->performSearch($searchObj,"onlyResults",'','','',$loggedInProfileObj);
			if($respObj->getSearchResultsPidArr() && is_array($respObj->getSearchResultsPidArr()))
			{
				$this->profileSetTemp = $respObj->getSearchResultsPidArr();
				$totalResultsCount = $respObj->getTotalResults();
			}
			unset($respObj);

			if($this->profileSetTemp)
                	{
                        	$this->profileSet=array_merge($this->profileSet,$this->profileSetTemp);
                        	$levelCount=count($this->profileSetTemp);
                        	while($levelCount>0)
                        	{
                                	$this->logicLevel[]='52';
                                	$levelCount--;
                        	}
                	}
			unset($this->profileSetTemp);
		}

		if(count($this->profileSet)<$maxLimit && $canUseRelaxation)        //RELAX AGE,HEIGHT,CASTE as in matchalerts
                {
			$relaxCriteria = $relaxCriteria.",".$this->getAgeHeightCasteRelaxation();

			$queryLimit = $maxLimit-count($this->profileSet);

			$searchObj->setNoOfResults($queryLimit);
			$searchObj->performRelaxation($this->getAgeHeightCasteRelaxation());
			if($this->profileSet)
			{
				if($searchObj->getIgnoreProfiles())
					$searchObj->setIgnoreProfiles($searchObj->getIgnoreProfiles()." ".implode(" ",$this->profileSet));
				else
					$searchObj->setIgnoreProfiles(implode(" ",$this->profileSet));
			}
			$respObj = $SearchServiceObj->performSearch($searchObj,"onlyResults",'','','',$loggedInProfileObj);
			if($respObj->getSearchResultsPidArr() && is_array($respObj->getSearchResultsPidArr()))
			{
				$this->profileSetTemp = $respObj->getSearchResultsPidArr();
				$totalResultsCount = $respObj->getTotalResults();
			}
			unset($respObj);

			if($this->profileSetTemp)
                        {
                                $this->profileSet=array_merge($this->profileSet,$this->profileSetTemp);
                                $levelCount=count($this->profileSetTemp);
                                while($levelCount>0)
                                {
                                        $this->logicLevel[]='53';
                                        $levelCount--;
                                }
                        }
			unset($this->profileSetTemp);
		}

		if(count($this->profileSet))
                {
			if($totalResultsCount>MailerConfigVariables::$maxLimitNewMatchesMails)
				$is_more_link_required = "Y";
			else
				$is_more_link_required = "N";

                        $this->logRecordsNewMatchesMail($this->profileSet,$this->receiverObj->getProfileObj()->getPROFILEID(),$this->db,MailerConfigVariables::$strategyNTvsNewLogic,$this->logicLevel,$is_more_link_required,$relaxCriteria);
                }
                else
                {
                        $gap=MailerConfigVariables::getNoOfDays();
                        $zeropid=$this->receiverObj->getProfileObj()->getPROFILEID();
                        $sql_y="INSERT INTO new_matches_emails.ZERONTvNEW(PROFILEID,DATE) VALUES($zeropid,$gap)";
                        mysql_query($sql_y,$this->db) or logerror1("In matchalert_mailer.php",$sql_y);
                }
		unset($searchObj);
		unset($loggedInProfileObj);
	}
}
?>
