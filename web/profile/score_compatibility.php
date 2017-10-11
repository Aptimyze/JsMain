<?php

function getScore($logged_profileid, $viewed_profileid)
{
	$contactsArr=array();
	$trendUser = array();

	$userArr = array("$logged_profileid","$viewed_profileid");
        $last20AccContactsArr = getLast20Contacts($viewed_profileid,'A');     
        $last20DecContactsArr = getLast20Contacts($viewed_profileid,'D');
        $contactsArr          = array_merge($last20AccContactsArr,$last20DecContactsArr,$userArr); 
       	$dataArray 	      = getTrends($contactsArr); 
	$trends 	      = $dataArray['TREND'];	
	$date_of_birth	      =	$dataArray['DOB'];
	$gender		      =	$dataArray['G'];

	/* Get Logged In User Score */
        $trendUser[] = $trends[$logged_profileid];
        if($logged_profileid)
                $logged_user_score = Newgetting_reverse_trend($trendUser,$viewed_profileid);
        if(count($logged_user_score) !=0)
                $UserScore = $logged_user_score[$logged_profileid];
        else
                $UserScore=0;
	// Average of 20 Accepted Contacts		
	$acc_Contacts_scoreAvg = getLast20Contacts_ScoreAvg($viewed_profileid,$last20AccContactsArr,$trends);
	// Average of 20 Rejected Contacts
	$dec_Contacts_scoreAvg = getLast20Contacts_ScoreAvg($viewed_profileid,$last20DecContactsArr,$trends);

	// Condition Applied to show Score Status
	if($UserScore==0 && $acc_Contacts_scoreAvg==0 && $dec_Contacts_scoreAvg==0)
		$score = "";
	else if($acc_Contacts_scoreAvg >= $dec_Contacts_scoreAvg)
	{
		if($UserScore >=$acc_Contacts_scoreAvg){
			$UserScore = percentScore($UserScore,'Highly');
			$score = array("score"=>$UserScore,"status"=>"Highly Recommended");
		}
		else if( ($UserScore < $acc_Contacts_scoreAvg) && ($UserScore > $dec_Contacts_scoreAvg) ){
			$UserScore = percentScore($UserScore,'Recommended');
			$score= array("score"=>$UserScore,"status"=>"Recommended");
		}
		else{
			$score = "";
		}
	}
	else if($dec_Contacts_scoreAvg >$acc_Contacts_scoreAvg )
	{
		$acc_Contacts_scoreAvg = percentScore($acc_Contacts_scoreAvg,'Recommended');
		$score = array("score"=>$acc_Contacts_scoreAvg,"status"=>"Recommended");	
	}
	else
		$score = "";

	return array("SCORE"=>$score,"DOB"=>$date_of_birth,"G"=>$gender);
}

function percentScore($score, $status)
{
        $redPer         = 33;
        $yellowPer      = 33;
        $greenPer       = 34;
	if($status =='Highly')
		$scoreVal = (($score/100)*$greenPer)+($redPer+$yellowPer); 
	else if($status =='Recommended')
		$scoreVal = (($score/100)*$yellowPer)+($redPer);
	else
		$scoreVal = ($score/100)*$redPer;
	return $scoreVal;
}

/* Function Defined */
function checkCompatibilityStatus($viewed_profileid)
{
        $sql_co = "select OPEN_CONTACTS,ACC_ME,DEC_ME,ACC_BY_ME from newjs.CONTACTS_STATUS where PROFILEID='$viewed_profileid'";
        $result_co = mysql_query_decide($sql_co)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_co,"ShowErrTemplate");
        $row = mysql_fetch_array($result_co);
        $tot_con = $row['OPEN_CONTACTS']+$row['ACC_ME']+$row['DEC_ME']+$row['ACC_BY_ME'];
        if($tot_con>=10)
                return true;
        return false;
}

function getTrends($profileid)
{
	$trend=array();$dateOfBirth=array();$gender=array();
	if(is_array($profileid))
		$profileidStr = implode(",",$profileid);
	else 
		return;
	$sql = "SELECT * from newjs.JPROFILE where  activatedKey=1 and PROFILEID in ($profileidStr)";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate") ;
	while($myrow=mysql_fetch_array($result))
	{
		$profileid              = $myrow['PROFILEID'];
		$trend[$profileid]      = calculate_user_trend($myrow);
		$dateOfBirth[$profileid]= $myrow['DTOFBIRTH'];	
		$gender[$profileid] 	= $myrow['GENDER'];
	}
	$dataArray = array();
	$dataArray = array("TREND"=>$trend,"DOB"=>$dateOfBirth,"G"=>$gender);
	return $dataArray;
}

function getLast20Contacts($viewed_profileid,$type="")
{
	$typeIn         = "'$type'";
	$max_members    = "20";
	$senders        = array();
	$Acc_Contacts=getResultSet("SENDER","","","$viewed_profileid","","$typeIn","","","","TIME DESC","","",$max_members);
	if(!$Acc_Contacts)
		return $senders;
        if(is_array($Acc_Contacts))
        {
                foreach($Acc_Contacts as $key=>$value)
                        $senders[]=$Acc_Contacts[$key]["SENDER"];
        }
        unset($Acc_Contacts);
	return $senders;	
}

function getLast20Contacts_ScoreAvg($viewed_profileid,$last20AccContactsArr,$trends)
{
	$selTrendArr    = array();
	if(is_array($trends)){
		foreach($last20AccContactsArr as $key=>$value)
			$selTrendArr[]=$trends[$value];			
	}
	$scoreArray 	= Newgetting_reverse_trend($selTrendArr,$viewed_profileid); 
	$score_cnt = count($scoreArray);
	$score_sum = 0;
	foreach($scoreArray as $key=>$val){
		$score_sum = $score_sum+$val;		
	}
	if($score_sum <= 0 || $score_cnt == 0)
		return 0;
	$score_avg = $score_sum/$score_cnt;
	return $score_avg;
}

/* function returns score
   Return Type Array
*/
function Newgetting_reverse_trend($trend,$viewed_profileid)
{
	$scoreArr = array();
        for($i=0;$i<count($trend);$i++)
        {
                $trend_profileid[]=$trend[$i]['my_profileid'];
        }
        if(count($trend))
        {
	        $sql4="select * FROM twowaymatch.TRENDS where PROFILEID=$viewed_profileid";
                $res4=mysql_query_decide($sql4)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql4,"ShowErrTemplate");;
                if($row4=mysql_fetch_array($res4))
                {
                        foreach($trend as $key=>$val)
                        {
				if(!$val)
					break;
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
                                $sql5=" SELECT $sql_final as score,$sql_final2,MAX_SCORE from twowaymatch.TRENDS where PROFILEID='$row4[PROFILEID]' ";
                                $result5=mysql_query_decide($sql5) or die(mysql_error_js());
                                if($row5=mysql_fetch_array($result5))
                                {
                                        $score=$row5['score'];
                                        $max_score=$row5['MAX_SCORE'];
                                        if($max_score)
                                                $per_score=round(($score/$max_score)*100);
                                        else
                                                $per_score='';
                                        //$admin_score=$SCORE_TREND[$my_profileid];
				}
				$scoreArr[$my_profileid] = $per_score;
			}
		}

	}

	return $scoreArr;
}

?>
