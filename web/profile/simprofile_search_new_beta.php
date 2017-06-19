<?php

/************************************************************************************************************************
*    DESCRIPTION        : new simillar profile logic introduced.(mantis id 2015)
			  A===>B(finding simillar profile of B)
*    CREATED BY         : lavesh
***********************************************************************************************************************/

include_once "connect.inc";
include_once "search.inc";
include_once "sphinx_search_function.php";
global $previous_rec_arr;
global $all_previous_rec_str;
$db=connect_db4();
$db1=connect_db();
/* All profiles in which logged in user is/was interested . This will include the current selected profile as well.*/
$senders[]=$profileid_receiver;
if(!$limit)
		$limit=9;
//echo $profileid_receiver;die;
/**/
//$sql="SELECT SQL_CACHE RECEIVER AS CONTACT_HISTORY FROM newjs.CONTACTS_SEARCH_NEW WHERE SENDER = '$contactedby'"; 
/**/

/*
$sql="SELECT SQL_CACHE RECEIVER AS CONTACT_HISTORY FROM newjs.CONTACTS_SEARCH_NEW, newjs.TEMPRECEIVER WHERE SENDER = '$contactedby' and newjs.CONTACTS_SEARCH_NEW.RECEIVER=newjs.TEMPRECEIVER.receiverId and newjs.TEMPRECEIVER.numSent < 300";
*/
$sql="SELECT SQL_CACHE RECEIVER AS CONTACT_HISTORY FROM newjs.CONTACTS_SEARCH_NEW, newjs.TEMPRECEIVER WHERE SENDER = '$contactedby' and newjs.CONTACTS_SEARCH_NEW.RECEIVER=newjs.TEMPRECEIVER.receiverId";
$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
while($row=mysql_fetch_array($res))
{
	$sen=$row['CONTACT_HISTORY'];
	$sen_str.="('".$sen."'),";
	$senders[]=$row['CONTACT_HISTORY'];
}
$sen_history_cnt=count($senders);
$sen_str.="('".$profileid_receiver."'),";
$sen_str=rtrim($sen_str,',');
$five_percent_of_loggedinuser=round((count($senders)*0.05),0);
if($sen_history_cnt>10)//--------------->Logged in user should have contact>10(added new)
//New algo will be used if logged-in user has contact history(excluding the current one choosen) .
{
        //Sharding of CONTACTS done by Sadaf
        /* All profiles in which selected/contacted user is/was interested. */
        $receiversIn=$profileid_receiver;
        $sendersNotIn=$contactedby;

        $sql="SELECT SQL_CACHE SENDER FROM newjs.CONTACTS_SEARCH_NEW WHERE RECEIVER = '$profileid_receiver' AND SENDER != '$contactedby' limit 500";
        $res=mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
        //echo $sql;
        $rec_str='';
        while($row=mysql_fetch_array($res))
        {
                $rec_str.="('".$row["SENDER"]."'),";
        }

/*
        $contactResult=getResultSet("SENDER",'',$sendersNotIn,$receiversIn);
        if(is_array($contactResult))
        {
                foreach($contactResult as $key=>$value)
                        $rec_str.="('".$contactResult[$key]["SENDER"]."'),";
                unset($contactResult);
        }
        $receiversNotIn=$contactedby;
        $sendersIn=$profileid_receiver;
        $typeIn="'A'";
        $contactResult=getResultSet("RECEIVER",$sendersIn,'','',$receiversNotIn,$typeIn);
        if(is_array($contactResult))
        {
                foreach($contactResult as $key=>$value)
                        $rec_str.="('".$contactResult[$key]["RECIEVER"]."'),";
                unset($contactResult);
       }
*/
        @mysql_ping_js($db);

	$sql="CREATE TEMPORARY TABLE SEN1(SEN int(11) , INDEX (SEN))";
$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);

	$sql="INSERT INTO SEN1 VALUES $sen_str";
$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
	if($rec_str)
	{
		$sql="CREATE TEMPORARY TABLE REC1(REC int(11))";
		$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);

                $rec_str=rtrim($rec_str,',');
                $sql="INSERT INTO REC1 VALUES $rec_str";
                $res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);

		/*** Finding total no of profile 'B' contact history profile has contacted or received acceptance*****/
		$sql="CREATE TEMPORARY TABLE REC_HISTORY(DISPLAY_PID int(11) unsigned, CNT smallint(2) unsigned , INDEX (DISPLAY_PID) )";
		$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);

		disable_keys("REC_HISTORY",$db);
		/**/
		//$sql="INSERT INTO newjs.REC_HISTORY(CNT,DISPLAY_PID)(SELECT SQL_CACHE COUNT(*) AS CNT , SENDER FROM newjs.CONTACTS_SEARCH_NEW WHERE SENDER IN ($rec_str) GROUP BY SENDER ORDER BY NULL)";
		/*
		$sql="INSERT INTO newjs.REC_HISTORY(CNT,DISPLAY_PID) (select numSent, senderId from newjs.TEMPSENDER where senderId in ($rec_str) and numSent < 300)"; //jaccards value will automatically reduce the wt so reducing the limit to 200
		*/
		$sql="INSERT INTO newjs.REC_HISTORY(CNT,DISPLAY_PID) (select numSent, senderId from newjs.TEMPSENDER where senderId in ($rec_str))"; //jaccards value will automatically reduce the wt so reducing the limit to 200
                //echo "\n1111".$sql;
		/**/
		$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);

		/*** as above with extra condition that result set is in 'A' as well*****/
		$sql="CREATE TEMPORARY TABLE COMMON_HISTORY(DISPLAY_PID int(11) unsigned, CNT smallint(2) unsigned , INDEX (DISPLAY_PID) )";
		$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);

		disable_keys("COMMON_HISTORY",$db);

		//$sql="INSERT INTO newjs.COMMON_HISTORY(CNT,DISPLAY_PID)(SELECT SQL_CACHE COUNT(*) AS CNT , SENDER FROM newjs.CONTACTS_SEARCH_NEW WHERE SENDER IN ($rec_str) AND RECEIVER IN ($sen_str) GROUP BY SENDER ORDER BY NULL)";
                $sql="INSERT INTO newjs.COMMON_HISTORY(CNT,DISPLAY_PID)(SELECT SQL_NO_CACHE COUNT(*) AS CNT, SENDER FROM newjs.CONTACTS_SEARCH_NEW JOIN REC1 ON REC=SENDER JOIN SEN1 ON SEN=RECEIVER GROUP BY SENDER ORDER BY NULL)";
		$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);

		if(!$remove_viewed_profile)
		{
			$sql="SELECT * FROM newjs.COMMON_HISTORY  WHERE CNT>$five_percent_of_loggedinuser LIMIT 1";
			$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
			if(!mysql_fetch_array($res))
			{
				$use_logged_out_logic=1;
				$new_logic_flag=0;
			}
		}
	}
//TEMP
//$use_logged_out_logic=0;
//$new_logic_flag=1;
//TEMP
	if($rec_str && !$use_logged_out_logic)
	{
		/** Calculating score of B' contact history profiles.This score will be used to compute final receiver score **/
		$sql="CREATE TEMPORARY TABLE REC_HISTORY_SCORE(DISPLAY_PID int(11) unsigned, CNT float(4) unsigned , INDEX (DISPLAY_PID) )";
		$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);

		disable_keys("REC_HISTORY_SCORE",$db);
		enable_keys("REC_HISTORY",$db);
		enable_keys("COMMON_HISTORY",$db);

		$sql="INSERT INTO newjs.REC_HISTORY_SCORE(DISPLAY_PID,CNT)(SELECT a.DISPLAY_PID , (b.CNT/($sen_history_cnt + a.CNT - b.CNT)) as SCORE FROM REC_HISTORY a , COMMON_HISTORY b WHERE a.DISPLAY_PID=b.DISPLAY_PID )";
		$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);

		@mysql_ping_js($db1);    

		skipped_records($contactedby,$profileid_receiver,$db1);

		if(!is_array($previous_rec_arr))
			$previous_rec_arr=array();
		$all_previous_rec_arr = array_merge($senders,$previous_rec_arr);
		$all_previous_rec_str=implode($all_previous_rec_arr,"','");
		unset($all_previous_rec_arr);
		unset($previous_rec_arr);
		unset($senders);

		enable_keys("REC_HISTORY_SCORE",$db);
		/***** sending 10 profiles to search.inc to display ******/
		if($remove_viewed_profile)//single contact
		{
			$sql="SELECT RECEIVER as PROFILEID , SUM(CNT) AS total FROM newjs.REC_HISTORY_SCORE JOIN newjs.CONTACTS_SEARCH_NEW ON REC_HISTORY_SCORE.DISPLAY_PID = CONTACTS_SEARCH_NEW.SENDER WHERE RECEIVER  NOT IN ('$all_previous_rec_str') GROUP BY RECEIVER ORDER BY total DESC ,RECEIVER_TOTAL_POINTS DESC limit ".$limit;
			$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
			if(mysql_num_rows($res)>0)
			{
				$smarty->assign("STYPE","CN");
                                //if(!$j)
                                        //update_random_simlogics("OLD_CNT",$contactedby,$profileid_receiver,'OLD',$db1);

				updateSimTempLog($res,$db1,$contactedby);
				/* TESTED
				while($myviews=mysql_fetch_array($res))
				{
					$sql_insert_views="INSERT IGNORE INTO newjs.SIM_PROFILE_LOG_TEMP(PROFILEID,VIEWED_PROFILE,DATE) VALUES('$contactedby','$myviews[PROFILEID]',now())";
					mysql_query_decide($sql_insert_views,$db1) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql_insert_views,"ShowErrTemplate");
				}
				*/
				mysql_data_seek($res,0);
				connect_737_ro();
				//displayresults($res,"",'flag_single_contact_aj',"","","1","","","","");
				$smarty->assign("TOTAL_RECORDS",$TOTALREC);
				//new_displayresults($res,$start_from,$TOTALREC,9,"simprofile_search_new.php");
				if($limit==9)
					set_results($res,"single_contact",9);
				else{
                                        if ($newAlgo == "hide") {
                                                return $res;
                                        }
                                        new_displayresults($res,$start_from_blank,10,10,"view_similar_profile.php");
                                }
                                        
			}	
			else
			{
				single_contact_no_sim_profile($profileid_receiver,$contactedby,$db1,$limit);
			}
		}
		else
		{
			if($from_viewprofile_v)
			 	$sql="SELECT SQL_CALC_FOUND_ROWS SUM(CNT) AS total, RECEIVER  AS PROFILEID FROM newjs.REC_HISTORY_SCORE JOIN newjs.CONTACTS_SEARCH_NEW ON REC_HISTORY_SCORE.DISPLAY_PID = CONTACTS_SEARCH_NEW.SENDER WHERE RECEIVER  NOT IN ('$all_previous_rec_str') GROUP BY RECEIVER ORDER BY total DESC,RECEIVER_TOTAL_POINTS DESC limit 0,".$limit;
			else
			{
				$sql="SELECT SQL_CALC_FOUND_ROWS SUM(CNT) AS total, RECEIVER FROM newjs.REC_HISTORY_SCORE JOIN newjs.CONTACTS_SEARCH_NEW ON REC_HISTORY_SCORE.DISPLAY_PID = CONTACTS_SEARCH_NEW.SENDER WHERE RECEIVER  NOT IN ('$all_previous_rec_str') GROUP BY RECEIVER ORDER BY total DESC,RECEIVER_TOTAL_POINTS DESC limit $j,".$limit;
			}
			$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);

			/***** Counting the result set ****/
			$sql_cosmo_rows="select FOUND_ROWS() as cnt";
			$resultcosmo=mysql_query_decide($sql_cosmo_rows,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_cosmo_rows,"ShowErrTemplate","","",$db);
			$countcosmo=mysql_fetch_row($resultcosmo);
			$TOTALREC=$countcosmo[0];

			$moreurl="contact=".$contact;

			if(mysql_num_rows($res)>0)
			{
				$smarty->assign("STYPE","VN");
				//if(!$j)
					//update_random_simlogics("NEW_CNT",$contactedby,$profileid_receiver,'NEW',$db1);

				//mysql_close($db);
				//mysql_close($db1);
				connect_737_ro();
				//displayresults($res,$j,"simprofile_search_new.php",$TOTALREC,"","1","",$moreurl,"","",12);
				$smarty->assign("TOTAL_RECORDS",$TOTALREC);
				if($from_viewprofile_v)
				{
					set_results($res,"single_contact",9);
                                        return;
				}
				//echo "yes";
				if ($newAlgo == "hide") {
					return $res;
                                }       
				new_displayresults($res,$start_from,$TOTALREC,10,"simprofile_search_new.php");
			}
			else
				sim_no_contact_results($profileid_receiver,$profileid_receiver,$db1);
		}
	}
	elseif(!$use_logged_out_logic)
	{
		if($remove_viewed_profile)
		{
			//mysql_close($db1);    
			$db1=connect_db();

			skipped_records($contactedby,$profileid_receiver,$db1);
			if(!is_array($previous_rec_arr))
				$previous_rec_arr=array();
			$all_previous_rec_arr = array_merge($senders,$previous_rec_arr);
			$all_previous_rec_str=implode($all_previous_rec_arr,"','");
			unset($all_previous_rec_arr);
			unset($previous_rec_arr);
			global $all_previous_rec_str;
			single_contact_no_sim_profile($profileid_receiver,$contactedby,$db1,$limit);
		}
		else
			sim_no_contact_results($profileid_receiver,$profileid_receiver,$db1);
	}
}
else
	$new_logic_flag=0;//use existing logic only.
?>
