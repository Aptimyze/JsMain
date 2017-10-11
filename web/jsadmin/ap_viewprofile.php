<?php

/******************    Include Files  ********************/
$flag_using_php5=1;
include_once("time.php");
include_once("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include("../profile/arrays.php");
include_once("../profile/functions.inc");
include_once("../profile/manglik.php");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once("../jsadmin/display_common.php");
include_once("ap_common.php");
include_once("ap_functions.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
/*************   Include Files Ends  ****************/

if(!$PRINT_LIST){
if(!authenticated($cid))
{
        $msg="Your session has been timed out<br><br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
        exit;
}}

	$PERSON_LOGGED_IN=1;
	$VIEWPROFILE_IMAGE_URL="http://ser2.jeevansathi.com/profile";
	global $listMainArray;	
	$smarty->assign("cid",$cid);

	/* Section to get role of the logged in user
	*  Roles defines as -> SE,QA,DISPATCHER,TELECALLER	
	*/
	//$role = fetchRole($cid);
	//$smarty->assign("ROLE",$role);
	/* Ends */

        /* Get the profileid which is viewed and the corresponding matchid 
	 * GET parameters: profileid,matchid
	*/
	if($PRINT_LIST=='1'){
		$profileid =$profileid1;
		$matchid =$matchid1;
		$list =$list1;
		$lead =$lead1;
	}
	else{
                $name = getname($cid);
                $smarty->assign("name",$name);
                $role = fetchRole($cid);
                $smarty->assign("ROLE",$role);

        	if($_GET['profileid'])
        	        $profileid= $_GET['profileid']; 
		if($_GET['matchid'])
			$matchid= $_GET['matchid'];
		if($_GET["list"])
			$list =$_GET['list'];
	}
	/* Ends */

	// Check for the lead profile 
	if($lead =='1'){
		$lead_id =$profileid;
		if(get_UserProfileid($lead_id))
			$profileid =get_UserProfileid($lead_id);
	}
	
	if($list=='MYPROFILE')
	{
		if($profileid && $matchid=='')
			$matchid =$profileid;
	}	

        // show previus & next link in detailed profile page for user list
        if($user_list)
        {
                if(!$total_rec){
                        $total_rec="1";
                }
                // GET parameters:j, total_rec, offset, actual_offset, user_list
                $get_profileid =get_prev_next_profile($j,$offset,$actual_offset,$total_rec,$show_profile,$user_list,$name,$profileid,$matchid,$cid);
		if($get_profileid){
			$profileidArr =explode("#",$get_profileid);
			$profileid=$profileidArr[0];
			if($profileidArr[1]){
				$lead_id =$profileid;
		                if(get_UserProfileid($lead_id))
        		                $profileid =get_UserProfileid($lead_id);
				$lead ='1';
			}
		}
        }
	// Ends previus & next link

	// Check for the lead profile and get the lead profile details	
	if($lead =='1')
	{
		leadContactDetails($lead_id);
                $leadData_arr =leadDetails($lead_id);
		$file_id =$leadData_arr['file_id'];    
                $source =$leadData_arr['SOURCE'];
                $source_name =$leadData_arr['SOURCE_NAME'];
                $edition_date =$leadData_arr['EDITION_DT'];
                $assistant =$leadData_arr['ASSISTANT'];
                $filename =$leadData_arr['FILENAME'];
                if($edition_date){
                        $dateArr        =explode("-",$edition_date);
                        $dateTimestamp  = mktime(0,0,0,$dateArr[1],$dateArr[2],$dateArr["0"]);
                        $edition_date   = date("dS M Y",$dateTimestamp);
                }
                $smarty->assign("source",$source);
                $smarty->assign("source_name",$source_name);
                $smarty->assign("edition_date",$edition_date);
                $smarty->assign("assistant",$assistant);
                $smarty->assign("filename",$filename);
                $smarty->assign("lead_id",$lead_id);
		$smarty->assign("file_id",$file_id);

		$leadArr =array($lead_id);
		$resultSet =get_lead_details_all('',$leadArr);
		if($resultSet){
			$name_l =$resultSet[$lead_id]['NAME'];
			$age =$resultSet[$lead_id]['AGE']." years";
			$gender =$resultSet[$lead_id]['GENDER'];
			$height =$resultSet[$lead_id]['HEIGHT'];
			$caste =$resultSet[$lead_id]['CASTE'];
			$religion =$resultSet[$lead_id]['RELIGION'];
			$mtongue =$resultSet[$lead_id]['MTONGUE'];
               		$mtongue_s =$resultSet[$lead_id]['MTONGUE_S'];
               		$occupation =$resultSet[$lead_id]['OCCUPATION'];
               		$residence =$resultSet[$lead_id]['RESIDENCE'];
               		$income =$resultSet[$lead_id]['INCOME'];
               		$education =$resultSet[$lead_id]['EDUCATION'];
			$mstatus =$resultSet[$lead_id]['MSTATUS_VAL'];
			$relation =$resultSet[$lead_id]['RELATION'];
		}
		if($name_l)
        		$smarty->assign("PROFILENAME",$name_l);
		else
			$smarty->assign("PROFILENAME",$lead_id);
		$smarty->assign("MSTATUS",$MSTATUS[$mstatus]);
		$smarty->assign("RELATION",$RELATIONSHIP[$relation]);
		$smarty->assign("AGE", $age);
		$smarty->assign("HEIGHT",$height);	
		$smarty->assign("PROFILEGENDER",$gender);
		$smarty->assign("RELIGION_SELF",$religion);
		$smarty->assign("CASTE",$caste);
		$smarty->assign("MTONGUE",$mtongue);
               	$smarty->assign("EDU_LEVEL_NEW",$education);
               	$smarty->assign("OCCUPATION",$occupation);
               	$smarty->assign("INCOME",$income);
               	$smarty->assign("CITY_RES",$residence);
                if($gender =='M')
                	$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/Request-a-photo-male.gif");
                else
                	$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/Request-a-photo-Female.gif");  

	}
	$smarty->assign("lead",$lead);
	// End lead profile details

	/* If TELECALLER logins, 
	* the profile from the queus gets selected in ASC order
	* profile gets selected from AP_QUEUE table	
	*/
	if($role=='TC'){
		if($callreqFlag)
			$callreq_pid =$profileid; 
		if($list=='PULL'){
			$profileRow = fetchNextProfile($role,$name,'','','',$callreq_pid,$qtype);
			$profileid =$profileRow["MATCH_ID"];
			$matchid =$profileid;
			if($profileid==''){
				echo "No profiles exist in the To Be Called(TBC) list";
				die;
			}
			$CONTACT_DETAILS=1;
		}
		elseif($list=='CALL'){
			$matchid =$profileid; 
			$CONTACT_DETAILS=1;
		}		
		else{
			$list='CALLERS';
		}
	}

	// Check added to show the 'EXTRA FORM DETAILS' link for the profile
	$ex_form_detail ="";
	if( ($role=='TC') && ($list =='PULL' || $list =='CALL') )
		$ex_form_detail ='add';
	elseif($user_list=='TBD' || $user_list=='TBC' || ($list=='TBD' && $PRINT_LIST) ){
		$form_exist =check_ex_form_detail($profileid);		
		if($form_exist)
			$ex_form_detail ='show';
	}
	$smarty->assign("ex_form_detail",$ex_form_detail);
	// Check Ends


	// Check to show the print action button on the profile
	if($role=='DIS' && $user_list=='TBD'){
		$PRINT_BUTTON ='1';
		$smarty->assign("PRINT_BUTTON",$PRINT_BUTTON);	
	}	

	// Profileid which is viewed 
	$viewed_profileid= $profileid;
	$viewer_profileid=$matchid;
	$smarty->assign("profileid",$profileid);
	$smarty->assign("matchid",$matchid);	
	$smarty->assign("list",$list);
	$smarty->assign("user_list",$user_list);

	// Checks the ownership of the viewed profile by the loggedIn User  
	$ownership = checkProfileOwnership($profileid,$name);
	$PERSON_OWNER ="";
	if($ownership || ($profileid ==$matchid)){
		$PERSON_OWNER =1;
		$viewer_profileid=$viewed_profileid;
	}

	// Check to show CONTACT or BASIC details
	$smarty->assign("BASIC_DETAILS",'1');
	if($PERSON_OWNER){
		$CONTACT_DETAILS=1;
	}

	// Get JPROFILE query result	
	if(!$lead)
		get_jprofile_query($viewer_profileid,$viewed_profileid);

	/****  Show Partner Profile Details for PRINT Section  ******/
	if($user_list =='FIL' || $user_list =='SL' || $user_list =='TBD' || $user_list =='DIS' || $user_list =='CALLERS')
		$partner=1;
	if($PRINT || $PRINT_LIST || $partner)
	{		
		if(!$lead){
			include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
        		include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
			include_once("ap_showPartnerProfile.php");
			showPartnerProfile($viewed_profileid);
			$smarty->assign("SHOW_PARTNER_PROFILE",'1');
		}
		if($PRINT || $PRINT_LIST){
			$smarty->assign("PRINT_LIST",$PRINT_LIST);
			$CONTACT_DETAILS =1;
		}
	}
	/***** Show Partner Profile Details Ends   *******************/

        // Get CONTACT DETAILS  
        if($CONTACT_DETAILS && !$lead){
                showContactDetails($viewer_profileid,$viewed_profileid);
		$smarty->assign("CONTACT_DETAILS","$CONTACT_DETAILS");
	}

	// Check the AD REQUEST status of the profile 
	if($role=='SE' && $list=='MYPROFILE'){
		$adRequest = getAdRequest_Status($profileid,$name);
		if($adRequest['AD_STATUS']=='' || $adRequest['AD_STATUS']=='DONE')
			$smarty->assign("ad_request",'1');
		elseif($adRequest['AD_STATUS']=='REQ' || $adRequest['AD_STATUS']=='REM')
			$smarty->assign("ad_reminder",'1');
	}

        // Check the Profile COMMENTS 
	// get the profile COMMENTS if already exists for a Proifle
	if( (($role=='SE' || $role=='DIS') && ($user_list=='TBD')) || ($PRINT_LIST) ){
		$comments=1;
		$profileid_comments =$matchid;
		$matchid_comments =$profileid;
		$commentsArr = getProfileComments($profileid_comments,$matchid_comments);
	}
	elseif($role=='TC' && $user_list=='CALLERS'){
		$comments=1;
		$profileid_comments =$profileid;
		$matchid_comments =$matchid;
		$commentsArr = getProfileComments($profileid_comments,$matchid_comments);
	}
	elseif($role=='TC' && ($list=='CALL' || $list=='PULL')){
		$comments=1;
		$comment_TBC=1;
		$profileid_comments =$profileid;
		$matchid_comments =$matchid;
		$commentsArr = getProfileComments($profileid_comments,$matchid_comments);
	}
	if($comments=='1'){
		if( $profileid && $matchid && CallHistoryStatusCheck($profileid,$matchid,"Y"))
			$smarty->assign("CALL_CLOSED",1);
		if($commentsArr['PROFILEID']){
                	$smarty->assign("comments_display","1");
                	$comments =$commentsArr['COMMENTS'];
			$comment_date =datetime_format($commentsArr['ADDED_ON']);
                	$smarty->assign("comments","$comments");
                	$smarty->assign("comment_date",$comment_date);
		}
		else{
			$smarty->assign("comments_add","1");
			$smarty->assign("comments","");
			$smarty->assign("comment_date","");
		}
		$smarty->assign("profileid_comments",$profileid_comments);
		$smarty->assign("matchid_comments",$matchid_comments);
		$smarty->assign("comment_TBC",$comment_TBC);
        }
	// COMMENTS section ends

	// fetch the left panel links 
        $profileArray=array($matchid);
        $countArray=getNumberInList($profileArray,$listMainArray);
	
	if($list)
		$listSel =$list;
	else
		$listSel =$user_list;

	/* function fetchLeftPanelLinks() called to display the left panel links in the detailed profile page	
	 * $displayleftPanel = fetchLeftPanelLinks($role,$cid,$profileid,'',$list,$countArray,$matchid);
	*/
	$displayleftPanel = fetchLeftPanelLinks($role,$cid,$matchid,$new,$listSel,$countArray,$callreq_pid);
	$smarty->assign("leftpanel","$displayleftPanel");

	// To show 'Back to' link on the top of page
	$link_page ="ap_list.php?cid=$cid&profileid=$matchid&list=$listSel";	 
	if($user_list=='SL'){
		$search_param ="";
		$link_search ="";
		if($setSearch || $categorySearch)	// condition when search is used
		{	
			$link_text ="<< Show all Shortlisted or ";
			$search_param ="&j=$j&setSearch=$setSearch&setDate=$setDate&categorySearch=$categorySearch&caste=$caste&lage=$lage&hage=$hage&mtongue=$mtongue&city_Res=$city_Res&mstatus=$mstatus&havephoto=$havephoto&match_type=$match_type";	
			$search_page =$link_page.$search_param;
			$link_search ="<a href='$search_page' class='blink'><< Go back to Search Results</a>";
		}
		else
			$link_text ="Go to Shortlisted";	// without search	
	}
	elseif($user_list =='FL')
		$link_text ="Back to, filtered";
	elseif($user_list =='TBD')
		$link_text ="Back to, to be dispatched";
	elseif($user_list =='TBC')
		$link_text ="Back to, to be called";
	elseif($user_list =='DIS')
		$link_text ="Back to, dispatched";
	else
		$link_text ="Go Back";			
	$smarty->assign("link_page",$link_page);
	$smarty->assign("link_text",$link_text);
	$smarty->assign("link_search",$link_search);
	$smarty->assign("search_param",$search_param);	
	// Ends to show 'Back to' link


if(!$lead)
{
/****************Below code is to display links in contactgrid for astro services************/
	if($jprofile_result["viewed"]['BTIME'] == ":" || !$jprofile_result["viewed"]['CITY_BIRTH'] || !$jprofile_result["viewed"]['COUNTRY_BIRTH'])
	{
			$smarty->assign("REQUESTKUNDALI","Y");
	}
	else
	{
			$smarty->assign("KUNDALI","Y");
	}

/*************************** Contact Details Shown   *********************************************/

	//Contact Details of the person to be shown if he has taken membership to show his conatct details 
        // Field 'D' in SUBCSCIPTION field tells that he has taken the membership
        //if(strstr($jprofile_result["viewed"]['SUBSCRIPTION'],"D") && !strstr($jprofile_result["viewed"]['SUBSCRIPTION'],"S"))
	if(strstr($jprofile_result["viewed"]['SUBSCRIPTION'],"D") && strstr($jprofile_result["viewed"]['SUBSCRIPTION'],"S"))
        	$smarty->assign("ECLASSIFIED_MEM_HIDDEN","yes");
	else
        	$smarty->assign("ECLASSIFIED_MEM_HIDDEN","no");

        if(strstr($jprofile_result["viewed"]['SUBSCRIPTION'],"D"))
        {
		if($PERSON_LOGGED_IN)
		{
			$smarty->assign("ECLASSIFIED_MEM","Y");
	                $smarty->assign("CONTACTDETAILS","1");
			$smarty->assign("HISEMAIL","****<br>Please login to view contact details");
			$smarty->assign("NOT_LOGGED_IN_EC","Y");
        	        $CONTACTDETAILS=1;
		}
        }

/*******************************************************************************************************************/
	
	/******************************************************
	check for photographs starts here
	******************************************************/
	// if main photograph is there and is screened
	if($jprofile_result["viewed"]["HAVEPHOTO"]=="Y")
	{
		//Symfony Photo Modification - start
		$screenedMainPhoto = SymfonyPictureFunctions::haveScreenedMainPhoto($profileid);
		if($screenedMainPhoto=='Y')
			$main_photo_is_screened = 1;
		if($main_photo_is_screened)
		{
			$album = SymfonyPictureFunctions::getAlbum($profileid,1);
			$smarty->assign("PHOTOFILE",$album['profile']);
		//Symfony Photo Modification - end
		}
		else 
		{
			if($jprofile_result["viewed"]["GENDER"]=="M")
				$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/photocomming_b.gif");
			else
				 $smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/photocomming_g.gif");
		}
	}
	// main photo is being screened
	elseif($jprofile_result["viewed"]["HAVEPHOTO"]=="U" || $jprofile_result["viewed"]["HAVEPHOTO"]=="E")
	{
		if($PERSON_OWNER)
			$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/no_photo.gif");
		elseif($jprofile_result["viewed"]["GENDER"]=="M")
                	$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/photocomming_b.gif");
                else
                        $smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/photocomming_g.gif");
	}
	else{
		if($jprofile_result["viewed"]["GENDER"]=="M")	
			$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/Request-a-photo-male.gif");
		else
			$smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/Request-a-photo-Female.gif");
	}	
	/******************************************************
	check for photographs ends here
	******************************************************/

        $contactperson=$jprofile_result["viewed"]["USERNAME"];
        $smarty->assign("PROFILENAME",$jprofile_result["viewed"]["USERNAME"]);
	$smarty->assign("GENDER",$jprofile_result["viewed"]["GENDER"]);
	$height=$jprofile_result["viewed"]["HEIGHT"];
	$height1=explode("(",$HEIGHT_DROP["$height"]);
	$smarty->assign("HEIGHT",$height1[0]);
	$smarty->assign("PHEIGHT",$height1[0]);
	$smarty->assign("AGE",$jprofile_result["viewed"]["AGE"] . " years");

        //code added by nikhil dhiman on 25 May 2007 For Setting Manglik Status
	$return_data=manglik($profileid,'viewed');  
	$manglik_data=explode("+",$return_data);
	$smarty->assign("Own_Manglik_Status",$manglik_data[0]);
	$smarty->assign("Own_Manglik","Manglik");

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
	
		$caste=$jprofile_result["viewed"]["CASTE"];
		$caste=$CASTE_DROP["$caste"];
	
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
	
		$occupation=$jprofile_result["viewed"]["OCCUPATION"];
		$country_birth=$jprofile_result["viewed"]["COUNTRY_BIRTH"];
		$country_res=$jprofile_result["viewed"]["COUNTRY_RES"];

		$occupation=$OCCUPATION_DROP["$occupation"];
		$country_birth=$COUNTRY_DROP["$country_birth"];
		$country_res=$COUNTRY_DROP["$country_res"];
	
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

		if($family_values=="")
			$smarty->assign("FAMILY_VALUES","-");
		else
			$smarty->assign("FAMILY_VALUES",$FAMILY_VALUES[$family_values]);
		
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
		
	if($jprofile_result["viewed"]["CITY_RES"]!="")
        {
                $city_res_val = $jprofile_result["viewed"]["CITY_RES"];
                $sql_ci = "SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$city_res_val'";
                $res_ci = mysql_query_decide($sql_ci);
                $row_ci = mysql_fetch_array($res_ci);
                $city_res = $row_ci['LABEL'];
        }
	//added to show country from astro details table if the user opts to show horoscope.
	
	if($jprofile_result["viewed"]['SHOW_HOROSCOPE']=='Y' || $PERSON_OWNER)
	{
		$sql_horo = "SELECT COUNTRY_BIRTH,CITY_BIRTH FROM newjs.ASTRO_DETAILS WHERE PROFILEID='$profileid'";
		$res_horo = mysql_query_decide($sql_horo);
		$row_horo = mysql_fetch_array($res_horo);
		$astro_city_birth = $row_horo['CITY_BIRTH'];
		$smarty->assign("COUNTRY_BIRTH",$row_horo['COUNTRY_BIRTH']);
	}
	//end of to show country from astro details table if the user opts to show horoscope.
	else{
		$smarty->assign("COUNTRY_BIRTH",$country_birth);
		$astro_city_birth ="";
	}
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
        if(strlen($emailid)>21)
        {
                $email_id=explode("@",$emailid);
                $emailid=$email_id[0]."<br>@".$email_id[1];
        }
        $CITIZENSHIP=display_format_new($jprofile_result["viewed"]["CITIZENSHIP"]);
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
	else{
		$smarty->assign("BTIMEHOUR","");
		$smarty->assign("BTIMEMIN","");
	}

	$smarty->assign("CITYBIRTH","-"); 
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
		elseif($PERSON_OWNER) 
			$smarty->assign("CITYBIRTH",ucwords($jprofile_result["viewed"]["CITY_BIRTH"]) . "<br>" . $SCREENING_MESSAGE_SELF);
		else 
			$smarty->assign("CITYBIRTH",$SCREENING_MESSAGE);
	}
		
	if($jprofile_result["viewed"]["SUBCASTE"]=="")
		$smarty->assign("SUBCASTE","-");
	elseif(isFlagSet("SUBCASTE",$jprofile_result["viewed"]["SCREENING"]))
		$smarty->assign("SUBCASTE",$jprofile_result["viewed"]["SUBCASTE"]);
	elseif($PERSON_OWNER) 
		$smarty->assign("SUBCASTE",$jprofile_result["viewed"]["SUBCASTE"] . "<br>" . $SCREENING_MESSAGE_SELF);
	else 
		$smarty->assign("SUBCASTE",$SCREENING_MESSAGE);

        $yourinfo1 =array();
        $subyourinfo ="";
        $yourinfo ="";
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
	elseif($PERSON_OWNER)
	{
		if(trim($jprofile_result["viewed"]["YOURINFO"]))
                {
			$yourinfo1=trim($jprofile_result["viewed"]["YOURINFO"]);
                	$yourinfo = $yourinfo1 . "<br>" . $SCREENING_MESSAGE_SELF;
			$infolen=strlen($yourinfo1);
                	$smarty->assign("INFOLEN",$infolen);
		}
	}
	
	$jobinfo ="";
	if(isFlagSet("JOB_INFO",$jprofile_result["viewed"]["SCREENING"]))
	{
		if(trim($jprofile_result["viewed"]["JOB_INFO"]))
			$jobinfo =$jprofile_result["viewed"]["JOB_INFO"];
	}
	elseif($PERSON_OWNER)
	{
		if(trim($jprofile_result["viewed"]["JOB_INFO"]))
			$jobinfo =$jprofile_result["viewed"]["JOB_INFO"] . "<br>" . $SCREENING_MESSAGE_SELF;
	}
	$spouseinfo ="";
	if(isFlagSet("SPOUSE",$jprofile_result["viewed"]["SCREENING"]))
        {
		if(trim($jprofile_result["viewed"]["SPOUSE"]))
			$spouseinfo =$jprofile_result["viewed"]["SPOUSE"];
	}
	elseif($PERSON_OWNER)
	{
		if(trim($jprofile_result["viewed"]["SPOUSE"]))
	                $spouseinfo =$jprofile_result["viewed"]["SPOUSE"] . "<br>" . $SCREENING_MESSAGE_SELF;
	}
	$smarty->assign("YOURINFO",nl2br($yourinfo));
	$smarty->assign("JOBINFO",nl2br($jobinfo));
	$smarty->assign("SPOUSEINFO",nl2br($spouseinfo));
	$scn_msg=0;
	$familyinfo ="";
	if(isFlagSet("FAMILYINFO",$jprofile_result["viewed"]["SCREENING"]))
	{
		if(trim($jprofile_result["viewed"]["FAMILYINFO"]))
			$familyinfo=$jprofile_result["viewed"]["FAMILYINFO"];
	}
	elseif($PERSON_OWNER)
	{
		if(trim($jprofile_result["viewed"]["FAMILYINFO"]))
		{
                        $familyinfo=$jprofile_result["viewed"]["FAMILYINFO"];
			$scn_msg=1;
		}
	}
	if($scn_msg)
		$smarty->assign("FAMILYINFO",nl2br($familyinfo) . "<br>" . $SCREENING_MESSAGE_SELF);
	else
		$smarty->assign("FAMILYINFO",nl2br($familyinfo));

	if($jprofile_result["viewed"]["RELIGION"]!=3)
	{
		if($jprofile_result["viewed"]["GOTHRA"]=="")
			$smarty->assign("GOTHRA","-");
		elseif(isFlagSet("GOTHRA",$jprofile_result["viewed"]["SCREENING"]))
			$smarty->assign("GOTHRA",$jprofile_result["viewed"]["GOTHRA"]);
		elseif($PERSON_OWNER) 
			$smarty->assign("GOTHRA",$jprofile_result["viewed"]["GOTHRA"] . "<br>" . $SCREENING_MESSAGE_SELF);
		else 
			$smarty->assign("GOTHRA",$SCREENING_MESSAGE);
	}
	
		
	$smarty->assign("NAKSHATRA",$jprofile_result["viewed"]["NAKSHATRA"]);

	$scn_msg1=0;
	$eduinfo ="";
	if(isFlagSet("EDUCATION",$jprofile_result["viewed"]["SCREENING"]))
        {
                if(trim($jprofile_result["viewed"]["EDUCATION"]))
                        $eduinfo=$jprofile_result["viewed"]["EDUCATION"];
        }
        elseif($PERSON_OWNER && !$search)
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

	//commented on 29th jan 2006 by shiv
	$sql="select SQL_CACHE PROFILEID from newjs.HIDE_DOB where PROFILEID='$profileid'";
	$hideresult=mysql_query_decide($sql);
	if($hideresult && mysql_num_rows($hideresult)<=0)
	{
		$dob=explode("-",$jprofile_result["viewed"]["DTOFBIRTH"]);
		$smarty->assign("DTOFBIRTH",my_format_date($dob[2],$dob[1],$dob[0]));
		$smarty->assign("DTOFBIRTH_BI",my_format_date($dob[2],$dob[1],$dob[0],2));	
		unset($dob);
	}
	else{
		$smarty->assign("DTOFBIRTH","-");
		$smarty->assign("DTOFBIRTH_BI","-");
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
	
	$sql="select * from newjs.JHOBBY where PROFILEID='$profileid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	
	$HOBBIES_ARR =array();
	$HOBBY =array();
	$INTEREST =array();
	$MUSIC =array();
	$BOOK=array();
	$SPORTS= array();
	$CUISINE =array();
	$DRESS =array();
	$LANGUAGE =array();
	$MOVIE =array();
	$smarty->assign("MOVIE","-");
	$smarty->assign("HOBBY","-");
	$smarty->assign("MUSIC","-");
	$smarty->assign("BOOK","-");
	$smarty->assign("SPORTS","-");
	$smarty->assign("CUISINE","-");
	$smarty->assign("DRESS","-");
	$smarty->assign("LANGUAGE","-");
	$smarty->assign("INTEREST","-");

	if(mysql_num_rows($result) > 0)
	{
		$myrow=mysql_fetch_array($result);
		
		$sql="select SQL_CACHE VALUE,LABEL,TYPE from newjs.HOBBIES order by SORTBY";
		$result_hobby=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
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
	
			
		if(is_array($HOBBY) && ($myrow["HOBBY"]))
			$smarty->assign("HOBBY",implode(", ",$HOBBY));
			
		if(is_array($INTEREST) && ($myrow["HOBBY"]))
			$smarty->assign("INTEREST",implode(", ",$INTEREST));
			
		if(is_array($MUSIC) && ($myrow["HOBBY"]))
			$smarty->assign("MUSIC",implode(", ",$MUSIC));
			
		if(is_array($BOOK) && ($myrow["HOBBY"]))
			$smarty->assign("BOOK",implode(", ",$BOOK));
			
		if((is_array($MOVIE)) && ($myrow["HOBBY"]))
			$smarty->assign("MOVIE",implode(", ",$MOVIE));

					
		if(is_array($SPORTS) && ($myrow["HOBBY"]))
			$smarty->assign("SPORTS",implode(", ",$SPORTS));
			
		if(is_array($CUISINE) && ($myrow["HOBBY"]))
			$smarty->assign("CUISINE",implode(", ",$CUISINE));
			
		if(is_array($DRESS) && ($myrow["HOBBY"]))
			$smarty->assign("DRESS",implode(", ",$DRESS));
			
		if(is_array($LANGUAGE) && ($myrow["HOBBY"]))
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

	/*************************************************************************
	Contacts section starts here
	*************************************************************************/
	if($PERSON_LOGGED_IN)
	{
		$NUDGES=array();
		$n_source=$jprofile_result["viewed"]["SOURCE"];
		//$contact_status_new=get_contact_status_dp($profileid,$viewer_profileid);
		
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
		 	$smarty->assign("NO_CONTACT_ALLOW","1");
		 	$smarty->assign("CANNOTCONTACT","1");
		 	$smarty->assign("LIMIT_CONTACT_MESSAGE",$contact_limit_message);
		 }
		elseif($samegender==1)
		{
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
					$op_msg_sql="SELECT MESSAGE FROM jsadmin.OFFLINE_OPERATOR_MESSAGES WHERE MATCH_ID='$viewer_profileid' AND PROFILEID='$profileid'";
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
			//$my_rights=get_rights($viewer_profileid);
			//if(in_array("F",$my_rights))
			//	$smarty->assign("PAID","1");
					

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

                        if($found_R==2 && $allow_shift_archive==0 && $VIEWED_USER<=0)
                        {
                                $sql_cs="update CONTACTS_STATUS set LAST_30_OPEN_CONTACTS=LAST_30_OPEN_CONTACTS-1,LAST_30_90_OPEN_CONTACTS=LAST_30_90_OPEN_CONTACTS+1 where PROFILEID=$viewer_profilied";
                                mysql_query_decide($sql_cs)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_cs,"ShowErrTemplate");

                        }
			
		}
                $smarty->assign("contact_status",$contact_status);
	}
	else 
	{
		$smarty->assign("CONTACT","1");
		$smarty->assign("CONTACTSUMMARY2_HEAD","IC");
	//	$smarty->assign("CONTACTSUMMARY2","To initiate contact with <b>$contactperson</b>, click on the \"Contact\" button"); 
	}
	
	/*************************************************************************
	Contacts section ends here
	*************************************************************************/
	
	/*************************************************************************
	Filters section starts here
	*************************************************************************/
	// filter can be applied only if the person who is viewing is logged in and the person viewing and the person being viewed is different and the person being viewed has filled partner profile and the person viewing and the one being viewed have not contacted each other before
	if($PERSON_LOGGED_IN && $viewer_profileid!=$profileid && $HAVE_PARTNER  && $samegender!=1)
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
			// check whether the person being viewed has set the filters
			$sql="select * from FILTERS where PROFILEID='$profileid'";
			$resultfilter=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		
			if(mysql_num_rows($resultfilter) > 0)
			{
				$filterrow=mysql_fetch_array($resultfilter);
				
				if($filterrow["AGE"]=="Y" || $filterrow["MSTATUS"]=="Y" || $filterrow["RELIGION"]=="Y" || $filterrow["COUNTRY_RES"]=="Y" || $filterrow["MTONGUE"] =="Y" || $filterrow['CASTE']=="Y" || $filterrow["CITY_RES"]=="Y" || $filterrow["INCOME"]=="Y")
				{
				

					if($PARTNER_CASTE)
					$PARTNER_CASTE_LIST=get_all_caste($PARTNER_CASTE);

					$temp_age=$jprofile_result["viewer"]["AGE"];
					if($filterrow["AGE"]=="Y" && ($FILTER_LAGE>$temp_age || $temp_age>$FILTER_HAGE) )
						$filter_flag=1;	
					elseif($filterrow["MSTATUS"]=="Y" && is_array($FILTER_MSTATUS) && !in_array($jprofile_result["viewer"]["MSTATUS"],$FILTER_MSTATUS))
						$filter_flag=1;	
					elseif($filterrow["RELIGION"]=="Y" && is_array($PARTNER_RELIGION) && !in_array($jprofile_result["viewer"]["RELIGION"],$PARTNER_RELIGION))
						$filter_flag=1;	
					elseif($filterrow["CASTE"]=="Y" && is_array($PARTNER_CASTE_LIST) && !in_array($jprofile_result["viewer"]["CASTE"],$PARTNER_CASTE_LIST))
						$filter_flag=1;
					elseif($filterrow["COUNTRY_RES"]=="Y" && is_array($PARTNER_COUNTRYRES) && !in_array($jprofile_result["viewer"]["COUNTRY_RES"],$PARTNER_COUNTRYRES))
						$filter_flag=1;	
					elseif($filterrow["MTONGUE"]=="Y" && is_array($PARTNER_MTONGUE) && !in_array($jprofile_result["viewer"]["MTONGUE"],$PARTNER_MTONGUE))
						$filter_flag=1;	
					elseif($filterrow["CITY_RES"]=="Y" && is_array($PARTNER_CITYRES) && !in_array($jprofile_result["viewer"]["CITY_RES"],$PARTNER_CITYRES))
						$filter_flag=1;
					elseif($filterrow["INCOME"]=="Y" && is_array($PARTNER_INCOME) && !in_array($jprofile_result["viewer"]["INCOME"],$PARTNER_INCOME))
						$filter_flag=1;
					if($filter_flag)
					{
						$IVR_filtersCheck =1;
					// if the filtered privacy option is set then don't show the profile as the person has been filtered
						if($PRIVACY=="F" && $CONTACTMADE!=1)
						{
							showProfileError("","F");
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
							if($myrow_gender=='M')
							       $smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/photo_fil_big_b.gif");
							else
							       $smarty->assign("PHOTOFILE","$VIEWPROFILE_IMAGE_URL/images/photo_fil_big_g.gif");
						}
					}
				}
			}
		
			mysql_free_result($resultfilter);
		}
	}
				
	/*************************************************************************
	Filters section ends here
	*************************************************************************/

	//$checksum ="";
	//$smarty->assign("CHECKSUM",$checksum);
	$profilechecksum =md5($profileid)."i".$profileid;
	$smarty->assign("PROFILECHECKSUM",$profilechecksum);

	// Religions specific code for castes in religion
	$pid = $profileid;
	if($religion[0] == 'Hindu')
	{
		$ras = $jprofile_result["viewed"]["RASHI"];
		$sql_ras = "select LABEL from newjs.RASHI WHERE VALUE = '$ras'";
		$res_ras = mysql_query_decide($sql_ras) or logError("error",$sql_ras);
		if($myrow_ras = mysql_fetch_array($res_ras))
			$smarty->assign("RASHI",$myrow_ras["LABEL"]);
		else
			$smarty->assign("RASHI","  -");
		if($jprofile_result["viewed"]["ANCESTRAL_ORIGIN"]=="")
        	        $smarty->assign("NATIVE_PLACE","-");
	        elseif(isFlagSet("ANCESTRAL_ORIGIN",$jprofile_result["viewed"]["SCREENING"]))
                	$smarty->assign("NATIVE_PLACE",$jprofile_result["viewed"]["ANCESTRAL_ORIGIN"]);
	        elseif($PERSON_OWNER)
        	        $smarty->assign("NATIVE_PLACE",$jprofile_result["viewed"]["ANCESTRAL_ORIGIN"] . "<br>" . $SCREENING_MESSAGE_SELF);
	        else
        	        $smarty->assign("NATIVE_PLACE",$SCREENING_MESSAGE);

		$smarty->assign("HOROSCOPE_MATCH",$jprofile_result["viewed"]["HOROSCOPE_MATCH"]);
	}
	elseif($religion[0] == 'Jain')
	{
		$sql_jain = "SELECT SAMPRADAY FROM newjs.JP_JAIN WHERE PROFILEID='$pid'";
		$res_jain=mysql_query_decide($sql_jain) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_jain,"ShowErrTemplate");
		$row_jain=mysql_fetch_array($res_jain);
		$smarty->assign("SAMPRADAY",$SAMPRADAY[$row_jain['SAMPRADAY']]);
	}
	elseif($religion[0] == 'Christian')
	{
		$sql_christian = "SELECT * FROM newjs.JP_CHRISTIAN WHERE PROFILEID='$pid'";
		$res_christian=mysql_query_decide($sql_christian) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_christian,"ShowErrTemplate");
		$row_christian=mysql_fetch_array($res_christian);
                if($row_christian["DIOCESE"]=="")
                        $smarty->assign("DIOCESE","-");
                elseif(isFlagSet("GOTHRA",$jprofile_result["viewed"]["SCREENING"]))
                        $smarty->assign("DIOCESE",$row_christian["DIOCESE"]);
                elseif($PERSON_OWNER)
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
		$sql_muslim = "SELECT * FROM newjs.JP_MUSLIM WHERE PROFILEID='$pid'";
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
		$sql_sikh = "SELECT * FROM newjs.JP_SIKH WHERE PROFILEID='$pid'";
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
		$sql_parsi = "SELECT * FROM newjs.JP_PARSI WHERE PROFILEID='$pid'";
		$res_parsi= mysql_query_decide($sql_parsi) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_parsi,"ShowErrTemplate");
		$row_parsi=mysql_fetch_array($res_parsi);
		$smarty->assign("ZARATHUSHTRI",$row_parsi['ZARATHUSHTRI']);
		$smarty->assign("PARENTS_ZARATHUSHTRI",$row_parsi['PARENTS_ZARATHUSHTRI']);
        }
}

if($PRINT || $PRINT_LIST)
{
	if($lead && $file_id){
		$smarty->assign("lead_attachment",'1');
		//$path= $_SERVER['DOCUMENT_ROOT']."/sugarcrm/cache/upload/";
	}
	else
		$smarty->assign("lead_attachment",'');

	$smarty->display("ap_viewprofile_print1.htm");

        if($ex_form_detail =='show'){
		/* parameters included in the file:'ap_profile_extra_form.php' 
		 * $profileid,$list,$cid,$ex_form_print
		*/
		$ex_form_print=1;
                include("ap_profile_extra_form.php");
        }
}
else
{
        $smarty->display("ap_viewprofile1.htm");
}

?>
