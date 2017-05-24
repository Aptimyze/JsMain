<?php
include_once(JsConstants::$alertDocRoot."/new_matchalert/SortingArray.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/Sender.php");
include_once(JsConstants::$alertDocRoot."/commonFiles/incomeCommonFunctions.inc");
class TrendsUtility
{
	
	public function calculateForwardScrore($profileId,$db,$profileSetTemp,$strategyTT='')
	{
		global $db_fast;
		$sortingArray=new SortingArray();
		$sql="select PROFILEID,W_CASTE,CASTE_VALUE_PERCENTILE,W_MTONGUE,MTONGUE_VALUE_PERCENTILE,W_AGE,AGE_VALUE_PERCENTILE,W_HEIGHT,HEIGHT_VALUE_PERCENTILE,W_EDUCATION,EDUCATION_VALUE_PERCENTILE,W_OCCUPATION,OCCUPATION_VALUE_PERCENTILE,W_CITY,CITY_VALUE_PERCENTILE,W_NRI,NRI_N_P,NRI_M_P,W_MSTATUS,MSTATUS_M_P,MSTATUS_N_P,W_MANGLIK,MANGLIK_M_P,MANGLIK_N_P,GENDER,MAX_SCORE,W_INCOME,INCOME_VALUE_PERCENTILE from TRENDS where PROFILEID=".$profileId;
		$res=mysql_query($sql,$db) or logerror1("In matchalert_mailer.php",$sql);
		while($row=mysql_fetch_array($res))
		{   
			$w_profileID=$row["PROFILEID"];
			$w_caste=$row["W_CASTE"];
			$w_casteValue=$row["CASTE_VALUE_PERCENTILE"];
			$w_mtongue=$row["W_MTONGUE"];
			$w_mtongueValue=$row["MTONGUE_VALUE_PERCENTILE"];
			$w_age=$row["W_AGE"];
			$w_ageValue=$row["AGE_VALUE_PERCENTILE"];
			$w_height=$row["W_HEIGHT"];
			$w_heightValue=$row["HEIGHT_VALUE_PERCENTILE"];
			$w_education=$row["W_EDUCATION"];
			$w_educationValue=$row["EDUCATION_VALUE_PERCENTILE"];
			$w_occupation=$row["W_OCCUPATION"];   
			$w_occupationValue=$row["OCCUPATION_VALUE_PERCENTILE"];
			$w_city=$row["W_CITY"];
			$w_cityValue=$row["CITY_VALUE_PERCENTILE"];   
			$w_income=$row["W_INCOME"];
			$w_incomeValue=$row["INCOME_VALUE_PERCENTILE"];

			$w_nri=$row["W_NRI"];
		        $nri_n_p=$row["NRI_N_P"];
                	$nri_m_p=$row["NRI_M_P"];

 	                $w_mstatus=$row["W_MSTATUS"];
        	        $mstatus_m_p=$row["MSTATUS_M_P"];
                	$mstatus_n_p=$row["MSTATUS_N_P"];

	                $w_manglik=$row["W_MANGLIK"];
        	        $manglik_m_p=$row["MANGLIK_M_P"];
                	$manglik_n_p=$row["MANGLIK_N_P"];

			$gender=$row["GENDER"];

			if($strategyTT)
			{
				if($gender=='F')
					$table="HEAP_TRENDS_MALE";
				else
					$table="HEAP_TRENDS_FEMALE";
			}
			else
			{
				if($gender=='F')
					$table="HEAP_NOTRENDS_MALE";
				else
					$table="HEAP_NOTRENDS_FEMALE";
			}
			$max_score=$row["MAX_SCORE"];
		}

		$str=implode(",",$profileSetTemp);
 	        $sql1="select PROFILEID,AGE,CASTE,MTONGUE,HEIGHT,EDU_LEVEL_NEW,OCCUPATION,CITY_RES,COUNTRY_RES,MSTATUS,MANGLIK,ENTRY_DT,INCOME from $table WHERE PROFILEID IN(".$str.")";
		$res1=mysql_query($sql1,$db_fast) or logerror1("In matchalert_mailer.php",$sql1);
		while($row=mysql_fetch_array($res1))
		//foreach($profileSetTemp as $k=>$v)
		{
			$profileId=$row["PROFILEID"];
			$age=$row["AGE"];
			//$age=$matchesGlobalInfo[$v]->getAge();
			$age_score=0;	
			$age_score=$this->calculateScore($w_age,$w_ageValue,$age);

			$caste=$row["CASTE"];
			//$caste=$matchesGlobalInfo[$v]->getCaste();
			$caste_score=0;
			$caste_score=$this->calculateScore($w_caste,$w_casteValue,$caste);

                        $mtongue=$row["MTONGUE"];
			//$mtongue=$matchesGlobalInfo[$v]->getMtongue();
                        $mtongue_score=0;
                        $mtongue_score=$this->calculateScore($w_mtongue,$w_mtongueValue,$mtongue);
			
			$height=$row["HEIGHT"];
			//$height=$matchesGlobalInfo[$v]->getHeight();
                        $height_score=0;
                        $height_score=$this->calculateScore($w_height,$w_heightValue,$height);

                        $education=$row["EDU_LEVEL_NEW"];
			//$education=$matchesGlobalInfo[$v]->getEdu_level();
                        $education_score=0;
                        $education_score=$this->calculateScore($w_education,$w_educationValue,$education);

                        $occupation=$row["OCCUPATION"];
			//$occupation=$matchesGlobalInfo[$v]->getOccupation();
                        $occupation_score=0;
                        $occupation_score=$this->calculateScore($w_occupation,$w_occupationValue,$occupation);

                        $cityres=$row["CITY_RES"];
			//$cityres=$matchesGlobalInfo[$v]->getCity_res();
                        $city_score=0;
                        $city_score=$this->calculateScore($w_city,$w_cityValue,$cityres);

			$income=$row["INCOME"];
			/* untested */
		        $my_income=getTrendsSortBy($income);	
			$income=$my_income;
			/* untested */

			$income_score=0;
			$income_score=$this->calculateScore($w_income,$w_incomeValue,$income);

                        $country=$row["COUNTRY_RES"];
			//$country=$matchesGlobalInfo[$v]->getCountry_res();
			if($country==51)
				$country_score=$w_nri*$nri_n_p;
			else
				$country_score=$w_nri*$nri_m_p;

                        $mstatus=$row["MSTATUS"];
			//$mstatus=$matchesGlobalInfo[$v]->getMstatus();
	                if($mstatus!='N')
        	               $mstatus_score=$w_mstatus*$mstatus_m_p;
                	else
        	               $mstatus_score=$w_mstatus*$mstatus_n_p;
			
                        $manglik=$row["MANGLIK"];
			//$manglik=$matchesGlobalInfo[$v]->getManglik();
	                if($manglik=='M')
        	                $manglik_score=$w_manglik * $manglik_m_p;
                	else
        	                $manglik_score=$w_manglik * $manglik_n_p;


			$total_score=$caste_score+$mtongue_score+$age_score+$income_score+$height_score+$education_score+$occupation_score+$city_score+$mstatus_score+$manglik_score+$country_score;

			$tscore=$total_score;//temp
			if(!$max_score)
				$max_score=100;
			$total_score=$total_score/$max_score * 100;
			$total_score1=$total_score;//temp

			if($total_score>=30 || $strategyTT)  //no cutoff for strategyTT
			{
				if(!$strategyTT)
				{
					$entry_dt=$row["ENTRY_DT"];
					//$entry_dt=$matchesGlobalInfo[$v]->getEntry_dt();
					$total_score=JSstrToTime($entry_dt);
				}
				$send=new Sender($profileId,$total_score);
	                        $sortingArray->add($send);

				//temp
				$pid=$row["PROFILEID"];
			}
		}
		return $sortingArray;
	}
	
	
	public function calculateScore($weightage,$attributeString,$correspondingAttribute)
	{
			$correspondingAttribute="|".$correspondingAttribute."#";
			$caste_score=0;
			if(strstr($attributeString,$correspondingAttribute))
			{
				$index=strpos($attributeString,$correspondingAttribute);
				$indexnew=strlen($correspondingAttribute)+$index;
				$subStr=substr($attributeString,$indexnew,3);
				$subStrArr=explode("|",$subStr);		
				$subStr=$subStrArr[0];
				$caste_score=($weightage) * ($subStr) ;
			}
			return $caste_score;
	}
	
	public function calculateReverseScrore($db,$profileSetTemp,$receiverObj)
	{
		global $db_fast;
		$countArr=count($profileSetTemp);

		//for($i=0;$i<$countArr;$i=$i+configVariables::$queryLimitForOptimization)
		{
			$str=implode(",",$profileSetTemp);
			$sortingArray1=new SortingArray();
			//$table="matchalerts.TRENDS";
                        if($receiverObj->getPartnerProfile()->getGENDER()=='F')
                                $table="matchalerts.FEMALE_TRENDS_HEAP";
                        else
                                $table="matchalerts.MALE_TRENDS_HEAP";
			//$table="matchalerts.FEMALE_TRENDS_HEAP";
			$sql_query="select PROFILEID,W_CASTE,CASTE_VALUE_PERCENTILE,W_MTONGUE,MTONGUE_VALUE_PERCENTILE,W_AGE,AGE_VALUE_PERCENTILE,W_HEIGHT,HEIGHT_VALUE_PERCENTILE,W_EDUCATION,EDUCATION_VALUE_PERCENTILE,W_OCCUPATION,OCCUPATION_VALUE_PERCENTILE,W_CITY,CITY_VALUE_PERCENTILE,W_NRI,NRI_N_P,NRI_M_P,W_MSTATUS,MSTATUS_M_P,MSTATUS_N_P,W_MANGLIK,MANGLIK_M_P,MANGLIK_N_P,GENDER,MAX_SCORE,W_INCOME,INCOME_VALUE_PERCENTILE from $table where PROFILEID IN($str)";
			//$res=mysql_query($sql_query,$db) or logerror1("In matchalert_mailer.php",$sql_query);
			$res=mysql_query($sql_query,$db_fast) or logerror1("In matchalert_mailer.php",$sql_query);
			while($row=mysql_fetch_array($res))
			{		
				$w_profileID=$row["PROFILEID"];

				$w_caste=$row["W_CASTE"];
				$w_casteValPercen=$row["CASTE_VALUE_PERCENTILE"];
				$caste_score=$this->calculateScore($w_caste,$w_casteValPercen,$receiverObj->getRecCaste());

				$w_mtongue=$row["W_MTONGUE"];
				$w_mtongueValPercen=$row["MTONGUE_VALUE_PERCENTILE"];
				$mtongue_score=$this->calculateScore($w_mtongue,$w_mtongueValPercen,$receiverObj->getRecMtongue());

				$w_age=$row["W_AGE"];
				$w_ageValPercen=$row["AGE_VALUE_PERCENTILE"];
				$age_score=$this->calculateScore($w_age,$w_ageValPercen,$receiverObj->getRecAge());
				
				$w_income=$row["W_INCOME"];
				$w_incomeValPercen=$row["INCOME_VALUE_PERCENTILE"];
				$income_score=$this->calculateScore($w_income,$w_incomeValPercen,$receiverObj->getRecIncome());
				
				$w_height=$row["W_HEIGHT"];
				$w_heigthValPercen=$row["HEIGHT_VALUE_PERCENTILE"];
				$height_score=$this->calculateScore($w_height,$w_heigthValPercen,$receiverObj->getRecHeight());

				$w_education=$row["W_EDUCATION"]; 
				$w_educationValPercen=$row["EDUCATION_VALUE_PERCENTILE"];
				$education_score=$this->calculateScore($w_education,$w_educationValPercen,$receiverObj->getRecEdu());
				
				$w_occupation=$row["W_OCCUPATION"];	
				$w_occupationValPercen=$row["OCCUPATION_VALUE_PERCENTILE"];
				$occupation_score=$this->calculateScore($w_occupation,$w_occupationValPercen,$receiverObj->getRecOcc());
				
				$w_city=$row["W_CITY"];	
				$w_cityValPercen=$row["CITY_VALUE_PERCENTILE"];
				$city_score=$this->calculateScore($w_city,$w_cityValPercen,$receiverObj->getRecCity());

				if($receiverObj->getRecMstatus()!='N')
					$mstatus_score=$row["W_MSTATUS"] * $row["MSTATUS_M_P"];
				else
					$mstatus_score=$row["W_MSTATUS"] * $row["MSTATUS_N_P"];

				if($receiverObj->getRecManglik()=='M')
					$manglik_score=$row["W_MANGLIK"] * $row["MANGLIK_M_P"];
				else
					$manglik_score=$row["W_MANGLIK"] * $row["MANGLIK_N_P"];

				if($receiverObj->getRecCountry()=='51')
					$country_score=$row["W_NRI"] * $row["NRI_N_P"];
				else
					$country_score=$row["W_NRI"] * $row["NRI_M_P"];

				$total_score=$caste_score+$mtongue_score+$age_score+$income_score+$height_score+$education_score+$occupation_score+$city_score+$mstatus_score+$manglik_score+$country_score;
				$max_score=$row["MAX_SCORE"];
		
				//not sure
				if(!$max_score)
					$max_score=100;
				//not sure
				//temp
				//$tscore=$total_score;
				//temp

				$total_score=$total_score/$max_score * 100;
				$send=new Sender($w_profileID,$total_score);
				$sortingArray1->add($send);

			}
		}
		return $sortingArray1;
	}
}
?>
