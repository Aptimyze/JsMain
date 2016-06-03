<?php
	$start_tm=microtime(true);
	if($from_horo_layer || $from_registration)
	{
		include_once("horoscope_details_update.php");
		horoscope_details_update($checksum);
	}
	ini_set('memory_limit', '64M');

	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt && !$dont_zip_now)
		ob_start("ob_gzhandler");
	//end of it

	//Sharding+Combining
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
	$mysqlObj=new Mysql;
	$jpartnerObj=new Jpartner;
	$jpartnerObj_logged=new Jpartner;
	//Sharding+Combining

	// common include file
	include_once("connect.inc");
	// contains array definitions
	include_once("arrays.php");
	include_once("hin_arrays.php");
	// contains screening information
	include_once("flag.php");
	// contains values and labels for dropdowns
	include_once("dropdowns.php");
	include_once("hits.php");
	include("manglik.php");
	include_once("contact.inc");

	//contains function profile_percent
	include_once('functions.inc');	
	include_once('ntimes_function.php');
	//include_once('contacts_functions.php');

/*	$smarty->assign("groupname",$groupname);
	$smarty->assign("reg_comp_frm_ggl",$reg1_comp_frm_ggl);
	$smarty->assign("reg_comp_frm_ggl_nri",$reg1_comp_frm_ggl_nri);
 	$smarty->assign("profile_score",$profile_score);
	$smarty->assign("REGISTRATION",$smarty->fetch("registration_tracking.htm"));*/
	//WAP related code --added by jaiswal
	include_once("mobile_detect.php");

	//WAP Related code- ends here


	//View similar stypes
	$STYPE_ARR=array("VO","VN","CO","CN","CN2");
	if(!in_array("$stype",$STYPE_ARR) && $searchid && $ONLINE_SEARCH!=1)
		$smarty->assign("SHOW_NEXT_PREV",1);	

	//HANDLING BLANK STYPE FROM PHOTO REQUEST.
	if(!$stype && $clicksource=='photo_request')
		$stype=17;
	//HANDLING BLANK STYPE FROM PHOTO REQUEST.

	if($isMobile)
		if($stype)
			$stype=29;
	//calling common_assign function, since this page can open in logout case as well
	common_assign($data);
	//tracking code for STYPE
	//end
	//Used to set main tab of My Profile to change
	if($fromPage=='contacts')
	{
		if($page != "matches")
		{
			$other_params = 'NAVIGATOR='.$NAVIGATOR.'&j='.$j.'&inf_checksum='.$inf_checksum."&stype=".$stype;
			include_once("cmr.php");
			$profilechecksum = getNextProfile($profileids, $total_rec, $actual_offset, $profilechecksum, $contact, $self, $self_profileid, $flag, $type, $archive, $date_search, $start_date, $end_date, $other_params, $page);
			$smarty->assign("fromPage",'contacts');
		}
	}
	
	if($searchid && $show_profile && !$PRINT)
	{
		if(!$j)
			$j=1;
		if(!$actual_offset)	
			$actual_offset=($j-1)*10+$offset;
		
		 
		$db_master = connect_db();
        	$next_prev_prof=next_prev_view_profileid($searchid,$Sort,$actual_offset,$show_profile,$stype);
		$profilechecksum=md5($next_prev_prof)."i$next_prev_prof";
		if($show_profile=="prev")
		{
			$actual_offset=$actual_offset-1;
		}
		elseif($show_profile="next")
		{
			$actual_offset=$actual_offset+1;
		}				
	}
	// connect to database
	$db=connect_db();
	if($searchid && !$PRINT)
	{
		if(is_array($_GET))
			foreach($_GET as $key=>$val)
			{
				if($key!='profilechecksum' && $key!='NAVIGATOR' && $key!="searchid" && $key!="j" && $key!='total_rec' && $key!='actual_offset' && $key!='offset' && $key!='show_profile' && $key!="after_login_call" && $key!="CALL_ME" && $key!="CAME_FROM_CONTACT_MAIL")
					$other_params.="&$key=$val";
			}
		$smarty->assign("other_params","$other_params");

		if(!$actual_offset)
		{
			if(!$j)
				$j=1;
			$actual_offset=($j-1)*10+$offset;	
		}
		$smarty->assign("SHOW_PREV",1);
		$smarty->assign("SHOW_NEXT",1);
		if($actual_offset==0)
			$smarty->assign("SHOW_PREV",0);
		$total_records=$total_rec-1;
		if($actual_offset==$total_records)
			$smarty->assign("SHOW_NEXT",0);
		$smarty->assign("searchid",$searchid);
		$smarty->assign("j",$j);
		 $smarty->assign("actual_offset",$actual_offset);
		$smarty->assign('total_rec',$total_rec);
		if($stype=='W')
		{
			$date=date('Y-m-d');
			$sql_fp="UPDATE MIS.FEATURED_PROFILE_VIEW SET COUNT=COUNT+1 WHERE DATE='$date'";
	                $res_fp=mysql_query_decide($sql_fp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_fp,"ShowErrTemplate");
			if(!mysql_affected_rows($db))
			{
				$sql_no="INSERT INTO MIS.FEATURED_PROFILE_VIEW (DATE,COUNT) VALUES ('$date','1')";
				$res_no=mysql_query_decide($sql_no) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_no,"ShowErrTemplate");
			}

		}
	}
	/*
	$searchid=3100;	
	$offset=1;
	$flag='P';
	$Sort='O';
	$db_master = connect_db();
	echo next_prev_view_profileid($searchid,$Sort,$offset,$flag);
	die;
	*/
        //Added by Lavesh For Matchalert-mis purpose
        if($logic_used)
        {
                $matchalert_mis_variable=$logic_used."###".$recomending."###".$is_user_active;
                $smarty->assign("matchalert_mis_variable",$matchalert_mis_variable);
		$smarty->assign("matchalertlogin",1);
        }
	else
		$smarty->assign("matchalertlogin",$matchalertlogin);
        //Added by Lavesh For Matchalert-mis purpose

	//Added by lavesh for showing login layer when user clicks on contact button of matchalert template	
        if($matchalert_contact)
        {
		/*
                $datatest=authenticated($checksum);
                if(!$datatest["PROFILEID"])
                {
                        foreach ($_GET as $var => $value)
                        {
                                if($var!='matchalert_contact')
                                        $passingvar.="$var=$value".'&';
                        }
                        $smarty->assign("target_url","$SITE_URL/profile/contact_profilepage_decide.php?$passingvar");
                        $smarty->assign("target_url_onclose","$SITE_URL/profile/viewprofile.php?$passingvar");
                        $smarty->assign("loginlayer",1);
                        $smarty->assign("MATCHALERT_RECEIVER",$USERNAME_RECEIVER);
                }
		*/
        }
	//Added by lavesh for showing login layer when user clicks on contact button of matchalert template	

	//Added By lavesh for counting number of user that accept initial contact through suggested Profile.
        if($suggest_profile==1)
                $smarty->assign("suggest_profile",1);
        //Ends Here
	//print_r($_POST);
	//print_r($_GET);
	$VIEWPROFILE_IMAGE_URL="$IMG_URL/profile";
	//$VIEWPROFILE_IMAGE_URL="http://ser4.jeevansathi.com/profile";
	//$VIEWPROFILE_IMAGE_URL="/profile";

	//$smarty->assign("coming_from_index","1");
	$smarty->assign("head_tab","my jeevansathi");   //flag for headnew.htm tab
	//Added By lavesh rawat for adding diff channel id's for all the google ad slots .
	$smarty->assign("bottom_channel","details_bottom");
	//Ends here.

	//added by sriram.
	$db_master = connect_db();
	$db_slave = connect_737_ro();
	$db = connect_db();

	$smarty->assign("VIEWPROFILE","Y");
	

	//Added by lavesh-->if profile is declined from search page..(decline button should be clicked by default)	
	if($search_decline || ($CAME_FROM_CONTACT_MAIL==1&& $button=='decline'))
		$smarty->assign("search_decline",1);

	if($source)
	{
		setcookie("JS_SOURCE",$source,time()+2592000,"/",$domain);
		savehit($source,'viewprofile.php');
		if($source=='onoffreg' && $lead && $mid)
                {
                        setcookie("JS_LEAD",$lead,time()+2592000,"/",$domain);
                        $sql_ma="UPDATE sugarcrm.LEAD_MATCHES_LOG SET CLICKED='Y' WHERE ID='$mid'";
                        mysql_query_optimizer($sql_ma) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_ma,"ShowErrTemplate");

                }

	}


	//Bug Id:40225,, Search by profile id is case sensitive 	
	if($_GET['username'])
	{
		$username_temp=$protect_obj->get_correct_username($username);
		if($username_temp)
		{
			$_GET['username']=$username=$username_temp;
		}
	}
	//bug done

	// changes made by Shobha Kumari on 06.04.2006 for auto logging of the user 
	// in case profile is being viewed by Infovision executive
	
	if ($crmback == "admin")
	{
		$data = infovision_auth($inf_checksum);	// authentication of user in case of infovision
		$smarty->assign("crmback",$crmback);
		$smarty->assign("inf_checksum",$inf_checksum);
		$smarty->assign("cid",$cid);
	}
	else
        {
                if($CAME_FROM_CONTACT_MAIL)
                        $checksum="";
                if($mbureau=="bureau" && !$checksum)// && !$CAME_FROM_CONTACT_MAIL)
                {
                        $fromprofilepage=1;//used in ../marriage_bureau/connectmb.inc
                        include_once('../marriage_bureau/connectmb.inc');
                        $data=login_every_user($pid);

                }
                else
		{
			if($_GET['CAME_FROM_CONTACT_MAIL'] || $_GET['clicksource']=='matchalert1' || $enable_auto_loggedin)
        		{
				$data=authenticated();
				if($_GET['echecksum'])
				{
					$show_chatbar=1;
					if($_COOKIE['chatbar']=='yes')
						$show_chatbar=0;
					
				}
				if(!$data['PROFILEID'])
				{
	        			$epid=$protect_obj->js_decrypt($_GET['echecksum']);
					if($_GET['checksum']==$epid)
					{
						$epid_arr=explode("i",$epid);
						$profileid=$epid_arr[1];
						if($profileid)
						{
							$smarty->assign("for_about_us","1");
							
							$sql="SELECT USERNAME,PASSWORD,ACTIVATED FROM newjs.JPROFILE WHERE  PROFILEID=$profileid and  activatedKey=1";
							$res=mysql_query_decide($sql) or die($sql.mysql_error());
							if(!($row=mysql_fetch_assoc($res)))
							{
								$sql="SELECT USERNAME,PASSWORD,ACTIVATED FROM newjs.JPROFILE WHERE  PROFILEID=$profileid and  activatedKey=0";
	                                                        $res=mysql_query_decide($sql) or die($sql.mysql_error());
        	                                                $row=mysql_fetch_assoc($res);
							}
							if(!$row)
							{
								showProfileError_DP();
							}
							if($row[ACTIVATED]!='Y')
								showProfileError_DP($row[ACTIVATED]);

							$_POST['username']=$row['USERNAME'];
							$_POST['password']=$row['PASSWORD'];
							$username=$row['USERNAME'];
							$password=$row['PASSWORD'];
							$data =login($username,$password);
							//authenticated();//added by manoranjan for match alert and autologin, where chat bar was not coming previously
							/* Work done for Invalid Number of profiles who are coming from mailer 

							$sql="SELECT COUNT(*) AS CNT FROM incentive.INVALID_PHONE WHERE PROFILEID='$profileid'";
							$res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
							$row=mysql_fetch_array($res);
							$count=$row['CNT'];
							if($count)
							{
								$profilechecksum = md5($profileid)."i".$profileid;
								echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/P/valid_number.php?checksum=$checksum&profilechecksum=$profilechecksum&post_login=1\"></body></html>";											                                                          exit;
							} */
						}
					}
				}
				else
				{
					$profileid=$data['PROFILEID'];
					$username=$data['USERNAME'];
				}
		
				if($show_chatbar==1)
				{
					$request_uri=$_SERVER['REQUEST_URI'];
                        
                        		$pos = strpos($request_uri,"login.php");
	                	        $pos1= strpos($request_uri,"intermediate.php");
        		                $pos2=strpos($request_uri,"login_redirect.php");
	        	               // $pos3=strpos($request_uri,"valid_number.php");
                        	       // if($pos == false && $pos1 == false && $pos2== false && $pos3 == false){
                        	        if($pos == false && $pos1 == false && $pos2== false){
                                	        header("Location:".$SITE_URL."/profile/intermediate.php?parentUrl=".$request_uri);
                                        	exit;
                                	}
				}
			}
                        else
                                $data=authenticated($checksum);

			if($data['PROFILEID'] && !$PRINT)
                        {
				if($ownview==1 || $gtalk_mailer==1)
                                {
                                        $profilechecksum=createChecksumForSearch($data['PROFILEID']);
                                }
				//Fetching month ,today and total contacts initiated by this user, this is required this script won't call login_relogin function//
				//Getting data from memcache.
				$memcache_cont_stat=unserialize(memcache_call($data['PROFILEID']));
				if(!$memcache_cont_stat)
				{
					put_back_to_contact_status();
					$memcache_cont_stat=unserialize(memcache_call($profileid));
				}
				if(!$memcache_cont_stat)
				{
					 $sql_cont_stat = "SELECT MONTH_INI_BY_ME,TODAY_INI_BY_ME,TOTAL_CONTACTS_MADE FROM CONTACTS_STATUS WHERE PROFILEID='$data[PROFILEID]'";
	        	        	$res_cont_stat = mysql_query_optimizer($sql_cont_stat) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_cont_stat,"ShowErrTemplate");
				}
				else
					$res_cont_stat= $memcache_cont_stat;
                                                                                                                             
	        	        if($res_cont_stat)
        	        	{
                			 //$row_cont_stat = mysql_fetch_array($res_cont_stat);
	                		 $data['MONTH_INI_TOTAL']=$row_cont_stat['MONTH_INI_BY_ME'];
					 $data['TODAY_INI_TOTAL']=$row_cont_stat['TODAY_INI_BY_ME']; 
					 $data['TOTAL_CONTACTS_MADE']=$row_cont_stat['TOTAL_CONTACTS_MADE'];
	 
                		}
			}
						leftpanel_membership();
		}
		$profileid_conn=$data['PROFILEID'];
		if(!$data['PROFILEID'] && $From_Mail)
		{
			TimedOut();
			exit;
		}
		if($profileid_conn)
		{
			$myDbName=getProfileDatabaseConnectionName($profileid_conn,'',$mysqlObj);
			$myDb=$mysqlObj->connect("$myDbName");
		}


        }
	if($gtalk_mailer && $data["CHECKSUM"] != '' && $profilechecksum!='')
        {
                $x=explode("|i|",$data["CHECKSUM"]);
                $profilechecksum=$x[1];
        }
	if(file_exists("photo_buffer/".$data['PROFILEID']."_readymade.gif"))
		unlink("photo_buffer/".$data['PROFILEID']."_readymade.gif");
        if(file_exists("photo_buffer/".$data['PROFILEID']."_readymade.jpg"))
                unlink("photo_buffer/".$data['PROFILEID']."_readymade.jpg");
	/*** CODE TO BE ADDED FOR LEFT PANEL COUNTS ***/
        //Added for contact details on left panel
        //if($data)
        //        login_relogin_auth($data);
        //Ends here

	
	if($data["BUREAU"]==1 && ($mbureau=="bureau" || $_COOKIE['JSMBLOGIN']))
        {
                $fromprofilepage=1;//used in ../marriage_bureau/connectmb.inc
                include_once('../marriage_bureau/connectmb.inc');
                mysql_select_db_js('marriage_bureau');
                $mbdata=authenticatedmb($mbchecksum);
                if(!$mbdata)timeoutmb();
                $smarty->assign("source",$mbdata['SOURCE']);
                $smarty->assign("mbchecksum",$mbdata["CHECKSUM"]);
                mysql_select_db_js('newjs');
                $smarty->assign("againstprofileid",$data["PROFILEID"]);
                $smarty->assign("mb_username_profile",$data["USERNAME"]);
                $smarty->assign("checksum",$data['CHECKSUM']);
                $smarty->assign("bureau","1");
                $checksum=$data["CHECKSUM"];
                $mbureau="bureau1";
                $CONTACTMADE=1;
        }
	//for the site messenger
                $smarty->assign("c_login",$VIEWING_PROFILE);

	//for showing leftpanel links
		$smarty->assign("google_ads_left","1");
		
	/*
	else if($mbureau=="bureau")
	{
		$fromprofilepage=1;//used in ../marriage_bureau/connectmb.inc
		include('../marriage_bureau/connectmb.inc');
		mysql_select_db_js('marriage_bureau');
		$data=authenticatedmb($checksum);
		mysql_select_db_js('newjs');
		$smarty->assign("bureau",$fromprofilepage);
		$smarty->assign("source",$source);
		$smarty->assign("cid",$checksum);
	}
	else
	{
		if($CAME_FROM_CONTACT_MAIL)
			$checksum="";
		$data=authenticated($checksum);
	}*/
	

//	$ORIGIN=$data["origin"];

	/*********************Portion of Code added for display of Banners*****************/
	$smarty->assign("data",$data["PROFILEID"]);
	$smarty->assign("bms_topright",7);
	$smarty->assign("bms_left",8);
	$smarty->assign("bms_bottom",9);
	$smarty->assign("bms_right",10);
	$smarty->assign("bms_new_win",34);
	/********************End of Portion of Code*****************************************/

	//added by sriram to condtionally show the header and footer for profile page and editprofile page.
	if(!$CAME_FROM_CONTACT_MAIL)
	{
		$tocheck_id = explode("i",$profilechecksum);
		if($data['PROFILEID'])
		{	
			if($data['PROFILEID']==$tocheck_id[1])
			{
				$smarty->assign("pr_view","0");
			}
			else
			{
				$smarty->assign("pr_view","1");
			}
		}
		else
			$smarty->assign("pr_view","1");
	}
	else
		$smarty->assign("pr_view","0");

/*************************************************************************************************************************
			Added By	:	Shakti Srivastava
			Date		:	9 November, 2005
			Reason		:	For allowing user to view the profile maximum 5 times else
					:	make him log-in
*************************************************************************************************************************/
	if($uPID)
	{
                //ADDED SEARCHTYPE WHERE ITS EARLIER SKIPPED
                if(!$stype)
                        $stype='10';
                //ADDED SEARCHTYPE WHERE ITS EARLIER SKIPPED

		$userId=substr($uPID,0,strlen($uPID)-2);
		$userName=substr($uPID,strlen($uPID)-2,1);
		$rotator=substr($uPID,strlen($uPID)-1,1);

		for($tempcnt=0;$tempcnt<strlen($userId);$tempcnt++)
		{
			$newpos=$tempcnt-$rotator;
			if($newpos<0)
				$newpos=$newpos+strlen($userId);
			else
				$newpos=$newpos;
			$userIdOrg[$newpos]=$userId{$tempcnt};
		}

		ksort($userIdOrg);

		if(count($userIdOrg)>1)
			$userProId=implode("",$userIdOrg);
		else
			$userProId=$userIdOrg[0];


		//Gender Field,smarty Added By lavesh on 30 june 2006 for google-adsense call on gender basis.
		$sql="SELECT USERNAME,GENDER,ACTIVATED FROM newjs.JPROFILE WHERE activatedKey=1 and PROFILEID='".$userProId."'";
		$res=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if(!($row=mysql_fetch_array($res)))
		{
			$sql="SELECT USERNAME,GENDER,ACTIVATED FROM newjs.JPROFILE WHERE activatedKey=0 and PROFILEID='".$userProId."'";
	                $res=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$row=mysql_fetch_array($res);
		}
		//No row found
		if(!$row)
			showProfileError_DP();
		//Profile is not activated
		if($row[ACTIVATED]!="Y")
			showProfileError_DP($row[ACTIVATED]);
		
		$smarty->assign("gender_google",$row['GENDER']);

		for($tempcnt=0;$tempcnt<strlen($row['USERNAME']);$tempcnt++)
		{
			$asciiChr=ord($row['USERNAME']{$tempcnt});

			if($asciiChr>=33 && $asciiChr<=126)
			{
				$appendChar=$row['USERNAME']{$tempcnt};
				break;
			}
		}
		
		if($userName==$appendChar)
			$profilechecksum=md5($userProId)."i".$userProId;
		else
			showProfileError_DP();
	}
/*************************************************************************************************************************/


/*************************************************************************************************************************
			Added By	:	Shakti Srivastava
			Date		:	9 November, 2005
			Reason		:	For allowing user to view the profile maximum 5 times else
					:	make him log-in
*************************************************************************************************************************/

	$lang=$_COOKIE['JS_LANG'];
	if($lang!="hin")
		unset($lang);

	if(0)//!$data && !$CAME_FROM_CONTACT_MAIL && !$viewprofile)
	{
		if(!$_COOKIE['OPEN_JS'])
		{
			setcookie("OPEN_JS","1",0,"/",$domain);					//set cookie first time
			$smarty->assign("SHOW_SEARCH","0");
			$ALLOW5=true;
		}
		else if($_COOKIE['OPEN_JS'])
		{
			$cookie_val=$_COOKIE['OPEN_JS'];			//value stored in cookie is string
			settype($cookie_val,"integer");					//so we have to convert it to int

			if($cookie_val>=3)
			{
				setcookie("OPEN_JS","4",0,"/",$domain);				//remove cookie
				if(!isset($_COOKIE['JS_SOURCE']))
				{
					setcookie("JS_SOURCE","js_4thpage",time()+2592000,"/",$domain);
					savehit("js_4thpage",$_SERVER['PHP_SELF']);
				}
			}
			else if($cookie_val<3)
			{
				if ($crmback!='admin')
                                {
                                        $cookie_val=$cookie_val+1;
                                }
				//$cookie_val=$cookie_val+1;
				settype($cookie_val,"string");
				$smarty->assign("SHOW_SEARCH","0");
				$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/imagesnew/ser2/h_grooms_light.gif");
				$ALLOW5=true;

				setcookie("OPEN_JS","$cookie_val",0,"/",$domain);		//increment value
			}
		}
	}
/*************************************************************************************************************************/


        // don't want to display search option in left panel on profile page in order to reduce page size
	$smarty->assign("NO_SEARCH_OPTION","1");
	if($data || (!$profileurlchecksum && !$CAME_FROM_CONTACT_MAIL) || $viewprofile=="INTERNAL")
	{
		if($data)
		{
			//added by gaurav on 29 sept for showing error if empty message if being tried to send
        	        $smarty->assign("err",$err);
        	        // end
		 	$PERSON_LOGGED_IN=true;
			$smarty->assign("LOGGED_PERSON_PROFILEID",$data["PROFILEID"]);
			//by nikhil for messenger
                        $smarty->assign("LOGGED_PERSON_USERNAME",$data["USERNAME"]);
                        //unique chat name for each window
                        $chatsendername=$data["USERNAME"];
		}
	}
	// if the person is not logged in and has clicked on the Accept/Decline button in initial contact mail then make him login first and then take him to the profile page of this person
	elseif($CAME_FROM_CONTACT_MAIL)
	{
		if($profilechecksum=="")
		{
			$sql="select PROFILEID,ACTIVATED from JPROFILE where  activatedKey=1 and USERNAME='" . addslashes(stripslashes($username)) . "'";
			$result=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			if(mysql_num_rows($result) <= 0)
			{
				$sql="select PROFILEID,ACTIVATED from JPROFILE where  activatedKey=0 and USERNAME='" . addslashes(stripslashes($username)) . "'";
	                        $result=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			}
			if(mysql_num_rows($result) <= 0)
			{
				$sql="SELECT PROFILEID FROM CUSTOMISED_USERNAME WHERE OLD_USERNAME='" . addslashes(stripslashes($username)) . "'";
				$result=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
				if(mysql_num_rows($result) <= 0)
				{
					//$smarty->assign("NO_SEARCH_OPTION","0");
					showProfileError_DP();
				}
				else
				{
					$myrow=mysql_fetch_array($result);
					$profileid=$myrow["PROFILEID"];
					$profilechecksum=md5($profileid) . "i" . $profileid;
				}
			}
			else 
			{
				$myrow=mysql_fetch_array($result);
				$profileid=$myrow["PROFILEID"];

				if($myrow['ACTIVATED']!='Y')
		                        showProfileError_DP($myrow['ACTIVATED']);

				$profilechecksum=md5($profileid) . "i" . $profileid;
			}
			
			mysql_free_result($result);
		}
		if(!$data)
		{
			if($button=="accept")
				$smarty->assign("STATUS","A");
			elseif($button=="decline")
				$smarty->assign("STATUS","D");
			$smarty->assign("CAME_FROM_CONTACT_MAIL","1");
		}
			
	}
	// below code added to allow people from rediff to view a profile without loggin in
        elseif($profileurlchecksum)
        {
                $arr=explode("i",$profileurlchecksum);
                $temp="Y".$arr[1];
                if($arr[0]!=md5($temp))
                {
                        showProfileError_DP();
                }
                else
                {
                        $profilechecksum=md5($arr[1]) . "i" . $arr[1];
                }
                $smarty->assign("PROFILECHECKSUM",$profilechecksum);
		$smarty->assign("NO_SEARCH_OPTION","0");
        }
	else
	{
		if($profilechecksum=="")
		{
			$sql="select PROFILEID,ACTIVATED from JPROFILE where  activatedKey=1 and USERNAME='" . addslashes(stripslashes($username)) . "'";
			$result=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			if(mysql_num_rows($result) <= 0)
			{
				$sql="select PROFILEID,ACTIVATED from JPROFILE where  activatedKey=0 and USERNAME='" . addslashes(stripslashes($username)) . "'";
	                        $result=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			}
			if(mysql_num_rows($result) <= 0)
			{
				$sql="SELECT PROFILEID FROM CUSTOMISED_USERNAME WHERE OLD_USERNAME='" . addslashes(stripslashes($username)) . "'";
				$result=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				
				if(mysql_num_rows($result) <= 0)
				{
					$smarty->assign("NO_SEARCH_OPTION","0");
					showProfileError_DP();
				}
				else
				{
					$myrow=mysql_fetch_array($result);
					$profileid=$myrow["PROFILEID"];
					$profilechecksum=md5($profileid) . "i" . $profileid;
				}
			}
			else 
			{
				$myrow=mysql_fetch_array($result);

				if($myrow['ACTIVATED']!='Y')
		                        showProfileError_DP($myrow['ACTIVATED']);

				$profileid=$myrow["PROFILEID"];
				$profilechecksum=md5($profileid) . "i" . $profileid;
			}
			
			mysql_free_result($result);
		}
		
		$smarty->assign("PROFILECHECKSUM",$profilechecksum);
		$smarty->assign("LOGINMESSAGE","Please Log in to view the user profile.");
		$smarty->assign("CONTACTTYPE","single");
		$smarty->assign("LOGIN2CONTACT","1");

		
		// added to remove naukri banner from login page
		$smarty->assign("NOBOTTOMBANNER","1");
		if($lang)
		{
			$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
			$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch($lang."_subfooternew.htm"));
			$smarty->assign("SUBHEADER",$smarty->fetch($lang."_subheader.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch($lang."_leftpanelnew.htm"));
		}
		else
		{
			if($crmback!="admin")
			{

				$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
				$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
				$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
				$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
				$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
			}
		}
		$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));

		/*****
			Changes made by ketaki for templates served on basis of gender
			Date : Aug 19, 2005
		*****/

		list($temp,$profileid)=explode("i",$profilechecksum);

		if(isset($_COOKIE['JS_GENDER']))
			$gender1=$_COOKIE['JS_GENDER'];

		$query="select GENDER from JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
		if($result=mysql_query_optimizer($query))
		{
			$row=mysql_fetch_array($result);
		}
		$gender2=$row['GENDER'];

		if($gender2=='M')
		{
			$smarty->assign("gender",'F');
		}
		elseif($gender2=='F')
		{
			$smarty->assign("gender",'M');
		}
		elseif($gender1=='M')
		{
			$smarty->assign("gender",'F');
		}
		else
		{
			$smarty->assign("gender",'M');
		}

		$smarty->assign("EARLIER_USER_CHECKSUM",$checksum);

		login_register();

		if($cookie_val>=3)
		{
			if(!isset($_COOKIE['JS_SOURCE']))
			{
				$smarty->assign("TIEUP_SOURCE","js_4thpage");
			}
		}
		$smarty->assign("showlogin","1");
                $smarty->assign("newip","1");
		$smarty->assign("fromprofilepage","1");

		// clicksource variable is used for tracking contacts through match alerts
		$smarty->assign("CLICKSOURCE",$clicksource);
		
		// clicksource variable is used for tracking contacts through match alerts
		$smarty->assign("CLICKSOURCE",$clicksource);
		
		//$smarty->assign("CREATIVE",tieup_creative($source,$cookie_gender,$lang));
		//$smarty->assign("TEMPLATE_CREATIVE",tieup_creative($source,$cookie_gender,$lang,$new_creative='y'));

		if($lang)
			$smarty->display($lang."_login_register.htm");
		else
			$smarty->display("login_register.htm");
			//$smarty->display("newip1.htm");

		exit;
	}
	
	$SCREENING_MESSAGE="<span class=\"smallred\">This field is currently being screened. Please re-check shortly.</span>";
	
	$SCREENING_MESSAGE_SELF="<span class=\"green lf\" style=\"font-size:11px;\">This field is currently being screened. This may take upto 24 hours to go live.</span>";
	if($profilechecksum!="")
	{
		$arr=explode("i",$profilechecksum);
		if(md5($arr[1])!=$arr[0])
		{
			showProfileError_DP();
		}
		else 
			$profileid=$arr[1];
		//nikhil for marriage_bureau
                if($profileid==$data["PROFILEID"])
			$smarty->assign("self_bureau","1");
	}
	elseif($username!="")
	{
		$sql="select PROFILEID,ACTIVATED from JPROFILE where  activatedKey=1 and USERNAME='" . addslashes(stripslashes($username)) . "'";
		$result=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	   	if(mysql_num_rows($result) <= 0)
		{
			$sql="select PROFILEID,ACTIVATED from JPROFILE where  activatedKey=0 and USERNAME='" . addslashes(stripslashes($username)) . "'";
                $result=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}	
		if(mysql_num_rows($result) <= 0)
		{
			$sql="SELECT PROFILEID FROM CUSTOMISED_USERNAME WHERE OLD_USERNAME='" . addslashes(stripslashes($username)) . "'";
			$result=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			if(mysql_num_rows($result) <= 0)
			{
				//$smarty->assign("NO_SEARCH_OPTION","0");
				showProfileError_DP();
			}
			else
			{
				$myrow=mysql_fetch_array($result);
				$profileid=$myrow["PROFILEID"];
				$profilechecksum=md5($profileid) . "i" . $profileid;
			}
		}
		else 
		{
			$myrow=mysql_fetch_array($result);
			if($myrow['ACTIVATED']!='Y')
	                        showProfileError_DP($myrow['ACTIVATED']);

			$profileid=$myrow["PROFILEID"];
		}
                $profilechecksum=md5($profileid) . "i" . $profileid;
		if($username==$data["USERNAME"])
			$smarty->assign("self_bureau","1");
		mysql_free_result($result);
	}	
	else 
	{
		showProfileError_DP();
	}
	
	//added by lavesh on 9 aug to reduce query on JPROFILE. jprofile_result array stores info of both the viewer and viewed person.Accordingly all variable and query on jprofile are replaced.
	
	include_once("reduce_jprofilequery.php");
	limiting_jprofile_query($data["PROFILEID"],$profileid,1);

	$navigation_link=navigation("DP","",$jprofile_result['viewed']['USERNAME']);
	$smarty->assign("sim_contact",$profileid);
	$smarty->assign("viewed_gender",$jprofile_result["viewed"]["GENDER"]);
	//Ends here.

	// logging of the profiles viewed by a person
	if($data && !$PRINT)
	{	
		$db_211 = connect_211();
		//added by lavesh on 9 aug as query on jprofile is prevented.
		$privacy=$jprofile_result["viewer"]["PRIVACY"];
                                                                                                                             
                if($privacy!='C' && $data["PROFILEID"]!=$profileid && $data['GENDER']!=$jprofile_result['viewed']['GENDER'])
                {
                         //$SUFFIX=getsuffix($profileid);
			 $sql_trig="REPLACE INTO VIEW_LOG_TRIGGER  (VIEWER,VIEWED,DATE) VALUES ('$data[PROFILEID]','$profileid',now())";
                         //mysql_query_optimizer($sql_trig);
                         mysql_query_decide($sql_trig,$db_211);
                }

		$sql="select count(*) as cnt from VIEW_LOG where VIEWER='$data[PROFILEID]' and VIEWED='$profileid'";
		//$res=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$res=mysql_query_decide($sql,$db_211) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row=mysql_fetch_array($res);
		$VIEWED_USER=$cnt=$row['cnt'];                 
		if($cnt<=0)
		{
			$sql="insert ignore into VIEW_LOG(VIEWER,VIEWED,DATE,VIEWED_MMM) values ('$data[PROFILEID]','$profileid',now(),'Y')";
			//mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			mysql_query_decide($sql,$db_211) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}
		//@mysql_close($db_211);
		$db = connect_db();
		
		$referrer_path=$_SERVER['HTTP_REFERER'];
		$yday=mktime(0,0,0,date("m"),date("d")-30,date("Y")); // To get the time for previous day
		$back_30_days=date("Y-m-d",$yday);
		
		if($msgid)
			make_msg_read($msgid);	
	}

	/* IVR - Callnow feature added
	 * Get data of the viewer for Callnow display
	*/
	if($data){
		$std = $jprofile_result["viewer"]["STD"];
		$phone_mob = $jprofile_result["viewer"]["PHONE_MOB"];
		$phone_res = $jprofile_result["viewer"]["PHONE_RES"];
		if($jprofile_result["viewed"]["PHONE_MOB"])
			$smarty->assign("SHOW_MOB_DETAILS",'1');
		if($jprofile_result["viewed"]["PHONE_RES"])
			$smarty->assign("SHOW_RES_DETAILS",'1');

		// Mentioned MYMOBILE, LANDLINE here as in 'myjs_verify_phoneno.php' these are used as GET parameters.
		$smarty->assign("MYMOBILE",$phone_mob);
		$smarty->assign("LANDLINE",$phone_res);
		$smarty->assign("STD",$std);
		// Ends 
	}
	/* Ends IVR - Callnow feature  */

	//Getting The status of the user on gtalk.
	$onlinesql="select count(*) from bot_jeevansathi.user_online where USER='$profileid'";
        $onlineresult=mysql_query_decide($onlinesql) or die($sql.mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$onlinesql,"ShowErrTemplate");
	$myonline=mysql_fetch_array($onlineresult);
        if($myonline[0]>0)
        {
		$smarty->assign("GTALK_ONLINE",1);
		$smarty->assign("profileID",$profileid);
        }
	//Fetching gtalk data ends here
	//Getting The status of the user on yahoo.
	$onlinesql="select chat_flag,online_flag from bot_jeevansathi.user_yahoo where PROFILEID='$profileid' and show_in_search=1 and online_flag IN(1,2,3)";
        $onlineresult=mysql_query_decide($onlinesql) or die($sql.mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$onlinesql,"ShowErrTemplate");

        if($myonline=mysql_fetch_array($onlineresult))
        {
                //Declared in connect.inc
                global $yahoo_status,$yahoo_title;
                $chat_flag=$myonline['chat_flag'];
                $on_off_flag=$myonline['online_flag'];
                $Y_STATUS=$yahoo_status[$chat_flag][$on_off_flag];
                $Y_TITLE=$yahoo_title[$chat_flag][$on_off_flag];
                $smarty->assign("Y_STATUS",$Y_STATUS);
                $smarty->assign("Y_TITLE",$Y_TITLE);
		$smarty->assign("profileID",$profileid);
        }


	// find out whether the person whose profile is being viewed is currently online
	$sql="select count(*) from userplane.users where userID='$profileid'";
	$result=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	
	$myonline=mysql_fetch_row($result);
	
	if($myonline[0] > 0)
	{
		$smarty->assign("CHATID",$profileid);
		$smarty->assign("ISONLINE","1");
	}
	mysql_free_result($result);

        //added by lavesh on 9 aug as query on jprofile is prevented.
        if(substr($jprofile_result["viewed"]['SOURCE'],0,2)=='mb')
	{
                $fromprofilepage=1;//used in ../marriage_bureau/connectmb.inc
                include_once('../marriage_bureau/connectmb.inc');
                $mb_data=getdata_mb($jprofile_result["viewed"]['SOURCE']);
                $smarty->assign('NAME_OF_BUREAU',$mb_data['NAME']);
                $smarty->assign('POSTED_BY_MB',"1");
        }
        else
                $smarty->assign('POSTED_BY_MB',"0");
        $smarty->assign("mbprofileid",$profileid);
	
/***********changes made By Puneet on 24 Dec for tracking the views***************************************/        
	if((substr($clicksource,0,10))=='matchalert')
                $frommatchalert='Y';
	else if($clicksource=='NRU_alert')
                $frommatchalert='T';
        else
                $frommatchalert='N';
        if(strstr($jprofile_result["viewed"]['SUBSCRIPTION'],'F') || strstr($jprofile_result["viewed"]['SUBSCRIPTION'],'D'))
		$paid='Y';
	else
		$paid='N';
		
	if($clicksource=="matchalert1")
		$contact_matchalert=1;
	elseif($clicksource=="matchalert2")
		$contact_matchalert=2;
	elseif($clicksource=="visitoralert")
                $visitoralert="Y";

        //Added by Lavesh for recording matchalert profile views
        $dt=date("Y-m-d");
        $sql_view_matchalert="UPDATE MIS.MATCHALERT_TRACKING_V2 SET PROFILE_COUNT=PROFILE_COUNT+1 WHERE LOGIC_USED=$logic_used AND RECOMEND='$recomending' AND  IS_USER_ACTIVE='$is_user_active' AND ENTRY_DT='$dt'";
        mysql_query_decide($sql_view_matchalert);

        if(mysql_affected_rows_js()==0)
        {
                $sql_view_matchalert="INSERT into MIS.MATCHALERT_TRACKING_V2(LOGIC_USED,RECOMEND,ENTRY_DT,IS_USER_ACTIVE,PROFILE_COUNT) VALUES($logic_used,'$recomending','$dt','$is_user_active',1)";
                mysql_query_decide($sql_view_matchalert);
        }
        //Added by Lavesh for recording matchalert profile views
		
	$sql_views="insert delayed into MIS.VIEW_FOR_MIS(PROFILEID,GENDER,PAID,PHOTO,MTONGUE,CASTE,DATE,MATCHALERT,CONTACT_MATCHALERT,VISITORALERT) VALUES('$profileid','".$jprofile_result[viewed][GENDER]."','$paid','".$jprofile_result[viewed][HAVEPHOTO]."','".$jprofile_result[viewed][MTONGUE]."','".$jprofile_result[viewed][CASTE]."',now(),'$frommatchalert','$contact_matchalert','$visitoralert')";	

	$result_views=mysql_query_optimizer($sql_views) ;//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_views,"ShowErrTemplate");
/*********************************************************************************************************/

	// free the recordset
	//mysql_free_result($result);

/****************Below code is to display links in contactgrid for astro services************/

	//added by sriram.	
	if($data["PROFILEID"]==$profileid)
		$HOROSCOPE=check_astro_details($profileid,"Y");
	else
		$HOROSCOPE=check_astro_details($profileid,$jprofile_result["viewed"]['SHOW_HOROSCOPE']);
	if($HOROSCOPE)
		$smarty->assign("HOROSCOPE","Y");
	else
	{
                $smarty->assign("HOROSCOPE","N");
                $smarty->assign("REQUESTHOROSOCOPE","Y");
	}
/*
        if($jprofile_result["viewed"]['SHOW_HOROSCOPE']=='Y')
	{
		if($jprofile_result["viewed"]['COUNTRY_BIRTH']!='0' && $jprofile_result["viewed"]['CITY_BIRTH']!='' && $jprofile_result["viewed"]['BTIME']!='')
			$smarty->assign("HOROSCOPE","Y");
		else
			$smarty->assign("REQUESTHOROSCOPE","Y");
	        
        }
	else
        {
                $smarty->assign("HOROSCOPE","N");
                $smarty->assign("REQUESTHOROSOCOPE","Y");

        }
*/

       $sql_no="SELECT COUNT(*) as CNT from HOROSCOPE_REQUEST_BLOCK where PROFILEID=$profileid";
       $result_no=mysql_query_optimizer($sql_no) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_no,"ShowErrTemplate");
       $myrow_no=mysql_fetch_array($result_no);
       if($myrow_no["CNT"] >0)
       {
	 $smarty->assign("REQUESTHOROSCOPE","N");
       }         
/*added by alok to show demo to TOI
echo "<!--Alok : $jprofile_result["viewed"]['USERNAME']-->";
if($jprofile_result["viewed"]['USERNAME'] == 'test4js')
	$smarty->assign("HOROSCOPE","Y");
*/

	if(strstr($jprofile_result["viewed"]['SUBSCRIPTION'],"H"))
	{
//		if($username=="test4js")	
	//if(!$jprofile_result["viewed"]['BTIME'] || !$jprofile_result["viewed"]['CITY_BIRTH'] || !$jprofile_result["viewed"]['COUNTRY_BIRTH'])
	//	$smarty->assign("REQUESTHOROSCOPE","Y");
	//else
		$smarty->assign("HOROSCOPE","Y");
	}
	if(strstr($data['SUBSCRIPTION'],"K"))
	{
		//if(!$jprofile_result["viewed"]['BTIME'] || !$jprofile_result["viewed"]['CITY_BIRTH'] || !$jprofile_result["viewed"]['COUNTRY_BIRTH'])
		//condition changed by Alok on 29Oct
		if($jprofile_result["viewed"]['BTIME'] == ":" || !$jprofile_result["viewed"]['CITY_BIRTH'] || !$jprofile_result["viewed"]['COUNTRY_BIRTH'])
		{
			//if($username=="swapdummy")
				$smarty->assign("REQUESTKUNDALI","Y");
		}
		else
		{
			//if($username=="kushtest")
				$smarty->assign("KUNDALI","Y");
		}
	}
/****************************Astro services section ends here**********************************/
	// the profile is not to be shown if it is not activated. However, if the person is viewing his own profile, it should be allowed
	if($jprofile_result["viewed"]["ACTIVATED"]!="Y" && $data["PROFILEID"]!=$profileid)
                showProfileError_DP($jprofile_result["viewed"]["ACTIVATED"]);

	// privacy setting - let the person view the profile only if he is logged in
	// R - means that only allow logged in people to view
	// F - means that only allow logged in people to view provided they are not filtered
	// C - means that only allow logged in people to view provided there has been a contact between us
	if(!$PERSON_LOGGED_IN && ($jprofile_result["viewed"]["PRIVACY"]=="R" || $jprofile_result["viewed"]["PRIVACY"]=="F" || $jprofile_result["viewed"]["PRIVACY"]=="C"))
	{
		if($_GET['clicksource']=='photo_request')
                {
			$smarty->assign("login_mes","Please login to continue");
                        Timedout('photoMailer1');
                        exit;
                }
		 no_profile('login');	
		 exit;
	}
	
        // contact details of the person to be shown if he has taken the membership to show his conatct details
        // Field 'D' in SUBCSCIPTION field tells that he has taken the membership
        //if(strstr($jprofile_result["viewed"]['SUBSCRIPTION'],"D") && !strstr($jprofile_result["viewed"]['SUBSCRIPTION'],"S"))//commented by sriram
	//added by sriram
	if(strstr($jprofile_result["viewed"]['SUBSCRIPTION'],"D") && strstr($jprofile_result["viewed"]['SUBSCRIPTION'],"S"))
        	$smarty->assign("ECLASSIFIED_MEM_HIDDEN","yes");
	else
        	$smarty->assign("ECLASSIFIED_MEM_HIDDEN","no");
	//end of - added by sriram

	//his_rights moved on top. was being called under partner profile section and JPROFILE was queried again.
	$his_rights=explode(",",$jprofile_result["viewed"]["SUBSCRIPTION"]);

        if(strstr($jprofile_result["viewed"]['SUBSCRIPTION'],"D"))
        {
		if(($data['GENDER']!=$jprofile_result["viewed"]['GENDER']) || $PERSON_LOGGED_IN)
		{
			$smarty->assign("ECLASSIFIED_MEM","Y");
	                $smarty->assign("CONTACTDETAILS","1");
			$smarty->assign("HISEMAIL","****<br>Please login to view contact details");
			$smarty->assign("NOT_LOGGED_IN_EC","Y");
        	        $CONTACTDETAILS=1;
		}
        }


	//added by sriram for displaying contact details on acceptance between paid and free member
	if($data["PROFILEID"])
	if((strstr($jprofile_result["viewed"]['SUBSCRIPTION'],"F") && $data['SUBSCRIPTION']==""))
	{
		if($data["PROFILEID"])
			$contact_det_arr = get_contact_status_dp($profileid,$data["PROFILEID"]);
		
		if("A"==$contact_det_arr["TYPE"] || "RA"==$contact_det_arr["R_TYPE"])
			$CONTACTDETAILS = 1;
	}
	//added by sriram for displaying contact details on acceptance between paid and free member
	if($data)
	{
		//(sriram) to display the upgrade membership box, only when the logged in user is a free member.
        	//added by lavesh on 9 aug as query on jprofile is prevented.
		$my_rights=explode(",",$jprofile_result["viewer"]["SUBSCRIPTION"]);
		if($jprofile_result["viewer"]["SUBSCRIPTION"]=='')
			$smarty->assign("SHOW_UPGRADE_BOX","1");
		elseif($jprofile_result["viewer"]["SUBSCRIPTION"]=='D')
			$smarty->assign("SENDER_ECLASS","1");

		//code added by sriram for displaying start rate of membership
		$sql_mem_start = "SELECT SQL_CACHE PRICE_RS_TAX, PRICE_DOL FROM billing.SERVICES WHERE SERVICEID='P2'";
		$res_mem_start = mysql_query_optimizer($sql_mem_start) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_mem_start,"ShowErrTemplate");
		$row_mem_start = mysql_fetch_array($res_mem_start);
		
		if($jprofile_result["viewer"]["COUNTRY_RES"]=='51')
		{
			$mem_starts_at = "Rs. ".$row_mem_start['PRICE_RS_TAX'];
			$smarty->assign("MEM_STARTS_AT",$mem_starts_at);
		}
		else
		{
			$mem_starts_at = "US($) ".$row_mem_start['PRICE_DOL'];
			$smarty->assign("MEM_STARTS_AT",$mem_starts_at);
		}
		
		/* Added By sriram for managing link for sending sms on detailed profile page 18 dec 2006*/
		if(($jprofile_result["viewed"]["PHONE_MOB"]=='')||($jprofile_result["viewed"]["GET_SMS"]=='N')||($jprofile_result["viewed"]["COUNTRY_RES"]!='51'))
			$sms='';
		else	
			$sms='Y';

		$smarty->assign("sms",$sms);
		/*Addition Ends Here*/
	}	


	// if privacy option is set the user cannot forward the profile to anybody even if he passes the privacy test
	if($jprofile_result["viewed"]["PRIVACY"]=="R" || $jprofile_result["viewed"]["PRIVACY"]=="F" || $jprofile_result["viewed"]["PRIVACY"]=="C")
		$smarty->assign("NO_FORWARD_OPTION","1");
		
	$PRIVACY=$jprofile_result["viewed"]["PRIVACY"];

	if($lang)
		$smarty->assign("AGE",$jprofile_result["viewed"]["AGE"] . " वर्ष");
	else	
		$smarty->assign("AGE",$jprofile_result["viewed"]["AGE"] . " years");
	

	//==================  IVR- Verification to check phone number is verified, mobile number check, case: normal viewed profile  ===============

	// Mobile Number verification check
	$chk_mobStatus =getPhoneStatus($jprofile_result["viewed"],'','M');
	if($chk_mobStatus =='Y')
		$mob_verified='Y';
	else
		$mob_verified='N';

	if($jprofile_result["viewed"]["SHOWPHONE_MOB"]=="Y" && $mob_verified=='Y')
		$show_mob="Y";
	else
		$show_mob="N";		

	// Landline Number verification check
	$chk_landlStatus =getPhoneStatus($jprofile_result["viewed"],'','L');
	if($chk_landlStatus =='Y')
		$res_verified='Y';
	else
		$res_verified='N';

        if($jprofile_result["viewed"]["SHOWPHONE_RES"]=="Y" && $res_verified=='Y')
                $show_res="Y";
        else
                $show_res="N";

	//================   Ends IVR- Verification to check phone number =====================

		
        if($jprofile_result["viewed"]["GENDER"]=='F')
                $mob_gender="her";
        else
                $mob_gender="his";

        //Mantis issue 4818
        if($data["PROFILEID"]!=$profileid)
        {
                if($clicksource=='matchalert1')
                {
                        include_once("track_matchalert.php");
                        $smarty->assign("frommatchalert","&frommatchalert=1&");
			TrackMatchViewed_MA($profileid,$npos,$logic_used);

			//LIKE/DISLIKE TRACKING : PHASE2
			$receiver=$data["PROFILEID"];
			$match=$profileid;
			if($MatchAlertlike)
				MatchLikedOrNor($MatchAlertlike,$receiver,$match);
			//LIKE/DISLIKE TRACKING
                }
        }
        //Mantis issue 4818


	// indicate that the person is viewing his own profile
	if($data["PROFILEID"]==$profileid)
	{
                //Mantis issue 4818
                if($clicksource=='matchalert1' && $EditWhatNew=='FocusDpp')
                {
                        include_once("track_matchalert.php");
                        $smarty->assign("frommatchalert","&frommatchalert=1&");
                        TrackEditDpp_MA($profileid,'V',$logic_used);
                }
                //Mantis issue 4818

		$PERSON_HIMSELF=true;

		include_once("horoscope_upload.inc");

		$jprofile_result["viewed"]["NTIMES"]=ntimes_count($profileid,"SELECT");

		if(get_horoscope($profileid))
                	$smarty->assign("HOROSCOPE1","Y");
	        else
                	$smarty->assign("HOROSCOPE1","N");


		$mydate=substr($jprofile_result["viewed"]["MOD_DT"],0,10);	// for displaying last date of profile modification
                $VIEWS = $jprofile_result["viewed"]["NTIMES"];		// for displaying number of times profile has been viewed.
		$mydateArr=explode("-",$mydate);
                $mydate=my_format_date($mydateArr[2],$mydateArr[1],$mydateArr[0],1);

		//Sharding+Combining
		//$PROFILE_PERCENT = profile_percent($profileid,"1",'',$mysqlObj,$jpartnerObj);
		//Sharding+Combining

		$Parents_Contact=$jprofile_result["viewed"]['PARENTS_CONTACT'];
		$Address 	=$jprofile_result["viewed"]['CONTACT'];
		$pincode 	=$jprofile_result["viewed"]['PINCODE'];
		$State_Code 	=$jprofile_result["viewed"]["STD"];
		$Phone 		=$jprofile_result["viewed"]["PHONE_RES"];
		$Mobile 	=$jprofile_result["viewed"]["PHONE_MOB"];
		$Messenger_ID 	=$jprofile_result["viewed"]["MESSENGER_ID"];
		$messenger	=$jprofile_result["viewed"]["MESSENGER_CHANNEL"];
		$MobStatus	=$jprofile_result["viewed"]["MOB_STATUS"];
		$LandlStatus	=$jprofile_result["viewed"]["LANDL_STATUS"];
		$PhoneFlag	=$jprofile_result["viewed"]["PHONE_FLAG"];

                //$smarty->assign("MESSENGER_CHANNEL",$MESSENGER_CHANNEL["$mymessenger"]);


		// Changes made by Shobha on 01.12.2006 to display photo request count
		/*$PHOTO_REQ_COUNT = 0;
		$sql_photo = "SELECT COUNT(*) AS CNT FROM newjs.PHOTO_REQUEST WHERE PROFILEID_REQ_BY='$profileid'";
		$res_photo = mysql_query_optimizer($sql_photo) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_photo,"ShowErrTemplate");
		$row_photo = mysql_fetch_array($res_photo);
		$PHOTO_REQ_COUNT = $row_photo['CNT'];

		$sql_contact="select count(*) as cnt from CONTACTS where RECEIVER='" . $data["PROFILEID"] . "' and TYPE='I'";
		$res_contact =mysql_query_optimizer($sql_contact) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_contact,"ShowErrTemplate");
		$row_contact=mysql_fetch_array($res_contact);

		$smarty->assign("RECEIVED_I",$row_contact["cnt"]);
		$smarty->assign("PHOTO_REQ_COUNT",$PHOTO_REQ_COUNT);*/
		if($data['GENDER']=='F')
                        $smarty->assign("viewed_gender",'M');
                else
                        $smarty->assign("viewed_gender",'F');

		login_relogin_auth($data);
		if(!$search)
		$smarty->assign("SELF","1");
	}
	// assert that the person is viewing the profile of a person having the same gender
	elseif($data["GENDER"]==$jprofile_result["viewed"]["GENDER"])
	{
                $samegender=1;
                if($data['GENDER']=='F')
                        $smarty->assign("viewed_gender",'M');
                else
                        $smarty->assign("viewed_gender",'F');
        }

		
	// if the gender is same and privacy option of F or C is set don't show the profile
	if($samegender==1 && ($PRIVACY=="F" || $PRIVACY=="C"))
		showProfileError_DP("","S");

	// setting the cookie for gender
	if(!isset($_COOKIE["JS_GENDER"]) || ($_COOKIE["JS_GENDER"] != $jprofile_result["viewed"]["GENDER"]) )
	{
		if($samegender == 1)
		{
			if($jprofile_result["viewed"]["GENDER"] == 'F')
				$gender = 'M';
			else
				$gender = 'F';
				
			setcookie("JS_GENDER",$gender,time()+2592000,"/",$domain);
		}
		else
		{	
			setcookie("JS_GENDER",$jprofile_result["viewed"]["GENDER"],time()+2592000,"/",$domain);
		}
	}

	// if the person is different and the profile is not being viewed by the backend operations people then update the No. of profile views
	elseif(!$viewprofile && !$PERSON_HIMSELF) 
	{
		ntimes_count($profileid,"UPDATE");

		//$sql="update JPROFILE set NTIMES=NTIMES+1,TIMESTAMP=TIMESTAMP where PROFILEID='$profileid'";
		//mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	}

	//@mysql_close();
	//$db=connect_737_lan();
	
	/******************************************************
	check for photographs starts here
	******************************************************/
//	print_r($myrow);
	// if main photograph is there and is screened
	unset($sub);
	$sub=array();
	$sub=explode(",",$jprofile_result["viewed"]["SUBSCRIPTION"]);
	if(in_array("1",$sub))
	{
		$member_101=1;
		$smarty->assign("MEMBER_101","1");
	}
	if(in_array("T",$sub))
	{
		include_once($_SERVER['DOCUMENT_ROOT']."/jsadmin/ap_dpp_common.php");
		$assistedProductOnline=1;
		if($apEditMsg)
			$smarty->assign("apEditMsg",1);
		$liveDPP=fetchCurrentDPP($profileid);
		$APeditID=$liveDPP["DPP_ID"];
		$sqlAP="SELECT * FROM Assisted_Product.AP_TEMP_DPP WHERE PROFILEID='$profileid' AND CREATED_BY='ONLINE'";
		$resAP=mysql_query_decide($sqlAP) or logError("Due to a temporary problem your request could not be processed. Please try again after a couple of minutes",$sqlAP,"ShowErrTemplate");
		if(mysql_num_rows($resAP))
		{
			$currentDPP=mysql_fetch_assoc($resAP);
			$smarty->assign("apEditMsg",1);
		}
		$sqlAP="SELECT * FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE WHERE PROFILEID='$profileid' AND ONLINE='Y' AND ROLE='ONLINE' AND CREATED_BY='ONLINE'";
		if($liveDPP["DPP_ID"])
			$sqlAP.=" AND DPP_ID>'$liveDPP[DPP_ID]'";
		$sqlAP.=" ORDER BY DPP_ID DESC LIMIT 1";
		$resAP=mysql_query_decide($sqlAP) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlAP,"ShowErrTemplate");
		if(mysql_num_rows($resAP))
		{
			$rowAP=mysql_fetch_assoc($resAP);
			$APeditID=$rowAP["DPP_ID"];
			$smarty->assign("apEditMsg",1);
			if(!is_array($currentDPP))
				$currentDPP=$rowAP;
		}
		else
		{
			if(!is_array($currentDPP))
				$currentDPP=$liveDPP;
		}
		if($APeditID)
			$smarty->assign("APeditID",$APeditID);
	}
	if($jprofile_result["viewed"]["HAVEPHOTO"]=="Y")
	{
		//if main photo is under screening and earlier one is available
		if(!isFlagSet("MAINPHOTO",$jprofile_result["viewed"]['PHOTOSCREEN']))
		{
			$sql="SELECT COUNT(*) AS cnt FROM PICTURE WHERE PROFILEID='$profileid' AND MAINPHOTO<>''";
			$res=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$row=mysql_fetch_array($res);
			if($row['cnt']>0)
				$main_photo_is_screened=1;
		}
		
		// if both the album photos are screened
		if(isFlagSet("ALBUMPHOTO1",$jprofile_result["viewed"]["PHOTOSCREEN"]) && isFlagSet("ALBUMPHOTO2",$jprofile_result["viewed"]["PHOTOSCREEN"]))
		{
			// check whether the photos are there or not
			$sql="select count(*) from PICTURE where PROFILEID='$profileid' and (ALBUMPHOTO1<>'' or ALBUMPHOTO2<>'')";
		}
		// if album photo 1 is screened and album photo 2 is not screened
		elseif(isFlagSet("ALBUMPHOTO1",$jprofile_result["viewed"]["PHOTOSCREEN"]))
		{
			// check whether the photos are there or not
			$sql="select count(*) from PICTURE where PROFILEID='$profileid' and ALBUMPHOTO1<>''";
		}
		// if album photo 2 is screened and album photo 1 is not screened
		elseif(isFlagSet("ALBUMPHOTO2",$jprofile_result["viewed"]["PHOTOSCREEN"]))
		{
			// check whether the photos are there or not
			$sql="select count(*) from PICTURE where PROFILEID='$profileid' and ALBUMPHOTO2<>''";
		}
		else 
			$no_photo_query=1;
		
		if(!$no_photo_query)
		{
			$photoresult=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			$photorow=mysql_fetch_row($photoresult);
			
			if($photorow[0] > 0)
			{
				$smarty->assign("ISALBUM","1");
			}
		}
		
		// if the main photo is screened
		if(isFlagSet("MAINPHOTO",$jprofile_result["viewed"]["PHOTOSCREEN"]) || $main_photo_is_screened)
		{
			$photoVersion_arr =getPhotoVersion($jprofile_result["viewed"]["PROFILEID"]);
			$version =$photoVersion_arr[$jprofile_result["viewed"]["PROFILEID"]];
			$smarty->assign("FULLVIEW","1");
			if($isMobile)
			$smarty->assign("PHOTOFILE","$PHOTO_URL/profile/photo_serve.php?version=".$version."&profileid=" . md5($profileid+5) . "i" . ($profileid+5) . "&photo=THUMBNAIL");
			else
			$smarty->assign("PHOTOFILE","$PHOTO_URL/profile/photo_serve.php?version=".$version."&profileid=" . md5($profileid+5) . "i" . ($profileid+5) . "&photo=PROFILEPHOTO");
			
			// if the person is viewing his own profile
			if(!$PERSON_HIMSELF || $search)
			{
				// if the user has chosen to hide the photo
				if($jprofile_result["viewed"]["PHOTO_DISPLAY"]=="H")
				{
					$smarty->assign("FULLVIEW","");
					$smarty->assign("ISALBUM","");
					//$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/photo_hidden.gif");
					if($isMobile)
					{
						if($jprofile_result["viewed"]["GENDER"]=="M")
							$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/ser4_images/mobilejs/ic_hidden_b_60x60.gif");
						else
							$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/ser4_images/mobilejs/ic_hidden_g_60x60.gif");
					}
					else{
						if($jprofile_result["viewed"]["GENDER"]=="M")
							$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/ph_hidden_b.gif");
						else
							$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/ph_hidden_g.gif");
					}
				}
				// if the user has chosen to display photo conditionally then check for contact made and then decide which photo to show
				elseif($jprofile_result["viewed"]["PHOTO_DISPLAY"]=="C")
				{
					$CHECK_FOR_PHOTO_CONTACT=1;
					//added by sriram
					$myrow_gender = $jprofile_result["viewed"]["GENDER"];
					//if(!$PERSON_LOGGED_IN || $samegender==1)
					if(!$PERSON_LOGGED_IN)
					{
						$smarty->assign("FULLVIEW","");
						$smarty->assign("ISALBUM","");
						//$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/photo_visible_if_user_accept.gif");
						if($isMobile){
							if($jprofile_result["viewed"]["GENDER"]=="M")
								$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/ser4_images/mobilejs/ic_login_to_view_b_60x60.gif");
							else
								$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/ser4_images/mobilejs/ic_login_to_view_g_60x60.gif");
						}
						else{
						if($jprofile_result["viewed"]["GENDER"]=="M")
								$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/login_to_view_photo_big_b.gif");
							else
								$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/login_to_view_photo_big_g.gif");
						}
//						$smarty->assign("PHOTOFILE","images/login_toview_photo.gif");
					}
					elseif($samegender==1)
                                        {
                                                $smarty->assign("FULLVIEW","");
                                                $smarty->assign("ISALBUM","");
												if($isMobile){
													if($jprofile_result["viewed"]["GENDER"]=="M")
															$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/ser4_images/mobilejs/ic_photo_vis_if_b_60x60.gif");
													else
														$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/ser4_images/mobilejs/ic_photo_vis_if_b_60x60.gif");
												}
												else{
													if($jprofile_result["viewed"]["GENDER"]=="M")
															$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/ph_visible_b.jpg");
													else
														$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/ph_visible_g.jpg");
												}
                                        }
				}
				elseif($jprofile_result["viewed"]["PHOTO_DISPLAY"]=="F")
				{
					$CHECK_FOR_FILTERED=1;
					$myrow_gender = $jprofile_result["viewed"]["GENDER"];
					//if(!$PERSON_LOGGED_IN || $samegender==1)
					if(!$PERSON_LOGGED_IN)
					{
						$smarty->assign("FULLVIEW","");
						$smarty->assign("ISALBUM","");
//						$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/photo_visible_not_filtered.gif");
						if($isMobile){
								if($jprofile_result["viewed"]["GENDER"]=="M")
									$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/ser4_images/mobilejs/ic_login_to_view_b_60x60.gif");
								else
									$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/ser4_images/mobilejs/ic_login_to_view_g_60x60.gif");
						}
						else{
							if($jprofile_result["viewed"]["GENDER"]=="M")
								$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/login_to_view_photo_big_b.gif");
							else
								$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/login_to_view_photo_big_g.gif");
						}
//						$smarty->assign("PHOTOFILE","images/login_toview_photo.gif");
					}
					elseif($samegender==1)
                                        {
                                                $smarty->assign("FULLVIEW","");
                                                $smarty->assign("ISALBUM","");
												if($isMobile)
												{
                                                if($jprofile_result["viewed"]["GENDER"]=="M")
                                                        $smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/ser4_images/mobilejs/photo_fil_sm_b_60x60.gif");
                                                else
                                                        $smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/ser4_images/mobilejs/photo_fil_sm_g_60x60.gif");
												}
												else
												{
                                                if($jprofile_result["viewed"]["GENDER"]=="M")
                                                        $smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/photo_fil_big_b.gif");
                                                else
													$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/photo_fil_big_g.gif");
												}
                                        }
				}
			}
		}
		else 
		{
			$smarty->assign("FULLVIEW","");
			$smarty->assign("ISALBUM","");
			if($isMobile)
			{
				if($jprofile_result["viewed"]["GENDER"]=="M")
					$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/ser4_images/mobilejs/ic_photo_coming_b_60x60.gif");
				else
					 $smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/ser4_images/mobilejs/ic_photo_coming_g_60x60.gif");
			}
			else
			{
				if($jprofile_result["viewed"]["GENDER"]=="M")
					$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/photocomming_b.gif");
				else
					$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/photocomming_g.gif");
			}
		}
	}
	// main photo is being screened
	elseif($jprofile_result["viewed"]["HAVEPHOTO"]=="U" || $jprofile_result["viewed"]["HAVEPHOTO"]=="E")
	{
		if($PERSON_HIMSELF && !$search)
			$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/no_photo.gif");
		elseif($jprofile_result["viewed"]["GENDER"]=="M"){
			if($isMobile)
			{
				if($jprofile_result["viewed"]["GENDER"]=="M")
					$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/ser4_images/mobilejs/ic_photo_coming_b_60x60.gif");
				else
					 $smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/ser4_images/mobilejs/ic_photo_coming_g_60x60.gif");
			}
			else
			{
				if($jprofile_result["viewed"]["GENDER"]=="M")
                	$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/photocomming_b.gif");
                else
                        $smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/photocomming_g.gif");
			}
		}
	}
	// if the person is viewing his own profile and does not have a photo give him the option to upload photo
	else
	{
		if($PERSON_HIMSELF && !$search)
		{
			$upload_photo=1;
			$smarty->assign("UPLOADPHOTO",$upload_photo);
//			$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/upload_photo1.gif");
			if($jprofile_result["viewed"]["GENDER"]=="M")
				$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/ph_upload_b.gif");
			else
				$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/ph_upload_g.gif");
		}
		else 
		{
			$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/no_photo.gif");
		}
		$smarty->assign("HAVEPHOTO",$jprofile_result["viewed"]["HAVEPHOTO"]);		
	}
	
	/******************************************************
	check for photographs ends here
	******************************************************/
		
	$contactperson=$jprofile_result["viewed"]["USERNAME"];
	 //PROFILECHATID by nikhil for chat window
        $chatreceivername=$jprofile_result["viewed"]["USERNAME"];
        $smarty->assign("PROFILECHATID",$jprofile_result["viewed"]["PROFILEID"]);
        //chatsendername is defined before
        $chat_cmp=strcmp($chatsendername,$chatreceivername);
        if($chat_cmp==1)
        {
                $threadname=$chatsendername."_".$chatreceivername;
        }
        else
        {
                $threadname=$chatreceivername."_".$chatsendername;
        }
        $smarty->assign("threadname",$threadname);
        //till here by nikhil
	$smarty->assign("PROFILENAME",$jprofile_result["viewed"]["USERNAME"]);
	$smarty->assign("GENDER",$jprofile_result["viewed"]["GENDER"]);
	
	$height=$jprofile_result["viewed"]["HEIGHT"];
	$height1=explode("(",$HEIGHT_DROP["$height"]);
	$smarty->assign("HEIGHT",$height1[0]);
	$smarty->assign("PHEIGHT",$height1[0]);

        //code added by nikhil dhiman on 25 May 2007 For Setting Manglik Status
	$return_data=manglik($profileid,'viewed');  
	$manglik_data=explode("+",$return_data);
	$smarty->assign("Own_Manglik_Status",$manglik_data[0]);
	if($data)
	{
	     $return_data=manglik($data['PROFILEID'],'viewer');    
	     $manglik_data=explode("+",$return_data);
	     $smarty->assign("Own_Manglik",$manglik_data[1]);
	}
	else
	     $smarty->assign("Own_Manglik","Manglik");

	if($lang)
	{
		$smarty->assign("RELATION",$RELATIONSHIP_HIN[$jprofile_result["viewed"]["RELATION"]]);
		$smarty->assign("PROFILEGENDER",$GENDER_HIN[$jprofile_result["viewed"]["GENDER"]]);
		$smarty->assign("MSTATUS",$MSTATUS_HIN[$jprofile_result["viewed"]["MSTATUS"]]);
		$smarty->assign("CHILDREN",$CHILDREN_HIN[$jprofile_result["viewed"]["HAVECHILD"]]);
		$smarty->assign("MANGLIK",$MANGLIK_HIN[$jprofile_result["viewed"]["MANGLIK"]]);
		$smarty->assign("BODYTYPE",$BODYTYPE_HIN[$jprofile_result["viewed"]["BTYPE"]]);
		$smarty->assign("COMPLEXION",$COMPLEXION_HIN[$jprofile_result["viewed"]["COMPLEXION"]]);
		$smarty->assign("DIET",$DIET_HIN[$jprofile_result["viewed"]["DIET"]]);
		$smarty->assign("SMOKE",$SMOKE_HIN[$jprofile_result["viewed"]["SMOKE"]]);
		$smarty->assign("DRINK",$DRINK_HIN[$jprofile_result["viewed"]["DRINK"]]);
		$smarty->assign("RSTATUS",$RSTATUS_HIN[$jprofile_result["viewed"]["RES_STATUS"]]);
		$smarty->assign("HANDICAPPED",$HANDICAPPED_HIN[$jprofile_result["viewed"]["HANDICAPPED"]]);
	}
	else
	{
		$smarty->assign("RELATION",$RELATIONSHIP[$jprofile_result["viewed"]["RELATION"]]);
		$smarty->assign("PROFILEGENDER",$GENDER[$jprofile_result["viewed"]["GENDER"]]);
		$smarty->assign("MSTATUS",$MSTATUS[$jprofile_result["viewed"]["MSTATUS"]]);
		$smarty->assign("CHILDREN",$CHILDREN[$jprofile_result["viewed"]["HAVECHILD"]]);
		$smarty->assign("MANGLIK",$MANGLIK[$jprofile_result["viewed"]["MANGLIK"]]);
		$smarty->assign("BODYTYPE",$BODYTYPE[$jprofile_result["viewed"]["BTYPE"]]);
		$smarty->assign("COMPLEXION",$COMPLEXION[$jprofile_result["viewed"]["COMPLEXION"]]);
		$smarty->assign("DIET",$DIET[$jprofile_result["viewed"]["DIET"]]);
		$smarty->assign("SMOKE",$SMOKE[$jprofile_result["viewed"]["SMOKE"]]);
		$smarty->assign("DRINK",$DRINK[$jprofile_result["viewed"]["DRINK"]]);
		$smarty->assign("RSTATUS",$RSTATUS[$jprofile_result["viewed"]["RES_STATUS"]]);
		$smarty->assign("HANDICAPPED",$HANDICAPPED[$jprofile_result["viewed"]["HANDICAPPED"]]);
	}
	
	$caste=$jprofile_result["viewed"]["CASTE"];
	if($lang=='hin')
	{
		$caste_temp=label_select("HIN_CASTE",$caste1);
                $caste=$caste_temp[0];
		unset($caste_temp);
	}
	else
		$caste=$CASTE_DROP["$caste"];
	
	if($lang=='hin')
	{
		$mtongue=label_select("HIN_MTONGUE",$jprofile_result["viewed"]["MTONGUE"]);
		$religion=label_select("HIN_RELIGION",$jprofile_result["viewed"]["RELIGION"]);
		$income=array($INCOME_DROP[$jprofile_result["viewed"]["INCOME"]]);
		$edu_level=label_select("HIN_EDUCATION_LEVEL",$jprofile_result["viewed"]["EDU_LEVEL"]);
		$edu_level_new=label_select("HIN_EDUCATION_LEVEL_NEW",$jprofile_result["viewed"]["EDU_LEVEL_NEW"]);
		$family_back=label_select("HIN_FAMILY_BACK",$jprofile_result["viewed"]["FAMILY_BACK"]);
	}
	else
	{
		//added by lavesh on 9 aug as dropdown.php array should be used istead of using query.
                $mtongue = array($MTONGUE_DROP[$jprofile_result["viewed"]["MTONGUE"]]);
		$religion=array($RELIGIONS[$jprofile_result["viewed"]["RELIGION"]]);
		$income=array($INCOME_DROP[$jprofile_result["viewed"]["INCOME"]]);
                $edu_level=array($EDUCATION_LEVEL_DROP[$jprofile_result["viewed"]["EDU_LEVEL"]]);
		$edu_level_new=array($EDUCATION_LEVEL_NEW_DROP[$jprofile_result["viewed"]["EDU_LEVEL_NEW"]]);

		$family_back=array($FAMILY_BACK_DROP[$jprofile_result["viewed"]["FAMILY_BACK"]]);
		$family_type=$FAMILY_TYPE[$jprofile_result["viewed"]['FAMILY_TYPE']];
		$family_status=$FAMILY_STATUS[$jprofile_result["viewed"]['FAMILY_STATUS']];
		$mother_occ=array($MOTHER_OCC_DROP[$jprofile_result["viewed"]['MOTHER_OCC']]);
		$tbrother=$jprofile_result["viewed"]['T_BROTHER'];
		$mbrother=$jprofile_result["viewed"]['M_BROTHER'];
		$tsister=$jprofile_result["viewed"]['T_SISTER'];
		$msister=$jprofile_result["viewed"]['M_SISTER'];
	}
	$occupation=$jprofile_result["viewed"]["OCCUPATION"];
	$country_birth=$jprofile_result["viewed"]["COUNTRY_BIRTH"];
	$country_res=$jprofile_result["viewed"]["COUNTRY_RES"];

	if($lang=='hin')
	{
		$occupation_temp=label_select("HIN_OCCUPATION",$occ);
                $occupation=$occupation_temp[0];
		unset($occupation_temp);

		$country_birth_temp=label_select("HIN_COUNTRY",$country_birth);
		$country_birth=$country_birth_temp[0];
		unset($country_birth_temp);

		$country_res_temp=label_select("HIN_COUNTRY",$country_res);
		$country_res=$country_res_temp[0];
		unset($country_res_temp);
	}
	else
	{
		$occupation=$OCCUPATION_DROP["$occupation"];
		$country_birth=$COUNTRY_DROP["$country_birth"];
		$country_res=$COUNTRY_DROP["$country_res"];
	}
	
	$wife_working=$jprofile_result["viewed"]["WIFE_WORKING"];
	if($wife_working=="Y")
		$smarty->assign("WORKINGSPOUSE","She should be working");
	elseif($wife_working=="N")
		$smarty->assign("WORKINGSPOUSE","She should be homemaker");
	elseif($wife_working=="D")
		$smarty->assign("WORKINGSPOUSE","Doesn't matter");
	elseif($wife_working=="")
		$smarty->assign("WORKINGSPOUSE","-");

	$married_working=$jprofile_result["viewed"]["MARRIED_WORKING"];
	$smarty->assign("CAREER_AFTER_MARRIAGE",$married_working);	
	$parents_city_same=$jprofile_result["viewed"]["PARENT_CITY_SAME"];
	if($parents_city_same=="Y")
		$smarty->assign("LIVE_WITH_PARENTS","Yes");
	elseif($parents_city_same=="N")
		$smarty->assign("LIVE_WITH_PARENTS","No");
	elseif($parents_city_same=="D")
		$smarty->assign("LIVE_WITH_PARENTS","Not Applicable");
	elseif($parents_city_same=="")
		$smarty->assign("LIVE_WITH_PARENTS","-");
		
	$family_values=$jprofile_result["viewed"]["FAMILY_VALUES"];

	if($lang)
	{
		if($family_values=="1")
			$smarty->assign("FAMILY_VALUES","परम्परागत");
		elseif($family_values=="2")
			$smarty->assign("FAMILY_VALUES","सामान्य/मध्यम");
		elseif($family_values=="3")
			$smarty->assign("FAMILY_VALUES","लीबरल");
		elseif($family_values=="")
			$smarty->assign("FAMILY_VALUES","-");
	}
	else
	{
		if($family_values=="")
			$smarty->assign("FAMILY_VALUES","-");
		else
			$smarty->assign("FAMILY_VALUES",$FAMILY_VALUES[$family_values]);
	}
		
	if($caste=="")
		$caste="-";
		
	if($mtongue[0]=="")
		$mtongue[0]="-";
	
	if($religion[0]=="")
		$religion[0]="-";
	
	if($income[0]=="")
		$income[0]="-";
		
	if($edu_level[0]=="")
		$edu_level[0]="-";
		
	if($occupation=="")
		$occupation="-";
		
	if($country_birth=="")
		$country_birth="-";
		
	if($country_res=="")
		$country_res="-";
		
	/*if($jprofile_result["viewed"]["COUNTRY_RES"]=="51")
	{
		$city_res=$jprofile_result["viewed"]["CITY_RES"];
		if($lang=='hin')
		{
			$city_res_temp=label_select("HIN_CITY_INDIA",$city_res);
			$city_res=$city_res_temp[0];
			unset($city_res_temp);
		}
		else
			$city_res=$CITY_INDIA_DROP["$city_res"];
	}
	elseif($jprofile_result["viewed"]["COUNTRY_RES"]=="128")
	{
		$city_res=$jprofile_result["viewed"]["CITY_RES"];
		$city_res=$CITY_USA_DROP["$city_res"];
	}
	else 
		$city_res="";
	*/
	if($jprofile_result["viewed"]["CITY_RES"]!="")
        {
                $city_res_val = $jprofile_result["viewed"]["CITY_RES"];
                $sql_ci = "SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$city_res_val'";
                $res_ci = mysql_query_optimizer($sql_ci);
                $row_ci = mysql_fetch_array($res_ci);
                $city_res = $row_ci['LABEL'];
        }
	//added by sriram to show country from astro details table if the user opts to show horoscope.
	if($jprofile_result["viewed"]['SHOW_HOROSCOPE']=='Y')
	{
		$sql_horo = "SELECT COUNTRY_BIRTH,CITY_BIRTH FROM newjs.ASTRO_DETAILS WHERE PROFILEID='$profileid'";
		$res_horo = mysql_query_optimizer($sql_horo);
		$row_horo = mysql_fetch_array($res_horo);
		$astro_city_birth = $row_horo['CITY_BIRTH'];
		$smarty->assign("COUNTRY_BIRTH",$row_horo['COUNTRY_BIRTH']);
	}
	//end of added by sriram to show country from astro details table if the user opts to show horoscope.
	else
		$smarty->assign("COUNTRY_BIRTH",$country_birth);
	$smarty->assign("HORODISPLAY",$jprofile_result["viewed"]['SHOW_HOROSCOPE']);
	$smarty->assign("COUNTRY_RES",$country_res);
	$smarty->assign("CITY_RES",$city_res);
	$smarty->assign("OCCUPATION",$occupation);
	$smarty->assign("EDUCATION_LEVEL",$edu_level[0]);
	$smarty->assign("INCOME",$income[0]);
	$smarty->assign("RELIGION_SELF",$religion[0]);
	$smarty->assign("MTONGUE",$mtongue[0]);
	$smarty->assign("CASTE",$caste);
	$smarty->assign("EDU_LEVEL_NEW",$edu_level_new[0]);
	$smarty->assign("FAMILY_BACK",$family_back[0]);
	$smarty->assign("MOTHER_OCC",$mother_occ[0]);
	$smarty->assign("FAMILY_TYPE",$family_type);
	$smarty->assign("FAMILY_STATUS",$family_status);
	$emailid=$jprofile_result["viewed"]["EMAIL"];
        if(strlen($emailid)>26)
        {
                $email_id=explode("@",$emailid);
                $emailid=$email_id[0]."<br>@".$email_id[1];
        }
        $CITIZENSHIP=display_format($jprofile_result["viewed"]["CITIZENSHIP"]);
        $ws=$jprofile_result["viewed"]["WORK_STATUS"];
        $WORK_STATUS=$WORK_STATUS[$ws];
        $bg=$jprofile_result["viewed"]["BLOOD_GROUP"];
        $BLOOD_GROUP=$BLOOD_GROUP[$bg];
        $WEIGHT=$jprofile_result["viewed"]["WEIGHT"]."Kg";
        $nh=$jprofile_result["viewed"]["NATURE_HANDICAP"];
        $NATURE_HANDICAP1=$NATURE_HANDICAP[$nh];
        $HIV=$jprofile_result["viewed"]["HIV"];
        $timeToCallStart=$jprofile_result["viewed"]["TIME_TO_CALL_START"];
        $timeToCallEnd=$jprofile_result["viewed"]["TIME_TO_CALL_END"];
        $pno=$jprofile_result["viewed"]["PHONE_NUMBER_OWNER"];
        $PHONE_NUMBER_OWNER=$NUMBER_OWNER[$pno];
        $PHONE_OWNER_NAME=$jprofile_result["viewed"]["PHONE_OWNER_NAME"];
        $mno=$jprofile_result["viewed"]["MOBILE_NUMBER_OWNER"];
        $MOBILE_NUMBER_OWNER=$NUMBER_OWNER[$mno];
        $MOBILE_OWNER_NAME=$jprofile_result["viewed"]["MOBILE_OWNER_NAME"];
        $gender_logged_in=$jprofile_result["viewed"]["GENDER"];
        $smarty->assign("GENDER_LOGGED_IN",$gender_logged_in);
        $smarty->assign("EMAILID",$emailid);
        $smarty->assign("CITIZENSHIP",get_partner_string_from_array($CITIZENSHIP,"COUNTRY_NEW"));
        $smarty->assign("WORK_STATUS",$WORK_STATUS);
        $smarty->assign("BLOOD_GROUP",$BLOOD_GROUP);
        $smarty->assign("WEIGHT",$WEIGHT);
        $smarty->assign("NATURE_HANDICAP",$NATURE_HANDICAP1);
        $smarty->assign("HIV",$HIV);
        $smarty->assign("TIME_TO_CALL_START",$timeToCallStart);
        $smarty->assign("TIME_TO_CALL_END",$timeToCallEnd);
        $smarty->assign("PHONE_NUMBER_OWNER",$PHONE_NUMBER_OWNER);
        $smarty->assign("PHONE_OWNER_NAME",$PHONE_OWNER_NAME);
	$smarty->assign("MOBILE_NUMBER_OWNER",$MOBILE_NUMBER_OWNER);
        $smarty->assign("MOBILE_OWNER_NAME",$MOBILE_OWNER_NAME);
        if($tbrother==4)
		$tbrother="3+";
	if($mbrother==4)
		$mbrother="3+";
	if($tsister==4)
		$tsister="3+";
	if($msister==4)
		$msister="3+";
	$smarty->assign("T_BROTHER",$tbrother);
        $smarty->assign("M_BROTHER",$mbrother);
        $smarty->assign("T_SISTER",$tsister);
        $smarty->assign("M_SISTER",$msister);
	
	if($jprofile_result["viewed"]["BTIME"]!="")
	{
		$btime=explode(":",$jprofile_result["viewed"]["BTIME"]);
		$smarty->assign("BTIMEHOUR",$btime[0]);
		$smarty->assign("BTIMEMIN",$btime[1]);
	}
		
	if($astro_city_birth)
	{
		$smarty->assign("CITYBIRTH",$astro_city_birth);
	}
	else
	{
		if($jprofile_result["viewed"]["CITY_BIRTH"]=="")
			$smarty->assign("CITYBIRTH","-");
		elseif(isFlagSet("CITYBIRTH",$jprofile_result["viewed"]["SCREENING"]))
			$smarty->assign("CITYBIRTH",ucwords($jprofile_result["viewed"]["CITY_BIRTH"]));
		elseif($PERSON_HIMSELF && !$search) 
			$smarty->assign("CITYBIRTH",ucwords($jprofile_result["viewed"]["CITY_BIRTH"]) . "<br>" . $SCREENING_MESSAGE_SELF);
		else 
			$smarty->assign("CITYBIRTH",$SCREENING_MESSAGE);
	}
		
	if($jprofile_result["viewed"]["SUBCASTE"]=="")
		$smarty->assign("SUBCASTE","-");
	elseif(isFlagSet("SUBCASTE",$jprofile_result["viewed"]["SCREENING"]))
		$smarty->assign("SUBCASTE",$jprofile_result["viewed"]["SUBCASTE"]);
	elseif($PERSON_HIMSELF && !$search) 
		$smarty->assign("SUBCASTE",$jprofile_result["viewed"]["SUBCASTE"] . "<br>" . $SCREENING_MESSAGE_SELF);
	else 
		$smarty->assign("SUBCASTE",$SCREENING_MESSAGE);

	if(isFlagSet("YOURINFO",$jprofile_result["viewed"]["SCREENING"]) )
	{
		if(trim($jprofile_result["viewed"]["YOURINFO"]))
		{
			$yourinfo1=trim($jprofile_result["viewed"]["YOURINFO"]);
			$len=strlen($yourinfo1);
			$flag=0;
			for($i=0;$i<$len;$i++)
			{
				if($yourinfo1[$i]==' ')
				{
					$flag++;
				}
				if($flag<3)
				{
					$subyourinfo.=$yourinfo1[$i];
				}
				else
				{
					$yourinfo.=$yourinfo1[$i];
					$flag++;
				}
			}
		}
		$smarty->assign("SUBYOURINFO",$subyourinfo);
		$infolen=strlen($yourinfo)+strlen($subyourinfo);
		$smarty->assign("INFOLEN",$infolen);
	}
	elseif($PERSON_HIMSELF && !$search)
	{
		if(trim($jprofile_result["viewed"]["YOURINFO"]))
                {
			$yourinfo1=trim($jprofile_result["viewed"]["YOURINFO"]);
                	$yourinfo = $yourinfo1 . "<br>" . $SCREENING_MESSAGE_SELF;
			$infolen=strlen($yourinfo1);
                	$smarty->assign("INFOLEN",$infolen);
		}
	}

	if(isFlagSet("JOB_INFO",$jprofile_result["viewed"]["SCREENING"]))
	{
		if(trim($jprofile_result["viewed"]["JOB_INFO"]))
			$jobinfo =$jprofile_result["viewed"]["JOB_INFO"];
	}
	elseif($PERSON_HIMSELF && !$search)
	{
		if(trim($jprofile_result["viewed"]["JOB_INFO"]))
			$jobinfo =$jprofile_result["viewed"]["JOB_INFO"] . "<br>" . $SCREENING_MESSAGE_SELF;
	}
	if(isFlagSet("SPOUSE",$jprofile_result["viewed"]["SCREENING"]))
        {
		if(trim($jprofile_result["viewed"]["SPOUSE"]))
			$spouseinfo =$jprofile_result["viewed"]["SPOUSE"];
	}
	elseif($PERSON_HIMSELF && !$search)
	{
		if(trim($jprofile_result["viewed"]["SPOUSE"]))
	                $spouseinfo =$jprofile_result["viewed"]["SPOUSE"] . "<br>" . $SCREENING_MESSAGE_SELF;
	}
	$smarty->assign("YOURINFO",nl2br($yourinfo));
	$smarty->assign("JOBINFO",nl2br($jobinfo));
	$smarty->assign("SPOUSEINFO",nl2br($spouseinfo));
	$scn_msg=0;
	/*if(isFlagSet("FATHER_INFO",$jprofile_result["viewed"]["SCREENING"]))
	{
		if(trim($jprofile_result["viewed"]["FATHER_INFO"]))
			$familyinfo=$jprofile_result["viewed"]["FATHER_INFO"];
	}
	elseif($PERSON_HIMSELF && !$search)
	{
		if(trim($jprofile_result["viewed"]["FATHER_INFO"]))
		{
			$familyinfo=$jprofile_result["viewed"]["FATHER_INFO"];
			$scn_msg=1;
		}
	}

	if(isFlagSet("SIBLING_INFO",$jprofile_result["viewed"]["SCREENING"]))
	{
		if(trim($jprofile_result["viewed"]["SIBLING_INFO"]))
			$familyinfo.="\n".$jprofile_result["viewed"]["SIBLING_INFO"];
	}
	elseif($PERSON_HIMSELF && !$search)
	{
		if(trim($jprofile_result["viewed"]["SIBLING_INFO"]))
		{
                        $familyinfo.="\n".$jprofile_result["viewed"]["SIBLING_INFO"];
			$scn_msg=1;
		}
	}
	*/
	if(isFlagSet("FAMILYINFO",$jprofile_result["viewed"]["SCREENING"]))
	{
		if(trim($jprofile_result["viewed"]["FAMILYINFO"]))
			$familyinfo=$jprofile_result["viewed"]["FAMILYINFO"];
	}
	elseif($PERSON_HIMSELF && !$search)
	{
		if(trim($jprofile_result["viewed"]["FAMILYINFO"]))
		{
                        $familyinfo=$jprofile_result["viewed"]["FAMILYINFO"];
			$scn_msg=1;
		}
	}
	if($scn_msg)
		$smarty->assign("FAMILYINFO",nl2br(trim($familyinfo)) . "<br>" . $SCREENING_MESSAGE_SELF);
	else
		$smarty->assign("FAMILYINFO",nl2br(trim($familyinfo)));

	if($jprofile_result["viewed"]["RELIGION"]!=3)
	{
		if($jprofile_result["viewed"]["GOTHRA"]=="")
			$smarty->assign("GOTHRA","-");
		elseif(isFlagSet("GOTHRA",$jprofile_result["viewed"]["SCREENING"]))
			$smarty->assign("GOTHRA",$jprofile_result["viewed"]["GOTHRA"]);
		elseif($PERSON_HIMSELF && !$search) 
			$smarty->assign("GOTHRA",$jprofile_result["viewed"]["GOTHRA"] . "<br>" . $SCREENING_MESSAGE_SELF);
		else 
			$smarty->assign("GOTHRA",$SCREENING_MESSAGE);
	}
	
		
	$smarty->assign("NAKSHATRA",$jprofile_result["viewed"]["NAKSHATRA"]);

	//commented by sriram, as nakshatra field is a dropdown field so no screening conditions.
	/*if($jprofile_result["viewed"]["NAKSHATRA"]=="")
		$smarty->assign("NAKSHATRA","-");
	elseif(isFlagSet("NAKSHATRA",$jprofile_result["viewed"]["SCREENING"]))
		$smarty->assign("NAKSHATRA",$jprofile_result["viewed"]["NAKSHATRA"]);
	elseif($PERSON_HIMSELF) 
		$smarty->assign("NAKSHATRA",$jprofile_result["viewed"]["NAKSHATRA"] . "<br>" . $SCREENING_MESSAGE_SELF);
	else 
		$smarty->assign("NAKSHATRA",$SCREENING_MESSAGE);*/
	$scn_msg1=0;
	if(isFlagSet("EDUCATION",$jprofile_result["viewed"]["SCREENING"]))
        {
                if(trim($jprofile_result["viewed"]["EDUCATION"]))
                        $eduinfo=$jprofile_result["viewed"]["EDUCATION"];
        }
        elseif($PERSON_HIMSELF && !$search)
        {
                if(trim($jprofile_result["viewed"]["EDUCATION"]))
                {
                        $eduinfo=$jprofile_result["viewed"]["EDUCATION"];
                        $scn_msg1=1;
                }
        }
	if($scn_msg1)
                $smarty->assign("EDUCATION",nl2br($eduinfo) . "<br>" . $SCREENING_MESSAGE_SELF);
        else
                $smarty->assign("EDUCATION",nl2br($eduinfo));

/*	if($jprofile_result["viewed"]["JOB_INFO"]=="")
		$smarty->assign("JOBPROFILE","-");
	elseif(isFlagSet("JOB_INFO",$jprofile_result["viewed"]["SCREENING"]))
		$smarty->assign("JOBPROFILE",nl2br($jprofile_result["viewed"]["JOB_INFO"]));
	elseif($PERSON_HIMSELF) 
		$smarty->assign("JOBPROFILE",nl2br($jprofile_result["viewed"]["JOB_INFO"]) . "<br>" . $SCREENING_MESSAGE_SELF);
	else 
		$smarty->assign("JOBPROFILE",$SCREENING_MESSAGE);*/
	
	//commented on 29th jan 2006 by shiv
	$sql="select SQL_CACHE PROFILEID from HIDE_DOB where PROFILEID='$profileid'";
	$hideresult=mysql_query_optimizer($sql);
	
	if ($hideresult && mysql_num_rows($hideresult)<=0)
	{
		$dob=explode("-",$jprofile_result["viewed"]["DTOFBIRTH"]);
		$smarty->assign("DTOFBIRTH",my_format_date($dob[2],$dob[1],$dob[0]));
		$smarty->assign("DTOFBIRTH_BI",my_format_date($dob[2],$dob[1],$dob[0],2));	
		unset($dob);
	}
	
	$dob=explode("-",substr($jprofile_result["viewed"]["MOD_DT"],0,10));
	
	if($dob[0]!="0000" && $dob[1]!="00" && $dob[2]!="00" && $dob[0]!="" && $dob[1]!="" && $dob[2]!="")
		$smarty->assign("MOD_DATE",my_format_date($dob[2],$dob[1],$dob[0]));
	
	unset($dob);
	
	$dob=explode("-",$jprofile_result["viewed"]["LAST_LOGIN_DT"]);
	
	
	if($dob[0]!="0000" && $dob[1]!="00" && $dob[2]!="00" && $dob[0]!="" && $dob[1]!="" && $dob[2]!="")
	{
		$mk_time=mktime(0,0,0,$dob[1],$dob[2],$dob[0]);
		$last_login_dt=date("jS M Y",$mk_time);
		$smarty->assign("LAST_LOGIN_DT",$last_login_dt);
	}
	
	/****************************************************************************
	Hobbies section starts here
	****************************************************************************/
	
	$sql="select * from JHOBBY where PROFILEID='$profileid'";
	$result=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	
	if(mysql_num_rows($result) > 0)
	{
		$myrow=mysql_fetch_array($result);
		
		$sql="select SQL_CACHE VALUE,LABEL,TYPE from HOBBIES order by SORTBY";
		$result_hobby=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
		while($myhobby=mysql_fetch_array($result_hobby))
		{
			$HOBBIES_ARR[$myhobby["VALUE"]]=array("LABEL" => $myhobby["LABEL"],
								"TYPE" => $myhobby["TYPE"]);
		}
		
		mysql_free_result($result_hobby);
		
		$myhobbies=explode(",",$myrow["HOBBY"]);
		
		$hobbycount=count($myhobbies);
		
		for($i=0;$i<$hobbycount;$i++)
		{
			$label=$HOBBIES_ARR[$myhobbies[$i]]["LABEL"];
			$type=$HOBBIES_ARR[$myhobbies[$i]]["TYPE"];
			
			${$type}[]=$label;
		}
		
		if(is_array($HOBBY))
			$smarty->assign("HOBBY",implode(", ",$HOBBY));
			
		if(is_array($INTEREST))
			$smarty->assign("INTEREST",implode(", ",$INTEREST));
			
		if(is_array($MUSIC))
			$smarty->assign("MUSIC",implode(", ",$MUSIC));
			
		if(is_array($BOOK))
			$smarty->assign("BOOK",implode(", ",$BOOK));
			
		if(is_array($MOVIE))
			$smarty->assign("MOVIE",implode(", ",$MOVIE));
			
		if(is_array($SPORTS))
			$smarty->assign("SPORTS",implode(", ",$SPORTS));
			
		if(is_array($CUISINE))
			$smarty->assign("CUISINE",implode(", ",$CUISINE));
			
		if(is_array($DRESS))
			$smarty->assign("DRESS",implode(", ",$DRESS));
			
		if(is_array($LANGUAGE))
			$smarty->assign("LANGUAGE",implode(", ",$LANGUAGE));
			
		if($myrow["ALLMUSIC"]=="N")
			$smarty->assign("MUSIC","Not too keen on music");
		elseif($myrow["ALLMUSIC"]=="Y")
			$smarty->assign("MUSIC","Enjoy most forms of music");
			
		if($myrow["ALLBOOK"]=="N")
			$smarty->assign("BOOK","Not much of a reader");
		elseif($myrow["ALLBOOK"]=="Y")
			$smarty->assign("BOOK","Love reading almost anything");
			
		if($myrow["ALLMOVIE"]=="N")
			$smarty->assign("MOVIE","Not a movie buff");
		elseif($myrow["ALLMOVIE"]=="Y")
			$smarty->assign("MOVIE","Love all kinds of movies");
			
		if($myrow["ALLSPORTS"]=="N")
			$smarty->assign("SPORTS","Not a sportsperson");
			
		if($myrow["ALLCUISINE"]=="N")
			$smarty->assign("CUISINE","Not much of a food-lover");
		elseif($myrow["ALLCUISINE"]=="Y")
			$smarty->assign("CUISINE","Anything edible is great!");
			
	}
	else 
	{
		$smarty->assign("NOHOBBY","1");
	}

	mysql_free_result($result);
	
	/*************************************************************************
	Hobbies section ends here
	*************************************************************************/
	
	if($profileid)//profileid is viewed profileid
	{
		$viewedDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
		$viewedDb=$mysqlObj->connect("$viewedDbName");
	}

	//Sharding added by Lavesh	
	$dppFlag=1;
	if($assistedProductOnline && $PERSON_HIMSELF)
	{
		if(is_array($currentDPP))
		{
			$dppFlag=0;
			$jpartnerObj->PartnerProfileExist='Y';
			$jpartnerObj->setPROFILEID($profileid);
			$jpartnerObj->setGENDER($currentDPP["GENDER"]);
			$jpartnerObj->setCHILDREN($currentDPP["CHILDREN"]);
			$jpartnerObj->setLAGE($currentDPP["LAGE"]);
			$jpartnerObj->setHAGE($currentDPP["HAGE"]);
			$jpartnerObj->setLHEIGHT($currentDPP["LHEIGHT"]);
			$jpartnerObj->setHHEIGHT($currentDPP["HHEIGHT"]);
			$jpartnerObj->setHANDICAPPED($currentDPP["HANDICAPPED"]);
			$jpartnerObj->setNHANDICAPPED($currentDPP["NHANDICAPPED"]); 
			$jpartnerObj->setCASTE_MTONGUE($currentDPP["CASTE_MTONGUE"]);
			$jpartnerObj->setPARTNER_BTYPE($currentDPP["PARTNER_BTYPE"]);
			$jpartnerObj->setPARTNER_CASTE($currentDPP["PARTNER_CASTE"]);
			$jpartnerObj->setPARTNER_CITYRES($currentDPP["PARTNER_CITYRES"]);
			$jpartnerObj->setPARTNER_COUNTRYRES($currentDPP["PARTNER_COUNTRYRES"]);
			$jpartnerObj->setPARTNER_DIET($currentDPP["PARTNER_DIET"]);
			$jpartnerObj->setPARTNER_DRINK($currentDPP["PARTNER_DRINK"]);
			$jpartnerObj->setPARTNER_ELEVEL($currentDPP["PARTNER_ELEVEL"]);
			$jpartnerObj->setPARTNER_ELEVEL_NEW($currentDPP["PARTNER_ELEVEL_NEW"]);
			$jpartnerObj->setPARTNER_INCOME($currentDPP["PARTNER_INCOME"]);
			$jpartnerObj->setLINCOME($currentDPP["LINCOME"]);
			$jpartnerObj->setHINCOME($currentDPP["HINCOME"]);
			$jpartnerObj->setLINCOME_DOL($currentDPP["LINCOME_DOL"]);
			$jpartnerObj->setHINCOME_DOL($currentDPP["HINCOME_DOL"]);
			$jpartnerObj->setPARTNER_MSTATUS($currentDPP["PARTNER_MSTATUS"]);
			$jpartnerObj->setPARTNER_MTONGUE($currentDPP["PARTNER_MTONGUE"]);
			$jpartnerObj->setPARTNER_NRI_COSMO($currentDPP["PARTNER_NRI_COSMO"]);
			$jpartnerObj->setPARTNER_OCC($currentDPP["PARTNER_OCC"]);
			$jpartnerObj->setPARTNER_RELATION($currentDPP["PARTNER_RELATION"]);
			$jpartnerObj->setPARTNER_RES_STATUS($currentDPP["PARTNER_RES_STATUS"]);
			$jpartnerObj->setPARTNER_SMOKE($currentDPP["PARTNER_SMOKE"]);
			$jpartnerObj->setPARTNER_COMP($currentDPP["PARTNER_COMP"]);
			$jpartnerObj->setPARTNER_RELIGION($currentDPP["PARTNER_RELIGION"]);
			$jpartnerObj->setPARTNER_NAKSHATRA($currentDPP["PARTNER_NAKSHATRA"]);
			$jpartnerObj->setPARTNER_MANGLIK($currentDPP["PARTNER_MANGLIK"]);
		}
		else
			$dppFlag=1;
	}
	if($dppFlag)
	        $jpartnerObj->setPartnerDetails($profileid,$viewedDb,$mysqlObj);

        if($jpartnerObj->isPartnerProfileExist($viewedDb,$mysqlObj,$profileid))
        {

                $HAVE_PARTNER=true;
		
		if($member_101 && $jprofile_result["viewer"]["SUBSCRIPTION"]!='')
			$member_101_details=member_101_details_show($jprofile_result["viewer"],$jpartnerObj);
		else
			$member_101_details='';

                $other_user_activated=$jprofile_result["viewed"]["ACTIVATED"];

                if($jpartnerObj->getLAGE()!="" && $jpartnerObj->getHAGE()!="")
                {
                        $FILTER_LAGE=$jpartnerObj->getLAGE();
                        $FILTER_HAGE=$jpartnerObj->getHAGE();
                        if($lang)
                                $smarty->assign("PARTNER_AGE",$jpartnerObj->getLAGE() . " से " . $$jpartnerObj->getHAGE() . " तक");
                        else
                                $smarty->assign("PARTNER_AGE",$jpartnerObj->getLAGE() . " to " . $jpartnerObj->getHAGE());
                }
                else
                        $smarty->assign("PARTNER_AGE",21 . " to " . 70);

                if($jpartnerObj->getLHEIGHT()!="" && $jpartnerObj->getHHEIGHT()!="")
                {
                        $FILTER_LHEIGHT=$lheight=$jpartnerObj->getLHEIGHT();
			if($lheight)
                        	$lheight=$HEIGHT_DROP["$lheight"];
			else
				$lheight=$HEIGHT_DROP["1"];
                        $FILTER_HHEIGHT=$hheight=$jpartnerObj->getHHEIGHT();
			if($hheight)
                        	$hheight=$HEIGHT_DROP["$hheight"];
			else
				$hheight=$HEIGHT_DROP["37"];
                        $lheight1=explode("(",$lheight);
                        $hheight1=explode("(",$hheight);

                        if($lang)
                                $smarty->assign("PARTNER_HEIGHT",$lheight1[0] . " से " . $hheight1[0] . " तक");
                        else
                                $smarty->assign("PARTNER_HEIGHT",$lheight1[0] . " to " . $hheight1[0]);

                }
                else
                        $smarty->assign("PARTNER_HEIGHT",$HEIGHT_DROP["1"] . " to " . $HEIGHT_DROP["37"]);

                if($lang)
                {
                        if($jpartnerObj->getCHILDREN()=="")
                                $smarty->assign("PARTNER_CHILDREN","मान्य नही");
                        elseif($jpartnerObj->getCHILDREN()=="N")
                                $smarty->assign("PARTNER_CHILDREN","नही");
                        elseif($jpartnerObj->getCHILDREN()=="Y")
                                $smarty->assign("PARTNER_CHILDREN","हाँ");
                }
                else
                {
                        if($jpartnerObj->getCHILDREN()=="")
                                $smarty->assign("PARTNER_CHILDREN","");
                        elseif($jpartnerObj->getCHILDREN()=="N")
                                $smarty->assign("PARTNER_CHILDREN","No");
                        elseif($jpartnerObj->getCHILDREN()=="Y")
                                $smarty->assign("PARTNER_CHILDREN","Yes");
                }

/*                if($lang)
                {
                        if($jpartnerObj->getHANDICAPPED()=="")
                                $smarty->assign("PARTNER_HANDICAPPED","मान्य नही");
                        elseif($jpartnerObj->getHANDICAPPED()=="N")
                                $smarty->assign("PARTNER_HANDICAPPED","नही");
                        elseif($jpartnerObj->getHANDICAPPED()=="Y")
                                $smarty->assign("PARTNER_HANDICAPPED","हाँ");
                }*/
                if($jpartnerObj->getHANDICAPPED()!="")
		{
			$ph_str = substr($jpartnerObj->getHANDICAPPED(),1,strlen($jpartnerObj->getHANDICAPPED())-2);
			$ph_val_arr = explode("','",$ph_str);
			for($i=0;$i<count($ph_val_arr);$i++)
			{
				$ph_val=$ph_val_arr[$i];
				$ph_arr[$i]=$HANDICAPPED[$ph_val];
			}
			if(count($ph_arr)>1)
				$ph_fstr = implode(",",$ph_arr);
			elseif(count($ph_arr)==1)
				$ph_fstr = $ph_arr[0];
			else
				$ph_fstr = "";
			if(strstr($ph_fstr,'Physically Handicapped from birth')||strstr($ph_fstr,'Physically Handicapped due to accident'))
				$showit=1;
		     	$smarty->assign("PARTNER_HANDICAPPED",$ph_fstr);
		}
		if($jpartnerObj->getNHANDICAPPED()!="")
                {
                        $nph_str = substr($jpartnerObj->getNHANDICAPPED(),1,strlen($jpartnerObj->getNHANDICAPPED())-2);
                        $nph_val_arr = explode("','",$nph_str);
                        for($i=0;$i<count($nph_val_arr);$i++)
                        {
                                $nph_val=$nph_val_arr[$i];
                                $nph_arr[$i]=$NATURE_HANDICAP[$nph_val];
                        }
			if(count($nph_arr)>1)
                                $nph_fstr = implode(",",$nph_arr);
                        elseif(count($nph_arr)==1)
                                $nph_fstr = $nph_arr[0];
                        else
                                $nph_fstr = "";
			if($showit)
				$smarty->assign("showit",1);
			else
				$smarty->assign("showit",0);
                        $smarty->assign("PARTNER_NHANDICAPPED",$nph_fstr);
                }
		else
		{
			if($showit)
                                $smarty->assign("showit",1);
                        else
                                $smarty->assign("showit",0);
		}
                $p_manglik=trim($jpartnerObj->getPARTNER_MANGLIK(),"'");
                $p_mtongue=trim($jpartnerObj->getPARTNER_MANGLIK(),"'");

/*
                $PARTNER_TABLES["PARTNER_BTYPE"]="BODYTYPE";
                $PARTNER_TABLES["PARTNER_COMP"]="COMPLEXION";
                $PARTNER_TABLES["PARTNER_DIET"]="DIET";
                $PARTNER_TABLES["PARTNER_DRINK"]="DRINK";
                $PARTNER_TABLES["PARTNER_MANGLIK"]="MANGLIK";
                $PARTNER_TABLES["PARTNER_MSTATUS"]="MSTATUS";
                $PARTNER_TABLES["PARTNER_RES_STATUS"]="RSTATUS";
                $PARTNER_TABLES["PARTNER_SMOKE"]="SMOKE";
*/

                {
			$temp=display_format($jpartnerObj->getPARTNER_BTYPE());
			for($ll=0;$ll<count($temp);$ll++)
				$PARTNER_BTYPE[]=$BODYTYPE[$temp[$ll]];		
			unset($temp);

			$temp=display_format($jpartnerObj->getPARTNER_COMP());
			for($ll=0;$ll<count($temp);$ll++)
				$PARTNER_COMP[]=$COMPLEXION[$temp[$ll]];
			unset($temp);

			$temp=display_format($jpartnerObj->getPARTNER_DIET());
			for($ll=0;$ll<count($temp);$ll++)
				$PARTNER_DIET[]=$DIET[$temp[$ll]];	
			unset($temp);

			$temp=display_format($jpartnerObj->getPARTNER_DRINK());
			for($ll=0;$ll<count($temp);$ll++)
				$PARTNER_DRINK[]=$DRINK[$temp[$ll]];
			unset($temp);

			$temp=display_format($jpartnerObj->getPARTNER_MANGLIK());
			for($ll=0;$ll<count($temp);$ll++)
				$PARTNER_MANGLIK[]=$MANGLIK[$temp[$ll]];	
			unset($temp);

			$temp=display_format($jpartnerObj->getPARTNER_MSTATUS());
                        $FILTER_MSTATUS=$temp;
			for($ll=0;$ll<count($temp);$ll++)
			{
				$PARTNER_MSTATUS[]=$MSTATUS[$temp[$ll]];		
			}
			unset($temp);

			$temp=display_format($jpartnerObj->getPARTNER_RES_STATUS());
			for($ll=0;$ll<count($temp);$ll++)
				$PARTNER_RES_STATUS[]=$RSTATUS[$temp[$ll]];
			unset($temp);

			$temp=display_format($jpartnerObj->getPARTNER_SMOKE());
			for($ll=0;$ll<count($temp);$ll++)
				$PARTNER_SMOKE[]=$SMOKE[$temp[$ll]];
			unset($temp);

                        $PARTNER_CASTE=display_format($jpartnerObj->getPARTNER_CASTE());
			$PARTNER_RELIGION=display_format($jpartnerObj->getPARTNER_RELIGION());
                        $PARTNER_ELEVEL=display_format($jpartnerObj->getPARTNER_ELEVEL());
			$PARTNER_ELEVEL_NEW=display_format($jpartnerObj->getPARTNER_ELEVEL_NEW());
                        $PARTNER_MTONGUE=display_format($jpartnerObj->getPARTNER_MTONGUE());
                        $PARTNER_OCC=display_format($jpartnerObj->getPARTNER_OCC());
                        $PARTNER_COUNTRYRES=display_format($jpartnerObj->getPARTNER_COUNTRYRES());
                        $PARTNER_INCOME=display_format($jpartnerObj->getPARTNER_INCOME());
                }
		
		include_once("incomeCommonFunctions.inc");
		$cur_sort_arr["minID"]=$jpartnerObj->getLINCOME_DOL();
		$cur_sort_arr["maxID"]=$jpartnerObj->getHINCOME_DOL();
		$cur_sort_arr["minIR"]=$jpartnerObj->getLINCOME();
		$cur_sort_arr["maxIR"]=$jpartnerObj->getHINCOME();
		global $INCOME_MAX_DROP,$INCOME_MIN_DROP;

		if($cur_sort_arr["minID"]!='' && $cur_sort_arr["minIR"]!='')
			$varr=getIncomeText($cur_sort_arr);

                if($varr){
                      $income_arr[]=implode(",</br>&nbsp;",$varr);
		      foreach ($income_arr as $key=>$val)
			$PARTNER_INCOME_NEW=$income_arr[$key];
		}

                $return_data1=partnermanglik($p_mtongue,$p_manglik);
                $manglik_data1=explode("+",$return_data1);
		if($manglik_data1[0]=="")
			$manglik_data1[0]=" - ";
                $smarty->assign("Partner_Manglik_Status",$manglik_data1[0]);
                $smarty->assign("Partner_Manglik",$manglik_data1[1]);
                                                                                                                             
		if($lang)
		{
			if(is_array($PARTNER_BTYPE))
				$smarty->assign("PARTNER_BTYPE",implode(", ",$PARTNER_BTYPE));
			else 
				$smarty->assign("PARTNER_BTYPE","मान्य नही");

			if(is_array($PARTNER_COMP))
				$smarty->assign("PARTNER_COMP",implode(", ",$PARTNER_COMP));
			else 
				$smarty->assign("PARTNER_COMP","मान्य नही");
				
			if(is_array($PARTNER_DIET))
				$smarty->assign("PARTNER_DIET",implode(", ",$PARTNER_DIET));
			else 
				$smarty->assign("PARTNER_DIET","मान्य नही");
				
			if(is_array($PARTNER_DRINK))
				$smarty->assign("PARTNER_DRINK",implode(", ",$PARTNER_DRINK));
			else 
				$smarty->assign("PARTNER_DRINK","मान्य नही");
				
			if(is_array($PARTNER_MANGLIK))
				$smarty->assign("PARTNER_MANGLIK",implode(", ",$PARTNER_MANGLIK));
			else 
				$smarty->assign("PARTNER_MANGLIK","मान्य नही");
				
			if(is_array($PARTNER_MSTATUS))
				$smarty->assign("PARTNER_MSTATUS",implode(", ",$PARTNER_MSTATUS));
			else 
				$smarty->assign("PARTNER_MSTATUS","मान्य नही");
				
			if(is_array($PARTNER_RES_STATUS))
				$smarty->assign("PARTNER_RES_STATUS",implode(", ",$PARTNER_RES_STATUS));
			else 
				$smarty->assign("PARTNER_RES_STATUS","मान्य नही");
				
			if(is_array($PARTNER_SMOKE))
				$smarty->assign("PARTNER_SMOKE",implode(", ",$PARTNER_SMOKE));
			else 
				$smarty->assign("PARTNER_SMOKE","मान्य नही");
		}
		else
		{		
			if(is_array($PARTNER_BTYPE))
				$smarty->assign("PARTNER_BTYPE",implode(", ",$PARTNER_BTYPE));
			else 
				$smarty->assign("PARTNER_BTYPE","   - ");

			if(is_array($PARTNER_COMP))
				$smarty->assign("PARTNER_COMP",implode(", ",$PARTNER_COMP));
			else 
				$smarty->assign("PARTNER_COMP","   - ");
				
			if(is_array($PARTNER_DIET))
				$smarty->assign("PARTNER_DIET",implode(", ",$PARTNER_DIET));
			else 
				$smarty->assign("PARTNER_DIET","   - ");
				
			if(is_array($PARTNER_DRINK))
				$smarty->assign("PARTNER_DRINK",implode(", ",$PARTNER_DRINK));
			else 
				$smarty->assign("PARTNER_DRINK","   - ");
				
			if(is_array($PARTNER_MANGLIK))
				$smarty->assign("PARTNER_MANGLIK",implode(", ",$PARTNER_MANGLIK));
			else 
				$smarty->assign("PARTNER_MANGLIK","");
				
			if(is_array($PARTNER_MSTATUS))
				$smarty->assign("PARTNER_MSTATUS",implode(", ",$PARTNER_MSTATUS));
			else 
				$smarty->assign("PARTNER_MSTATUS","   - ");
			
			if(is_array($PARTNER_RES_STATUS))
				$smarty->assign("PARTNER_RES_STATUS",implode(", ",$PARTNER_RES_STATUS));
			else 
				$smarty->assign("PARTNER_RES_STATUS","   - ");
				
			if(is_array($PARTNER_SMOKE))
				$smarty->assign("PARTNER_SMOKE",implode(", ",$PARTNER_SMOKE));
			else 
				$smarty->assign("PARTNER_SMOKE","   - ");
		}
			
		if($lang)
		{
			$smarty->assign("PARTNER_CASTE",get_partner_string_from_array($PARTNER_CASTE,"HIN_CASTE"));
			$smarty->assign("PARTNER_ELEVEL",get_partner_string_from_array($PARTNER_ELEVEL,"HIN_EDUCATION_LEVEL"));
$smarty->assign("PARTNER_ELEVEL_NEW",get_partner_string_from_array($PARTNER_ELEVEL_NEW,"HIN_EDUCATION_LEVEL_NEW"));
			$smarty->assign("PARTNER_MTONGUE",get_partner_string_from_array($PARTNER_MTONGUE,"HIN_MTONGUE"));
			$smarty->assign("PARTNER_OCC",get_partner_string_from_array($PARTNER_OCC,"HIN_OCCUPATION"));
			$smarty->assign("PARTNER_COUNTRYRES",get_partner_string_from_array($PARTNER_COUNTRYRES,"HIN_COUNTRY"));
		}
		else
		{
			$smarty->assign("PARTNER_CASTE",get_partner_string_from_array($PARTNER_CASTE,"CASTE"));
			$smarty->assign("PARTNER_RELIGION",get_partner_string_from_array($PARTNER_RELIGION,"RELIGION"));
			$smarty->assign("PARTNER_ELEVEL",get_partner_string_from_array($PARTNER_ELEVEL,"EDUCATION_LEVEL"));
$smarty->assign("PARTNER_ELEVEL_NEW",get_partner_string_from_array($PARTNER_ELEVEL_NEW,"EDUCATION_LEVEL_NEW"));
			$smarty->assign("PARTNER_MTONGUE",get_partner_string_from_array($PARTNER_MTONGUE,"MTONGUE"));
			$smarty->assign("PARTNER_OCC",get_partner_string_from_array($PARTNER_OCC,"OCCUPATION"));
			$smarty->assign("PARTNER_COUNTRYRES",get_partner_string_from_array($PARTNER_COUNTRYRES,"COUNTRY"));
		}
/****
*       ADDED BY         :  Gaurav Arora
*       DATE OF ADDITION :  4 April 2005
*       ADDITION         :  code to add PARTNER INCOME in DPP
****/
                $smarty->assign("PARTNER_INCOME",$PARTNER_INCOME_NEW);
// end of code to add PARTNER INCOME in DPP
		$PARTNER_CITYRES=display_format($jpartnerObj->getPARTNER_CITYRES());
		if(is_array($PARTNER_CITYRES))
		{
			$str=implode("','",$PARTNER_CITYRES);

			if($lang)
				$sql="select SQL_CACHE LABEL from HIN_CITY_INDIA where VALUE in ('$str')";
			else
				/*$sql="select SQL_CACHE LABEL FROM CITY_NEW where VALUE in ('$str')";
			$dropresult=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			if ($partner_city_str != "")
                                unset($partner_city_str);
			while($droprow=mysql_fetch_array($dropresult))
			{
				$partner_city_str.=$droprow["LABEL"] . ", ";
			}
			
			mysql_free_result($dropresult);
			
			$sql="select SQL_CACHE LABEL FROM CITY_NEW where VALUE in ('$str')";
			$dropresult=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			while($droprow=mysql_fetch_array($dropresult))
			{
				$partner_city_str.=$droprow["LABEL"] . ", ";
			}
				
			mysql_free_result($dropresult);*/

				$sql="select SQL_CACHE LABEL from CITY_NEW where VALUE in ('$str')";
                        $dropresult=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                        while($droprow=mysql_fetch_array($dropresult))
                        {
                                $partner_city_str.=$droprow["LABEL"] . ", ";
                        }

                        mysql_free_result($dropresult);
			
			$partner_city_str=substr($partner_city_str,0,strlen($partner_city_str)-2);
			$smarty->assign("PARTNER_CITYRES",$partner_city_str);
		}
	}
	else 
	{
		if($member_101)
			$member_101_details=1;
		$smarty->assign("NOPARTNER","1");
	}

	if($member_101 && $member_101_details)
	{
		$CONTACTDETAILS=1;
		$smarty->assign("CONTACTDETAILS",1);
	}
	elseif($member_101)
	{
		$CONTACTDETAILS='';;
		$smarty->assign("CONTACTDETAILS","");
	}
		
	
	/*************************************************************************
	Partner Profile section ends here
	*************************************************************************/
	//Used to check whether to shift the awaiting response person to archive when he viewed by the login user
        $allow_shift_archive=1;	
	/*************************************************************************
	Contacts section starts here
	*************************************************************************/
	if($PERSON_LOGGED_IN)
	{
		$day_contact= $data['TODAY_INI_TOTAL'];
		$month_contact= $data['MONTH_INI_TOTAL'];
		$total_contact=$data['TOTAL_CONTACTS_MADE'];
		$day_limit=$data['DAY_LIMIT']=25;
		$month_limit=$data['MONTH_LIMIT']=500;
		$overall_limit=$data['OVERALL_LIMIT']=500;
	
		//This function will give you the actual overall limit;
		if(isPaid($data['SUBSCRIPTION']))
        	{
			$day_limit=$data['DAY_LIMIT']=150;
			$overall_limit=$data['OVERALL_LIMIT']=300;
		        check_profile_percent();
		}
		
		$overall_limit=$data['OVERALL_LIMIT'];

		if($day_contact>=$day_limit )
		{
			$TYPE='T';
			$contact_limit_message="You cannot contact this profile as you have reached your day contact limit";
		}
		 if($month_contact>=$month_limit && $data['GENDER']!='F' )
		 {
			//This variable to be called in check_dpp function.
			//$is_spam=1;
			if($data['SUBSCRIPTION']=='')
		 	{
				$TYPE='M';
		 		$contact_limit_message="You cannot contact this profile as you have reached your month contact limit";
			}
		 }
		 if( $total_contact>=$overall_limit )
		 {
		 	$TYPE='O';
			$contact_limit_message="You cannot contact this profile as you have reached your contact limit.";
		 }	
	         
		//Getting the contact status , since if contact is accpeted or decline , contact limit message can't be shown	 		 
		$NUDGES=array();
		$n_source=$jprofile_result["viewed"]["SOURCE"];
		$contact_status_new=get_contact_status_dp($profileid,$data["PROFILEID"]);
		
                if($contact_status_new["R_TYPE"])
                        $contact_status = $contact_status_new["R_TYPE"];
                else
                        $contact_status = $contact_status_new["TYPE"];

		//This is required since we have not to contact privacy error and filter message, when already contacted.
		if($contact_status)
			$CONTACTMADE=1;

                 if($contact_limit_message && !is_array($contact_status_new) && $NUDGES['STATUS']=='')
		 {
			$contact_limit_reached=1;
		 	if($TYPE)
				contact_hit_limit($data['PROFILEID'],$TYPE);
				
		 	$smarty->assign("NO_CONTACT_ALLOW","1");
		 	$smarty->assign("CANNOTCONTACT","1");
		 	$smarty->assign("LIMIT_CONTACT_MESSAGE",$contact_limit_message);
		 }
		elseif($samegender==1)
		{
			//@mysql_close();
	                //$db=connect_db();

			$smarty->assign("CANNOTCONTACT","1");
			$smarty->assign("SAMEGENDER","1");
		}
		else 
		{
			if($NUDGES['STATUS'])
			{
				//$smarty->assign("NUDGE_STATUS",$NUDGES['STATUS']);
				setNudgeDetails($NUDGES['STATUS'],$jprofile_result["viewed"]["USERNAME"]);
				if($NUDGES['STATUS']=='ACC')
				{
					$CONTACTDETAILS=1;
					$smarty->assign("CONTACTDETAILS","1");
					$smarty->assign("SENDCUSTOMISED",1);
				}
				//if($NUDGES['STATUS']=='NNOW')
				//{
					$op_msg_sql="SELECT MESSAGE FROM jsadmin.OFFLINE_OPERATOR_MESSAGES WHERE MATCH_ID='$data[PROFILEID]' AND PROFILEID='$profileid'";
					$op_msg_res=mysql_query_decide($op_msg_sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes",$op_msg_sql,"ShowErrTemplate");
					if(mysql_num_rows($op_msg_res))
					{
						$op_msg_row=mysql_fetch_assoc($op_msg_res);
						$smarty->assign("MESSAGE_OPERATOR",html_entity_decode($op_msg_row["MESSAGE"]));
					}
					else
						$smarty->assign("MESSAGE_OPERATOR",'');
					mysql_free_result($op_msg_res);
				//}
			}
				if($_GET['nudge']=='true')
					setNudgeLogread($msgid);
			
			if($n_source=='ofl_prof')
			{
			 	$op_email=get_operator_email($profileid);
			}
			//To show email of operator if offline profile is viewed.
			$smarty->assign("OP_EMAIL",$op_email);
		
			// get the rights of the person whose logged in
			//$my_rights=get_rights($data["PROFILEID"]);
			
			if(in_array("F",$my_rights))
				$smarty->assign("PAID","1");
					
			// get the rights of the profile being viewed
			//$his_rights=get_rights($profileid);
			
			//added by puneet on dec 19 for inbox
			//$contact_status=get_contact_status($profileid,$data["PROFILEID"]);
			//added by puneet on dec 19 for inbox

			//modified by sriram on Jan 10 2007
			//$contact_status_new = get_contact_status($profileid,$data["PROFILEID"]);
			
			//@mysql_close();
	                //$db=connect_db();

			if($contact_status_new["R_TYPE"])
				$contact_status = $contact_status_new["R_TYPE"];
			else
				$contact_status = $contact_status_new["TYPE"];

			$myrow = $contact_status_new;

			if(strstr($contact_status,"R"))
				$found_R = 1;
			elseif($contact_status=="")
				$found_R = 3;
			else
				$found_R = 2;
			if($found_R==1)
			{
				if($myrow['TYPE']=='A' || $myrow['TYPE']=='C')
					$see_photo=1;
			}
			elseif($found_R==2)
			{
				if($myrow['TYPE']=='I' || $myrow['TYPE']=='A' || $myrow["TYPE"]=='D')
					$see_photo=1;
			}

			/*added by puneet on dec 19 2006 for last message interchaged*/
			
			if($CHECK_FOR_PHOTO_CONTACT)
			{
				if($see_photo && $samegender!=1)
				{
					$photoVersion_arr =getPhotoVersion($jprofile_result["viewed"]["PROFILEID"]);
					$version =$photoVersion_arr[$jprofile_result["viewed"]["PROFILEID"]];
					$smarty->assign("FULLVIEW","1");
					$smarty->assign("PHOTOFILE","$PHOTO_URL/profile/photo_serve.php?version=".$version."&profileid=" . md5($profileid+5) . "i" . ($profileid + 5) . "&photo=PROFILEPHOTO");
				}
				else 
				{
					$smarty->assign("FULLVIEW","");
					$smarty->assign("ISALBUM","");
//					$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/photovisible_only.gif");
					if($isMobile){
						if($myrow_gender=="M")
							$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/ser4_images/mobilejs/ic_photo_vis_if_b_60x60.gif");
						else
							$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/ser4_images/mobilejs/ic_photo_vis_if_b_60x60.gif");
					}
					else{
						if($myrow_gender=="M")
							$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/ph_visible_b.jpg");
						else
							$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/ph_visible_g.jpg");
					}
				}
			}
			
		}
		//Required for message 
                $smarty->assign("other_profileid",$jprofile_result['viewed']['PROFILEID']);
                $smarty->assign("contact_status",$contact_status);
	}
	else 
	{
		//@mysql_close();
		//$db=connect_db();

		$smarty->assign("CONTACT","1");
		$smarty->assign("CONTACTSUMMARY2_HEAD","IC");
	//	$smarty->assign("CONTACTSUMMARY2","To initiate contact with <b>$contactperson</b>, click on the \"Contact\" button"); 
	}
	
	// if the privacy option for contact is set and contact has been made then show the profile else not
	// if the person is viewing his own profile then it is allowed
	if(!$PERSON_HIMSELF && $PRIVACY=="C" && $CONTACTMADE!=1)
		showProfileError_DP("","C");
	
	/*************************************************************************
	Contacts section ends here
	*************************************************************************/

	
	
	/*************************************************************************
	Filters section starts here
	*************************************************************************/
	// filter can be applied only if the person who is viewing is logged in and the person viewing and the person being viewed is different and the person being viewed has filled partner profile and the person viewing and the one being viewed have not contacted each other before
	if($PERSON_LOGGED_IN && $data["PROFILEID"]!=$profileid && $HAVE_PARTNER  && $samegender!=1)
	{
		global $IVR_filtersCheck;
		//PAID MEMBER or spammer profile IS ALLOWED TO DO CONTACTS greater than limit only when DPP matches 
		if(check_dpp($is_spam,$FILTER_HAGE,$FILTER_LAGE,$PARTNER_COUNTRYRES,$PARTNER_CASTE,$PARTNER_MTONGUE))
		{
			$spammer=1;
			if($CONTACTMADE!=1)
			{
				$smarty->assign("CONTACT","");
				$smarty->assign("SENDCUSTOMISED","");
				$smarty->assign("CONTACTDETAILS","");
				$smarty->assign("FILTERED","1");
				$smarty->assign("CANNOTCONTACT","1");
			}
		}
		else
		{		
			$filter_flag=check_spammer_filter($jprofile_result);	
			if($filter_flag)
			{
					if($filter_flag)
					{
						$IVR_filtersCheck =1;
						$sql="insert into FILTER_LOG(VIEWER,VIEWED,DATE) values ('" . $data["PROFILEID"] . "','$profileid',now())";
						mysql_query_optimizer($sql);
					
					// if the filtered privacy option is set then don't show the profile as the person has been filtered
						if($PRIVACY=="F" && $CONTACTMADE!=1)
						{
							showProfileError_DP("","F");
						}
						
						$filter_prof=1;
						if($CONTACTMADE!=1)
						{	
							$smarty->assign("CONTACT","");
							$smarty->assign("SENDCUSTOMISED","");
							$smarty->assign("CONTACTDETAILS","");
							$smarty->assign("FILTERED","1");
							$smarty->assign("CANNOTCONTACT","1");
						}					
						if($CHECK_FOR_FILTERED && $CONTACTMADE!=1)
						{
							$smarty->assign("FULLVIEW","");
							$smarty->assign("ISALBUM","");
							//$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/photofiltered.gif");
							//added by sriram
							//$sql_gend = "SELECT GENDER FROM JPROFILE WHERE  activatedKey=1 and PROFILEID = '$profileid'";
							//$res_gend = mysql_query_optimizer($sql_gend) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_gend,"ShowErrTemplate");
                                                //$row_gend = mysql_fetch_array($res_gend);
							if($isMobile){
								if($myrow_gender=='M')
									   $smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/ser4_images/mobilejs/photo_fil_sm_b_60x60.gif");
								else
									   $smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/ser4_images/mobilejs/photo_fil_sm_b_60x60.gif");
							}
							else
							{
								if($myrow_gender=='M')
									   $smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/photo_fil_big_b.gif");
								else
									   $smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/ser2/photo_fil_big_g.gif");
							//added by sriram
							}

						}
					}
				
					//mysql_free_result($resfil);
			}
		
			@mysql_free_result($resultfilter);
		}
	}
				
	/*************************************************************************
	Filters section ends here
	*************************************************************************/
	
	/*************************************************************************
	Bookmarks section starts here
	*************************************************************************/
	
	if($PERSON_LOGGED_IN && $data["PROFILEID"]!=$profileid)
	{
		$sql="select BKNOTE from BOOKMARKS where BOOKMARKER='" . $data["PROFILEID"] . "' and BOOKMARKEE='$profileid'";	
		$bookresult=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if(mysql_num_rows($bookresult))
		{		
			$smarty->assign("BOOKMARKED","1");
			mysql_free_result($bookresult);
		}

		$sql_i="SELECT count(*) FROM newjs.IGNORE_PROFILE WHERE PROFILEID='" . $data["PROFILEID"] . "' AND IGNORED_PROFILEID='$profileid'";
                        $result_i=mysql_query_optimizer($sql_i) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_i,"ShowErrTemplate");
                        $ignorerow_i=mysql_fetch_row($result_i);
		if($ignorerow_i[0] > 0)
			$smarty->assign("IGNORED","1");
		mysql_free_result($result_i);
	}
	
	/*************************************************************************
	Bookmarks section ends here
	*************************************************************************/
	
	if($PERSON_LOGGED_IN)
		$smarty->assign("PERSON_LOGGED_IN","1");
		
	/*************************************************************************
	Full Members section starts here
	*************************************************************************/
	
	if($PERSON_LOGGED_IN)
	{
		//Contact details section starts here
		//added by lavesh on 9 aug as query on jprofile is prevented.
		if(substr($jprofile_result["viewed"]["SOURCE"],0,2)=="mb")
                {
                        $fromprofilepage=1;
                        include_once('../marriage_bureau/connectmb.inc');
                        $mbpd=getdata_mb($jprofile_result["viewed"]["SOURCE"]);
                        if($mbpd["EMAIL"])
                                $smarty->assign("HISEMAIL",$mbpd["EMAIL"]);
                        else
                                $smarty->assign("BLANKEMAIL","1");
                        if($mbpd["TELEPHONE1"])
                        {
                                $phone=$mbpd["STD"]."-".$mbpd["TELEPHONE1"];
                                if($mbpd["TELEPHONE2"])
                                        $phone.=",".$mbpd["TELEPHONE2"];
                                $smarty->assign("PHONE",$phone);
                        }
                        else
                                $smarty->assign("BLANKPHONE","1");
                        if($mbpd["ADDRESS"])
                        {
                                $smarty->assign("ADDRESS",nl2br($mbpd["ADDRESS"]));
                        }
                        else
                                $smarty->assign("BLANKADDRESS","1");
                        $smarty->assign("postedbybureau",$mbpd["NAME"]);
                }
                else
		{
			/*if($member_101)
			{
				$sqlop="SELECT OPERATOR FROM jsadmin.ASSIGNED_101 WHERE PROFILEID='$profileid'";
				$resop=mysql_query_decide($sqlop) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlop,"ShowErrTemplate");
				$rowop=mysql_fetch_assoc($resop);
				$operator_101=$rowop["OPERATOR"];
				$operator_101_details=get_101_details($profileid,"EMAIL,PHONE",$operator_101);
			}*/
			if($CONTACTDETAILS==1)
			{
				/*if($member_101)	
					$smarty->assign("HISEMAIL",$operator_101_details["EMAIL"]);
				else*/
					$smarty->assign("HISEMAIL",$jprofile_result["viewed"]["EMAIL"]);
			}
			else 
				$smarty->assign("BLANKEMAIL","1");
			
			//To show only operator email, if offline profile is contacted
			if($op_email)
				$smarty->assign("HISEMAIL",$op_email);
			
			if($jprofile_result["viewed"]["SHOWPHONE_RES"]=="Y" && $jprofile_result["viewed"]["PHONE_RES"]!="")
				$phone=$jprofile_result["viewed"]["STD"]."-".$jprofile_result["viewed"]["PHONE_RES"];
				
			if($jprofile_result["viewed"]["SHOWPHONE_MOB"]=="Y" && $jprofile_result["viewed"]["PHONE_MOB"]!="")
			{
				if(trim($phone)=="")
					$phone=$jprofile_result["viewed"]["PHONE_MOB"];
				else 
					$phone.=", " . $jprofile_result["viewed"]["PHONE_MOB"];
			}
			
			if($CONTACTDETAILS==1)
			{
				/*if($member_101)
					$smarty->assign("PHONE",$operator_101_details["PHONE"]);
				else*/
					$smarty->assign("PHONE",trim($phone));
			}
			elseif(trim($phone)!="") 
				$smarty->assign("BLANKPHONE","1");
			
			if($jprofile_result["viewed"]["CONTACT"]!="" && $jprofile_result["viewed"]["SHOWADDRESS"]=="Y")
			{
				if($CONTACTDETAILS==1)
				{
					$smarty->assign("ADDRESS",nl2br($jprofile_result["viewed"]["CONTACT"]));
				}
				else 
					$smarty->assign("BLANKADDRESS","1");
			}
			
			if($jprofile_result["viewed"]["PARENTS_CONTACT"]!="" && $jprofile_result["viewed"]["SHOW_PARENTS_CONTACT"]=="Y")
			{
				if($CONTACTDETAILS==1)
				{
					$smarty->assign("PARENTS_ADDRESS",nl2br($jprofile_result["viewed"]["PARENTS_CONTACT"]));
				}
				else 
					$smarty->assign("BLANKPARENTADDRESS","1");
			}
				
			if($jprofile_result["viewed"]["SHOWMESSENGER"]=="Y")
			{
				if($CONTACTDETAILS==1)
				{
					$mymessenger=$jprofile_result["viewed"]["MESSENGER_CHANNEL"];
					$smarty->assign("MESSENGER_CHANNEL",$MESSENGER_CHANNEL["$mymessenger"]);
					$smarty->assign("MESSENGER_ID",$jprofile_result["viewed"]["MESSENGER_ID"]);
				}
				else		 
					$smarty->assign("BLANKMESSENGER","1");
			}
		}	
		//mysql_free_result($emailresult);
		//unset($emailrow);
		
		/*********************************************************************
		Contact details section ends here
		*********************************************************************/
		if($SENDCUSTOMISED==1)
		{
			//added by lavesh on 9 aug as query on jprofile is prevented.
			$smarty->assign("EMAIL",$jprofile_result["viewer"]["EMAIL"]);
			//Ends Here
		
			$sql="select DRAFTID,DRAFTNAME,MESSAGE from DRAFTS where PROFILEID='" . $data["PROFILEID"] . "'";
			$resultdraft=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			if(mysql_num_rows($resultdraft) > 0)
			{
				while($mydraft=mysql_fetch_array($resultdraft))
				{
					$mymessage=$mydraft["MESSAGE"];
					
					while($mymessage!=ereg_replace("\r\n|\n\r|\n|\r","#n#",str_replace("\"","'",$mymessage)))
						$mymessage=ereg_replace("\r\n|\n\r|\n|\r","#n#",str_replace("\"","'",$mymessage));
					$drafts[]=array("DRAFTID" => $mydraft["DRAFTID"],
							"DRAFTNAME" => $mydraft["DRAFTNAME"],
							"MESSAGE" => $mymessage);
				}
				
				$smarty->assign("DRAFTS",$drafts);
			}
			else 
				$smarty->assign("NODRAFT","1");
				
			mysql_free_result($resultdraft);
		}
	}
        $pass_a=0;
        if($PERSON_HIMSELF)
        {
                $sql_a ="select REASON,SCREENED from newjs.`ANNULLED` where PROFILEID='".$data['PROFILEID']."'";
                $res_a=mysql_query_decide($sql_a) or die(mysql_error_js());
                if($row_a=mysql_fetch_row($res_a))
                {
                        //if($row_a[1]=='Y')
                                $smarty->assign("Annulled_Reason",nl2br($row_a[0]));
                                $pass_a=1;
                }
        }
        else
        {
                $sql_a ="select REASON,SCREENED from newjs.`ANNULLED` where PROFILEID='$profileid'";
                $res_a=mysql_query_decide($sql_a) or die(mysql_error_js());
                if($row_a=mysql_fetch_row($res_a))
                {
                        if($row_a[1]=='Y')
                                $smarty->assign("Annulled_Reason",nl2br($row_a[0]));
                        $pass_a=1;
                }
        }

        if(!$pass_a)
        {
                $smarty->assign("Annulled_Reason",'No Reason Specified');
        }
	
		
	/*************************************************************************
	Full Members section ends here
	*************************************************************************/
	$smarty->assign("PROFILECHECKSUM",$profilechecksum);
	//Used for search , in order to provide security.
        $PROFILE_CHECKSUM=createChecksumForSearch($jprofile_result['viewed']["PROFILEID"]);

        $smarty->assign("PROFILECHECKSUM_NEW",$PROFILE_CHECKSUM);

	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));

	//section added by Gaurav on 29 May 2007 for horoscope compatibility
	$compatibility_link=compatibility_link($data['PROFILEID'],$profileid);
	$smarty->assign("COMPATIBILITY_LINK",$compatibility_link);
	self_astro_details($data['PROFILEID'],'viewer');
	//end of section added by Gaurav on 29 May 2007 for horoscope compatibility
	$voip_profileid_selected = $jprofile_result['viewer']['PROFILEID'];
	if(!$PERSON_HIMSELF)
	{
		if($data)
			if($data['PROFILEID']!=$profileid)
			{
				color_code_dpp($jprofile_result['viewer']['PROFILEID'],$jprofile_result['viewed']['PROFILEID']);
			}
		$updatecontact=0;
		express_page($jprofile_result,$data,$contact_status_new,$NUDGES,$spammer,$filter_prof,$contact_limit_reached,$samegender);
		if($data && $profileid)
		{
			$mypid=$data['PROFILEID'];
			include("alter_seen_table.php");
		}

	        /* IVR - Callnow feature added
        	 * Get data of the viewer for Callnow display
		 * Enable only if CALL_NOW variable is activated.
        	*/
		global $CALL_NOW;
		if($CALL_NOW && $data)
		{
			$voip_profileid_selected= $jprofile_result['viewed']['PROFILEID'];
			$viewer_profileid   	= $jprofile_result['viewer']['PROFILEID'];

			// Check for logged in User and Paid Members Only
			if(in_array("F",$my_rights))
			{
				$callAccessArr = callAccess($voip_profileid_selected);
				if($callAccessArr[$voip_profileid_selected] =='Y'){
					$smarty->assign("CALL_ACCESS",'1');
					if($call_tab_sel){
						$smarty->assign("CALL_TAB_SEL",1);
						recordCallnowHits('CALLNOW_CLICK');
					}
					$mypid=$data["PROFILEID"];
					ivrCallNow($viewer_profileid,$voip_profileid_selected);
					$myprofilechecksum = md5($mypid)."i".($mypid);
					$smarty->assign("myprofilechecksum",$myprofilechecksum);
					$smarty->assign("REC_PROFILEID",$voip_profileid_selected);
				}
			}
		}
		/* Ends IVR - Callnow functionality  */			
	}

	if($lang)
	{
		$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
		$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch($lang."_subfooternew.htm"));
		$smarty->assign("SUBHEADER",$smarty->fetch($lang."_subheader.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch($lang."_leftpanelnew.htm"));
	}
	else
	{
		if($mbureau=="bureau1")
                {
                        $smarty->assign("pid",$pid);
                        //$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                        $smarty->assign("MBHEAD",$smarty->fetch("top_band.htm"));
                        $smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
                        $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
                        $smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));
                        if($onlyc=="yes")
                                $smarty->assign("SHOWCONTACTLINK_MB","1");
                }
                else if($crmback!="admin")
                {
			//$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			//$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
			$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
			//$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
		}
	}


    // clicksource variable is used for tracking contacts through match alerts
	$smarty->assign("CLICKSOURCE",$clicksource);
	$smarty->assign("MOB_VERIFIED",$show_mob);
	$smarty->assign("RES_VERIFIED",$show_res);
	$smarty->assign("STYPE",$stype);
	$smarty->assign("GENDER_MOB",$mob_gender);
	//echo $show_mob;
	$pid = $profileid;
	if($religion[0] == 'Hindu')
	{
		$ras = $jprofile_result["viewed"]["RASHI"];
		$sql_ras = "select LABEL from RASHI WHERE VALUE = '$ras'";
		$res_ras = mysql_query_optimizer($sql_ras) or logError("error",$sql_ras);
		if($myrow_ras = mysql_fetch_array($res_ras))
			$smarty->assign("RASHI",$myrow_ras["LABEL"]);
		if($jprofile_result["viewed"]["ANCESTRAL_ORIGIN"]=="")
        	        $smarty->assign("NATIVE_PLACE","-");
	        elseif(isFlagSet("ANCESTRAL_ORIGIN",$jprofile_result["viewed"]["SCREENING"]))
                	$smarty->assign("NATIVE_PLACE",$jprofile_result["viewed"]["ANCESTRAL_ORIGIN"]);
	        elseif($PERSON_HIMSELF && !$search)
        	        $smarty->assign("NATIVE_PLACE",$jprofile_result["viewed"]["ANCESTRAL_ORIGIN"] . "<br>" . $SCREENING_MESSAGE_SELF);
	        else
        	        $smarty->assign("NATIVE_PLACE",$SCREENING_MESSAGE);

		$smarty->assign("HOROSCOPE_MATCH",$jprofile_result["viewed"]["HOROSCOPE_MATCH"]);
	}
	elseif($religion[0] == 'Jain')
	{
		$sql_jain = "SELECT SAMPRADAY FROM JP_JAIN WHERE PROFILEID='$pid'";
		$res_jain=mysql_query_decide($sql_jain) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_jain,"ShowErrTemplate");
		$row_jain=mysql_fetch_array($res_jain);
		$smarty->assign("SAMPRADAY",$SAMPRADAY[$row_jain['SAMPRADAY']]);
	}
	elseif($religion[0] == 'Christian')
	{
		$sql_christian = "SELECT * FROM JP_CHRISTIAN WHERE PROFILEID='$pid'";
		$res_christian=mysql_query_decide($sql_christian) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_christian,"ShowErrTemplate");
		$row_christian=mysql_fetch_array($res_christian);
                if($row_christian["DIOCESE"]=="")
                        $smarty->assign("DIOCESE","-");
                elseif(isFlagSet("GOTHRA",$jprofile_result["viewed"]["SCREENING"]))
                        $smarty->assign("DIOCESE",$row_christian["DIOCESE"]);
                elseif($PERSON_HIMSELF && !$search)
                        $smarty->assign("DIOCESE",$row_christian["DIOCESE"] . "<br>" . $SCREENING_MESSAGE_SELF);
                else
                        $smarty->assign("DIOCESE",$SCREENING_MESSAGE);
		$smarty->assign("BAPTISED",$row_christian['BAPTISED']);
		$smarty->assign("READ_BIBLE",$row_christian['READ_BIBLE']);
		$smarty->assign("OFFER_TITHE",$row_christian['OFFER_TITHE']);
		$smarty->assign("SPREADING_GOSPEL",$row_christian['SPREADING_GOSPEL']);
	}
	elseif($religion[0] == 'Muslim')
	{
		$sql_muslim = "SELECT * FROM JP_MUSLIM WHERE PROFILEID='$pid'";
		$res_muslim=mysql_query_decide($sql_muslim) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_muslim,"ShowErrTemplate");
		$row_muslim=mysql_fetch_array($res_muslim);
		$math_val = $row_muslim['MATHTHAB'];
		if($caste == "Muslim: Sunni")
			$smarty->assign("MATHTHAB",$MATHTHAB_SUNNI[$math_val]);
		elseif($caste == "Muslim: Shia")
			$smarty->assign("MATHTHAB",$MATHTHAB_SHIA[$math_val]);
		$smarty->assign("SPEAK_URDU",$jprofile_result["viewed"]["SPEAK_URDU"]);
		$smarty->assign("NAMAZ",$NAMAZ[$row_muslim['NAMAZ']]);
		$smarty->assign("ZAKAT",$row_muslim['ZAKAT']);
		$smarty->assign("FASTING",$FASTING[$row_muslim['FASTING']]);
		$smarty->assign("QURAN",$QURAN[$row_muslim['QURAN']]);
		$smarty->assign("UMRAH_HAJJ",$UMRAH_HAJJ[$row_muslim['UMRAH_HAJJ']]);
		$smarty->assign("SUNNAH_BEARD",$SUNNAH_BEARD[$row_muslim['SUNNAH_BEARD']]);
		$smarty->assign("SUNNAH_CAP",$SUNNAH_CAP[$row_muslim['SUNNAH_CAP']]);
		$smarty->assign("HIJAB",$row_muslim['HIJAB']);
		$smarty->assign("HIJAB_MARRIAGE",$row_muslim['HIJAB_MARRIAGE']);
		$smarty->assign("WORKING_MARRIAGE",$row_muslim['WORKING_MARRIAGE']);
	}
	elseif($religion[0] == 'Sikh')
	{
		$sql_sikh = "SELECT * FROM JP_SIKH WHERE PROFILEID='$pid'";
		$res_sikh= mysql_query_decide($sql_sikh) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_sikh,"ShowErrTemplate");
		$row_sikh=mysql_fetch_array($res_sikh);
		$smarty->assign("AMRITDHARI",$row_sikh['AMRITDHARI']);
		$smarty->assign("CUT_HAIR",$row_sikh['CUT_HAIR']);
		$smarty->assign("TRIM_BEARD",$row_sikh['TRIM_BEARD']);
		$smarty->assign("WEAR_TURBAN",$row_sikh['WEAR_TURBAN']);
		$smarty->assign("CLEAN_SHAVEN",$row_sikh['CLEAN_SHAVEN']);
	}
	elseif($religion[0] == 'Parsi')
	{
		$sql_parsi = "SELECT * FROM JP_PARSI WHERE PROFILEID='$pid'";
		$res_parsi= mysql_query_decide($sql_parsi) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_parsi,"ShowErrTemplate");
		$row_parsi=mysql_fetch_array($res_parsi);
		$smarty->assign("ZARATHUSHTRI",$row_parsi['ZARATHUSHTRI']);
		$smarty->assign("PARENTS_ZARATHUSHTRI",$row_parsi['PARENTS_ZARATHUSHTRI']);
        }
	if($lang)
		$smarty->display($lang."_profile_preview.htm");
	else
	{
		/*if ($PERSON_HIMSELF)
			$smarty->display("profile_edit.htm");
		else*/
		if($isMobile){
					if(strlen($yourinfo)>100){
						$smarty->assign("ABT_MORE",1);
						$yourinfo_arr=explode('|', wordwrap($yourinfo, 100, '|'));
						$yourinfo2=$yourinfo_arr[0];
						$smarty->assign("YOURINFO1",str_replace("\r","",str_replace("\n","",addslashes(nl2br($yourinfo2)))));
						$smarty->assign("YOURINFO",str_replace("\r","",str_replace("\n","",addslashes(nl2br($yourinfo)))));
						$smarty->assign("SUBYOURINFO1",str_replace("\r","",str_replace("\n","",addslashes(nl2br($subyourinfo)))));
					}
					if(strlen($familyinfo)>100){
						$smarty->assign("FAM_MORE",1);
						$familyinfo_arr=explode('|', wordwrap($familyinfo, 100, '|'));
						$familyinfo2=$familyinfo_arr[0];
						$smarty->assign("FAMILYINFO1",str_replace("\r","",str_replace("\n","",addslashes(nl2br($familyinfo2)))));
						$smarty->assign("FAMILYINFO",str_replace("\r","",str_replace("\n","",addslashes(nl2br($familyinfo)))));
					}
					if(strlen($spouseinfo)>100){
						$smarty->assign("SPS_MORE",1);
						$spouseinfo_arr=explode('|', wordwrap($spouseinfo, 100, '|'));
						$spouseinfo2=$spouseinfo_arr[0];
						$smarty->assign("SPOUSEINFO1",str_replace("\r","",str_replace("\n","",addslashes(nl2br($spouseinfo2)))));
						$smarty->assign("SPOUSEINFO",str_replace("\r","",str_replace("\n","",addslashes(nl2br($spouseinfo)))));
					}
					$caste_small_label=$CASTE_DROP_SMALL[$jprofile_result["viewed"]["CASTE"]];
					$caste_small_label=str_replace("-","",$caste_small_label);
					$mtongue_small_label=$MTONGUE_DROP_SMALL[$jprofile_result["viewed"]["MTONGUE"]];
					$snip_view_arr=array();
					$snip_view_arr[]=$jprofile_result["viewed"]["AGE"];
					$snip_view_arr[]=$height1[0];
					$snip_view_arr[]=$religion[0];
					$snip_view_arr[]=$caste_small_label;
					$snip_view_arr[]=$mtongue_small_label;
					if($jprofile_result["viewed"]["GOTHRA"])
						if(isFlagSet("GOTHRA",$jprofile_result["viewed"]["SCREENING"]))
							$snip_view_arr[]=$jprofile_result["viewed"]["GOTHRA"]."(Gothra)";
					if($edu_level_new[0])
						$snip_view_arr[]=$edu_level_new[0];
					if($jprofile_result["viewed"]["INCOME"])
						$snip_view_arr[]=$income_map[$jprofile_result["viewed"]["INCOME"]];
					if($occupation)
						$snip_view_arr[]=$occupation;
					$snip_view_str=implode(",",$snip_view_arr);
					if($jprofile_result["viewed"]["CITY_RES"])
					{
						$residence=$CITY_INDIA_DROP[$jprofile_result["viewed"]["CITY_RES"]];
						if(!$residence)
							$residence=$CITY_DROP[$jprofile_result["viewed"]["CITY_RES"]];
					}
					else
						$residence=$COUNTRY_DROP[$jprofile_result["viewed"]["COUNTRY_RES"]];
					$snip_view_str.=" in $residence";
					$smarty->assign("SNIP_VIEW",$snip_view_str);
					$smarty->assign("CASTE_SMALL",$caste_small_label);
					$smarty->assign("MTONGUE_SMALL",$mtongue_small_label);
					$header=$smarty->fetch("mobilejs/jsmb_header.html");
					$footer=$smarty->fetch("mobilejs/jsmb_footer.html");
					$smarty->assign("HEADER",$header);
					$smarty->assign("FOOTER",$footer);
		}
			if ($PERSON_HIMSELF)
			{
				$smarty->assign("VIEWS",$VIEWS);
				$smarty->assign("LAST_MODIFIED",$mydate);
				//$smarty->assign("PROFILE_PERCENT",$PROFILE_PERCENT);
				$smarty->assign("PARENTS_CONTACT",$Parents_Contact);
				$smarty->assign("CONTACT",$Address);
				$smarty->assign("PINCODE",$pincode);
				// Phone Check assignment
				$smarty->assign("state_code",$State_Code);
				$smarty->assign("PHONE_RES",$Phone);
				$smarty->assign("PHONE_MOB",$Mobile);
				$smarty->assign("VERIFIED_MOB",$mob_verified);
				$smarty->assign("VERIFIED_RES",$res_verified);
				// Ends of Phone Check assignment
				$smarty->assign("MESSENGER_ID",$Messenger_ID);
				$smarty->assign("MSGR_CHANNEL",$messenger);
				if(!strpos($Messenger_ID,"@"))
					$smarty->assign("SHOW_MSGR_CHANNEL",1);
				else
					$smarty->assign("SHOW_MSGR_CHANNEL",0);
				$smarty->assign("RADIOPRIVACY",$PRIVACY);
				if($search || $PRINT)
				{
					$smarty->assign("self_profile","1");
					$smarty->assign("noprint","1");
                                	$smarty->assign("RELIGION",$religion[0]);
                                	$smarty->assign("PROFILECHECKSUM",$profilechecksum);
                                	$smarty->assign("CHECKSUM",$checksum);
                                	$smarty->assign("PRINT_VERSION","Y");
					$smarty->display("profile_print.htm");
					die;
                                	//$smarty->display("profile_by_mail.htm");
				}
				elseif($PRINT)
				{
					$smarty->display("profile_print.html");
					die;
					
				}
				else
				{
					$pid = $data["PROFILEID"];
                                        $smarty->assign("INCOMPLETE",$jprofile_result["viewed"]["INCOMPLETE"]);
					/*if($post_login)
						$smarty->assign("post_login",1);*/	
					
					//My profile tab selected only when viewing his/her profile.
					if($PERSON_HIMSELF)
						$smarty->assign("con_chk",3);
                                        $smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
                                        $smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
                                        //$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
                                        $smarty->assign("REVAMP_RIGHT_PANEL",$smarty->fetch("revamp_rightpanel.htm"));
                                        $smarty->assign("REVAMP_LEFT_PANEL",$smarty->fetch("revamp_leftpanel.htm"));
                                        $smarty->assign("FOOT",$smarty->fetch("footer.htm"));
                                        $smarty->assign("PHOTODISPLAY",$jprofile_result["viewed"]["PHOTO_DISPLAY"]);
                                        if($delete_yes == 1)
                                        {	
						if($replace && $cancel_up) 
						{
							values_retained($pid);
						}
						else
						{
							if($replace)
							{
								$sql3 = "DELETE from PHOTO_DATA_RETAIN where PROFILEID=$pid";
								mysql_query_decide($sql3) or die(mysql_error_js());
							}
							if($CASE == 2)
							{
								$sql_sel = "select ALBUMPHOTO2 from PICTURE_FOR_SCREEN where PROFILEID=$pid";
								$result_sel = mysql_query_decide($sql_sel) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_sel,"ShowErrTemplate");
								if(mysql_num_rows($result_sel) > 0)
								{
									$ro_sel=mysql_fetch_array($result_sel);
									$ap2 = $ro_sel["ALBUMPHOTO2"];
								}
								if($ap2 != "")
								{
									$CASE == 1;
									$sql_t = "UPDATE PICTURE_TITLES SET TITLE1=TITLE2,T1_IN_SCREEN=T2_IN_SCREEN,TITLE2='',T2_IN_SCREEN='' where PROFILEID=$pid";
									mysql_query_decide($sql_t) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_t,"ShowErrTemplate");
									$sql_del = "UPDATE PICTURE_FOR_SCREEN SET ALBUMPHOTO1=ALBUMPHOTO2,ALBUMPHOTO2='' where PROFILEID=$pid";
								}
								else
								{
									$sql_t = "UPDATE PICTURE_TITLES SET TITLE1='',T1_IN_SCREEN='' where PROFILEID=$pid";
									mysql_query_decide($sql_t) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_t,"ShowErrTemplate");
									$sql_del = "UPDATE PICTURE_FOR_SCREEN SET ALBUMPHOTO1='' where PROFILEID=$pid";
								}
							}
							elseif($CASE == 1)
							{
								$sql_t = "UPDATE PICTURE_TITLES SET TITLE2='',T2_IN_SCREEN='' where PROFILEID=$pid";
								mysql_query_decide($sql_t) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_t,"ShowErrTemplate");
								$sql_del = "UPDATE PICTURE_FOR_SCREEN SET ALBUMPHOTO2='' where PROFILEID=$pid";
							}
							elseif($CASE == 3)
							{
								$sql_t = "UPDATE PICTURE_TITLES SET TITLE='',T_IN_SCREEN='' where PROFILEID=$pid";
								mysql_query_decide($sql_t) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_t,"ShowErrTemplate");
								$sql_del = "delete from PICTURE_FOR_SCREEN where PROFILEID=$pid";

							}
							mysql_query_decide($sql_del) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_del,"ShowErrTemplate");
							delp($pid,$CASE);	
						}
						unset($replace);
						unset($cancel_up);
                                                unset($delete_yes);
                                                unset($filename2);
                                        }
				}
				if($edit_title)
				{
					$sql_sel = "select * from PICTURE_TITLES where PROFILEID=$pid";
                                        $result_sel = mysql_query_decide($sql_sel) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_sel,"ShowErrTemplate");
                                        if(mysql_num_rows($result_sel) > 0)
                                        {
						if($title)
						{
							$sqlupdate = "update PICTURE_TITLES set TITLE='$title',T_IN_SCREEN='Y' WHERE PROFILEID=$profileid";
							mysql_query_decide($sqlupdate) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlupdate,"ShowErrTemplate");;
							unset($title);
						}
						else if($title1)
						{
							$sqlupdate = "update PICTURE_TITLES set TITLE1='$title1',T1_IN_SCREEN='Y' WHERE PROFILEID=$profileid";
							mysql_query_decide($sqlupdate) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlupdate,"ShowErrTemplate");;
							unset($title1);
						}
						else if($title2)
						{
							$sqlupdate = "update PICTURE_TITLES set TITLE2='$title2',T2_IN_SCREEN='Y' WHERE PROFILEID=$profileid";
							mysql_query_decide($sqlupdate) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlupdate,"ShowErrTemplate");;
							unset($title2);
						}
						elseif($blank)
						{
							if($blank == 1)
							{
								$sqlupdate = "update PICTURE_TITLES set TITLE1='' WHERE PROFILEID=$profileid";
								mysql_query_decide($sqlupdate);
							}
							else
							{
								$sqlupdate = "update PICTURE_TITLES set TITLE2='' WHERE PROFILEID=$profileid";
								mysql_query_decide($sqlupdate);
							}	
						}
						else
						{
							$sqlupdate = "update PICTURE_TITLES set TITLE='' WHERE PROFILEID=$profileid";                               
							mysql_query_decide($sqlupdate);
						}
					}
					else
					{
						if($title)
	                                        {
                                                	$sqlupdate = "INSERT INTO PICTURE_TITLES (PROFILEID,TITLE,T_IN_SCREEN) VALUES ('$profileid','$title','Y')";
	                                                mysql_query_decide($sqlupdate) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlupdate,"ShowErrTemplate");;
        	                                        unset($title);
                	                        }
                        	                else if($title1)
                                	        {
							$sqlupdate = "INSERT INTO PICTURE_TITLES (PROFILEID,TITLE1,T1_IN_SCREEN) VALUES ('$profileid','$title1','Y')";
                                                	mysql_query_decide($sqlupdate) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlupdate,"ShowErrTemplate");;
	                                                unset($title1);
        	                                }
                	                        else if($title2)
                        	                {
							$sqlupdate = "INSERT INTO PICTURE_TITLES (PROFILEID,TITLE2,T2_IN_SCREEN) VALUES ('$profileid','$title2','Y')";
                                        	        mysql_query_decide($sqlupdate) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlupdate,"ShowErrTemplate");;
                                                	unset($title2);
	                                        }
						elseif($blank)
                	                        {
                        	                        if($blank == 1)
                                	                {
								$sqlupdate = "INSERT INTO PICTURE_TITLES (PROFILEID,TITLE1) VALUES ('$profileid','')";
                                                	        mysql_query_decide($sqlupdate);
	                                                }
        	                                        else
                	                                {
								$sqlupdate = "INSERT INTO PICTURE_TITLES (PROFILEID,TITLE2) VALUES ('$profileid','')";
                                	                        mysql_query_decide($sqlupdate);
                                        	        }
	                                        }
        	                                else
                	                        {
							$sqlupdate = "INSERT INTO PICTURE_TITLES (PROFILEID,TITLE) VALUES ('$profileid','')";
                                	                mysql_query_decide($sqlupdate);
                                        	}
					}
				}
				else
				{
					if(($title1)||($title2))
					{
						$sql_sel = "select * from PICTURE_TITLES where PROFILEID=$pid";
						$result_sel = mysql_query_decide($sql_sel) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_sel,"ShowErrTemplate");
						if(mysql_num_rows($result_sel) > 0)
						{
							if($title1)
							{
								$sqlupdate = "update PICTURE_TITLES set TITLE1='$title1',T1_IN_SCREEN='Y' WHERE PROFILEID=$profileid";
								mysql_query_decide($sqlupdate) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlupdate,"ShowErrTemplate");;
								unset($title1);
							}
							elseif($title2)
							{
								$sqlupdate = "update PICTURE_TITLES set TITLE2='$title2',T2_IN_SCREEN='Y' WHERE PROFILEID=$profileid";
								mysql_query_decide($sqlupdate) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlupdate,"ShowErrTemplate");;
								unset($title2);
							}
						}
						else
						{
							if($title1)
							{
								$sqlupdate = "INSERT INTO PICTURE_TITLES (PROFILEID,TITLE1,T1_IN_SCREEN) VALUES ('$profileid','$title1','Y')";
								mysql_query_decide($sqlupdate) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlupdate,"ShowErrTemplate");;
								unset($title1);
							}
							elseif($title2)
							{
								$sqlupdate = "INSERT INTO PICTURE_TITLES (PROFILEID,TITLE2,T2_IN_SCREEN) VALUES ('$profileid','$title2','Y')";
							mysql_query_decide($sqlupdate) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlupdate,"ShowErrTemplate");;
								unset($title2);
							}
						}
					}
				}
				$sql_sel = "select * from PICTURE_TITLES where PROFILEID=$pid";
				$result_sel = mysql_query_decide($sql_sel) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_sel,"ShowErrTemplate");
				if(mysql_num_rows($result_sel) > 0)
				{
					$ro_sel = mysql_fetch_array($result_sel);
					if(strlen($ro_sel["TITLE"])>15)
						$title = substr($ro_sel["TITLE"],0,15)."...";
					else
						$title = $ro_sel["TITLE"];
					if(strlen($ro_sel["TITLE1"])>15)
                                                $title1 = substr($ro_sel["TITLE1"],0,15)."...";
                                        else
                                                $title1 = $ro_sel["TITLE1"];
					if(strlen($ro_sel["TITLE2"])>15)
                                                $title2 = substr($ro_sel["TITLE2"],0,15)."...";
                                        else
                                                $title2 = $ro_sel["TITLE2"];
					$sp="&npsp;";
					if(strpos($title," ")?true:false)
						$title_4url=str_replace(" ",$sp,$title);
					else
						$title_4url=$title;
					if(strpos($title1," ")?true:false)
                                                $title1_4url=str_replace(" ",$sp,$title1);
                                        else
                                                $title1_4url=$title1;
					if(strpos($title2," ")?true:false)
                                                $title2_4url=str_replace(" ",$sp,$title2);
                                        else
                                                $title2_4url=$title2;
					$smarty->assign("title_4url",$title_4url);
                                        $smarty->assign("title1_4url",$title1_4url);
                                        $smarty->assign("title2_4url",$title2_4url);
					$smarty->assign("title",$title);
					$smarty->assign("title1",$title1);
					$smarty->assign("title2",$title2);
					unset($title);
					unset($title1);
					unset($title2);
				}
				if($no_layer)
				{
					$sql = "UPDATE PICTURE_FOR_SCREEN SET PROFILEPHOTO=MAINPHOTO,THUMBNAIL=MAINPHOTO where PROFILEID=$pid";
					mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");;
					$no_layer = 0;
				}
				if($replace && !$cancel_up)
				{
					$sqlt="UPDATE newjs.PICTURE_FOR_SCREEN set THUMBNAIL='' where PROFILEID='$profileid'";
					mysql_query_decide($sqlt) or die(mysql_error_js());

					$sqlt="UPDATE newjs.PICTURE set THUMBNAIL='' where PROFILEID='$profileid'";
					mysql_query_decide($sqlt) or die(mysql_error_js());

					$sqlt1="UPDATE newjs.PICTURE_OLD set THUMBNAIL='' where PROFILEID='$profileid'";
					mysql_query_decide($sqlt1) or die(mysql_error_js());

					$sqlt2 = "select count(*) as cnt from PICTURE_UPLOAD where PROFILEID = '$profileid' ";
					$rest2= mysql_query_decide($sqlt2) or die(mysql_error_js());
					$rowt2 = mysql_fetch_array($rest2);
					if($rowt2['cnt'] > 0)
					{
						$sqlt3="UPDATE newjs.PICTURE_UPLOAD set THUMBNAIL='' where PROFILEID = '$profileid' ";
						mysql_query_decide($sqlt3) or die(mysql_error_js());
					}

				}
				$logged_in_again = 0;
				$sql = "select * from PICTURE_FOR_SCREEN where PROFILEID=$pid";
				$result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				if(mysql_num_rows($result) > 0)
				{
					$ro = mysql_fetch_array($result);
					$filename1 = $ro["MAINPHOTO"];
					$filename2 = $ro["ALBUMPHOTO1"];
					$filename3 = $ro["ALBUMPHOTO2"];
					$filename4 = $ro["THUMBNAIL"];
				}
				else
					$logged_in_again = 1;
				$sql1 = "select * from PICTURE where PROFILEID=$pid";
				$result1 = mysql_query_decide($sql1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql1,"ShowErrTemplate");
				if(mysql_num_rows($result1) > 0)
				{
					$ro1 = mysql_fetch_array($result1);
					$smarty->assign("profileT",$ro1["MAINPHOTO"]);
					$smarty->assign("thumbT",$ro1["THUMBNAIL"]);
					$smarty->assign("albump1T",$ro1["ALBUMPHOTO1"]);
					$smarty->assign("album2T",$ro1["ALBUMPHOTO2"]);
					$smarty->assign("version",$ro1["VERSION"]);
				}
				if($filename1)
					$smarty->assign("profilephoto",1);
				if($filename4)
					$smarty->assign("thumbphoto",1);
				if($filename2)
					$smarty->assign("albumphoto1",1);
				if($filename3)
					$smarty->assign("albumphoto2",1);
				if($after_login)
				$smarty->assign("after_login",$after_login);
				unset($after_login);
				$smarty->assign("EditWhatNew",$EditWhatNew);
				$smarty->assign("callTime",$callTime);
				if($MOBILE_NO)
					$smarty->assign("MOBILE_NO",$MOBILE_NO);
				$legacyuser = 0;
				if($jprofile_result["viewed"]["ENTRY_DT"]>"06-04-2009")
					$legacyuser = 1;
				$smarty->assign("legacyuser",$legacyuser);
				$smarty->assign("FOOT",$smarty->fetch("footer.htm"));

				$browser = $_SERVER['HTTP_USER_AGENT'];
				$test = "no";
				if (strstr($browser,"MSIE")) $test = "yes";
				if ($test == "yes") // then we have a browser from our array
					$smarty->assign("test",$test);
				$sql_arch= "SELECT COUNT(*) AS CNT FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$pid'";
				$res_arch= mysql_query_decide($sql_arch) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_arch,"ShowErrTemplate");
				$row_arch=mysql_fetch_array($res_arch);
				$smarty->assign("is_archive",$row_arch["CNT"]);
				$photochecksum=md5($profileid+5) . "i" . ($profileid+5);
				$smarty->assign("photochecksum",$photochecksum);
				$smarty->assign("logged_in_again",$logged_in_again);
				$smarty->assign("DupEmail",$DupEmail);
				$url_r="$SITE_URL/profile/viewprofile.php?checksum=$checksum&profilechecksum=$profilechecksum&from_horo_layer=1";
				$smarty->assign("url_r",$url_r);
				
				//================ IVR- Verification of the Phone Number if User views HIMSELF ==================
				//Invalid Check
				include_once($_SERVER['DOCUMENT_ROOT']."/profile/login_intermediate_pages.php");
				if($PhoneFlag =='I')
					$invalidPhone =true;
				else
					$invalidPhone=is_invalid($profileid);
				if(($Mobile || $Phone) && !$invalidPhone)
				{
					// Mobile Check
					if($MobStatus =='Y')
						$smarty->assign("MYMOBILE",0);
					else
						$smarty->assign("MYMOBILE",1);			
				
					// Landline Check
					if($LandlStatus =='Y')
						$smarty->assign("MYPHONE",0);
					else
						$smarty->assign("MYPHONE",1);

                                        if($jprofile_result["viewer"]["COUNTRY_RES"]!=51)
                                                $smarty->assign("NRI",1);
                                        else
                                                $smarty->assign("NRI",0);
				}	
				else{
					if($Mobile)
						$smarty->assign("MYMOBILE",1);
					if($Phone)
						$smarty->assign("MYPHONE",1);
				}	
                		//==================  IVR- Verification of the Phone Number   ======================

                                // function removed from top and added here to cacculate profile percent
                                $PROFILE_PERCENT = profile_percent($profileid,"1",'',$mysqlObj,$jpartnerObj);
                                $smarty->assign("PROFILE_PERCENT",$PROFILE_PERCENT);

				/****Tracking purpose********/
				if($tracking)
				{
					$SOURCE=$data["SOURCE"];
					$sql_gp = "SELECT GROUPNAME FROM MIS.SOURCE WHERE SourceID='$SOURCE'";
					$ressource_gp=mysql_query_decide($sql_gp) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate") ;
					if(mysql_num_rows($ressource_gp))
					{
						$mysource_gp=mysql_fetch_array($ressource_gp);
						$groupname=$mysource_gp["GROUPNAME"];
						if($groupname=="google")
						      $smarty->assign("reg_comp_frm_ggl","1");
						elseif($groupname=="Google_NRI")
						      $smarty->assign("reg_comp_frm_ggl_nri","1");
						if($groupname)
						       $VAR = $groupname;
						elseif($GROUPNAME)
						       $VAR = $GROUPNAME;
						elseif($SOURCE)
						       $VAR = $SOURCE;
						$pixelcode = pixelcode($VAR);
						if($jprofile_result["viewed"]["GENDER"]=='M')
							$genderLabel="Male";
						else
							$genderLabel="Female";
						$USERNAME = $jprofile_result['viewed']['USERNAME'];
						$pixelcode=str_replace('~$USERNAME`',$USERNAME,$pixelcode);
						$pixelcode=str_replace('~$PROFILEID`',$profileid,$pixelcode);
						$pixelcode=str_replace('~$CITY`',$city_res,$pixelcode);
						$pixelcode=str_replace('~$AGE`',$jprofile_result["viewed"]["AGE"],$pixelcode);
						$pixelcode=str_replace('~$GENDER`',$genderLabel,$pixelcode);
						$smarty->assign("pixelcode",$pixelcode);
						$smarty->assign("GROUPNAME",$groupname);
						$smarty->assign("SOURCE",$SOURCE);
						$smarty->assign("groupname",$groupname);
						$smarty->assign("fromeditpage",1);
						$smarty->assign("REGISTRATION",$smarty->fetch("registration_tracking.htm"));
					}
				}
				/*******Ends here***********/
				$smarty->assign("HAVEPHOTO",$jprofile_result["viewed"]["HAVEPHOTO"]);
				$smarty->assign("random",date("YmdHis"));
				if($isMobile)
				{
					$smarty->assign("PERSON_SELF",1);
					$smarty->display("mobilejs/jsmb_view_profile.html");
				}
				else
				$smarty->display("profile_edit.htm");
			}
			elseif($PRINT)
			{
				$smarty->display("profile_print.htm");
				die;
                        }
			else
			{

				/* Tracking Contact Center, as per Mantis 4724 Starts here */
		                $end_time=microtime(true)-$start_tm;
                		$smarty->assign("TRACK_FOOT",BrijjTrackingHelper::getTailTrackJs($end_time,true,2,"http://track.99acres.com/images/zero.gif"));
		                /* Ends Here */
				if($view_cd)
					$smarty->assign("SHOW_CONTACT_TAB_EV",1);
				 $con_det_message="To view contact details, <a href=\"#\" class=\"blink b\" onclick=\"javascript:{show_layer('show_express','show_contact','exp_layer','con_layer');return false;}\">Accept</a> this member";
				$smarty->assign("EXPRESS_LAYER",$smarty->fetch("dp_express_interest_layer.htm"));
				$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
				$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
				//$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
				$smarty->assign("REVAMP_RIGHT_PANEL",$smarty->fetch("revamp_rightpanel.htm"));
				$smarty->assign("REVAMP_LEFT_PANEL",$smarty->fetch("revamp_leftpanel.htm"));
				$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
				$ser=$_SERVER['REQUEST_URI'];
				$ser=explode("-",$ser);
				if($ser[0]=='/profile/matrimonial')
					//$smarty->assign("SHOW_CAN","NO"); 
				
				$profileid=$jprofile_result["viewed"]['PROFILEID'];
				$username=$jprofile_result["viewed"]['USERNAME'];

				if($profileid)		
					$stat_uname=stat_name($profileid,$username);
		                $CAN_URL="$SITE_URL/profile/matrimonial-$stat_uname.htm";

				$smarty->assign("CAN_URL",$CAN_URL);
				if($isMobile)
				{
					//Small labels are to be used for wap site
					switch($smarty->_tpl_vars['TAB_NAME']){
					case 'Send Reminder':
						$smarty->assign("TO_DO","reminder");
						break;
					case 'Express Interest':
						$smarty->assign("TO_DO","eoi");
						break;
					case 'Respond':
						$smarty->assign("TO_DO","respond");
						break;
					case 'Write Message':
						$smarty->assign("TO_DO","message");
						break;
					}
					$smarty->display("mobilejs/jsmb_view_profile.html");
				}
				else
			        $smarty->display("view_profile.htm");
	
				//$smarty->display("profile_preview.htm");
			}

		}

	
function setNudgeLogread($msgid)
{
	global $data;
        if(($msgid || $msgid==0))
        {       
                $sql="select FOLDERID from jsadmin.OFFLINE_NUDGE_LOG where RECEIVER_STATUS='U' and ID='$msgid'  and RECEIVER='".$data['PROFILEID']."'";
                $res_check_status=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_init,"ShowErrTemplate");
                if($row_check_status=mysql_fetch_array($res_check_status))
                        {
                                if($row_check_status['FOLDERID']==0)
                                {
        
                                                $sql_contact="Update CONTACTS_STATUS set NEW_MES=NEW_MES-1 where PROFILEID='".$data['PROFILEID']."'";
        
                                                mysql_query_decide($sql_contact) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_contact,"ShowErrTemplate");
                                }
                        

                        $sql_rec_status="update jsadmin.OFFLINE_NUDGE_LOG SET RECEIVER_STATUS='R',SENDER_STATUS='R' WHERE ID='$msgid'";
                        mysql_query_decide($sql_rec_status) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_rec_status,"ShowErrTemplate");;
			}
                
        }
} 
	// function to show error message if profile does not exist or is hidden or is not activated
	function showProfileError_DP($hidden="",$privacy="") 
	{
		global $checksum,$smarty,$prev;
		global $jprofile_result;


		$gender=$jprofile_result['viewed']['GENDER'];
		$username=$jprofile_result['viewed']['USERNAME'];
		if($gender=='M')
			$his_her="his";
		else
			$his_her="her";

		if($hidden=="N" || $hidden=="U" || $hidden=="P")
			$smarty->assign("MESSAGE","This profile is currently being Screened. Kindly view this profile after 24 hours");
		elseif($hidden=="H")
			$smarty->assign("MESSAGE","This profile is currently hidden. Please check after a couple of weeks");
		elseif($hidden=="D")
			$smarty->assign("MESSAGE","The profile $username was deleted");
		
		if($privacy=="F")
			$smarty->assign("MESSAGE","Sorry, you cannot view this profile as you have been FILTERED");
		elseif($privacy=="C")
			$smarty->assign("MESSAGE","Sorry, you cannot view this profile as you have not been contacted by this person");
		elseif($privacy=="S")
			$smarty->assign("MESSAGE","Sorry. You cannot view the detailed profile of this user as $his_her privacy  options prevent you from doing so.");
		/*elseif($muslim_check=="M")
			$smarty->assign("MESSAGE","Sorry, you cannot view a non muslim profile"); 
		elseif($muslim_check=="H")
                        $smarty->assign("MESSAGE","Sorry, you cannot view a muslim profile of foreign origin");*/

		if($mbureau=="bureau1")
                {
                        $smarty->assign("pid",$pid);
                        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
                        $smarty->assign("MBHEAD",$smarty->fetch("top_band.htm"));
                        $smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
                        $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
                        $smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));
                }
		global $gtalk_mailer;
		if($gtalk_mailer)
		{
			if($checksum=='')
			{
				$smarty->assign("login_mes","Login to continue");
                        	Timedout();
				exit;
			}
		}
                else
		{
			//for filter case
			global $PERSON_LOGGED_IN,$data,$profileid,$samegender;
			if($PERSON_LOGGED_IN && $data["PROFILEID"]!=$profileid && $samegender!=1)
			{
				if($data && $profileid)
				{
					$mypid=$data['PROFILEID'];
					include("alter_seen_table.php");
				}
			}
			//for filter case

			no_profile($hidden.$privacy);
			exit;
		}
	}
	
	// returns the comma separated labels of field values
	function get_partner_string_from_array($arr,$tablename)
	{
		global $lang;
		if(is_array($arr))
		{
			$str=implode("','",$arr);
			if(substr($str,-1)==",")
                        {
                                $wr_dt=print_r($_SERVER,true);
//                              send_email("nikhil.dhiman@jeevansathi.com","$wr_dt","adsfasd","info@jeevansathi.com");
                                $str=substr($str,0,strlen($str)-2);

                        }
			$sql="select SQL_CACHE distinct LABEL from $tablename where VALUE in ('$str')";
			$dropresult=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			while($droprow=mysql_fetch_array($dropresult))
			{
				$str1.=$droprow["LABEL"] . ", ";
			}
			
			mysql_free_result($dropresult);
			
			return substr($str1,0,-2);
		}
		elseif($lang=="hin") 
			return "मान्य नही";
		else
			return "   - ";
	}

	// returns the comma separated labels of field values
	/*function get_partner_string_from_array_hin($arr,$tablename)
	{
		if(is_array($arr))
		{
			global $db;
			@mysql_close($db);
			$db=connect_db2();

			mysql_query_optimizer("SET NAMES 'utf8'");

			$str=implode("','",$arr);
			$sql="select SQL_CACHE LABEL from $tablename where VALUE in ('$str')";
			$dropresult=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

			@mysql_close($db);
			$db=connect_db();
			
			while($droprow=mysql_fetch_array($dropresult))
			{
				$str1.=$droprow["LABEL"] . ", ";
			}
			
			mysql_free_result($dropresult);
			
			return substr($str1,0,-2);
		}
		else 
			return "मान्य नही";
	}*/


	function make_msg_read($msgid='',$contact_status='',$profileid='')
	{
		return 1;
		global $smarty,$data,$myDb,$mysqlObj;

		if($msgid)
		{	
			//Decrement the counter by 1 when user views his unread message
			$sql_check_status="select FOLDERID,SENDER from newjs.MESSAGE_LOG where ID='$msgid' and RECEIVER_STATUS='U' and RECEIVER='".$data['PROFILEID']."'";
			$res_check_status=$mysqlObj->executeQuery($sql_check_status,$myDb)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_check_status,"ShowErrTemplate");

			if($row_check_status=$mysqlObj->fetchArray($res_check_status))
			{
				$sender=$row_check_status['SENDER'];

				//Getting connection name on both sender and receiver side
				$myDbName1=getProfileDatabaseConnectionName($sender,'',$mysqlObj);
				$myDbName2=getProfileDatabaseConnectionName($data['PROFILEID'],'',$mysqlObj);
				if($myDbName1!=$myDbName2)
					$myDb1=$mysqlObj->connect("$myDbName1");

				if($row_check_status['FOLDERID']==0)
                                {

					$sql_contact="Update CONTACTS_STATUS set NEW_MES=NEW_MES-1 where PROFILEID='".$data['PROFILEID']."'";
					
					mysql_query_optimizer($sql_contact) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_contact,"ShowErrTemplate");
				}
			

			$sql_rec_status="update newjs.MESSAGE_LOG SET RECEIVER_STATUS='R',SENDER_STATUS='R' WHERE ID='$msgid'";
			$mysqlObj->executeQuery($sql_rec_status,$myDb)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_rec_status,"ShowErrTemplate");
			
			//if sender is in other shard.
                        if($myDb1)
                                $mysqlObj->executeQuery($sql_rec_status,$myDb1)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_rec_status,"ShowErrTemplate");

			}
		}	
		if($contact_status && $profileid)
		{
			$myDbName1=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
                        $myDbName2=getProfileDatabaseConnectionName($data['PROFILEID'],'',$mysqlObj);
                        if($myDbName1!=$myDbName2)
                                $myDb1=$mysqlObj->connect("$myDbName1");

			if($contact_status=='I' || $contact_status=='C')
				$sql="update newjs.MESSAGE_LOG SET RECEIVER_STATUS='R',SENDER_STATUS='R' WHERE RECEIVER='" . $data["PROFILEID"] . "' and SENDER='$profileid' AND TYPE='$contact_status' AND IS_MSG='N'";
			elseif($contact_status=='RA')
				$sql="update newjs.MESSAGE_LOG SET RECEIVER_STATUS='R',SENDER_STATUS='R' WHERE RECEIVER='" . $data["PROFILEID"] . "' and SENDER='$profileid' AND TYPE='A' AND IS_MSG='N'";
			elseif($contact_status=='RD')
				$sql="update newjs.MESSAGE_LOG SET RECEIVER_STATUS='R',SENDER_STATUS='R' WHERE RECEIVER='" . $data["PROFILEID"] . "' and SENDER='$profileid' AND TYPE='D' AND IS_MSG='N'";
		}
		if($sql)
		{
			$mysqlObj->executeQuery($sql,$myDb)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			//if sender is in other shard
                        if($myDb1)
                                $mysqlObj->executeQuery($sql,$myDb1)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}

	}

        function all_message_log($logged_pid,$profileid,$contact_status="")
        {
                global $db,$smarty,$myDb,$mysqlObj;
                $flag=0;
                 $sql = "select ID,SENDER from MESSAGE_LOG where RECEIVER='$logged_pid' and SENDER='$profileid' AND IS_MSG='Y' AND OBSCENE='N' UNION select ID,SENDER from MESSAGE_LOG where SENDER='$logged_pid' and RECEIVER='$profileid' AND IS_MSG='Y' AND OBSCENE='N' ORDER BY ID DESC LIMIT 10";
                $result=$mysqlObj->executeQuery($sql,$myDb)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                while($myrow=$mysqlObj->fetchArray($result))
                {
                        $ids=$myrow["ID"];
                        $id_array[]=$ids;
                        if($myrow["SENDER"]==$logged_pid)
                                $sender_or_rec[]=1;
                        else
                                $sender_or_rec[]=2;
                }

                if(is_array($id_array))
                {
                        $ids_str=implode(',',$id_array);
                        $k=1;
                        $sql="select MESSAGE,ID from MESSAGES WHERE ID in ($ids_str) ORDER BY ID DESC";
                        $result=$mysqlObj->executeQuery($sql,$myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        while($myrow=$mysqlObj->fetchArray($result))
                        {
                                if(!$flag)
                                {
                                        make_msg_read($myrow["ID"],$contact_status,$profileid);
                                        $flag=1;
                                        $message=nl2br($myrow['MESSAGE']);
                                }
                                else
                                {
                                        $this_message=nl2br($myrow['MESSAGE']);
                                        $jj=140;
		
                                        if(strlen($this_message)>140)
                                        {
                                                $layer_required=1;
						$complete_message[$k]=$this_message;
                                                $flag_1=0;
                                                if(substr($this_message,140,1)!=' ')
                                                {
                                                        while(!$flag_1)
                                                        {
                                                                if(substr($this_message,$jj,1)==' ')
                                                                        $flag_1=1;
                                                                else
                                                                        $jj--;

                                                                if($jj<130)
								{
                                                                        $jj=140;
									$flag_1=1;
								}
                                                        }
                                                }
                                        }
                                        $all_message[$k]=substr($this_message,0,$jj);
                                        $k++;
                                }
                        }
                }
		$smarty->assign("layer_required",$layer_required);
                $smarty->assign("all_message",$all_message);
                $smarty->assign("count_all_msg",count($all_message));
                $smarty->assign("complete_message",$complete_message);
                $smarty->assign("sender_or_rec",$sender_or_rec);
                return($message);
        }
function no_profile($which)
{
        global $smarty;
	global $jprofile_result,$isMobile;
        if(strtolower($which)=='login')
        {
		$redirect_url=urlencode($_SERVER['REQUEST_URI']);
		//$location="$SITE_URL/profile/registration_new.php?source=js_block&view_username=".$jprofile_result["viewed"]["USERNAME"]."&redirect_url=$redirect_url";
		//header("Location:$location");
		//die;
                $smarty->assign("LOGIN_REQUIRED",1);
        }
        if($which=="")
        {
		$smarty->assign("NO_PROFILE","Search by profile Id");
                $smarty->assign("MESSAGE","Sorry, the profile you requested was not found.");
        }
	$smarty->assign("PROFILENAME",$jprofile_result["viewed"]["USERNAME"]);
	
	if($_GET['CAME_FROM_CONTACT_MAIL'])
	{
		$smarty->assign("pr",1);
		$smarty->assign("LOGIN_REQUIRED",0);
		$smarty->assign("MESSAGE",'Please login to continue.<BR><a class="thickbox" href="login.php?SHOW_LOGIN_WINDOW=1">Click here</a> to login Now</div>');
	}
	if($isMobile){
		$smarty->assign("NO_PROFILE",1);
		$smarty->assign("HEADER",$smarty->fetch("mobilejs/jsmb_header.html"));
		$smarty->assign("FOOTER",$smarty->fetch("mobilejs/jsmb_footer.html"));
		$smarty->display("mobilejs/jsmb_view_profile.html");
		die;
	}
        //$smarty->assign("EXPRESS_LAYER",$smarty->fetch("dp_express_interest_layer.htm"));
        $smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
        $smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
        //$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
        $smarty->assign("REVAMP_RIGHT_PANEL",$smarty->fetch("revamp_rightpanel.htm"));
        //$smarty->assign("REVAMP_LEFT_PANEL",$smarty->fetch("revamp_leftpanel.htm"));
        $smarty->assign("FOOT",$smarty->fetch("footer.htm"));
        $smarty->display("no_profile.htm");
        die;
}

//added by lavesh to get next/previous profileid from search results
// offset order of profileid in search results.
//$flag='P' for previous
function next_prev_view_profileid($searchid,$Sort,$offset,$flag='',$stype='')
{
	global $_SERVER;
	mail('lavesh.rawat@jeevansathi.com,kumar.anand@jeevansathi.com','next_prev_view_profileid() called in profile/viewprofile.php',$_SERVER);
}
function color_code_dpp($viewer,$viewed)
{
	if($viewer!=$viewed && $viewer!="" && $viewed!="")
	{
		global $jpartnerObj;
		global $jpartnerObj_logged;
		global $mysqlObj;
		global $viewedDb;
		global $jprofile_result;
		//Sharding added by Lavesh     
		if($viewer)//profileid is viewed profileid
        	{
                	$viewerDbName=getProfileDatabaseConnectionName($viewer,'',$mysqlObj);
	                $viewerDb=$mysqlObj->connect("$viewerDbName");
        	} 

	        $jpartnerObj_logged->setPartnerDetails($viewer,$viewerDb,$mysqlObj);

        	if($jpartnerObj_logged->isPartnerProfileExist($viewerDb,$mysqlObj,$viewer) )
		{
			code_values($jprofile_result['viewed'],$jpartnerObj_logged,'on_profile');
			
	
		}
		if($jpartnerObj->isPartnerProfileExist($viewedDb,$mysqlObj,$viewed))
		{
			code_values($jprofile_result['viewer'],$jpartnerObj,'on_dpp');
		}
	}
	
}
function code_values($profile_arr,$jpartnerObj,$onwhat)
{
	global $smarty;

	if($profile_arr['AGE']>=$jpartnerObj->getLAGE() && $profile_arr['AGE']<=$jpartnerObj->getHAGE())
	{
			$CODE['AGE']='yes';
	}
	if($profile_arr['HEIGHT']>=$jpartnerObj->getLHEIGHT() && $profile_arr['HEIGHT']<=$jpartnerObj->getHHEIGHT())
        {
                        $CODE['HEIGHT']='yes';
        }

	$value=$jpartnerObj->getCHILDREN();
	$CHILD=explode(",",$value);
	if(is_array($CHILD))
		if(in_array($profile_arr['HAVECHILD'],$CHILD))
		{
				$CODE['HAVECHILD']='yes';
		}
	$HANDI=explode(",",remove_quot($jpartnerObj->getHANDICAPPED()));
	if(is_array($HANDI))
		if(in_array($profile_arr['HANDICAPPED'],$HANDI))
		{
				$CODE['HANDI']='yes';
		}
	$ARR=explode(",",remove_quot($jpartnerObj->getPARTNER_MANGLIK()));
	if(is_array($ARR))
	if(in_array($profile_arr['MANGLIK'],$ARR))
	{
                        $CODE['MANGLIK']='yes';
        }
	$ARR=explode(",",remove_quot($jpartnerObj->getPARTNER_BTYPE()));
	if(is_array($ARR))
	if(in_array($profile_arr['BTYPE'],$ARR))
        {
                        $CODE['BTYPE']='yes';
        }
	$ARR=explode(",",remove_quot($jpartnerObj->getPARTNER_COMP()));
	if(is_array($ARR))
	if(in_array($profile_arr['COMPLEXION'],$ARR))
	{
		$CODE['COMP']='yes';
	}
	$ARR=explode(",",remove_quot($jpartnerObj->getPARTNER_DIET()));
        if(is_array($ARR))
	if(in_array($profile_arr['DIET'],$ARR))
	{
		$CODE['DIET']='yes';
	}
	$ARR=explode(",",remove_quot($jpartnerObj->getPARTNER_DRINK()));
        if(is_array($ARR))
        if(in_array($profile_arr['DRINK'],$ARR))
	{
		$CODE['DRINK']='yes';
	}
	$ARR=explode(",",remove_quot($jpartnerObj->getPARTNER_MSTATUS()));
        if(is_array($ARR))
        if(in_array($profile_arr['MSTATUS'],$ARR))
	{
		$CODE['MSTATUS']='yes';
	}
	$ARR=explode(",",remove_quot($jpartnerObj->getPARTNER_RES_STATUS()));
        if(is_array($ARR))
        if(in_array($profile_arr['RES_STATUS'],$ARR))
        {
                $CODE['RES_STATUS']='yes';
        }
	$ARR=explode(",",remove_quot($jpartnerObj->getPARTNER_SMOKE()));
        if(is_array($ARR))
        if(in_array($profile_arr['SMOKE'],$ARR))
	{
		$CODE['SMOKE']='yes';
	}
	$caste=display_format($jpartnerObj->getPARTNER_CASTE());
	if($caste)
		$all_caste=get_all_caste($caste);
        if(is_array($all_caste))
        if(in_array($profile_arr['CASTE'],$all_caste))
        {
                $CODE['CASTE']='yes';
                $CODE['CASTE_1']='yes';
        }
	$ARR=explode(",",remove_quot($jpartnerObj->getPARTNER_RELIGION()));
        if(is_array($ARR))
	if(in_array($profile_arr['RELIGION'],$ARR))
	{
		$CODE['RELIGION']='yes';
		$CODE['RELIGION_1']='yes';
	}
	$ARR=explode(",",remove_quot($jpartnerObj->getPARTNER_ELEVEL()));
        if(is_array($ARR))
        if(in_array($profile_arr['EDU_LEVEL'],$ARR))
	{
		$CODE['ELEVEL']='yes';
                $CODE['ELEVEL_1']='yes';
	}
	$ARR=explode(",",remove_quot($jpartnerObj->getPARTNER_ELEVEL_NEW()));
        if(is_array($ARR))
        if(in_array($profile_arr['EDU_LEVEL_NEW'],$ARR))
        {
                $CODE['ELEVEL_NEW']='yes';
                $CODE['ELEVEL_NEW_1']='yes';
        }
	$ARR=explode(",",remove_quot($jpartnerObj->getPARTNER_MTONGUE()));
        if(is_array($ARR))
        if(in_array($profile_arr['MTONGUE'],$ARR))
        {
                $CODE['MTONGUE']='yes';
                $CODE['MTONGUE_1']='yes';
        }
	$ARR=explode(",",remove_quot($jpartnerObj->getPARTNER_OCC()));
        if(is_array($ARR))
        if(in_array($profile_arr['OCCUPATION'],$ARR))
        {
                $CODE['OCCUPATION']='yes';
                $CODE['OCCUPATION_1']='yes';
        }
	$ARR=explode(",",remove_quot($jpartnerObj->getPARTNER_COUNTRYRES()));
        if(is_array($ARR))
        if(in_array($profile_arr['COUNTRY_RES'],$ARR))
        {
                $CODE['COUNTRYRES']='yes';
        }
	$ARR=explode(",",remove_quot($jpartnerObj->getPARTNER_INCOME()));
        if(is_array($ARR))
        if(in_array($profile_arr['INCOME'],$ARR))
        {
                $CODE['INCOME']='yes';
        }
	$ARR=explode(",",remove_quot($jpartnerObj->getPARTNER_CITYRES()));
        if(is_array($ARR))
        if(in_array($profile_arr['CITY_RES'],$ARR))
        {
                $CODE['CITYRES']='yes';
        }
	if($onwhat=='on_profile')
	{
		if(is_array($CODE))
		{
			foreach($CODE as $key=>$val)
				$ON_PROF["SET_".$key]=$val;
			$smarty->assign("ON_PROF",$ON_PROF);
		}
		
	}
	elseif($onwhat=='on_dpp')
	{
		if(is_array($CODE))
                {
                        foreach($CODE as $key=>$val)
				$ON_DPP["SET_P_".$key]=$val;
			$smarty->assign("ON_DPP",$ON_DPP);

                }
	}

}
function ENCRYPT_DECRYPT($Str_Message) {
    $Len_Str_Message=STRLEN($Str_Message);
    $Str_Encrypted_Message="";
    FOR ($Position = 0;$Position<$Len_Str_Message;$Position++){
        $Key_To_Use = (($Len_Str_Message+$Position)+1); // (+5 or *3 or ^2)
        $Key_To_Use = (255+$Key_To_Use) % 255;
        $Byte_To_Be_Encrypted = SUBSTR($Str_Message, $Position, 1);
        $Ascii_Num_Byte_To_Encrypt = ORD($Byte_To_Be_Encrypted);
        $Xored_Byte = $Ascii_Num_Byte_To_Encrypt ^ $Key_To_Use;  //xor operation
        $Encrypted_Byte = CHR($Xored_Byte);
        $Str_Encrypted_Message .= $Encrypted_Byte;
    }
    RETURN $Str_Encrypted_Message;
} //end function 

function remove_quot($value)
{
	return str_replace("'","",$value);
}
function check_any_contact($logged_pid,$profileid,$type)
{
	
	if(!($logged_pid && $profileid))
		return 0;
	if($type!="")
		return 1;
	{
		$sql="select STATUS from jsadmin.OFFLINE_MATCHES where MATCH_ID='$logged_pid' and PROFILEID='$profileid'";
	        $res=mysql_query_decide($sql)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_init,"ShowErrTemplate");
        	if($row=mysql_fetch_array($res))
			return 1;
	}
	
	global $db,$smarty,$myDb,$mysqlObj;
	$sql="select count(*) as cnt from newjs.BOOKMARKS where BOOKMARKER=$logged_pid and BOOKMARKEE='$profileid'";
        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        if($myrow=mysql_fetch_row($result))
        {
		if($myrow[0]>0)
			return 1;
        }
	$sql="select count(*) as cnt from `HOROSCOPE_REQUEST` where PROFILEID_REQUEST_BY='$logged_pid' and PROFILEID='$profileid' UNION select count(*) as cnt from `HOROSCOPE_REQUEST` where PROFILEID_REQUEST_BY='$profileid' and PROFILEID='$logged_pid'";

         $result=$mysqlObj->executeQuery($sql,$myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        while($myrow=$mysqlObj->fetchArray($result))
	{
		if($myrow['cnt']>0)
			return 1;
	}
	$sql="select count(*) as cnt from `PHOTO_REQUEST` where PROFILEID_REQ_BY='$logged_pid' and PROFILEID='$profileid' UNION select count(*) as cnt from `PHOTO_REQUEST` where PROFILEID_REQ_BY='$profileid' and PROFILEID='$logged_pid'";

         $result=$mysqlObj->executeQuery($sql,$myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        while($myrow=$mysqlObj->fetchArray($result))
	{
		if($myrow['cnt']>0)
                        return 1;
	}
	$sql="select count(*) as cnt from userplane.CHAT_REQUESTS where SENDER='$logged_pid' and RECEIVER='$profileid' union select count(*) as cnt from userplane.CHAT_REQUESTS where SENDER='$profileid' and RECEIVER='$logged_pid' ";
        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        while($myrow=mysql_fetch_array($result))
	{
		if($myrow['cnt']>0)
			return 1;
	}
}
function delp($profileid,$case)
{
        if($case == 1)
        {
                $sql="update newjs.PICTURE, newjs.JPROFILE set newjs.PICTURE.ALBUMPHOTO2='', newjs.JPROFILE.PHOTODATE=NOW() where newjs.PICTURE.PROFILEID = newjs.JPROFILE.PROFILEID and newjs.JPROFILE.PROFILEID='$profileid' and newjs.JPROFILE.activatedKey=1";
                mysql_query_decide($sql) or die(mysql_error_js());

                $sql="update newjs.PICTURE_OLD set ALBUMPHOTO2='' where PROFILEID = '$profileid'";
                mysql_query_decide($sql) or die(mysql_error_js());

                $sql = "select count(*) as cnt from PICTURE_UPLOAD where PROFILEID = '$profileid' ";
                $res= mysql_query_decide($sql) or die(mysql_error_js());
                $row = mysql_fetch_array($res);
                if($row['cnt'] > 0)
                {
                        $sql="update newjs.PICTURE_UPLOAD set ALBUMPHOTO2='' where PROFILEID = '$profileid' ";
                        mysql_query_decide($sql) or die(mysql_error_js());
			update_photoVersion($profileid);
                }

                $sql = "select count(*) as cnt from PICTURE_DELETE where PROFILEID = '$profileid' ";
                $res = mysql_query_decide($sql) or die(mysql_error_js());
                $row = mysql_fetch_array($res);
                if($row['cnt'] > 0)
                {
                        $sql="UPDATE newjs.PICTURE_DELETE SET ALBUMPHOTO2='Y', DELETED='' where PROFILEID = '$profileid'";
                }
                else
                {
                        $sql="INSERT INTO newjs.PICTURE_DELETE (PROFILEID, ALBUMPHOTO2) VALUES ('$profileid', 'Y')";
                }
                mysql_query_decide($sql) or die(mysql_error_js());
        }
	elseif($case == 2)
        {
                $sql="update newjs.PICTURE, newjs.JPROFILE set newjs.PICTURE.ALBUMPHOTO1='', newjs.JPROFILE.PHOTODATE=NOW() where newjs.PICTURE.PROFILEID = newjs.JPROFILE.PROFILEID and newjs.JPROFILE.PROFILEID='$profileid'  and newjs.JPROFILE.activatedKey=1";
                mysql_query_decide($sql) or die(mysql_error_js());

                //Changes made by Amit Gupta to make entry in PICTURE_DELETE

                $sql="update newjs.PICTURE_OLD set ALBUMPHOTO1='' where PROFILEID = '$profileid'";
                mysql_query_decide($sql) or die(mysql_error_js());

                $sql = "select count(*) as cnt from PICTURE_UPLOAD where PROFILEID = '$profileid' ";
                $res= mysql_query_decide($sql) or die(mysql_error_js());
                $row = mysql_fetch_array($res);

                if($row['cnt'] > 0)
                {
                        $sql="update newjs.PICTURE_UPLOAD set ALBUMPHOTO1='' where PROFILEID = '$profileid' ";
                        mysql_query_decide($sql) or die(mysql_error_js());
			update_photoVersion($profileid);
                }
                $sql = "select count(*) as cnt from PICTURE_DELETE where PROFILEID = '$profileid' ";
                $res = mysql_query_decide($sql) or die(mysql_error_js());
                $row = mysql_fetch_array($res);
                if($row['cnt'] > 0)
                {
                        $sql="UPDATE newjs.PICTURE_DELETE SET ALBUMPHOTO1='Y', DELETED='' where PROFILEID = '$profileid'";
                }
                else
                {
                        $sql="INSERT INTO newjs.PICTURE_DELETE (PROFILEID, ALBUMPHOTO1) VALUES ('$profileid', 'Y')";
                }
                mysql_query_decide($sql) or die(mysql_error_js());
        }
	elseif($case == 3)
        {
                $sql="delete from newjs.PICTURE where PROFILEID='$profileid'";
                mysql_query_decide($sql) or die(mysql_error_js());
                $sql="update newjs.JPROFILE set PHOTOSCREEN='31',HAVEPHOTO='N',MOD_DT=NOW(),PHOTODATE=NOW(),PHOTO_DISPLAY='A' where PROFILEID='$profileid'  and activatedKey=1";
                mysql_query_decide($sql) or die(mysql_error_js());
                //Changes made by Amit Gupta to make entry in PICTURE_DELETE

                $sql="delete from newjs.PICTURE_OLD where PROFILEID='$profileid'";
                mysql_query_decide($sql) or die(mysql_error_js());

                $sql = "select count(*) as cnt from PICTURE_UPLOAD where PROFILEID = '$profileid' ";
                $res= mysql_query_decide($sql) or die(mysql_error_js());
                $row = mysql_fetch_array($res);
                if($row['cnt'] > 0)
                {
                        $sql="delete from newjs.PICTURE_UPLOAD where PROFILEID = '$profileid' ";
                        mysql_query_decide($sql) or die(mysql_error_js());
			update_photoVersion($profileid);
                }

                $sql = "select count(*) as cnt from PICTURE_DELETE where PROFILEID = '$profileid' ";
                $res = mysql_query_decide($sql) or die(mysql_error_js());
                $row = mysql_fetch_array($res);
                if($row['cnt'] > 0)
                {
                        $sql="UPDATE newjs.PICTURE_DELETE SET MAINPHOTO='Y', ALBUMPHOTO1='Y', ALBUMPHOTO2='Y', DELETED='' WHERE PROFILEID='$profileid'";
                }
                else
                {
                        $sql="INSERT INTO newjs.PICTURE_DELETE (PROFILEID, MAINPHOTO, ALBUMPHOTO1, ALBUMPHOTO2) VALUES ('$profileid', 'Y', 'Y', 'Y')";
                }
 		mysql_query_decide($sql) or die(mysql_error_js());
        }
}
function values_retained($pid)
{
	$sql1 = "REPLACE INTO PICTURE_FOR_SCREEN (PROFILEID,MAINPHOTO,ALBUMPHOTO1,ALBUMPHOTO2,THUMBNAIL,PROFILEPHOTO,UPLOADED) select PROFILEID,MAINPHOTO,ALBUMPHOTO1,ALBUMPHOTO2,THUMBNAIL,PROFILEPHOTO,UPLOADED from PHOTO_DATA_RETAIN where PROFILEID='$pid'";
        mysql_query_decide($sql1) or die(mysql_error_js());

        $sql_sel2 = "select PHOTODATE,PHOTOSCREEN,HAVEPHOTO,MOD_DT from PHOTO_DATA_RETAIN where PROFILEID=$pid";
        $result_sel2 = mysql_query_decide($sql_sel2) or die(mysql_error_js());
        if(mysql_num_rows($result_sel2) > 0)
        {
                $ro_sel2 = mysql_fetch_array($result_sel2);
                $photodate = $ro_sel2["PHOTODATE"];
                $photoscreen = $ro_sel2["PHOTOSCREEN"];
                $havephoto = $ro_sel2["HAVEPHOTO"];
                $mod_dt = $ro_sel2["MOD_DT"];
        }
        $sql2 = "UPDATE JPROFILE SET PHOTODATE='$photodate',PHOTOSCREEN='$photoscreen',HAVEPHOTO='$havephoto',MOD_DT='$mod_dt' where PROFILEID='$pid' and  activatedKey=1";
        mysql_query_decide($sql2) or die(mysql_error_js());

	$sql3 = "DELETE from PHOTO_DATA_RETAIN where PROFILEID=$pid";
        mysql_query_decide($sql3) or die(mysql_error_js());
}
function pixelcode($VAR)
{        
      if($VAR)
      {       
	     $sql="SELECT PIXELCODE FROM MIS.PIXELCODE WHERE GROUPNAME='$VAR'";
	     $res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	     $row=mysql_fetch_array($res);
	     return $row["PIXELCODE"];
      }       
}  
// flush the buffer
if($zipIt && !$dont_zip_now)
	ob_end_flush();
	

?>
