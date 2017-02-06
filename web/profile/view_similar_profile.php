<?php
	$start_tm=microtime(true);
        $zipIt = 0;
        if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
                $zipIt = 1;
        if($zipIt)
                ob_start("ob_gzhandler");
                                                                                                                             
        include_once("connect.inc");
        include_once("search.inc");
	include_once("sphinx_search_function.php");
        include_once("similarProfilesConfig.php");
        include_once(JsConstants::$docRoot."/commonFiles/flag.php");
        $db=connect_db();
	$data = authenticated();
	if($_REQUEST['contactEngineConfirmation'])
	{
	$smarty->assign("contactEngineConfirmation",stripslashes(urldecode($_REQUEST['contactEngineConfirmation'])));
	}
        
        // Page Numbering if page number in url is out of range It will return page 1
	if(!$_GET["page"] or $_GET["page"]>3 or $_GET["page"]<1)
	{
		$_GET["page"]=1;
	}
	$page=$_GET["page"];
	$smarty->assign("page",($_GET["page"]+1));
        //Page Numbering End
        
        
	if($_REQUEST['layerToShow'])
	{
		$smarty->assign("layerToShow",$SITE_URL.$_REQUEST['layerToShow']);
		$smarty->assign("contactType",$_REQUEST['contactType']);
	}
	$smarty->assign("checksum",$checksum);

	$smarty->assign("STYPE","CO");
	//bms Code
	$SITE_URL=$data["SITE_URL"];
	$smarty->assign("data",$data["PROFILEID"]);
	$smarty->assign("bms_topright",14);
	$smarty->assign("bms_left",16);
	$smarty->assign("bms_bottom",23);
	$smarty->assign("bms_middle",15);
	$smarty->assign("bms_new_win",39);
	//bms Code

	$smarty->assign("SIM_SEARCH",$searchid);
	$smarty->assign("searchid",0);
        $smarty->assign("DONT_SHOW",1);
        $smarty->assign("VS",1);
        $smarty->assign("CONTACT_ID",$contact);
        $smarty->assign("NoOfResults",$suggAlgoNoOfResultsForEOI);
	if($contact && !is_numeric($contact))
        {
                include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
                ValidationHandler::getValidationHandler("","Non numeric contact Id in view_similar_profile.php:".$contact,"Y");
        }
	
	if($senders_data)
		$contact=getProfileidFromChecksum($senders_data);
	rightpanel();
	if($data)
	{ 
		login_relogin_auth($data);
		$contactedby = $data['PROFILEID'];
		$_SERVER['LIMIT10']=1;
		if(($nextViewSim || $nextViewSim1) && $contactedby)
		{
			//$_SERVER['LIMIT10']=1;
			if($nextViewSim)
				updateSimProfileLog($contactedby,$db);
		}
		savesearch_onsubheader($contactedby);
		//$contacted = md5($contact)."i".$contact;
		$contacted=createChecksumForSearch($contact);
		$from = "single_contact_aj";
		$scriptname = "view_similar_profile.php";
		$MESSAGE=urldecode($MESSAGE);
		$Y_MESSAGE=stripslashes(htmlspecialchars($MESSAGE));
		$smarty->assign("MESSAGE",$Y_MESSAGE);
		$smarty->assign("TRIM_MESSAGE",$Y_MESSAGE);
	
		if(stristr($data['SUBSCRIPTION'],'F'))
		{
			set_draft($MESSAGE);
		}
                // Limit is 18 result Per PAGE it can be changed directly
		revamp_get_other_relevant_pro($data,$contacted,$from,$scriptname,$suggAlgoNoOfResultsForEOI,$page);
	}
        $sql="SELECT USERNAME,MTONGUE,CASTE,GENDER,AGE,OCCUPATION,CITY_RES,COUNTRY_RES,SUBSCRIPTION FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='".$contact."'";
        $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        $row=mysql_fetch_array($res);
        $profile_name=$row['USERNAME'];
	$rec_sub=$row['SUBSCRIPTION'];
	$smarty->assign("NAVIGATOR1",$NAVIGATOR);
	
	//Bread crumb navigation in view similar profile.
	navigation("CVS","",$profile_name);
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

	if($data['PROFILEID'])
	{
		$logged_pid=$data['PROFILEID'];
		$tempStatus=getIncompleteUnscreenedStatus($logged_pid);
	        $sender_details["INCOMPLETE"] = $tempStatus["INCOMPLETE"];
        	$sender_details["ACTIVATED"] = $tempStatus["ACTIVATED"];
        	$tempParam = temporaryInterestSuccess($sender_details["INCOMPLETE"], $sender_details["ACTIVATED"]);
		if($tempParam)
		{ 
			$profilechecksum=createchecksumforsearch($logged_pid);
			if($tempParam=='incomplete')
				$smarty->assign("TEMP_MES","You had expressed interest in this profile and the same will be delivered once your profile is complete. Please <a href='/profile/viewprofile.php?checksum=&profilechecksum=$profilechecksum&EditWhatNew=incompletProfile'>click here</a> to complete your profile");
			else
				 $smarty->assign("TEMP_MES","You had expressed interest in this profile and the same will be delivered once your profile goes live");
		}
	}
	if(stristr($data['SUBSCRIPTION'],'F') || stristr($data['SUBSCRIPTION'],'D'))
	{
		$smarty->assign("PAID",1);
	}

	if(!stristr($data['SUBSCRIPTION'],'F'))
	{
		$smarty->assign("NOT_PAID",'<BR>To include contact details or send message, <a href=\'/profile/mem_comparison.php\' class=\'b\'>Become a Paid member now</a>');
	}
	else
	{
		if(!stristr($rec_sub,'D'))	
		{
		 	$smarty->assign("YES_PAID",'<BR>Please note that you can see this member\'s contact details after this member accepts your interest');
			

		}
	}
	//Treshold message code
	$threshold_message=get_limit_message($data[PROFILEID],$data[SUBSCRIPTION]);
        $smarty->assign("THRESHOLD_MESSAGE",$threshold_message);
        $metaTitle="Profiles similar to ".$profile_name;
	if($draft_name=="")
		$smarty->assign("SEND_REM",1);

	/* Tracking Contact Center, as per Mantis 4724 Starts here */
        $end_time=microtime(true)-$start_tm;
        $smarty->assign("TRACK_FOOT",BrijjTrackingHelper::getTailTrackJs($end_time,true,2,"https://track.99acres.com/images/zero.gif","SIMILAR_PROFILE_PAGE"));
        /* Ends Here */
	
	$smarty->assign("SIM_SEARCH",'Y');
	$smarty->assign("SIM_USERNAME",$profile_name);
	$smarty->assign("TYPE_OF_CON",$type_of_con);
	$smarty->assign("from_similar_profile","Y");
        $smarty->assign("NoClusterToDisplay",1);
	$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
	$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
	//$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
	$smarty->assign("REVAMP_RIGHT_PANEL",$smarty->fetch("revamp_rightpanel.htm"));
//	$smarty->assign("REVAMP_LEFT_PANEL",$smarty->fetch("revamp_leftpanel.htm"));
	$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
	$smarty->display("view_similar_updated.html");

	if($zipIt)
                ob_end_flush();
?>
