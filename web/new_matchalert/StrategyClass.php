<?php
include_once(JsConstants::$alertDocRoot."/commonFiles/dropdowns.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/matchalert_dropdowns.php");
abstract class StrategyClass 
{
	public abstract function doProcessing();
	private $fieldList = "S.PROFILEID AS PROFILEID,AGE_FILTER,CASTE_FILTER,PARTNER_CASTE,MTONGUE_FILTER,PARTNER_MTONGUE,COUNTRY_RES_FILTER,PARTNER_COUNTRYRES,MSTATUS_FILTER,PARTNER_MSTATUS,INCOME_FILTER,PARTNER_INCOME,CITY_RES_FILTER,PARTNER_CITYRES,PARTNER_LAGE,PARTNER_HAGE,PARTNER_RELIGION,RELIGION_FILTER";

	//----DVD-----
	function logRecordsDvD($resultArr,$receiverProfileid,$db)
	{
		$str=implode(",",$resultArr);
		$sql="INSERT INTO matchalerts.DvDLogs(RECEIVER,DATE,MATCHES) VALUES ('$receiverProfileid',now(),'$str')";
		mysql_query($sql,$db)  or logerror1("In DVDmatchalert_mailer.php",$sql);
	}
	//----DVD-----

	public function logRecords($resultArr,$receiverProfileid,$db,$logic,$logiclevelArr,$frequency)
	{
		global $idForTarcking;

		$gap=configVariables::getNoOfDays();

		foreach($resultArr as $k=>$v) 
		{
			$logiclevelValue=$logiclevelArr[$k];//---------
			$insertArr[]="('$receiverProfileid','$v',$gap,$logiclevelValue)";//------------
			$valueArr[]=$v;
		}
		if($insertArr)
		{
			$sql="INSERT INTO matchalerts.MAILER (RECEIVER";
			$n=count($insertArr);
			for($i=1;$i<=$n;$i++)
			{
				$sql.=",USER$i";
			}
			$valueStr=implode(",",$valueArr);
			$sql.=",LOGIC_USED,FREQUENCY) VALUES ($receiverProfileid,$valueStr,$logic,$frequency)";
			mysql_query($sql,$db)  or logerror1("In matchalert_mailer.php",$sql);
			$insertStr=implode(",",$insertArr);
			$sql_log="INSERT INTO matchalerts.LOG (RECEIVER,USER,DATE,LOGICLEVEL) VALUES $insertStr";
			mysql_query($sql_log,$db)  or logerror1("In matchalert_mailer.php",$sql_log);
                        
                        $sql_temp="INSERT INTO matchalerts.LOG_TEMP (RECEIVER,USER,DATE,LOGICLEVEL) VALUES $insertStr";
			mysql_query($sql_temp,$db)  or logerror1("In matchalert_mailer.php",$sql_temp);

        	        $sql_y="UPDATE matchalerts.TEMP_RESULT_COUNT set RESULTS$n=RESULTS$n+1 WHERE LOGIC='$logic'";
	                mysql_query($sql_y,$db) or logerror1("In matchalert_mailer.php",$sql_y);

	                $sql_y="INSERT INTO matchalerts.NEW_LOGIC_MATCHALERTS (ID,LOGIC,PROFILEID,NO_OF_RES) VALUES('$idForTarcking',$logic,$receiverProfileid,$n)";
	                mysql_query($sql_y,$db) or logerror1("In matchalert_mailer.php",$sql_y);
		}
	}

	public function runDBQuery($receiverObj,$filterBean,$profileSet,$db,$isMatchesTrending,$loginDateRelaxation,$forwardFilter='',$relaxForwardFilter='',$limit='',$sortBy='',$kundli_paid='',$matchingIds='',$start_dt='',$end_dt='',$CommunityModelLogic='')
        {
		global $db_fast;
                $whereArr=$filterBean->getFilterCriteriaArray($relaxForwardFilter,$receiverObj);
                if($filterBean->getSkippedRecords() || $profileSet)
                {
                        if($profileSet)
                                $skipStr="'".implode("','",$profileSet)."'";
                        if($profileSet && $filterBean->getSkippedRecords())
                        {
                                $skipStr=",".$skipStr;
                                $whereArr[]="S.PROFILEID NOT IN (".$filterBean->getSkippedRecords().$skipStr.")";
                        }
                        elseif($filterBean->getSkippedRecords())
                                $whereArr[]="S.PROFILEID NOT IN (".$filterBean->getSkippedRecords().")";
                        else
                                $whereArr[]="S.PROFILEID NOT IN ($skipStr)";

                }
                if($filterBean->getReverseCriteria() && !$forwardFilter)
                {
                        $whereArr[]="(PARTNER_CASTE LIKE \"%"."'".$filterBean->getReverseCriteria()."'"."%\" OR PARTNER_CASTE='')";
                }

                if($loginDateRelaxation)
		{
			$dt=date("Y-m-d",(time()-$loginDateRelaxation*24*60*60));
			$whereArr[]="LAST_LOGIN_DT >='".$dt."'";
			
		}
		
		if($start_dt && $end_dt)
		{
			$whereArr[]="(ASTRO_ENTRY_DT>\"".$start_dt."\" OR ASTRO_ENTRY_DT<\"".$end_dt."\")";
		}		

		//KEEP THIS CONDITION FOR WHERE ARRAY AS LAST CONDITION
		if($matchingIds)
		{
			$whereArr[]="S.PROFILEID IN (".implode(",",$matchingIds).") ORDER BY FIELD (S.PROFILEID,".implode(",",$matchingIds).")";
		}
		//KEEP THIS CONDITION FOR WHERE ARRAY AS LAST CONDITION

		if($CommunityModelLogic)	//NT-NT and NT-T logic
		{
			$sql = $this->getDrivingQueryForCommunityModelLogic($receiverObj,$this->setTable($receiverObj->getPartnerProfile()->getGENDER() , $isMatchesTrending,$kundli_paid),$whereArr,$CommunityModelLogic);
			if($sortBy)
                                $sql= $sql.",".$sortBy." ";
		}
		else
		{
                	$wherestr=implode(" AND " ,$whereArr);
                	 $sql="SELECT ".$this->fieldList." FROM ".$this->setTable($receiverObj->getPartnerProfile()->getGENDER() , $isMatchesTrending,$kundli_paid)." WHERE ".$wherestr;
                	if($sortBy)
                        	$sql.=" ORDER BY $sortBy ";
		}
//echo $sql."\n\n\n\n"; die;
//$limit='100';
		//Later We will move this to NTvsNT
		if($isMatchesTrending=='N' && $receiverObj->getHasTrend() != true) //NT-NT
		{
			for($i=0;$i<configVariables::$queryLimitForOptimizationMax;$i=$i+configVariables::$queryLimitForOptimization)
			{
				//$j=$i+configVariables::$queryLimitForOptimization;
				$j=configVariables::$queryLimitForOptimization;
				$finallimit="$i,$j";
				if($limit)
				{
					$sqlfinal=$sql." LIMIT $finallimit ";
				//$res=mysql_query($sqlfinal,$db) or logerror1("In matchalert_mailer.php",$sqlfinal);
				$res=mysql_query($sqlfinal,$db_fast) or logerror1("In matchalert_mailer.php",$sqlfinal);
				$limitReached=1;
				while($row=mysql_fetch_array($res))
				{
					$limitReached=0;
					$toBeInclude=0;
					$toBeInclude=$this->isMatchesFilterPassed($row,$receiverObj);
					if($toBeInclude)
					{
						$profileSet1[]=$row["PROFILEID"];
						if(count($profileSet1)>=$limit)
							$limitReached=1;
					}
					if($limitReached)
						break;
				}
				}
				if($limitReached)
					break;
			}
		}
		//Later We will move this to NTvsNT
		else
		{
			//$res=mysql_query($sql,$db) or logerror1("In matchalert_mailer.php",$sql);
			$res=mysql_query($sql,$db_fast) or logerror1("In matchalert_mailer.php",$sql);
			while($row=mysql_fetch_array($res))
			{
				$toBeInclude=0;
				$toBeInclude=$this->isMatchesFilterPassed($row,$receiverObj);
				if($toBeInclude)
	                		$profileSet1[]=$row["PROFILEID"];
				if(count($profileSet1)>=$limit && $limit)
                                        break;
			}
		}
		//echo "passed";
		return $profileSet1;
	}
	
	public function setTable($partnerGender , $isMatchesTrending,$kundli_paid="")
	{
                if($partnerGender=='M')
                {
                        if($isMatchesTrending=='Y')
                                $table="TRENDS_SEARCH_MALE";
			//----DVD-----
                        elseif($isMatchesTrending=='D')
                                $table="SEARCH_MALE";
			//----DVD-----
			//----KUNDLI-----
                        elseif($isMatchesTrending=='K')
			{
				if($kundli_paid)
                                	$table="kundli_alert.SEARCH_MALE_PAID";
				else
					$table="kundli_alert.SEARCH_MALE_UNPAID";
			}
			//----KUNDLI-----
                        else
                                $table="NOTRENDS_SEARCH_MALE";
                }
                else
                {
                        if($isMatchesTrending=='Y')
                                $table="TRENDS_SEARCH_FEMALE";
			//----DVD-----
                        elseif($isMatchesTrending=='D')
                                $table="SEARCH_FEMALE";
			//----DVD-----
			//----KUNDLI-----
                        elseif($isMatchesTrending=='K')
			{
				if($kundli_paid)
                                	$table="kundli_alert.SEARCH_FEMALE_PAID";
				else
					$table="kundli_alert.SEARCH_FEMALE_UNPAID";
			}
			//----KUNDLI-----
                        else
                                $table="NOTRENDS_SEARCH_FEMALE";
                }
		return $table." S";
	}
	
        public function checkManagerFilterRange($isFilterSet,$matchValue1,$matchValue2,$myValue)
        {
                if($isFilterSet=='Y' && $matchValue1 && $matchValue2)
                {
			if($myValue>=$matchValue1 && $myValue<=$matchValue2)
                                return 1;
                        return 0;
                }
                return 1;
        }

	
        public function checkManagerFilter($isFilterSet,$matchValue,$myValue)
        {
                if($isFilterSet=='Y' && $matchValue)
                {
                        $myValue="'".$myValue."'";
                        if(strstr($matchValue,$myValue))
                                return 1;
                        return 0;
                }
                return 1;
        }

	public function isMatchesFilterPassed($row,$receiverObj)
	{
		$toBeInclude=1;

		if($toBeInclude)
			$toBeInclude=$this->checkManagerFilterRange($row["AGE_FILTER"],$row["PARTNER_LAGE"],$row["PARTNER_HAGE"],$receiverObj->getRecAge());
		if($toBeInclude)
			$toBeInclude=$this->checkManagerFilter($row["RELIGION_FILTER"],$row["PARTNER_RELIGION"],$receiverObj->getRecReligion());
		if($toBeInclude)
			$toBeInclude=$this->checkManagerFilter($row["CASTE_FILTER"],$row["PARTNER_CASTE"],$receiverObj->getRecCaste());

		if($toBeInclude)
		{
			$toBeInclude=$this->checkManagerFilter($row["MTONGUE_FILTER"],$row["PARTNER_MTONGUE"],$receiverObj->getRecMtongue());
			if($toBeInclude)
			{
				$toBeInclude=$this->checkManagerFilter($row["COUNTRY_RES_FILTER"],$row["PARTNER_COUNTRYRES"],$receiverObj->getRecCountry());
				if($toBeInclude)
				{
					$toBeInclude=$this->checkManagerFilter($row["MSTATUS_FILTER"],$row["PARTNER_MSTATUS"],$receiverObj->getRecMstatus());
					if($toBeInclude)
					{
						$toBeInclude=$this->checkManagerFilter($row["INCOME_FILTER"],$row["PARTNER_INCOME"],$receiverObj->getRecIncome());
						if($toBeInclude)
						{
							$toBeInclude=$this->checkManagerFilter($row["CITY_RES_FILTER"],$row["PARTNER_CITYRES"],$receiverObj->getRecCity());
						}
					}
				}
			}
		}
		return $toBeInclude;
		//if($toBeInclude)
			//$profileSet1[]=$row["PROFILEID"];
	}

	public function setCasteRelaxation($caste,$filterBeanObj)
	{
		if($caste)
		{
			$crObj = new CasteRelaxation("matchalerts_slave_localhost");
			$output = $crObj->getRelaxedCasteList($caste);
			if($output && is_array($output))
			{
				$dppCaste = $filterBeanObj->getCaste();
				if($dppCaste)
				{
					$dppCaste = str_replace("'","",$dppCaste);
					$tempArr = explode(",",$dppCaste);
					foreach($output as $k=>$v)
						$tempArr[] = $v;
					$tempArr = array_unique($tempArr);
					$casteStr = "'".implode("','",$tempArr)."'";
					unset($tempArr);
					unset($dppCaste);
				}
				else
				{
					$casteStr = "'".implode("','",$output)."'";
				}
				unset($output);
				$filterBeanObj->setCaste($casteStr);
				unset($casteStr);
			}
			unset($crObj);
		}
	}

	private function getDrivingQueryForCommunityModelLogic($receiverObj,$tableName,$whereArr='',$CommunityModelLogic='')
	{
		global $db_fast,$MTONGUE_REGION_LABEL,$MTONGUE_REGION_DROP,$FINAL_WEIGHTS_ARRAY,$STATE_ZONE_ARRAY;

		$h1 = $this->generateHiddenWeightsLogic("H1");
		$h2 = $this->generateHiddenWeightsLogic("H2");
		$h3 = $this->generateHiddenWeightsLogic("H3");
		$h4 = $this->generateHiddenWeightsLogic("H4");
		$h5 = $this->generateHiddenWeightsLogic("H5");
		//echo $h1."\n\n".$h2."\n\n".$h3."\n\n".$h4."\n\n".$h5; die;
		
		if($CommunityModelLogic==2)		//NT-T
			$sql1 = $this->reverseTrendsScoreLogicGenerate($receiverObj);

		$sql = "SELECT ".$this->fieldList.",((".$FINAL_WEIGHTS_ARRAY["constant"].")+".$h1."+".$h2."+".$h3."+".$h4."+".$h5.") AS NT_MATCHING_SCORE ";

		if($CommunityModelLogic==2)		//NT-T
		{
			$sql = $sql.",".$sql1." ";
			unset($sql1);
		}

		$sql = $sql."FROM ";

		if($CommunityModelLogic==2)             //NT-T
			$sql=$sql."(";

		$sql = $sql."(((((((((".$tableName." LEFT JOIN bias_community_global com ON S.MTONGUE = com.receiver_community) LEFT JOIN bias_caste_global ct ON S.CASTE = ct.receiver_caste) LEFT JOIN bias_edu_level_new_global el ON S.EDU_LEVEL_NEW = el.receiver_edu_level) LEFT JOIN bias_height_global h ON S.HEIGHT = h.receiver_height) LEFT JOIN bias_income_global i ON S.INCOME = i.receiver_income) LEFT JOIN bias_occupation_global o ON S.OCCUPATION = o.receiver_occupation) LEFT JOIN bias_btype_global b ON S.BTYPE = b.receiver_btype) LEFT JOIN ";

		if($receiverObj->getRecGender()=='M')
			$sql = $sql."bias_cczone_global_sender_male ";
		else
			$sql = $sql."bias_cczone_global_sender_female ";

		$sql = $sql."cm ON S.MTONGUE_ZONE = cm.receiver_commzone AND S.CITY_ZONE = cm.receiver_cityzone) LEFT JOIN ";

		if($receiverObj->getRecGender()=='M')
			$sql = $sql."bias_age_global_sender_male_new ";
		else
			$sql = $sql."bias_age_global_sender_female_new ";

		$sql = $sql."am ON S.AGE = am.receiver_age AND S.MSTATUS_MATCHALERT = am.receiver_ref_mstatus) ";

		if($CommunityModelLogic==2)             //NT-T
		{
			if($receiverObj->getPartnerProfile()->getGENDER()=='F')
                                $table="FEMALE_TRENDS_HEAP th";
                        else
                                $table="MALE_TRENDS_HEAP th";

			$sql=$sql."LEFT JOIN ".$table." ON S.PROFILEID = th.PROFILEID) ";
			unset($table);
		}

		$sql = $sql."WHERE ";

		if($receiverObj->getRecMstatus()=='N')
			$sender_ref_mstatus='N';
		else
			$sender_ref_mstatus='M';

		if($receiverObj->getRecCountry()==51)
			$sender_cityzone = $STATE_ZONE_ARRAY[substr($receiverObj->getRecCity(),0,2)];
		else
			$sender_cityzone = 'F';

		
		foreach($MTONGUE_REGION_DROP as $k=>$v)
		{
			if($k==5)
				continue;
			$tempArr = explode(",",$v);
			foreach($tempArr as $kk=>$vv)
			{
				if($vv==$receiverObj->getRecMtongue())
				{
					$sender_commzone = substr($MTONGUE_REGION_LABEL[$k],0,1);
					break;
				}
			}
		}

		if($sender_commzone == 'O')
			$sender_commzone = 'F';

		$whereArr[] = "com.sender_community = '".$receiverObj->getRecMtongue()."' AND ct.sender_caste = '".$receiverObj->getRecCaste()."' AND el.sender_edu_level = '".$receiverObj->getRecEdu()."' AND el.sender_gender = '".$receiverObj->getRecGender()."' AND el.receiver_gender = '".$receiverObj->getPartnerProfile()->getGENDER()."' AND h.sender_height = '".$receiverObj->getRecHeight()."' AND h.sender_gender = '".$receiverObj->getRecGender()."' AND h.receiver_gender = '".$receiverObj->getPartnerProfile()->getGENDER()."' AND i.sender_income = '".$receiverObj->getRecIncome()."' AND i.sender_gender = '".$receiverObj->getRecGender()."' AND i.receiver_gender = '".$receiverObj->getPartnerProfile()->getGENDER()."' AND o.sender_occupation = '".$receiverObj->getRecOcc()."' AND o.sender_gender = '".$receiverObj->getRecGender()."' AND o.receiver_gender = '".$receiverObj->getPartnerProfile()->getGENDER()."' AND b.sender_btype = '".$receiverObj->getRecBtype()."' AND b.sender_gender = '".$receiverObj->getRecGender()."' AND b.receiver_gender = '".$receiverObj->getPartnerProfile()->getGENDER()."' AND cm.sender_commzone = '".$sender_commzone."' AND cm.sender_cityzone='".$sender_cityzone."' AND am.sender_age = '".$receiverObj->getRecAge()."' AND am.sender_ref_mstatus = '".$sender_ref_mstatus."'";	

		$wherestr = implode(" AND ",$whereArr);	

		$sql=$sql.$wherestr." ORDER BY NT_MATCHING_SCORE DESC ";

		return $sql;	
	} 

	private function generateHiddenWeightsLogic($label)
	{
		global $HIDDEN_WEIGHTS_ARRAY,$FINAL_WEIGHTS_ARRAY;

		$str = "(".$FINAL_WEIGHTS_ARRAY[$label]."*(POW((1 + EXP(-((".($HIDDEN_WEIGHTS_ARRAY[$label]["constant"]*1).") + (".$HIDDEN_WEIGHTS_ARRAY[$label]["bias_age"]."*am.matching_score_age) + (".$HIDDEN_WEIGHTS_ARRAY[$label]["bias_community"]."*com.matching_score_community) + (".$HIDDEN_WEIGHTS_ARRAY[$label]["bias_occupation"]."*o.matching_score_occupation) + (".$HIDDEN_WEIGHTS_ARRAY[$label]["bias_edu_level"]."*el.matching_score_edu_level) + (".$HIDDEN_WEIGHTS_ARRAY[$label]["bias_height"]."*h.matching_score_height) + (".$HIDDEN_WEIGHTS_ARRAY[$label]["bias_income"]."*i.matching_score_income) + (".$HIDDEN_WEIGHTS_ARRAY[$label]["bias_caste"]."*ct.matching_score_caste) + (".$HIDDEN_WEIGHTS_ARRAY[$label]["bias_btype"]."*b.matching_score_btype) + (".$HIDDEN_WEIGHTS_ARRAY[$label]["bias_cczone"]."*cm.matching_score_cczone)))),-1)))";
		//$str = $str." AS ".$label;
		return $str;
	}

	private function reverseTrendsScoreLogicGenerate($receiverObj)
	{
		$fieldArr = array("CASTE","MTONGUE","AGE","INCOME","HEIGHT","EDUCATION","OCCUPATION","CITY");
		$sql="";
		foreach($fieldArr as $k=>$v)
		{
			if($v=="CASTE")
				$val = $receiverObj->getRecCaste();
			elseif($v=="MTONGUE")
				$val = $receiverObj->getRecMtongue();
			elseif($v=="AGE")
				$val = $receiverObj->getRecAge();
			elseif($v=="INCOME")
				$val = $receiverObj->getRecIncome();
			elseif($v=="HEIGHT")
				$val = $receiverObj->getRecHeight();
			elseif($v=="EDUCATION")
				$val = $receiverObj->getRecEdu();
			elseif($v=="OCCUPATION")
				$val = $receiverObj->getRecOcc();
			elseif($v=="CITY")
				$val = $receiverObj->getRecCity();

			$sql = $sql."(W_".$v."*(IF(POSITION('|".$val."#' IN ".$v."_VALUE_PERCENTILE)>0,SUBSTRING(SUBSTRING_INDEX(".$v."_VALUE_PERCENTILE,'|".$val."#',-1),1,POSITION('|' IN SUBSTRING_INDEX(".$v."_VALUE_PERCENTILE,'|".$val."#',-1))-1),POSITION('|".$val."#' IN ".$v."_VALUE_PERCENTILE)))) + ";
		}
		unset($val);
		unset($fieldArr);

		if($receiverObj->getRecMstatus()!='N')
                    	$sql = $sql."(W_MSTATUS * MSTATUS_M_P) + ";
          	else
                    	$sql = $sql."(W_MSTATUS * MSTATUS_N_P) + ";

		if($receiverObj->getRecManglik()=='M')
                    	$sql = $sql."(W_MANGLIK * MANGLIK_M_P) + ";
            	else
                    	$sql = $sql."(W_MANGLIK * MANGLIK_N_P) + ";

		if($receiverObj->getRecCountry()=='51')
                  	$sql = $sql."(W_NRI * NRI_N_P)";
              	else
                  	$sql = $sql."(W_NRI * NRI_M_P)";

		$sql = "IF((((".$sql.")*100)/(IF(MAX_SCORE,MAX_SCORE,100))),(((".$sql.")*100)/(IF(MAX_SCORE,MAX_SCORE,100))),0) AS REVERSE_TREND_SCORE";
		return $sql;
	}
}
?>
