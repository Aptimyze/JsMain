<?php

/************************************************************************************************************************
*    DESCRIPTION        : new simillar profile logic introduced.(mantis id 2015)
			  A===>B(finding simillar profile of B)
*    CREATED BY         : lavesh
***********************************************************************************************************************/
/*
if(!$profile_link)
{
	if(select_random_simlogics($j,$contactedby,$profileid_receiver))
		$profile_link=0;
	else
		$profile_link=1;
}*/

$profile_link=1;
if($profile_link)
{
	include_once "simprofile_search_new_beta.php";
}
else
{
	include_once "connect.inc";
	include_once "search.inc";

	global $previous_rec_arr;
	global $all_previous_rec_str;

	$db=connect_db4_ddl();
	$db1=connect_db();

	//set of profile contacted by looged in user or he accepts the profile.
	$sql="SELECT SQL_CACHE RECEIVER AS CONTACT_HISTORY FROM newjs.CONTACTS_SEARCH_NEW WHERE SENDER = '$contactedby'"; 
	$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
	while($row=mysql_fetch_array($res))
	{
		$sen=$row['CONTACT_HISTORY'];
		$sen_str.="('".$sen."'),";
		$sen_str1.=$sen.",";
		$senders[]=$row['CONTACT_HISTORY'];
	}

	if(is_array($senders))
	{
		$sen_history_cnt=count($senders);
		if(!in_array($profileid_receiver,$senders))
			$sen_str.="('".$profileid_receiver."'),";
		$sen_str=rtrim($sen_str,',');
		$sen_str1=rtrim($sen_str1,',');
		$senders_str=implode(",",$senders);

		//Filter Clause --
		$filter_cnt=floor(count($senders)/20);
		//Filter Clause --

	}

	if($sen_history_cnt>1)
	//New algo will be used if logged-in user has contact history(excluding the current one choosen) .
	{
		/* All profiles in which selected/contacted user is/was interested. */
		$sql="SELECT DISTINCT(SENDER) AS CONTACT_HISTORY FROM newjs.CONTACTS_SEARCH_NEW WHERE RECEIVER IN ($senders_str)";
		$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
		while($row=mysql_fetch_array($res))
		{
			$rec_str.="('".$row['CONTACT_HISTORY']."'),";
			$rec_str1.=$row['CONTACT_HISTORY'].",";
		}

		@mysql_ping_js($db);    

		$sql="CREATE TEMPORARY TABLE SEN1(SEN int(11) , INDEX (SEN))";
		$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);

		$sql="INSERT INTO SEN1 VALUES $sen_str";
		$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
		unset($sen_str);

		if($rec_str)
		{
			$sql="CREATE TEMPORARY TABLE REC1(REC int(11))";
			$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);

			$rec_str=rtrim($rec_str,',');
			$rec_str1=rtrim($rec_str1,',');
			$sql="INSERT INTO REC1 VALUES $rec_str";
			$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
			unset($rec_str);

			/*** Finding total no of profile 'B' contact history profile has contacted or received acceptance*****/
			$sql="CREATE TEMPORARY TABLE REC_HISTORY(DISPLAY_PID int(11) unsigned, CNT smallint(2) unsigned , INDEX (DISPLAY_PID) )";
			$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
			disable_keys("REC_HISTORY",$db);

			$sql="INSERT INTO newjs.REC_HISTORY(CNT,DISPLAY_PID)(SELECT SQL_CACHE COUNT(*) AS CNT , SENDER FROM newjs.CONTACTS_SEARCH_NEW WHERE SENDER IN ($rec_str1) GROUP BY SENDER ORDER BY NULL)";
			$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);

			/*** as above with extra condition that result set is in 'A' as well*****/
                        $sql="CREATE TEMPORARY TABLE newjs.COMMON_HISTORY(DISPLAY_PID int(11) unsigned, CNT smallint(2) unsigned , INDEX (DISPLAY_PID) )";
                        $res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
                        //disable_keys("COMMON_HISTORY",$db);

                        $sql="INSERT INTO newjs.COMMON_HISTORY(CNT,DISPLAY_PID)SELECT SQL_NO_CACHE COUNT(*) AS CNT, SENDER FROM newjs.CONTACTS_SEARCH_NEW USE INDEX(RECEIVER) WHERE SENDER IN ($rec_str1) AND RECEIVER IN($sen_str1) GROUP BY SENDER HAVING CNT>$filter_cnt";
                        $res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);


			/** Calculating score of B' contact history profiles.This score will be used to compute final receiver score **/
			$sql="CREATE TEMPORARY TABLE REC_HISTORY_SCORE(DISPLAY_PID int(11) unsigned, CNT float(4) unsigned , INDEX (DISPLAY_PID) )";
			$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
			disable_keys("REC_HISTORY_SCORE",$db);
			enable_keys("REC_HISTORY",$db);
			//enable_keys("COMMON_HISTORY",$db);

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

			if($remove_viewed_profile)//single contact
			{
				$sql="SELECT SQL_CALC_FOUND_ROWS SUM(CNT * CONTACT_SCORE) AS total, RECEIVER FROM newjs.REC_HISTORY_SCORE JOIN newjs.CONTACTS_SEARCH_NEW ON REC_HISTORY_SCORE.DISPLAY_PID = CONTACTS_SEARCH_NEW.SENDER WHERE RECEIVER  NOT IN ('$all_previous_rec_str') GROUP BY RECEIVER ORDER BY total DESC,RECEIVER_TOTAL_POINTS DESC limit 9";
				$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);

				if(mysql_num_rows($res)>0)
				{
					$smarty->assign("STYPE","CN2");
					//if(!$j)
						//update_random_simlogics("NEW_CNT",$contactedby,$profileid_receiver,'NEW',$db1);

					updateSimTempLog($res,$db1,$contactedby);
					/* not in use
					while($myviews=mysql_fetch_array($res))
					{
						$sql_insert_views="INSERT IGNORE INTO newjs.SIM_PROFILE_LOG_TEMP(PROFILEID,VIEWED_PROFILE,DATE) VALUES('$contactedby','$myviews[RECEIVER]',now())";
						mysql_query_decide($sql_insert_views,$db1) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql_insert_views,"ShowErrTemplate");
					}
					*/
					mysql_data_seek($res,0);
					connect_737_ro();
					//displayresults($res,"",'flag_single_contact_aj',"","","1","","","","");
					if($_SERVER['LIMIT9']==1)
						new_displayresults($res,$start_from,10,10,"view_similar_profile.php");
					else
						set_results($res,"single_contact",9);
				}	
				else
				{
					single_contact_no_sim_profile($profileid_receiver,$contactedby,$db1);
				}
			}
			else
			{
				$sql="SELECT SQL_CALC_FOUND_ROWS SUM(CNT * CONTACT_SCORE) AS total, RECEIVER FROM newjs.REC_HISTORY_SCORE JOIN newjs.CONTACTS_SEARCH_NEW ON REC_HISTORY_SCORE.DISPLAY_PID = CONTACTS_SEARCH_NEW.SENDER WHERE RECEIVER  NOT IN ('$all_previous_rec_str') GROUP BY RECEIVER ORDER BY total DESC,RECEIVER_TOTAL_POINTS DESC limit $j,12 ";
				$res=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
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
					connect_737_ro();
					displayresults($res,$j,"simprofile_search_new.php",$TOTALREC,"","1","",$moreurl,"","",12);
				}
				else
					sim_no_contact_results($profileid_receiver,$profileid_receiver,$db1);
			}
		}
		else
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
				single_contact_no_sim_profile($profileid_receiver,$contactedby,$db1);
			}
			else
				sim_no_contact_results($profileid_receiver,$profileid_receiver,$db1);
		}
	}
	else
		$new_logic_flag=0;//use existing logic only.
}

function skipped_records($contactedby,$profileid_receiver,$db1)
{
	global $previous_rec_arr,$remove_viewed_profile;

	$previous_rec_arr[]=$profileid_receiver;//filtering out the receiver.

	if($remove_viewed_profile)
		$sql_ignore="SELECT IGNORED_PROFILEID AS ALL_IGNORED_PROFILEID FROM IGNORE_PROFILE WHERE PROFILEID='$contactedby' AND UPDATED='Y' UNION SELECT VIEWED_PROFILE AS ALL_IGNORED_PROFILEID FROM newjs.SIM_PROFILE_LOG WHERE PROFILEID='$contactedby' UNION select PROFILEID AS ALL_IGNORED_PROFILEID FROM IGNORE_PROFILE WHERE IGNORED_PROFILEID='$contactedby' AND UPDATED='Y'";
	else
		$sql_ignore="select IGNORED_PROFILEID AS ALL_IGNORED_PROFILEID FROM IGNORE_PROFILE WHERE PROFILEID='$contactedby' AND UPDATED='Y' UNION select PROFILEID AS ALL_IGNORED_PROFILEID FROM IGNORE_PROFILE WHERE IGNORED_PROFILEID='$contactedby' AND UPDATED='Y'";

	$result_ignore=mysql_query_decide($sql_ignore,$db1) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_ignore,"ShowErrTemplate","","",$db1);
	while($row_ignore=mysql_fetch_array($result_ignore))
	{
		 if($row_ignore["ALL_IGNORED_PROFILEID"])
			 $previous_rec_arr[]=$row_ignore["ALL_IGNORED_PROFILEID"];
	}

        //Sharding of CONTACTS done by Sadaf
        $sendersIn=$contactedby;
        $contactResult=getResultSet("RECEIVER",$sendersIn);
        if(is_array($contactResult))
        {
                foreach($contactResult as $key=>$value)
                        $previous_rec_arr[]=$contactResult[$key]["RECEIVER"];
                unset($contactResult);
        }

        $receiversIn=$contactedby;
        $typeIn="'I'";
        $contactResult=getResultSet("SENDER",'','',$receiversIn,'','',$typeIn);
        if(is_array($contactResult))
        {
                foreach($contactResult as $key=>$value)
                        $previous_rec_arr[]=$contactResult[$key]["SENDER"];
                unset($contactResult);
        }
}

function single_contact_no_sim_profile($profileid_receiver,$contactedby,$db1)
{
	global $all_previous_rec_str,$_SERVER;
	if($_SERVER['LIMIT10']==1)
		$mylimit=10;
	else
		$mylimit=9;
	$sql="SELECT AGE,CASTE,GENDER,MTONGUE FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid_receiver'";
	$res=mysql_query_decide($sql,$db1) or logError("Error while retrieving data",$sql,"ShowErrTemplate","","",$db1);
	$row=mysql_fetch_array($res);
	$age=$row['AGE'];
	$caste=$row['CASTE'];
	$gender=$row['GENDER'];
	$mtongue=$row['MTONGUE'];

	$lage=$age-2;
	$hage=$age+2;

	if(!is_array($caste) && $caste!="" && $caste!="All")
	{
		$tempcaste=$caste;
		unset($caste);
		$caste[0]=$tempcaste;
	}

	if(is_array($caste) && !in_array("All",$caste))
	{
		$seCaste=get_all_caste($caste);
		if(is_array($seCaste))
			$searchCaste="'" . implode($seCaste,"','") . "'";
	}
	$sql="SELECT SQL_CACHE SQL_CALC_FOUND_ROWS PROFILEID FROM ";

	if($gender=='M')
		$sql.=" newjs.SEARCH_MALE ";
	else if($gender=='F')
		$sql.=" newjs.SEARCH_FEMALE ";

	//$sql.=" WHERE AGE BETWEEN '".$lage."' AND '".$hage."' AND CASTE IN (".$searchCaste.") AND MTONGUE='$mtongue'";
        if($searchCaste)
                $sql.=" WHERE AGE BETWEEN '".$lage."' AND '".$hage."' AND CASTE IN (".$searchCaste.") AND MTONGUE='$mtongue'";
        else
                $sql.=" WHERE AGE BETWEEN '".$lage."' AND '".$hage."' AND MTONGUE='$mtongue'";

	if(trim($all_previous_rec_str)!="")
		$sql.=" AND PROFILEID NOT IN ('$all_previous_rec_str')";
	$sql.=" LIMIT $mylimit";
	$mysqlObj=new Mysql;
        $db_737=$mysqlObj->connect("737");
	$res=mysql_query_decide($sql,$db_737) or logError("Error while retrieving data",$sql,"ShowErrTemplate","","",$db_737);
	//$res=mysql_query_decide($sql,$db1) or logError("Error while retrieving data",$sql,"ShowErrTemplate","","",$db1);

	if(mysql_num_rows($res)>0)
	{
		updateSimTempLog($res,$db1,$contactedby);
		/* tested
		while($myviews=mysql_fetch_array($res))
		{
			$sql_insert_views="INSERT IGNORE INTO newjs.SIM_PROFILE_LOG_TEMP(PROFILEID,VIEWED_PROFILE,DATE) VALUES('$contactedby','$myviews[PROFILEID]',now())";
			mysql_query_decide($sql_insert_views,$db1) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql_insert_views,"ShowErrTemplate");
		}
		*/
		mysql_data_seek($res,0);
		//displayresults($res,"",'single_contact_aj.php',"","","1","","","","");
		if($mylimit==9)
			set_results($res,"single_contact",9);
		else
			new_displayresults($res,$start_from,10,10,"view_similar_profile.php");
	}
	else
	{
		$receiver=addslashes($receiver);
		$sql="insert into NO_SIMILAR_PROFILES(SENDER,RECEIVER,DATE) values ('$sender','$receiver',now())";
		mysql_query_decide($sql,$db1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db1);
	}
}

function sim_no_contact_results($profileid,$profileid_receiver='',$db1)
{
	global $checksum,$from_viewprofile_v;
	if($from_viewprofile_v)
	        return;

	$sql="SELECT AGE,CASTE,GENDER,MTONGUE FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='".$profileid."'";
	$res=mysql_query_decide($sql,$db1) or logError("Error while retrieving data",$sql,"ShowErrTemplate","","",$db1);
	$row=mysql_fetch_array($res);
	if(is_array($row))
	{
		$age=$row['AGE'];
		$caste=$row['CASTE'];
		$gender=$row['GENDER'];
		$mtongue=$row['MTONGUE'];
	
		$lage=$age-2;
		$hage=$age+2;
	
		$red_url=$SITE_URL."/search/perform?ignoreProfile=".$profileid_receiver."&gender=".$gender."&lage=".$lage."&hage=".$hage."&caste=".$caste."&mtongue=".$mtongue;
	}
	else
		$red_url=$SITE_URL."/search/perform";
	
		
	header("Location:".$red_url);
	exit;
}


function disable_keys($tablename,$db)
{
	$sql="alter table $tablename disable keys";
	mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
}

function enable_keys($tablename,$db)
{
	$sql="alter table $tablename enable keys";
	mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);
}
?>
