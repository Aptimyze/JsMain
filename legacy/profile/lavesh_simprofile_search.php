<?php

/************************************************************************************************************************
*    DESCRIPTION        : new simillar profile logic introduced.(mantis id 2015)
			  A===>B(finding simillar profile of B)
*    CREATED BY         : lavesh
***********************************************************************************************************************/
$fileName =  $_SERVER["SCRIPT_FILENAME"];
$http_msg=print_r($_SERVER,true);
mail("reshu.rajput@gmail.com,lavesh.rawat@gmail.com","For DLL Movement - $fileName",$http_msg);


$time_ini = microtime_float();

include_once "connect.inc";
include_once "search.inc";

$db=connect_slave();
$db1=connect_slave();

global $previous_rec_arr;
global $all_previous_rec_str;

/* All profiles in which logged in user is/was interested . This will include the current selected profile as well.*/
$senders[]=$profileid_receiver;
$sql="SELECT SQL_CACHE RECEIVER AS CONTACT_HISTORY FROM newjs.CONTACTS_SEARCH_NEW WHERE SENDER = '$contactedby'"; 
$res=mysql_query_decide($sql,$db) or die(mysql_error_js().$sql);
while($row=mysql_fetch_array($res))
{
	$senders[]=$row['CONTACT_HISTORY'];
}
$sen_history_cnt=count($senders);
$sen_str=implode($senders,',');

$time_end = microtime_float();
$time = $time_end - $time_ini;
echo '<br>time 1st block--->';
echo $time;
$time_start = microtime_float();

if($sen_history_cnt>1)
//New algo will be used if logged-in user has contact history(excluding the current one choosen) .
{
	/* All profiles in which selected/contacted user is/was interested. */
	$sql="SELECT SENDER AS CONTACT_HISTORY FROM newjs.CONTACTS WHERE RECEIVER='$profileid_receiver' AND SENDER<>$contactedby UNION ALL SELECT RECEIVER AS CONTACT_HISTORY FROM newjs.CONTACTS WHERE SENDER='$profileid_receiver' AND RECEIVER<>$contactedby AND TYPE='A'";
	$res=mysql_query_decide($sql,$db) or die(mysql_error_js().$sql);
	while($row=mysql_fetch_array($res))
	{
		$receiver[]=$row['CONTACT_HISTORY'];
	}

echo "COUNT--->".count($receiver);
$time_end = microtime_float();
$time = $time_end - $time_start;
echo '<br>time 2nd block--->';
echo $time;
$time_start = microtime_float();

	if(is_array($receiver))
	{
		$rec_str=implode($receiver,',');
		unset($receiver);

		/*** Finding total no of profile 'B' contact history profile has contacted or received acceptance*****/
		$sql="CREATE TEMPORARY TABLE lavesh(DISPLAY_PID int(11) unsigned, CNT int(4) unsigned , INDEX (DISPLAY_PID) )";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js().$sql);

		disable_keys("lavesh");

		$sql="INSERT INTO newjs.lavesh(CNT,DISPLAY_PID)(SELECT SQL_CACHE COUNT(*) AS CNT , SENDER FROM newjs.CONTACTS_SEARCH_NEW WHERE SENDER IN ($rec_str) GROUP BY SENDER ORDER BY NULL)";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js().$sql);
		
$time_end = microtime_float();
$time = $time_end - $time_start;
echo '<br>time 3nd block--->';
echo $time;
$time_start = microtime_float();
unset($my_arr);

		/*** as above with extra condition that result set is in 'A' as well*****/
		$sql="CREATE TEMPORARY TABLE lavesh1(DISPLAY_PID int(11) unsigned, CNT int(4) unsigned , INDEX (DISPLAY_PID) )";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js().$sql);

		disable_keys("lavesh1");

		$sql="INSERT INTO newjs.lavesh1(CNT,DISPLAY_PID)(SELECT SQL_CACHE COUNT(*) AS CNT , SENDER FROM newjs.CONTACTS_SEARCH_NEW WHERE SENDER IN ($rec_str) AND RECEIVER IN ($sen_str) GROUP BY SENDER ORDER BY NULL)";
                $res=mysql_query_decide($sql,$db) or die(mysql_error_js().$sql);

$time_end = microtime_float();
$time = $time_end - $time_start;
echo '<br>time 4th block--->';
echo $time;
$time_start = microtime_float();

		/** Calculating score of B' contact history profiles.This score will be used to compute final receiver score **/
		$sql="CREATE TEMPORARY TABLE lavesh2(DISPLAY_PID int(11) unsigned, CNT float(4) unsigned , INDEX (DISPLAY_PID) )";
		$res=mysql_query_decide($sql,$db1) or die(mysql_error_js().$sql);

		disable_keys("lavesh2");
		enable_keys("lavesh");
		enable_keys("lavesh1");

		$sql="INSERT INTO newjs.lavesh2(DISPLAY_PID,CNT)(SELECT a.DISPLAY_PID , (b.CNT/($sen_history_cnt + a.CNT - b.CNT)) as SCORE FROM lavesh a , lavesh1 b WHERE a.DISPLAY_PID=b.DISPLAY_PID )";
		$res=mysql_query_decide($sql,$db1) or die(mysql_error_js().$sql);

$time_end = microtime_float();
$time = $time_end - $time_start;
echo '<br>time 5th block--->';
echo $time;
$time_start = microtime_float();
		
		skipped_records($contactedby,$profileid_receiver);

$time_end = microtime_float();
$time = $time_end - $time_start;
echo '<br>time 6th block--->';
echo $time;
$time_start = microtime_float();

		if(!is_array($previous_rec_arr))
			$previous_rec_arr=array();
		$all_previous_rec_arr = array_merge($senders,$previous_rec_arr);
		$all_previous_rec_str=implode($all_previous_rec_arr,"','");
		unset($all_previous_rec_arr);
		unset($previous_rec_arr);
		unset($senders);

$time_end = microtime_float();
$time = $time_end - $time_start;
echo '<br>time 7th block--->';
echo $time;
$time_start = microtime_float();

		enable_keys("lavesh2");
		/***** sending 12 profiles to search.inc to display ******/
		if($remove_viewed_profile)//single contact
		{
			$sql="SELECT SUM(CNT) AS total, RECEIVER FROM newjs.lavesh2 JOIN newjs.CONTACTS_SEARCH_NEW ON lavesh2.DISPLAY_PID = CONTACTS_SEARCH_NEW.SENDER WHERE RECEIVER  NOT IN ('$all_previous_rec_str') GROUP BY RECEIVER ORDER BY total DESC limit 6";
			$res=mysql_query_decide($sql,$db) or die(mysql_error_js().$sql);
			if(mysql_num_rows($res)>0)
			{
				while($myviews=mysql_fetch_array($res))
				{
					$sql_insert_views="INSERT IGNORE INTO newjs.SIM_PROFILE_LOG(PROFILEID,VIEWED_PROFILE,DATE) VALUES('$contactedby','$myviews[PROIFLEID]',now())";
					mysql_query_decide($sql_insert_views) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql_insert_views,"ShowErrTemplate");
				}
				mysql_data_seek($res,0);
				displayresults($res,"",'flag_single_contact_aj',"","","1","","","","");
			}	
			else
			{
				single_contact_no_sim_profile($profileid_receiver,$contactedby);
			}
			
		}
		else
		{
			$sql="SELECT SQL_CALC_FOUND_ROWS SUM(CNT) AS total, RECEIVER FROM newjs.lavesh2 JOIN newjs.CONTACTS_SEARCH_NEW ON lavesh2.DISPLAY_PID = CONTACTS_SEARCH_NEW.SENDER WHERE RECEIVER  NOT IN ('$all_previous_rec_str') GROUP BY RECEIVER ORDER BY total DESC limit $j,12 ";
			$res=mysql_query_decide($sql,$db) or die(mysql_error_js().$sql);

$time_end = microtime_float();
$time = $time_end - $time_start;
echo '<br>time 8th(final query) block--->';
echo $time;
$time_start = microtime_float();

			/***** Counting the result set ****/
			$sql_cosmo_rows="select FOUND_ROWS() as cnt";
			$resultcosmo=mysql_query_decide($sql_cosmo_rows) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_cosmo_rows,"ShowErrTemplate");
			$countcosmo=mysql_fetch_row($resultcosmo);
			$TOTALREC=$countcosmo[0];
$time_end = microtime_float();
$time = $time_end - $time_start;
echo '<br>time 9th block--->';
echo $time;
$time_start = microtime_float();

			$moreurl="contact=".$contact;

			if(mysql_num_rows($res)>0)
				displayresults($res,$j,"simprofile_search_new.php",$TOTALREC,"","1","",$moreurl,"","",12);
			else
				no_contact_results($profileid_receiver,$profileid_receiver);
		
$time_end = microtime_float();
$time = $time_end - $time_start;
echo '<br>time 10th (display) block--->';
echo $time;
$time_start = microtime_float();
		}

		$time_end = microtime_float();
		$time = $time_end - $time_ini;
		echo '<br>time total block--->';
		echo $time;
		//die;
	}
	else
	{
		if($remove_viewed_profile)
		{
			skipped_records($contactedby,$profileid_receiver);
			if(!is_array($previous_rec_arr))
				$previous_rec_arr=array();
			$all_previous_rec_arr = array_merge($senders,$previous_rec_arr);
			$all_previous_rec_str=implode($all_previous_rec_arr,"','");
			unset($all_previous_rec_arr);
			unset($previous_rec_arr);
			global $all_previous_rec_str;
			single_contact_no_sim_profile($profileid_receiver,$contactedby);
		}
		else
			no_contact_results($profileid_receiver,$profileid_receiver);
	}
}
else
	$new_logic_flag=0;//use existing logic only.

function skipped_records($contactedby,$profileid_receiver)
{
	global $previous_rec_arr,$db,$remove_viewed_profile;

	$previous_rec_arr[]=$profileid_receiver;//filtering out the receiver.

	if($remove_viewed_profile)
		$sql_ignore="select IGNORED_PROFILEID from IGNORE_PROFILE WHERE PROFILEID='$contactedby' AND UPDATED='Y' UNION SELECT VIEWED_PROFILE AS IGNORED_PROFILEID FROM newjs.SIM_PROFILE_LOG WHERE PROFILEID='$contactedby'";
	else
		$sql_ignore="select IGNORED_PROFILEID from IGNORE_PROFILE WHERE PROFILEID='$contactedby' AND UPDATED='Y'";

	$result_ignore=mysql_query_decide($sql_ignore,$db) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_ignore,"ShowErrTemplate");
	while($row_ignore=mysql_fetch_array($result_ignore))
	{
		 if($row_ignore["IGNORED_PROFILEID"])
			 $previous_rec_arr[]=$row_ignore["IGNORED_PROFILEID"];
	}

	$sql="SELECT RECEIVER AS ALREADY_CONTACTED FROM newjs.CONTACTS WHERE SENDER='$contactedby' UNION SELECT SENDER AS ALREADY_CONTACTED FROM newjs.CONTACTS WHERE RECEIVER='$contactedby' AND TYPE<>'I'";
	$res=mysql_query_decide($sql,$db) or logError("Error while retrieving data from newjs.CONTACTS",$sql,"ShowErrTemplate");
	while($row=mysql_fetch_array($res))
	{
		$previous_rec_arr[]=$row["ALREADY_CONTACTED"];
	}
}

function single_contact_no_sim_profile($profileid_receiver,$contactedby)
{
	global $all_previous_rec_str;
	$sql="SELECT AGE,CASTE,GENDER,MTONGUE FROM newjs.JPROFILE WHERE PROFILEID='$profileid_receiver'";
	$res=mysql_query_decide($sql) or logError("Error while retrieving data",$sql,"ShowErrTemplate");
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

	$sql.=" WHERE AGE BETWEEN '".$lage."' AND '".$hage."' AND CASTE IN (".$searchCaste.") AND MTONGUE='$mtongue'";
	if(trim($all_previous_rec_str)!="")
		$sql.=" AND PROFILEID NOT IN ('$all_previous_rec_str')";
	$sql.=" LIMIT 6";
	$res=mysql_query_decide($sql) or logError("Error while retrieving data",$sql,"ShowErrTemplate");

	if(mysql_num_rows($res)>0)
	{
		while($myviews=mysql_fetch_array($res))
		{
			$sql_insert_views="INSERT IGNORE INTO newjs.SIM_PROFILE_LOG(PROFILEID,VIEWED_PROFILE,DATE) VALUES('$contactedby','$myviews[PROFILEID]',now())";
			mysql_query_decide($sql_insert_views) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql_insert_views,"ShowErrTemplate");
		}
		mysql_data_seek($res,0);
		displayresults($res,"",'single_contact_aj.php',"","","1","","","","");
	}
	else
		log_no_similar_profiles($contactedby,$profileid_receiver);
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function disable_keys($tablename)
{
	global $db;
	$sql="alter table $tablename disable keys";
	mysql_query_decide($sql) ;
}

function enable_keys($tablename)
{
	global $db;
	$sql="alter table $tablename enable keys";
	mysql_query_decide($sql) ;
}
?>

