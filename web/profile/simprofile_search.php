<?php
if(stristr($_SERVER['HTTP_USER_AGENT'],'googlebot') || stristr($_SERVER['HTTP_USER_AGENT'],'slurp'))
        exit;
/************************************************************************************************************************
*	FILE NAME		: simprofile_search.php
*	DESCRIPTION		: Similar Profile Search based on the person contacted by the user
*	CREATION DATE		: 29 November, 2005
*	CREATED BY		: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
************************************************************************************************************************/

	$start_tm=microtime(true);
	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it

	include_once "connect.inc";
	include_once "search.inc";
	include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
	include_once("sphinx_search_function.php");
        include_once("similarProfilesConfig.php");
        $smarty->assign("NoOfResults",$suggAlgoNoOfResultsForEOI);
	$db=connect_db();
	if($contact && !is_numeric($contact))
	{
		include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
		ValidationHandler::getValidationHandler("","Non numeric contact Id in simprofile_search.php: $contact","Y");
	}
	if($searchid && !is_numeric($searchid))
	{
		include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
		ValidationHandler::getValidationHandler("","Non numeric searchid in simprofile_search.php:$searchid ","Y");
	}
	if($j && !is_numeric($j))
	{
		include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
		ValidationHandler::getValidationHandler("","Non numeric variable j in simprofile_search.php:$j","Y");
	}

	$smarty->assign("SIM_SEARCH",1);
	$smarty->assign("searchid",$searchid);
	$smarty->assign("SIM_USERNAME",htmlentities($SIM_USERNAME));
	$smarty->assign("CONTACT_ID",$contact);

	$start_from=$j;
	$j=$j*9;
	if($j>9)
        	$j=$j-9;

	if($j=='')
		$sim_j=0;	
	else
		$sim_j=1;

	$lang=$_COOKIE["JS_LANG"];
	if($lang=="deleted")
		$lang="";

	$PAGELEN=12;
	$PAGELEN_QCACHE=120;
	$j_QCACHE=floor($j/120);
	$j_QCACHE=120*$j_QCACHE;

	if(!$j)
		$j = 0;
	$sno=$j+1;

	if($sno>600)
	{
		$temp_sno=floor($sno/12);
		$ip=FetchClientIP();
		if(strstr($ip, ","))    
		{
			$ip_new = explode(",",$ip);
			$ip = $ip_new[1];
		}
		$sql1="REPLACE INTO newjs.SEARCH_50_SIM VALUES('','$contact','$ip',now(),$temp_sno)";
		mysql_query_decide($sql1);
	}


	$smarty->assign("j",$j);
	$smarty->assign("my_scriptname",'simprofile_search.php');
	$smarty->assign('google_ads_left',"1");
	$smarty->assign("bottom_channel","search_bottom");
	
	$data=authenticated($checksum);
	if($data)
	{
		$profileid=$data["PROFILEID"];
	}
	savesearch_onsubheader($profileid);
	if($stype=='L')
	{
		$stypes='L';
		 $smarty->assign("STYPE","L");	
	}
	else
	{
		$stypes='V';
		$smarty->assign("STYPE","V");
	}

	if($data)
	{
		login_relogin_auth($data);
		$smarty->assign("PERSON_LOGGED_IN",1);
		if(strstr($data['SUBSCRIPTION'],'F'))
                        $smarty->assign("SUBSCRIPTION",'Y');
	}
	//This is required since, open_tab is layer, so clicking on navigator link , should take the url to view_similar script.
	if(strstr($_SERVER['PHP_SELF'],'open_tab'))		
		navigation("VS","searchid__-1@j__1@contact__$contact@SIM_USERNAME__$SIM_USERNAME@stype__$stypes@NAVIGATOR__$_GET[NAVIGATOR]@",$_GET['SIM_USERNAME']);
	else
		navigation("VS","",$_GET['SIM_USERNAME']);

        /* Portion of Code added for display of Banners*/
        $smarty->assign("data",$data["PROFILEID"]);
        $smarty->assign("bms_topright",18);
        $smarty->assign("bms_right",28);
        $smarty->assign("bms_bottom",19);
        $smarty->assign("bms_left",24);
        $smarty->assign("bms_new_win",32);

	if(!$j)
		get_similar_count();

	////mysql_close($db);	
	//$db=connect_slave();
	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("contact",$contact);

	$profileid_receiver=$contact;
	$contactedby=$data['PROFILEID'];

	if($contactedby)
	{
		//if(select_random_simlogics($sim_j,$contactedby,$profileid_receiver,$flag='other'))
		{
			$new_logic_flag=1;
			$profile_link=1;
			include("simprofile_search_new.php");
			//mysql_close($db);
			//mysql_close($db1);
			$db=connect_db();
		}
		//else
			//$new_logic_flag=0;
	}
	if($from_viewprofile_v && $new_logic_flag)
		return;
	if(!$new_logic_flag)
	{
		
		unset($senders);

		//Sharding of CONTACTS done by Sadaf
		if($contactedby)
		{
			$sendersNotIn=$contactedby;
			$receiversIn=$profileid_receiver;
		}
		else
		{
			$sendersNotIn='';
			$receiversIn=$profileid_receiver;
		}
                if(!$sendersNotIn && !$receiversIn && !$contactedby)
		{
                        Timedout();
			exit();
		}
		$contactResult=getResultSet("SENDER",'',$sendersNotIn,$receiversIn);

		if(is_array($contactResult))
		{
			foreach($contactResult as $key=>$value)
			{
				$senders[]="'".$contactResult[$key]["SENDER"]."'";
			}
			unset($contactResult);
		}

		if(count($senders)>1)
		{
			$sender_list=implode(",",$senders);
		}
		else if(count($senders)==1)
		{
			$sender_list=$senders[0];
		}
		else
		{
			$sender_list="''";
		}
		if($contactedby)
		{
			$sendersIn=$contactedby;
			$contactResult=getResultSet("RECEIVER",$sendersIn);
			if(is_array($contactResult))
			{
				foreach($contactResult as $key=>$value)
					$previous_rec_arr[]=$contactResult[$key]["RECEIVER"];
				unset($contactResult);
			}

			$receiversIn=$contactedby;
			$contactResult=getResultSet("SENDER",'','',$receiversIn);
			if(is_array($contactResult))
			{
				foreach($contactResult as $key=>$value)
					$previous_rec_arr[]=$contactResult[$key]["SENDER"];
				unset($contactResult);
			}
			if(count($previous_rec_arr)>1)
				$previous_rec=implode("','",$previous_rec_arr);
			else if(count($previous_rec_arr)==1)
				//$previous_rec="''"; corrected by lavesh
				$previous_rec=$previous_rec_arr[0];
			else
				$previous_rec="''";
		}
		
		if($contactedby)
		{
			$sql_ignore="select IGNORED_PROFILEID AS ALL_IGNORED_PROFILEID FROM IGNORE_PROFILE WHERE PROFILEID='$contactedby' AND UPDATED='Y' UNION select PROFILEID AS ALL_IGNORED_PROFILEID FROM IGNORE_PROFILE WHERE IGNORED_PROFILEID='$contactedby' AND UPDATED='Y'";
			$result_ignore=mysql_query_decide($sql_ignore) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_ignore,"ShowErrTemplate");
			while($row_ignore=mysql_fetch_array($result_ignore))
			{
				$ignore_str.="'".$row_ignore['ALL_IGNORED_PROFILEID']."',";
			}
			$ignore_str=substr($ignore_str,0,-1);
																     
			$previous_rec="'".$previous_rec."'";
			$previous_rec.=",".$ignore_str;
			$previous_rec=ltrim($previous_rec,',');
			$previous_rec=rtrim($previous_rec,',');

			if((count($previous_rec_arr)!=0)||($ignore_str))
				$previous_rec.=','.$profileid_receiver;
			else
				$previous_rec=$profileid_receiver;
		}

		if($sender_list!="''")
		{
			$db2=connect_db4();

			if($from_viewprofile_v)
			{
				$j_QCACHE=0;
				$PAGELEN_QCACHE=9;
				if($contactedby)
					$sql="SELECT SQL_CACHE SQL_CALC_FOUND_ROWS RECEIVER as PROFILEID,SUM(WEIGHT) AS CNT FROM newjs.CONTACTS_SEARCH WHERE SENDER IN (".$sender_list.") AND RECEIVER NOT IN (".$previous_rec.") GROUP BY RECEIVER ORDER BY CNT DESC,RECEIVER DESC LIMIT $j_QCACHE,$PAGELEN_QCACHE";
				else			
					$sql="SELECT SQL_CACHE SQL_CALC_FOUND_ROWS RECEIVER AS PROFILEID,SUM(WEIGHT) AS CNT FROM newjs.CONTACTS_SEARCH WHERE SENDER IN (".$sender_list.")  AND RECEIVER <>'$profileid_receiver' GROUP BY RECEIVER ORDER BY CNT DESC,RECEIVER DESC LIMIT $j_QCACHE,$PAGELEN_QCACHE";
			}
			else
			{
				if($contactedby)
					$sql="SELECT SQL_CACHE SQL_CALC_FOUND_ROWS RECEIVER,SUM(WEIGHT) AS CNT FROM newjs.CONTACTS_SEARCH WHERE SENDER IN (".$sender_list.") AND RECEIVER NOT IN (".$previous_rec.") GROUP BY RECEIVER ORDER BY CNT DESC,RECEIVER DESC LIMIT $j_QCACHE,$PAGELEN_QCACHE";
				else			
					$sql="SELECT SQL_CACHE SQL_CALC_FOUND_ROWS RECEIVER,SUM(WEIGHT) AS CNT FROM newjs.CONTACTS_SEARCH WHERE SENDER IN (".$sender_list.")  AND RECEIVER <>'$profileid_receiver' GROUP BY RECEIVER ORDER BY CNT DESC,RECEIVER DESC LIMIT $j_QCACHE,$PAGELEN_QCACHE";
			}


			$res=mysql_query_decide($sql,$db2) or logError("Error while retrieving data from newjs.CONTACTS_SEARCH",$sql,"ShowErrTemplate");
			$csql = "Select FOUND_ROWS()";
			$cres = mysql_query_decide($csql,$db2) or logError("Error while retrieving data from newjs.CONTACTS_SEARCH",$csql,"ShowErrTemplate");
			$db=connect_db();
			$crow = mysql_fetch_row($cres);
			$TOTALREC = $crow[0];
		
			if ($j)
				$cPage = ($j/$PAGELEN) + 1;
			else
				$cPage = 1;
			/*
			if(!function_exists(displayresults))
			{
				include("search.inc");
			}*/
											    
			$moreurl="contact=".$contact;
			if(mysql_num_rows($res)>0)
			{
                                $db=connect_737_ro();
				//$max_results=12;
				//displayresults($res,$j,"simprofile_search.php",$TOTALREC,"","1","",$moreurl,"","",12);
				$smarty->assign("TOTAL_RECORDS",$TOTALREC);
				if($from_viewprofile_v)
				{
					set_results($res,"single_contact",9);
					return;
				}
                                new_displayresults($res,$start_from,$TOTALREC,10,"simprofile_search.php");

                                $db=connect_db();

				if($stype=='L')
					$smarty->assign("STYPE","L");
				else
					$smarty->assign("STYPE","VO");
			}	
			else
				no_contact_results($profileid_receiver,$profileid_receiver);
		}
		else
		{
			no_contact_results($profileid_receiver,$profileid_receiver);
			$db=connect_db();
		}
	}

        if($from_viewprofile_v)
                return;

	$sql="SELECT USERNAME,MTONGUE,CASTE,GENDER,AGE,OCCUPATION,CITY_RES,COUNTRY_RES FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='".$profileid_receiver."'";
	$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row=mysql_fetch_array($res);
	$profile_name=$row['USERNAME'];

	$rec_mtongue=array($MTONGUE_DROP[$row['MTONGUE']]);
	$rec_caste=array($CASTE_DROP[$row['CASTE']]);
	$rec_occ=array($OCCUPATION_DROP[$row['OCCUPATION']]);
	if($row['GENDER']=="M")
		$rec_gender="male";
	else if($row['GENDER']=="F")
		$rec_gender="female";
	$rec_age=$row['AGE'];

	if($row['COUNTRY_RES']=="51")
		$rec_residence=array($CITY_INDIA_DROP[$row['CITY_RES']]);
	else if($row['COUNTRY_RES']=="128")
		$rec_residence=array($CITY_USA_DROP[$row['CITY_RES']]);
	else
		$rec_residence=array($COUNTRY_DROP[$row['COUNTRY']]);

	$metaKeywords=$rec_mtongue[0]." matrimonials,".$rec_caste[0]." matrimonials,".$rec_mtongue[0].",".$rec_caste[0].",".$rec_gender.",".$rec_age.",".$rec_occ[0].",".$rec_residence[0];
	$metaDescription=$rec_mtongue[0]." matrimonials,".$rec_caste[0]." matrimonials,".$rec_mtongue[0].",".$rec_caste[0]." ".$rec_gender." ".$rec_age." ".$rec_occ[0]." ".$rec_residence[0];

	$metaTitle="Profiles similar to ".$profile_name;
	$smarty->assign("from_similar_profile","Y");
	$smarty->assign("SEARCH_CLUSTERING","N");
	$smarty->assign("metaTitle",$metaTitle);
	$smarty->assign("metaKeywords",$metaKeywords);
	$smarty->assign("metaDescription",$metaDescription);
	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("SEARCHCHECKSUM",$searchchecksum);
	/* Tracking Contact Center, as per Mantis 4724 Starts here */
	$end_time=microtime(true)-$start_tm;
	$smarty->assign("TRACK_FOOT",BrijjTrackingHelper::getTailTrackJs($end_time,true,2,"http://track.99acres.com/images/zero.gif"));
	/* Ends Here */

	$smarty->assign("NoClusterToDisplay",1);
	$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
	$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
	//$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
	$smarty->assign("REVAMP_RIGHT_PANEL",$smarty->fetch("revamp_rightpanel.htm"));
	$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
	$smarty->display("view_similar_updated.html");

if($zipIt)
	ob_end_flush();

function get_similar_count()
{
	$date=date("Y-m-d");
	$sql="Update VIEW_SIMILAR_COUNT set CLICKS=CLICKS+1 where Date='$date'";
	mysql_query_decide($sql) or logError("Error",$sql,"ShowErrTemplate");
															     
	if(mysql_affected_rows_js()==0)
	{
		$sql="Insert into VIEW_SIMILAR_COUNT  (DATE,CLICKS) values ('$date','1')";
		mysql_query_decide($sql) or logError("Error",$sql,"ShowErrTemplate");
	}
}

/***********************************************************************************************************************
*	FUNCTION NAME	:  no_contact_results()
*	DESCRIPTION  	:  Re-directs the user to search.php on the basis of user's age, gender, caste and mtongue
*	RETURNS      	:  Nothing
	LAST MODIFIED   :  lavesh
***********************************************************************************************************************/
function no_contact_results($profileid,$profileid_receiver='')
{
	global $checksum,$from_viewprofile_v;
        if($from_viewprofile_v)
                return;

	$sql="SELECT AGE,CASTE,GENDER,MTONGUE FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='".$profileid."'";
	$res=mysql_query_decide($sql) or logError("Error while retrieving data",$sql,"ShowErrTemplate");
	$row=mysql_fetch_array($res);
	$age=$row['AGE'];
	$caste=$row['CASTE'];
	$gender=$row['GENDER'];
	$mtongue=$row['MTONGUE'];
														    
	$lage=$age-2;
	$hage=$age+2;
													    
	$red_url=$SITE_URL."/search/perform?ignoreProfile=".$profileid_receiver."&gender=".$gender."&lage=".$lage."&hage=".$hage."&caste=".$caste."&mtongue=".$mtongue;
	header("Location:".$red_url);
	exit;
}

?>
