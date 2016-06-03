<?php

class sms_match_alert{
	function processData($key,$num)
	{
		$sms = new ScheduleSms;
		$finalSms = array();
		$details1=array();
		$details2=array();
		$chunk=2000;
		//echo $sms->getTempJPROFILE();die;
		if($key = "MATCH_ALERT")
		{
			$sql = "select ".$sms->getJPROFILEFields()." from ".$sms->getTempJPROFILE()." WHERE PROFILEID %3 = ".$num.$sms->getSmsSubscriptionCriteria($key);
			$res = mysql_query($sql,$sms->dbMaster) or $sms->SMSLib->errormail($sql,mysql_errno().":".mysql_error(),"Error occured while fetching details for SMS Key: ".$key." in processData() function");
	        $count = mysql_num_rows($res);
	        $chunk=2000;
	        $totalChunks=ceil($count/$chunk);
	        $today=mktime(0,0,0,date("m"),date("d"),date("Y")); //timestamp for today
			$zero=mktime(0,0,0,01,01,2006); //timestamp for 1 Jan 2006
			$gap=($today-$zero)/(24*60*60)-$sms->timeCriteria[$key];
			//$gap = 20;    //for testing
	        for($j = 0;$j<$totalChunks;$j++)
	        {
				$finalSms = array();
				$trans = 0;
				$row_pool = array();
				$skip = $j*$chunk;
				mysql_data_seek($res,$skip);
				while(($row=mysql_fetch_assoc($res)) && $trans<$chunk)
	            {
					$profileid = $row["PROFILEID"];
					$bestProfile = $sms->getBestMatchProfile($profileid);				
					if($bestProfile)
					{
						$row_pool[$row["PROFILEID"]]=$row;
						$row_pool[$row["PROFILEID"]]["BEST_MATCH"] = $bestProfile;
						$trans++;
					}
					else {
						$arr = $sms->matchAlertArray($profileid,$gap);
						if(!empty($arr)){
							$row_pool[$row["PROFILEID"]]=$row;
							$row_pool[$row["PROFILEID"]]["MATCH_ARRAY"] = $arr;
							$trans++;
						}
					}
				}
				
				if($row_pool)
				{
					foreach($row_pool as $row_k=>$row_v)
					{
						$profileid = $row_k;
						if($row_pool[$row_k]["BEST_MATCH"])
						{
							$bestProfileA = $row_pool[$row_k]["BEST_MATCH"];
							$updateFlag = 1;
						}
						else {
							$matchArr = $row_pool[$row_k]["MATCH_ARRAY"];
							$bestProfileA = $sms->getBestProfile($profileid,$gap,$matchArr);
						}
						if($bestProfileA)
						{
							$bestProfile = $bestProfileA['PROFILEID'];
							$bestProfileScore = $bestProfileA['VIEW_COUNT'];
							$bestProfileArr=array($bestProfile);
							$detail = $sms->getDetailArr($bestProfileArr,'getReceiverDetail');
							if(empty($detail))
							{
								continue;
							}
							$familyIncome = $detail[$bestProfile]["FAMILY_INCOME"];
							$finalSms[$key][$row_k]["RECEIVER"] = $row_v;
							if($familyIncome>4 && $familyIncome!=8 && $familyIncome!= 15 && $familyIncome != 19)
							{
								$finalSms[$key][$row_k]["RECEIVER"]["CUSTOM_CRITERIA"]= 1;
							}
							else
							{
								$finalSms[$key][$row_k]["RECEIVER"]["CUSTOM_CRITERIA"]= 0;
							}
							$finalSms[$key][$row_k]["DATA_TYPE"] = "OTHER";
							$finalSms[$key][$row_k]["DATA"] = $detail[$bestProfile];
							if($updateFlag!=1)
							{
								$sms->insertInBestSmsLog($profileid,$bestProfile,$bestProfileScore);
								$updateFlag = 0;
							}
						}
					}
					$sms->smsDetail = $finalSms;
					$sms->getSmsContent($key);
	                $sms->insertInSmsLog();
	                unset($sms->smsDetail);
				}
			}
		}
	}
}

?>
