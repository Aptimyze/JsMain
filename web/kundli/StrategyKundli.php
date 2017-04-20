<?php
include_once(JsConstants::$alertDocRoot."/new_matchalert/configVariables.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/StrategyClass.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/FilterBean.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/SortingArray.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/Sender.php");
include_once("KundliFunctions.class.php");

class StrategyKundli extends StrategyClass
{
	//this is for just demo of DPP to DPP
	
	private $receiverObj; 
	private $filterBean;
	private $db;
	private $myDbArr;
	private $mysqlObj;
	private $isMatchesTrending;
	private $profileSet=array();
	private $matchesSet=array();
	private $kundli_paid;
	private $start_dt;
	private $end_dt;

	function __construct($receiverObj,$db,$shardArr,$mysqlObj,$kundli_paid,$start_dt="",$end_dt="") 
	{
		$this->mysqlObj = $mysqlObj;
		$this->receiverObj=$receiverObj;
		$this->filterBean=new filterBean($db,$shardArr,$this->mysqlObj,'',1); 
		$this->filterBean->setForwardCriteria($this->receiverObj);
		$this->isMatchesTrending='K';//for kundli
		$this->db=$db;
		$this->myDbArr=$shardArr;
		$this->kundli_paid=$kundli_paid;
		$this->start_dt = $start_dt;
		$this->end_dt = $end_dt;
    	}

	public function fetchFromCache($mailerLimit,$kundliFunctionsObj)
	{
		$finalIds = array();
                $limit = $mailerLimit;
                while(count($finalIds)<$mailerLimit)
                {
                        $matchingIds = $kundliFunctionsObj->fetchMatchIds($limit);
                        if ($matchingIds)
                        {
                                $verifiedIds = $this->runDBQuery($this->receiverObj,$this->filterBean,$this->profileSet,$this->db,$this->isMatchesTrending,'','','','','',$this->kundli_paid,$matchingIds);
                                if(count($verifiedIds))
                                        $finalIds = array_merge($finalIds,$verifiedIds);
                                unset($matchingIds);
                                unset($verifiedIds);
                                if (count($finalIds)==$mailerLimit)
                                        break;
                                else
                                        $limit = $mailerLimit - count($finalIds);
                        }
                        else
                        {
                                break;
                      	}
                }
		return $finalIds;
	}

	//This is the main function
	public function doProcessing()
	{
                $maxLimit=configVariables::$maxLimitKundli;
		$mailerLimit=configVariables::$kundliMailLimit;
                $queryLimit=$maxLimit;
		global $api_output_failure;

		$kundliFunctionsObj = new KundliFunctions($this->db,$this->mysqlObj,$this->receiverObj->getPartnerProfile()->getProfileId());
		$finalIds = $this->fetchFromCache($mailerLimit,$kundliFunctionsObj);		

        	if(count($finalIds)<$mailerLimit)
		{
			$kundliFunctionsObj->unsetTempId();
			$this->profileSetTemp=$this->runDBQuery($this->receiverObj,$this->filterBean,$this->profileSet,$this->db,$this->isMatchesTrending,'','','','','ASTRO_ENTRY_DT DESC',$this->kundli_paid,'',$this->start_dt,$this->end_dt);
			if($this->profileSetTemp)
                	{	
                        	$this->profileSet=array_merge($this->profileSet,$this->profileSetTemp);
                	}

                	if(count($this->profileSet))
                	{
				$flag = 0;
				foreach($this->profileSet as $k=>$v)
				{
					$tempSet[] = $v;
					if(count($tempSet)==$maxLimit || $k==(count($this->profileSet)-1))
					{
						$api_param = $kundliFunctionsObj->fetchAstroDetails($tempSet,$this->receiverObj->getPartnerProfile()->getGENDER());
                                		$url = "https://vendors.vedic-astrology.net/cgi-bin/JeevanSathi_FindCompatibility_Matchstro.dll?SearchCompatiblityMultipleFull";
                                		$ch = curl_init($url);
                                		curl_setopt($ch, CURLOPT_HEADER, 0);
                                		curl_setopt($ch, CURLOPT_POST, 1);
                                		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                		curl_setopt($ch, CURLOPT_POSTFIELDS, $api_param);
						curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                                		$api_output = curl_exec($ch);
                                		curl_close($ch);
						if ($api_output)
                                		{
							$api_output_failure = 0;
                                        		$matchArr = $kundliFunctionsObj->handleAstroAPIOutput($api_output);
                                        		if($matchArr)
								$this->matchesSet=array_merge($this->matchesSet,$matchArr);

							unset($matchArr);
							if(count($this->matchesSet)>=$mailerLimit)
							{
                                                		$kundliFunctionsObj->performDbAction($this->matchesSet,$this->setTable($this->receiverObj->getPartnerProfile()->getGENDER(),$this->isMatchesTrending,$this->kundli_paid));
								$flag = 1;
								$kundliFunctionsObj->updateDate($this->setTable($this->receiverObj->getPartnerProfile()->getGENDER() , $this->isMatchesTrending,$this->kundli_paid),$this->profileSet[0],$tempSet[count($tempSet)-1],$this->kundli_paid,$this->start_dt,$this->end_dt);
								unset($finalIds);
								$finalIds = $kundliFunctionsObj->fetchMatchIds($mailerLimit);
								$kundliFunctionsObj->insertIntoMailerTable($finalIds,$this->kundli_paid);
                        					$kundliFunctionsObj->updateTable();
								unset($tempSet);
								unset($this->matchesSet);
								break;
							}
							else
							{
								unset($tempSet);
							}	
                                		}
                                		else
                                		{
							$api_output_failure++;
							if($api_output_failure==5)
							{
                                        			mail("lavesh.rawat@jeevansathi.com","no output from kundli mailer api consecutive 5 times","no output from kundli mailer api consecutive 5 times");
								die;
							}
							unset($tempSet);
							break;
                                		}
					}
				}
				if($flag==0)
				{
					$kundliFunctionsObj->updateDate($this->setTable($this->receiverObj->getPartnerProfile()->getGENDER() , $this->isMatchesTrending,$this->kundli_paid),'','',$this->kundli_paid,$this->start_dt,$this->end_dt);
					if($this->matchesSet)
					{
						$kundliFunctionsObj->performDbAction($this->matchesSet,$this->setTable($this->receiverObj->getPartnerProfile()->getGENDER(),$this->isMatchesTrending,$this->kundli_paid));
						unset($finalIds);
                                             	$finalIds = $kundliFunctionsObj->fetchMatchIds($mailerLimit);
                                            	$kundliFunctionsObj->insertIntoMailerTable($finalIds,$this->kundli_paid);
                                             	$kundliFunctionsObj->updateTable();
					}
				}
                	}
                	else
                	{
				$kundliFunctionsObj->updateDate($this->setTable($this->receiverObj->getPartnerProfile()->getGENDER() , $this->isMatchesTrending,$this->kundli_paid),'','',$this->kundli_paid,'','');
				$zeropid=$this->receiverObj->getPartnerProfile()->getProfileId();
                        	$sql_y="INSERT INTO kundli_alert.ZERO_KUNDLI(RECEIVER,DATE) VALUES($zeropid,now())";
                        	mysql_query($sql_y,$this->db) or logerror1("In StrategyKundli.php",$sql_y);
                	}
		}
		else
		{
			$kundliFunctionsObj->insertIntoMailerTable($finalIds,$this->kundli_paid);
			$kundliFunctionsObj->updateTable();
		}
	}
}
?>
