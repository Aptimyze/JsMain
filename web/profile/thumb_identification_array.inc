<?php
include_once(JsConstants::$docRoot."/commonFiles/incomeCommonFunctions.inc");
function calculate_user_trend($myrow='')
{
        $i=0;
	global $income_of_login_user;
        if($myrow)
        {
                $trend['my_profileid']=$myrow['PROFILEID'];
                $trend['my_age']=$myrow['AGE'];
                $trend['my_height']=$myrow['HEIGHT'];
                $trend['my_mtongue']=$myrow['MTONGUE'];
                $trend['my_caste']=$myrow['CASTE'];
                $trend['my_manglik']=$myrow['MANGLIK'];
                $trend['my_city']=$myrow['CITY_RES'];
                $trend['my_country']=$myrow['COUNTRY_RES'];
                $trend['my_education']=$myrow['EDU_LEVEL_NEW'];
                $trend['my_occupation']=$myrow['OCCUPATION'];
                $trend['my_mstatus']=$myrow['MSTATUS'];
                $trend['my_income']=$myrow['INCOME'];
                $my_income=$trend['my_income'];

		if(getSortByIncome($my_income))
			$my_income=getTrendsSortBy($my_income);

                $trend['my_income']=$my_income;
		$trend['show_profile']='YES';

		//Setting show_profile equals to No , if trend profile income is less than login profile income.
		if($income_of_login_user)
                {
		        $income_of_login_user_temp=getTrendsSortBy($income_of_login_user);

			if($my_income<$income_of_login_user_temp)
				$trend['show_profile']='NO';
		}
		

                return $trend;
        }
}
function getting_reverse_trend_in_search($trend,$thumb=1)
{
        global $data;
        $profileid=$data['PROFILEID'];
	$gender=$data['GENDER'];
	$tot_cnt=count($trend);
        for($i=0;$i<$tot_cnt;$i++)
        {
		$allow=1;
		if($gender=='F')
			if($trend[$i]['show_profile']=='NO')
			{
				$allow=0;
				unset($trend[$i]);
			}
		if($allow)	
			$trend_profileid[]=$trend[$i]['my_profileid'];
		
        }
        if(count($trend))
        {
                $trendstr=implode("','",$trend_profileid);
                $sql="select PROFILEID,SCORE from incentive.MAIN_ADMIN_POOL where PROFILEID IN('$trendstr')";
                $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                while($row=mysql_fetch_array($res))
                {
                                $SCORE_TREND[$row['PROFILEID']]=$row['SCORE'];

                }
        }

        if(count($trend))
        {
                $sql4="select * FROM twowaymatch.TRENDS where PROFILEID=$profileid";
                $res4=mysql_query_decide($sql4)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql4,"ShowErrTemplate");;
                if($row4=mysql_fetch_array($res4))
                {
                        foreach($trend as $key=>$val)
			 {
                                foreach($val as $kk=>$vv)
                                        $$kk=$vv;
                                $sql_array[]="( W_CASTE * SUBSTRING( CASTE_VALUE_PERCENTILE, LOCATE( '#', CASTE_VALUE_PERCENTILE, POSITION( '|$my_caste#' IN CASTE_VALUE_PERCENTILE ) ) +1, LOCATE( '|', CASTE_VALUE_PERCENTILE, POSITION( '|$my_caste#' IN CASTE_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', CASTE_VALUE_PERCENTILE, POSITION( '|$my_caste#' IN CASTE_VALUE_PERCENTILE ) ) -1 ) )";
                                $sql_array[]="( W_MTONGUE * SUBSTRING( MTONGUE_VALUE_PERCENTILE, LOCATE( '#', MTONGUE_VALUE_PERCENTILE, POSITION( '|$my_mtongue#' IN MTONGUE_VALUE_PERCENTILE ) ) +1, LOCATE( '|', MTONGUE_VALUE_PERCENTILE, POSITION( '|$my_mtongue#' IN MTONGUE_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', MTONGUE_VALUE_PERCENTILE, POSITION( '|$my_mtongue#' IN MTONGUE_VALUE_PERCENTILE ) ) -1 ) )";
                                $sql_array[]="( W_AGE * SUBSTRING( AGE_VALUE_PERCENTILE, LOCATE( '#', AGE_VALUE_PERCENTILE, POSITION( '|$my_age#' IN AGE_VALUE_PERCENTILE ) ) +1, LOCATE( '|', AGE_VALUE_PERCENTILE, POSITION( '|$my_age#' IN AGE_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', AGE_VALUE_PERCENTILE, POSITION( '|$my_age#' IN AGE_VALUE_PERCENTILE ) ) -1 ) )";

                                $sql_array[]="( W_INCOME * SUBSTRING( INCOME_VALUE_PERCENTILE, LOCATE( '#', INCOME_VALUE_PERCENTILE, POSITION( '|$my_income#' IN INCOME_VALUE_PERCENTILE ) ) +1, LOCATE( '|', INCOME_VALUE_PERCENTILE, POSITION( '|$my_income#' IN INCOME_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', INCOME_VALUE_PERCENTILE, POSITION( '|$my_income#' IN INCOME_VALUE_PERCENTILE ) ) -1 ) )";
                                $sql_array[]="( W_HEIGHT * SUBSTRING( HEIGHT_VALUE_PERCENTILE, LOCATE( '#', HEIGHT_VALUE_PERCENTILE, POSITION( '|$my_height#' IN HEIGHT_VALUE_PERCENTILE ) ) +1, LOCATE( '|', HEIGHT_VALUE_PERCENTILE, POSITION( '|$my_height#' IN HEIGHT_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', HEIGHT_VALUE_PERCENTILE, POSITION( '|$my_height#' IN HEIGHT_VALUE_PERCENTILE ) ) -1 ) )";
                                $sql_array[]="( W_EDUCATION * SUBSTRING( EDUCATION_VALUE_PERCENTILE, LOCATE( '#', EDUCATION_VALUE_PERCENTILE, POSITION( '|$my_education#' IN EDUCATION_VALUE_PERCENTILE ) ) +1, LOCATE( '|', EDUCATION_VALUE_PERCENTILE, POSITION( '|$my_education#' IN EDUCATION_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', EDUCATION_VALUE_PERCENTILE, POSITION( '|$my_education#' IN EDUCATION_VALUE_PERCENTILE ) ) -1 ) )";
                                $sql_array[]="( W_OCCUPATION * SUBSTRING( OCCUPATION_VALUE_PERCENTILE, LOCATE( '#', OCCUPATION_VALUE_PERCENTILE, POSITION( '|$my_occupation#' IN OCCUPATION_VALUE_PERCENTILE ) ) +1, LOCATE( '|', OCCUPATION_VALUE_PERCENTILE, POSITION( '|$my_occupation#' IN OCCUPATION_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', OCCUPATION_VALUE_PERCENTILE, POSITION( '|$my_occupation#' IN OCCUPATION_VALUE_PERCENTILE ) ) -1 ) )";
				$sql_array[]="( W_CITY * SUBSTRING( CITY_VALUE_PERCENTILE, LOCATE( '#', CITY_VALUE_PERCENTILE, POSITION( '|$my_city#' IN CITY_VALUE_PERCENTILE ) ) +1, LOCATE( '|', CITY_VALUE_PERCENTILE, POSITION( '|$my_city#' IN CITY_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', CITY_VALUE_PERCENTILE, POSITION( '|$my_city#' IN CITY_VALUE_PERCENTILE ) ) -1 ) )";

                                if($my_mstatus!='N')
                                        $sql_array[]="( W_MSTATUS * MSTATUS_M_P)";
                                else
                                        $sql_array[]="( W_MSTATUS * MSTATUS_N_P)";

                                if($my_manglik=='M')
                                        $sql_array[]="( W_MANGLIK * MANGLIK_M_P)";
                                else
                                        $sql_array[]="( W_MANGLIK * MANGLIK_N_P)";

                                if($my_country=='51')
                                        $sql_array[]="( W_NRI * NRI_N_P)";
                                else
                                        $sql_array[]="( W_NRI * NRI_M_P)";

                                $sql_final[$my_profileid]="(".implode("+",$sql_array).")";
                                //$sql_final2=" ".implode(" , ",$sql_array)." ";
		                unset($sql_array);
			}
			
				//$sql5=" SELECT $sql_final as score,$sql_final2,MAX_SCORE from twowaymatch.TRENDS where PROFILEID='$row4[PROFILEID]' ";
			if(is_array($sql_final))
			{
				foreach($sql_final as $key=>$val)
				{
					$str_final.="$val as `$key`,";
					$prof_key[]=$key;
				}
				$sql5="SELECT $str_final MAX_SCORE from twowaymatch.TRENDS where PROFILEID='$row4[PROFILEID]'";
                                $result5=mysql_query_decide($sql5) or die(mysql_error_js());
                                if($row5=mysql_fetch_array($result5))
				{
					for($cnt_sc=0;$cnt_sc<10;$cnt_sc++)
					{
						$actual_score[0][]=$row5[$prof_key[$cnt_sc]];
						$actual_score[1][]=$prof_key[$cnt_sc];
					}

					$max_score=$row5['MAX_SCORE'];
				}
				for($cnt_sc=0;$cnt_sc<10;$cnt_sc++)
				{
					$my_profileid=$actual_score[1][$cnt_sc];
					
                                       $score=$actual_score[0][$cnt_sc];
                                        //$max_score=$row5['MAX_SCORE'];
                                        if($max_score)
                                                $per_score=round(($score/$max_score)*100);
                                        else
                                                $per_score='';
                                        $admin_score=$SCORE_TREND[$my_profileid];

                                        //This function returns the  recommendation b/w users
                                        if($thumb)
                                        {
                                               $recommendation_flag=map_admin_reverse_logic($admin_score,$per_score);
                                                if($recommendation_flag=='R')
                                                {
                                                        $trends_logic[$my_profileid]='ic_thumb_recom_1.gif';
                                                }
                                                elseif($recommendation_flag=='H')
                                                {
                                                        $trends_logic[$my_profileid]='ic_thumb_recom_2.gif';
                                                }
                                        }
                                }

                        }
                }
        }
        if($thumb)
        {
		   return $trends_logic;
	}
        else
                return null;
}
function getting_reverse_trend($trend,$thumb=1)
{
        global $data;
        $profileid=$data['PROFILEID'];
	$gender=$data['GENDER'];
        $tot_cnt=count($trend);
        for($i=0;$i<$tot_cnt;$i++)
        {
                $allow=1;
                if($gender=='F')
                        if($trend[$i]['show_profile']=='NO')
                        {
                                $allow=0;
                                unset($trend[$i]);
                        }
                if($allow)
                        $trend_profileid[]=$trend[$i]['my_profileid'];

        }
        if(count($trend))
        {
                $trendstr=implode("','",$trend_profileid);
                $sql="select PROFILEID,SCORE from incentive.MAIN_ADMIN_POOL where PROFILEID IN('$trendstr')";
                $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                while($row=mysql_fetch_array($res))
                {
                                $SCORE_TREND[$row['PROFILEID']]=$row['SCORE'];

                }
        }

        if(count($trend))
        {
                $sql4="select * FROM twowaymatch.TRENDS where PROFILEID=$profileid";
                $res4=mysql_query_decide($sql4)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql4,"ShowErrTemplate");;
                if($row4=mysql_fetch_array($res4))
                {
                        foreach($trend as $key=>$val)
			 {
                                foreach($val as $kk=>$vv)
                                        $$kk=$vv;
                                $sql_array[]="( W_CASTE * SUBSTRING( CASTE_VALUE_PERCENTILE, LOCATE( '#', CASTE_VALUE_PERCENTILE, POSITION( '|$my_caste#' IN CASTE_VALUE_PERCENTILE ) ) +1, LOCATE( '|', CASTE_VALUE_PERCENTILE, POSITION( '|$my_caste#' IN CASTE_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', CASTE_VALUE_PERCENTILE, POSITION( '|$my_caste#' IN CASTE_VALUE_PERCENTILE ) ) -1 ) )";
                                $sql_array[]="( W_MTONGUE * SUBSTRING( MTONGUE_VALUE_PERCENTILE, LOCATE( '#', MTONGUE_VALUE_PERCENTILE, POSITION( '|$my_mtongue#' IN MTONGUE_VALUE_PERCENTILE ) ) +1, LOCATE( '|', MTONGUE_VALUE_PERCENTILE, POSITION( '|$my_mtongue#' IN MTONGUE_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', MTONGUE_VALUE_PERCENTILE, POSITION( '|$my_mtongue#' IN MTONGUE_VALUE_PERCENTILE ) ) -1 ) )";
                                $sql_array[]="( W_AGE * SUBSTRING( AGE_VALUE_PERCENTILE, LOCATE( '#', AGE_VALUE_PERCENTILE, POSITION( '|$my_age#' IN AGE_VALUE_PERCENTILE ) ) +1, LOCATE( '|', AGE_VALUE_PERCENTILE, POSITION( '|$my_age#' IN AGE_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', AGE_VALUE_PERCENTILE, POSITION( '|$my_age#' IN AGE_VALUE_PERCENTILE ) ) -1 ) )";

                                $sql_array[]="( W_INCOME * SUBSTRING( INCOME_VALUE_PERCENTILE, LOCATE( '#', INCOME_VALUE_PERCENTILE, POSITION( '|$my_income#' IN INCOME_VALUE_PERCENTILE ) ) +1, LOCATE( '|', INCOME_VALUE_PERCENTILE, POSITION( '|$my_income#' IN INCOME_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', INCOME_VALUE_PERCENTILE, POSITION( '|$my_income#' IN INCOME_VALUE_PERCENTILE ) ) -1 ) )";
                                $sql_array[]="( W_HEIGHT * SUBSTRING( HEIGHT_VALUE_PERCENTILE, LOCATE( '#', HEIGHT_VALUE_PERCENTILE, POSITION( '|$my_height#' IN HEIGHT_VALUE_PERCENTILE ) ) +1, LOCATE( '|', HEIGHT_VALUE_PERCENTILE, POSITION( '|$my_height#' IN HEIGHT_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', HEIGHT_VALUE_PERCENTILE, POSITION( '|$my_height#' IN HEIGHT_VALUE_PERCENTILE ) ) -1 ) )";
                                $sql_array[]="( W_EDUCATION * SUBSTRING( EDUCATION_VALUE_PERCENTILE, LOCATE( '#', EDUCATION_VALUE_PERCENTILE, POSITION( '|$my_education#' IN EDUCATION_VALUE_PERCENTILE ) ) +1, LOCATE( '|', EDUCATION_VALUE_PERCENTILE, POSITION( '|$my_education#' IN EDUCATION_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', EDUCATION_VALUE_PERCENTILE, POSITION( '|$my_education#' IN EDUCATION_VALUE_PERCENTILE ) ) -1 ) )";
                                $sql_array[]="( W_OCCUPATION * SUBSTRING( OCCUPATION_VALUE_PERCENTILE, LOCATE( '#', OCCUPATION_VALUE_PERCENTILE, POSITION( '|$my_occupation#' IN OCCUPATION_VALUE_PERCENTILE ) ) +1, LOCATE( '|', OCCUPATION_VALUE_PERCENTILE, POSITION( '|$my_occupation#' IN OCCUPATION_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', OCCUPATION_VALUE_PERCENTILE, POSITION( '|$my_occupation#' IN OCCUPATION_VALUE_PERCENTILE ) ) -1 ) )";
				$sql_array[]="( W_CITY * SUBSTRING( CITY_VALUE_PERCENTILE, LOCATE( '#', CITY_VALUE_PERCENTILE, POSITION( '|$my_city#' IN CITY_VALUE_PERCENTILE ) ) +1, LOCATE( '|', CITY_VALUE_PERCENTILE, POSITION( '|$my_city#' IN CITY_VALUE_PERCENTILE ) +1 ) - LOCATE( '#', CITY_VALUE_PERCENTILE, POSITION( '|$my_city#' IN CITY_VALUE_PERCENTILE ) ) -1 ) )";

                                if($my_mstatus!='N')
                                        $sql_array[]="( W_MSTATUS * MSTATUS_M_P)";
                                else
                                        $sql_array[]="( W_MSTATUS * MSTATUS_N_P)";

                                if($my_manglik=='M')
                                        $sql_array[]="( W_MANGLIK * MANGLIK_M_P)";
                                else
                                        $sql_array[]="( W_MANGLIK * MANGLIK_N_P)";

                                if($my_country=='51')
                                        $sql_array[]="( W_NRI * NRI_N_P)";
                                else
                                        $sql_array[]="( W_NRI * NRI_M_P)";

                                $sql_final="(".implode("+",$sql_array).")";
                                $sql_final2=" ".implode(" , ",$sql_array)." ";
                                unset($sql_array);
                                if($thumb)
                                        $sql5=" SELECT $sql_final as score,$sql_final2,MAX_SCORE from twowaymatch.TRENDS where PROFILEID='$row4[PROFILEID]' ";
                                else
                                        $sql5=" SELECT $sql_final as score from twowaymatch.TRENDS where PROFILEID='$row4[PROFILEID]' ";

                                $result5=mysql_query_decide($sql5) or die(mysql_error_js());
                                if($row5=mysql_fetch_array($result5))
				{
                                        $score=$row5['score'];
                                        if(!$thumb)
                                                return $score;
                                        $max_score=$row5['MAX_SCORE'];
                                        if($max_score)
                                                $per_score=round(($score/$max_score)*100);
                                        else
                                                $per_score='';
                                        $admin_score=$SCORE_TREND[$my_profileid];

                                        //This function returns the  recommendation b/w users
                                        if($thumb)
                                        {
                                               $recommendation_flag=map_admin_reverse_logic($admin_score,$per_score);
                                                if($recommendation_flag=='R')
                                                {
                                                        $trends_logic[$my_profileid]='ic_thumb_recom_1.gif';
                                                }
                                                elseif($recommendation_flag=='H')
                                                {
                                                        $trends_logic[$my_profileid]='ic_thumb_recom_2.gif';
                                                }
                                        }
                                }

                        }
                }
        }
        if($thumb)
        {
		   return $trends_logic;
	}
        else
                return null;
}
function map_admin_reverse_logic($admin_score,$per_score)
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
