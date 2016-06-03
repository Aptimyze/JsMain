<?php
/**
This class is used to find the recommended profiles to a user from a set of given profiles.
**/
class RecommendedProfile
{
	public function __construct()
        {
        }

	/**
        This function is to be called to to find recommeded(single thumb/double thumb) profiles among a given set of profiles.
        * @param  1) profileObj of the person searching, 2) search results format as 
				$trend[0]["PROFILEID"] = 1615477;
			    	$trend[0]["AGE"] = 39;
			    	$trend[0]["HEIGHT"] = 8;
			    	$trend[0]["MTONGUE"] = 10;
			    	$trend[0]["CASTE"] = 14;
			    	$trend[0]["MANGLIK"] = "";
			    	$trend[0]["CITY_RES"] = "DE00";
			    	$trend[0]["COUNTRY_RES"] = 51;
			    	$trend[0]["EDU_LEVEL_NEW"] = 35;
			    	$trend[0]["OCCUPATION"] = 46;
			    	$trend[0]["MSTATUS"] = "W";
			    	$trend[0]["INCOME"] = 6;
			    	$trend[1]["PROFILEID"] = 1625361;
			    	$trend[1]["AGE"] = 23;
			    	$trend[1]["HEIGHT"] = 12;
			    	$trend[1]["MTONGUE"] = 12;
			    	$trend[1]["CASTE"] = 174;
			    	$trend[1]["MANGLIK"] = "";
			    	$trend[1]["CITY_RES"] = "GU";
			    	$trend[1]["COUNTRY_RES"] = 51;
			    	$trend[1]["EDU_LEVEL_NEW"] = 3;
			    	$trend[1]["OCCUPATION"] = 43;
			    	$trend[1]["MSTATUS"] = "N";
			    	$trend[1]["INCOME"] = 6;
	* @return array with index as the profileid of recommended profile and value as the image(single/double thumb)
	**/
	public function getting_reverse_trend_in_search($profileObj,$search_results,$thumb=1)
	{
		$profileId = $profileObj->getPROFILEID();
		$gender = $profileObj->getGENDER();
		$income = $profileObj->getINCOME();
		$search_results = $this->check_show_profile($income,$search_results);		//Modify the search results

		if(count($search_results))
		{
			//In case a female is searching then remove the profiles from search results having SHOW_PROFILE as NO
			foreach($search_results as $k=>$v)
			{
				$allow = 1;
				if($gender=="F")
				{
					if($v["SHOW_PROFILE"]=="NO")
					{
						$allow = 0;
						unset($search_results[$k]);
					}
				}
				if($allow)
					$search_results_profileid[]=$v['PROFILEID'];
			}
			//Ends

			if(!$search_results_profileid)
				return NULL;

			//Check if the user searching has trends
			$trends_obj = new TWOWAYMATCH_TRENDS;
			$haveTrends = $trends_obj->checkHaveTrends($profileId);
			unset($trends_obj);
			//Ends

			if($haveTrends)
			{
				//Get main admin score of search profiles
				$main_admin_pool_obj = new incentive_MAIN_ADMIN_POOL;
				$SCORE_TREND = $main_admin_pool_obj->getScore($search_results_profileid);
				unset($main_admin_pool_obj);
				//Ends

				$sql_final = $this->getTrendsQuery($search_results);	//Generated expression for calculating trends
				if(is_array($sql_final))
				{
					$str_final = "";
					foreach($sql_final as $key=>$val)
                                	{
                                        	$str_final.=$val." AS `".$key."`, ";
                                        	$prof_key[]=$key;
                                	}

					//Get trends score of profiles and MAX_SCORE of user searching
					$trends_obj = new TWOWAYMATCH_TRENDS;
					$row5 = $trends_obj->getTrendsScore($profileId,$str_final);
					unset($trends_obj);
					//Ends

					if($row5)
                                	{
						foreach($search_results as $k=>$v)
						{
							$actual_score[$prof_key[$k]] = $row5[$prof_key[$k]];	//Create an array with profileid and trends score
						}
						$max_score=$row5['MAX_SCORE'];
                                	}

					foreach($actual_score as $k=>$v)
					{
						$my_profileid=$k;

                                       		$score=$v;
                                        	if($max_score)					//Create per score
                                                	$per_score=round(($score/$max_score)*100);
                                        	else
                                                	$per_score='';
                                        	$admin_score=$SCORE_TREND[$my_profileid];	//Admin score

						if($thumb)
                                        	{
							$recommendation_flag = $this->map_admin_reverse_logic($admin_score,$per_score);	//Get recommend flag
							if($recommendation_flag=='R')		//Recommended
							{
								$search_results_logic[$my_profileid]='R';
							}
							elseif($recommendation_flag=='H')	//Highly recommended
							{
								$search_results_logic[$my_profileid]='H';
							}
                                        	}
					}
				}
			}
		}

	return $search_results_logic;
	}

	/**
        This function generates the expression to calculate trends score for each of the given profiles.
        * @param  search results as described above
        * @return array with index as profileid and value as expression
        **/
	private function getTrendsQuery($search_results)
	{
		foreach($search_results as $key=>$val)
		{
			foreach($val as $kk=>$vv)
				$$kk=$vv;

			$sql_array[]="( W_CASTE * SUBSTRING( CASTE_VALUE_PERCENTILE, LOCATE( '#', CASTE_VALUE_PERCENTILE, POSITION( '|$CASTE#' IN CASTE_VALUE_PERCENTILE ) ) +1, LOCATE( '|', CASTE_VALUE_PERCENTILE, POSITION( '|$CASTE#' IN CASTE_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', CASTE_VALUE_PERCENTILE, POSITION( '|$CASTE#' IN CASTE_VALUE_PERCENTILE ) ) -1 ) )";
			$sql_array[]="( W_MTONGUE * SUBSTRING( MTONGUE_VALUE_PERCENTILE, LOCATE( '#', MTONGUE_VALUE_PERCENTILE, POSITION( '|$MTONGUE#' IN MTONGUE_VALUE_PERCENTILE ) ) +1, LOCATE( '|', MTONGUE_VALUE_PERCENTILE, POSITION( '|$MTONGUE#' IN MTONGUE_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', MTONGUE_VALUE_PERCENTILE, POSITION( '|$MTONGUE#' IN MTONGUE_VALUE_PERCENTILE ) ) -1 ) )";
			$sql_array[]="( W_AGE * SUBSTRING( AGE_VALUE_PERCENTILE, LOCATE( '#', AGE_VALUE_PERCENTILE, POSITION( '|$AGE#' IN AGE_VALUE_PERCENTILE ) ) +1, LOCATE( '|', AGE_VALUE_PERCENTILE, POSITION( '|$AGE#' IN AGE_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', AGE_VALUE_PERCENTILE, POSITION( '|$AGE#' IN AGE_VALUE_PERCENTILE ) ) -1 ) )";
			$sql_array[]="( W_INCOME * SUBSTRING( INCOME_VALUE_PERCENTILE, LOCATE( '#', INCOME_VALUE_PERCENTILE, POSITION( '|$INCOME#' IN INCOME_VALUE_PERCENTILE ) ) +1, LOCATE( '|', INCOME_VALUE_PERCENTILE, POSITION( '|$INCOME#' IN INCOME_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', INCOME_VALUE_PERCENTILE, POSITION( '|$INCOME#' IN INCOME_VALUE_PERCENTILE ) ) -1 ) )";
			$sql_array[]="( W_HEIGHT * SUBSTRING( HEIGHT_VALUE_PERCENTILE, LOCATE( '#', HEIGHT_VALUE_PERCENTILE, POSITION( '|$HEIGHT#' IN HEIGHT_VALUE_PERCENTILE ) ) +1, LOCATE( '|', HEIGHT_VALUE_PERCENTILE, POSITION( '|$HEIGHT#' IN HEIGHT_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', HEIGHT_VALUE_PERCENTILE, POSITION( '|$HEIGHT#' IN HEIGHT_VALUE_PERCENTILE ) ) -1 ) )";
			$sql_array[]="( W_EDUCATION * SUBSTRING( EDUCATION_VALUE_PERCENTILE, LOCATE( '#', EDUCATION_VALUE_PERCENTILE, POSITION( '|$EDU_LEVEL_NEW#' IN EDUCATION_VALUE_PERCENTILE ) ) +1, LOCATE( '|', EDUCATION_VALUE_PERCENTILE, POSITION( '|$EDU_LEVEL_NEW#' IN EDUCATION_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', EDUCATION_VALUE_PERCENTILE, POSITION( '|$EDU_LEVEL_NEW#' IN EDUCATION_VALUE_PERCENTILE ) ) -1 ) )";
			$sql_array[]="( W_OCCUPATION * SUBSTRING( OCCUPATION_VALUE_PERCENTILE, LOCATE( '#', OCCUPATION_VALUE_PERCENTILE, POSITION( '|$OCCUPATION#' IN OCCUPATION_VALUE_PERCENTILE ) ) +1, LOCATE( '|', OCCUPATION_VALUE_PERCENTILE, POSITION( '|$OCCUPATION#' IN OCCUPATION_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', OCCUPATION_VALUE_PERCENTILE, POSITION( '|$OCCUPATION#' IN OCCUPATION_VALUE_PERCENTILE ) ) -1 ) )";
			$sql_array[]="( W_CITY * SUBSTRING( CITY_VALUE_PERCENTILE, LOCATE( '#', CITY_VALUE_PERCENTILE, POSITION( '|$CITY_RES#' IN CITY_VALUE_PERCENTILE ) ) +1, LOCATE( '|', CITY_VALUE_PERCENTILE, POSITION( '|$CITY_RES#' IN CITY_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', CITY_VALUE_PERCENTILE, POSITION( '|$CITY_RES#' IN CITY_VALUE_PERCENTILE ) ) -1 ) )";
	
			if($MSTATUS!='N')
				$sql_array[]="( W_MSTATUS * MSTATUS_M_P)";
			else
				$sql_array[]="( W_MSTATUS * MSTATUS_N_P)";

			if($MANGLIK=='M')
				$sql_array[]="( W_MANGLIK * MANGLIK_M_P)";
			else
				$sql_array[]="( W_MANGLIK * MANGLIK_N_P)";

			if($COUNTRY_RES=='51')
				$sql_array[]="( W_NRI * NRI_N_P)";
			else
				$sql_array[]="( W_NRI * NRI_M_P)";

			$sql_final[$PROFILEID]="(".implode("+",$sql_array).")";

			unset($sql_array);
		}
		return $sql_final;
	}

	/**
        This function has the logic to decide which profiles will be recommended based on admin score and per score
        * @param  admin score and per score
        * @return array with index as profileid and value as expression
        **/
	private function map_admin_reverse_logic($admin_score,$per_score)
	{
		if($admin_score=='' || $per_score=='')
			return '';
		if($per_score>=100)
			$per_score=99;

		$SCORE[0][0]=150;
		$SCORE[0][1]=250;
			//$MATCH[0][6]='R';
			//$MATCH[0][7]='R';
		       // $MATCH[0][8]='R';
			$MATCH[0][9]='H';
		$SCORE[1][0]=251;
		$SCORE[1][1]=350;
			//$MATCH[1][5]='R';
			//$MATCH[1][6]='R';
			//$MATCH[1][7]='R';
			//$MATCH[1][8]='R';
			$MATCH[1][9]='H';

		$SCORE[2][0]=351;
		$SCORE[2][1]=450;
			//$MATCH[2][3]='R';
			//$MATCH[2][4]='R';
			//$MATCH[2][5]='R';
			//$MATCH[2][6]='R';
			$MATCH[2][7]='R';
			$MATCH[2][8]='R';
			$MATCH[2][9]='H';
		$SCORE[3][0]=451;
		$SCORE[3][1]=550;
			//$MATCH[3][2]='R';
			//$MATCH[3][3]='R';
			//$MATCH[3][4]='R';
			$MATCH[3][5]='R';
			$MATCH[3][6]='R';
			$MATCH[3][7]='R';
			$MATCH[3][8]='H';
			$MATCH[3][9]='H';
		$SCORE[4][0]=551;
		$SCORE[4][1]=1000;
			//$MATCH[4][1]='R';
			//$MATCH[4][2]='R';
			$MATCH[4][3]='R';
			$MATCH[4][4]='R';
			$MATCH[4][5]='R';
			$MATCH[4][6]='H';
			$MATCH[4][7]='H';
			$MATCH[4][8]='H';
			$MATCH[4][9]='H';

		$score_d=$admin_score;
		$match_score=$per_score;

		for($i=0;$i<count($SCORE);$i++)
		{
			if($SCORE[$i][0]<=$score_d && $SCORE[$i][1]>=$score_d)
			{
				return $recommend=$MATCH[$i][$match_score/10];
			}
		}
        	return '';
	}

	/**
        If the income of match is less than than the user searching then we dont show the match as recommended(only for female) so we mark its SHOW_PROFILE parameter as NO. This function does the same task.
        * @param  income of user seaching, search results as described above
        * @return search results with additional parameter as SHOW_PROFILE and income changed to SORTBY income.
        **/
	private function check_show_profile($income,$search_results)
	{
		$income_arr=FieldMap::getFieldLabel("income_sortby",1,1);
		foreach($search_results as $k=>$v)
		{
			$search_results[$k]["SHOW_PROFILE"] = "YES";
			if($income)
				$login_user_sort_income = $income_arr[$income];
			if($v["INCOME"])
				$search_user_sort_income = $income_arr[$v["INCOME"]];

			if($search_user_sort_income)
				$search_results[$k]["INCOME"] = $search_user_sort_income;

			if($income)
			{
				if($search_results[$k]["INCOME"]<$login_user_sort_income)
					$search_results[$k]["SHOW_PROFILE"] = "NO";
			}
		}
		return $search_results;
	}
}
?>
