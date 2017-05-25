<?php
class SendMatchAlert 
{
	private $profileId;
	private $DbArr, $localdb;
	private $Two_MA_Daily = 1;
	private $One_MA_Daily = 2;
	private $One_MA_Alternate_Days = 3;
	private $One_MA_Weekly = 4;
	private $dayForWeeklySendingMails = "Saturday";
        private	$dayForAlternateDaySendingMails = 1;		//1 for Odd Day and 0 for Even Day. If change to even then change the less than 60 days condition below
	private $dayForNotSendingT_NTandNT_NT = "Sudayyyyy";

	public function __construct($whichProfileId, $DbArr, $localdb, $mysqlObj)
	{
		$this->profileId = $whichProfileId;
		$this->DbArr = $DbArr;
		$this->localdb = $localdb;
		$this->mysqlObj = $mysqlObj;
	}
	
	public function send($idForTarcking) 
	{
		$db = $this->localdb;
		$myDbArr = $this->DbArr;
		$mysqlObj = $this->mysqlObj;
		$receiverObj=new Receiver($this->profileId,$db);//get receiver profile
		if($receiverObj->getSameGenderError()!='Y')
		{
			global $idForTarcking;

			unset($skipthis);
			if($receiverObj->getHasTrend()==true)
			{
				$sql="SELECT  COUNT(*) AS CNT from  newjs.MATCH_LOGIC WHERE LOGIC_STATUS='O' AND PROFILEID='$this->profileId'";
				$result=mysql_query($sql,$db) or logerror1("In SendMatchAlert.php",$sql);
				$row=mysql_fetch_array($result);
				if($row["CNT"]>0)
					$skipthis=1;
			}

			$freqOutput = $this->checkFrequency($receiverObj);
			if($freqOutput["TODAY_TO_SEND"]=="Y")
			{
				if($receiverObj->getHasTrend() != true || $skipthis)
				{
					if($this->profileId%2==0)
						$communityModelLogic=0;
					else
						$communityModelLogic=0;

					if(($freqOutput["MATCHALERT_TYPE"]=="BOTH" || $freqOutput["MATCHALERT_TYPE"]=="NT") && date("l")!=$this->dayForNotSendingT_NTandNT_NT)
					{
						$strategyNTvsNT=new StrategyNTvsNT($receiverObj,$db,$myDbArr,$mysqlObj,$communityModelLogic,$freqOutput["FREQUENCY"]);
						$strategyNTvsNT->doProcessing();
					}

					//$matchalertsTrends='Y';
					if($freqOutput["MATCHALERT_TYPE"]=="BOTH" || $freqOutput["MATCHALERT_TYPE"]=="T")
					{
						$strategyNTvsT=new StrategyNTvsT($receiverObj,$db,$myDbArr,$mysqlObj,$communityModelLogic,$freqOutput["FREQUENCY"]);
						$strategyNTvsT->doProcessing();
					}
					//$matchalertsTrends='';
				}
				else
				{
					if(($freqOutput["MATCHALERT_TYPE"]=="BOTH" || $freqOutput["MATCHALERT_TYPE"]=="NT") && date("l")!=$this->dayForNotSendingT_NTandNT_NT)
					{
						$strategyTvsNT=new StrategyTvsNT($receiverObj,$db,$myDbArr,$mysqlObj,$freqOutput["FREQUENCY"]);
						$strategyTvsNT->doProcessing();
					}
					//$matchalertsTrends='Y';
					if($freqOutput["MATCHALERT_TYPE"]=="BOTH" || $freqOutput["MATCHALERT_TYPE"]=="T")
					{
						$strategyTvsT=new StrategyTvsT($receiverObj,$db,$myDbArr,$mysqlObj,$freqOutput["FREQUENCY"]);
						$strategyTvsT->doProcessing();
					}
					//$matchalertsTrends='';
				}
			}
		}
		else
		{
			$sameGenderArr.=$profileid. " , ";
			//A mail will be send to us
		}
	}

	//This function is used to determine the matchalert frequency as per new logic defined in Trac 3023
	private function checkFrequency($receiverObj,$matchAlertType)
	{
		$db = $this->localdb;

		if($receiverObj->getRecLastLoginDt()!="0000-00-00" && strstr($receiverObj->getRecEmail(),"@gmail"))
		{
			$today=mktime(0,0,0,date("m"),date("d")-15,date("Y")); //timestamp for 15 days less than today
        		$zero=mktime(0,0,0,01,01,2006); //timestamp for 1 Jan 2006
        		$gap=($today-$zero)/(24*60*60); //$gap is the no. of days since 1 Jan 2006.

			$days = round(abs(strtotime(date("Y-m-d"))-strtotime($receiverObj->getRecLastLoginDt()))/(60*60*24));

			if($days>15)
			{
				$sql="SELECT DATE from  matchalerts.TOP_VIEW_COUNT WHERE PROFILEID='$this->profileId' ORDER BY DATE DESC LIMIT 1";
                       		$result=mysql_query($sql,$db) or logerror1("In SendMatchAlert.php",$sql);
                     		$row=mysql_fetch_array($result);
			}

                      	if(!$row["DATE"] || $row["DATE"]<$gap)
			{

				if($days<=15)
					$output = null;
				elseif($days<=30)
				{
					$output["FREQUENCY"] = $this->One_MA_Daily;
					$output["TODAY_TO_SEND"] = "Y";
					if(date("j")%2 == 0)
						$output["MATCHALERT_TYPE"] = "NT";
					else
						$output["MATCHALERT_TYPE"] = "T";
				}
				elseif($days<=60)
				{
					$output["FREQUENCY"] = $this->One_MA_Alternate_Days;
					if(date("j")%2 == $this->dayForAlternateDaySendingMails)
						$output["TODAY_TO_SEND"] = "Y";
					else
						$output["TODAY_TO_SEND"] = "N";
					if(date("j")%4 == 1)
                                                $output["MATCHALERT_TYPE"] = "T";
					else
						$output["MATCHALERT_TYPE"] = "NT";
				}
				else
				{
					$output["FREQUENCY"] = $this->One_MA_Weekly;
					if(date("l") == $this->dayForWeeklySendingMails)	
						$output["TODAY_TO_SEND"] = "Y";
					else
						$output["TODAY_TO_SEND"] = "N";
					if((date("j")/7)%2==0)
                                                $output["MATCHALERT_TYPE"] = "T";
					else
						$output["MATCHALERT_TYPE"] = "NT";
				}
			}
		}
		if(!$output)
		{
			$output["FREQUENCY"] = $this->Two_MA_Daily;
			$output["TODAY_TO_SEND"] = "Y";
			$output["MATCHALERT_TYPE"] = "BOTH";	//Both
		}

		return $output;
	}	
}
?>
