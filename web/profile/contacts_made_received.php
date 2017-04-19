<?php
/********************************************************************************************
FILE NAME     : contacts_made_received.php  
* Modification DATE : 25 Aug, 2009
* MODIFIED BY    : Tanu Gupta
* REASON : changed to make it compatible with new changes on site
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
	//to zip the file before sending it
	$start_tm=microtime(true); // Finding Starting Micro time for tracking
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it
	//ini_set("memory_limit","40M");
	include_once("arrays.php");
	include_once("contact.inc");
                include_once("connect.inc");	
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
	include_once("cmr.php");
	include_once("sphinx_search_function.php");
	include_once("mobile_detect.php");
                
	include_once("../ivr/jsContactVerify.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
        if(($page=='matches' && $filter=='R') && !MobileCommon::isNewMobileSite())
        {
                header("Location:".$SITE_URL."/search/matchalerts");die;
        }               
        elseif($page=='kundli' && $filter=='R')
        {
                header("Location:".$SITE_URL."/search/kundlialerts");die;
        }
        elseif(($page=='visitors' && $filter=='R')&& MobileCommon::isDesktop())
        {
			if ($matchedOrAll== 'A')
			{
				header("Location:".$SITE_URL."/search/visitors?matchedOrAll=A");die;
			}
			else
			{
				header("Location:".$SITE_URL."/search/visitors");die;
			}
        }
        elseif(($page=='favorite' && $filter=='M')&& MobileCommon::isDesktop())
        {
                header("Location:".$SITE_URL."/search/shortlisted");die;
        }
        //redirect url to inbox listings
        elseif(MobileCommon::isDesktop())
        {
        	if($page || $filter)
        	{
				if($page=="viewed_contacts_by") $page='contact_viewers';
	        	$params = array("page"=>$page,"filter"=>$filter);
	        	$infoTypeId =  InboxEnums::getInfoTypeIdByInboxParams($params);
	        	if($infoTypeId && $infoTypeId != -1)
	        	{
	        		$redirectUrl = $SITE_URL."/inbox/".$infoTypeId."/1";
	        		header("Location:".$redirectUrl);die;
	        	}
	        	else
	        	{
	        		if($page=="accept" && $filter=="A")
	        			header("Location:".$SITE_URL."/inbox/3/1");die;
	        		if($page=="messages")
	        			header("Location:".$SITE_URL."/inbox/4/1");die;
	        	}
        	}
        	else
        		header("Location:".$SITE_URL."/inbox/3/1");die;
        }


		/******Forward action********/
		if(!$page && !$filter){
			$page = "eoi";
			$filter = "R";
			$resetPage = true;
		}

		$self_details=authenticated($checksum); 
// the next 4 lines update seen status of all photorequests received by the loggedin user as 'Y' if the page is photo_request ... JSI-443
		if ($page=='photo'){
		if ($self_details['PROFILEID']){
				$profileObj=LoggedInProfile::getInstance('newjs_master');
			$profileMemcacheObj = new ProfileMemcacheService($profileObj); 
$photoRCurrentCount=$profileMemcacheObj->get("PHOTO_REQUEST_NEW");
if ($photoRCurrentCount!='0'){	
	Inbox::setAllPhotoRequestsSeen($self_details['PROFILEID']);
	$profileMemcacheObj->update("PHOTO_REQUEST_NEW",-$photoRCurrentCount);
	$profileMemcacheObj->updateMemcache();
						}	
			
										}
							}
		
		

		if(MobileCommon::isNewMobileSite())
		{
		if($page=="viewed_contacts_by") $page='contact_viewers';

		switch($page)
		{
		case "accept":
			if($filter=="M")
				$searchId = 3;
			elseif($filter=="A" ||$filter=="R")
				$searchId = 2;
			break;
		case "eoi":
			if($filter=="M")
				$searchId=6;
			elseif($filter=="R")
				$searchId=1;
			break;
		case "messages":
			$searchId = 4;
			break;
		case "visitors":
			$searchId = 5;
			break;
		case "matches":
			$searchId = 7;
			break;
		case "favorite":
			$searchId  = 8;
			break;
		case "photo":
			if($filter=="R")
				$searchId  = 9;
			break;
		case "decline":
			if($filter=="R")
				$searchId  = 10;
			elseif($filter=="M")
				$searchId  = 11;
			break;
		case "filtered_eoi" :
			$searchId=12;
			break;	
		case "phonebook_contacts_viewed":
			if($filter=="M")
				$searchId  = 16;
			break;
		case "aeoi":
				$searchId  = 22;
			break;
		case "contact_viewers":
				$searchId  = 17;
			break;
		case "eeoi":
			if($filter=="R")
				$searchId=23;
			break;
		
		}
		if($searchId)
		{
				switch($searchId)
				{
					case 2:
						$page = "accept";
						$filter="R";//or A
						break;
					case 22:
						$page = "aeoi";
						$filter="R";//or A
						break;
					case 3:
						$page = "accept";
						$filter = "M";
						break;
					case 23:
						$page = "eeoi";
						$filter = "R";	
						break;
					case 1:
						$page = "eoi";
						$filter = "R";	
						break;
					case 6:
						$page = "eoi";
						$filter = "M";
						break;
					case 4:
						$page = "messages";
						break;
					case 5:
						$page = "visitors";
						$filter = "R";	
						break;
					case 7:
						$page = "matches";
						$filter = "R";	
						break;
					case 8:
						$page = "favorite";
						$filter = "M";
						break;
					case 9:
						$page = "photo";
						$filter = "R";
						break;
					case 10:
						$page = "decline";
						$filter = "R";
						break;
					case 11:
						$page = "decline";
						$filter = "M";
						break;
					case 12:
						$page = "filtered_eoi";
						$filter = "R";
						break;
					case 16:
						$page = "phonebook_contacts_viewed";
						$filter = "M";
						break;
					case 17:
						$page = "contact_viewers";
						$filter="R";
						break;	
				}
			if(!$filter)$filter="M";
			$itemStats = getItemDetail($page,$filter);
			sfContext::getInstance()->getRequest()->setParameter("navigation_type",$itemStats["navigation_type"]);
			$itemStats = array();
			sfContext::getInstance()->getRequest()->setParameter("searchId",$searchId);
			sfContext::getInstance()->getRequest()->setParameter("currentPage",1);
			MobileCommon::forwardmobilesite('','inbox','jsmsPerform');
		}
		}
		if($resetPage){
			$page = "";
			$filter = "";
		}
		/**************/
	$db=connect_db(); 
	 
	if($page=="contact_viewers") $page='viewed_contacts_by';
	$checksum_forp=$checksum;//added by lavesh.

	$data = $self_details;
	//added by lavesh
	$smarty->assign("head_tab",'my jeevansathi');
	$smarty->assign("my_scriptname",'contacts_made_received.php');
	$my_sub=array();

	if(!$data && $_GET['clicksource']=='matchalert1')
	{
		$epid=$protect_obj->js_decrypt($_GET['echecksum']);
		if($_GET['checksum']==$epid)
		{
			$epid_arr=explode("i",$epid);
			$profileid=$epid_arr[1];
			if($profileid)
			{
				$smarty->assign("for_about_us","1");
				$sql="SELECT USERNAME,PASSWORD FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID=$profileid";
				$res=mysql_query_decide($sql) or die($sql.mysql_error());
				$row=mysql_fetch_assoc($res);
				$_POST['username']=$row['USERNAME'];
				$_POST['password']=$row['PASSWORD'];

				$username=$row['USERNAME'];
				$password=$row['PASSWORD'];
				$data =login($username,$password);
				$self_details=$data;
			}
		}
	}

	if($data)
	{
        $smarty->assign("LOGGEDIN", 1);
                login_relogin_auth($data);
		$my_pid=$data['PROFILEID'];
		//profile_stats($my_pid);

		if(strstr($data['SUBSCRIPTION'],'F'))
		{
                        $smarty->assign("SUBSCRIPTION",'Y');
			$my_subscription=1;
		}
		elseif(strstr($data['SUBSCRIPTION'],'D'))
                        $smarty->assign("E_CLASS",'Y');

		$my_sub=explode(",",$data["SUBSCRIPTION"]);
                $myprofilechecksum = md5($my_pid)."i".($my_pid);
                $smarty->assign("myprofilechecksum",$myprofilechecksum);
	}

	//Page number
        $smarty->assign("j",$j);

	/************************************************End of Portion of Code*****************************************/

	//Needed for category and date search
	$loggedInChecksum = $checksum;

	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("profileid",$data["PROFILEID"]);

	if($self_details)
	{	

		$self_profileid=$self_details["PROFILEID"];

		//Initiate Page Number
		if(!$j)
			$j=1;

		//Set default page and filter
		$itemPrevStats = array();
		if(!$page && !$filter)
		{
			$itemPrevStats = getLandingPage($self_profileid);
			$page=$itemPrevStats["page"];
			$filter=$itemPrevStats["filter"];
		}
		$smarty->assign("page",$page);
		$smarty->assign("filter",$filter);
		//Define Page Length
		$PAGELEN=25;
		$itemPrevStats["PAGELEN"] = $PAGELEN;
		$itemPrevStats["self_profileid"] = $self_profileid;
		$itemPrevStats["GENDER"] = $self_details["GENDER"];
		$itemStats = getItemDetail($page,$filter);
		$item = array_merge($itemPrevStats, $itemStats);
		if($item["type"])
		{
			/*******Offline member check having addOn to call *****/
			$apMember = isApMember($data["SUBSCRIPTION"]);
			if($apMember)
			{
				$offlineCallCountArr = array();
				$introCallDetail = array();
				$offlineCallCountArr["TOTAL"] = 0;
				$introCallDetail["TOTAL"] = 0;
				$introCallDetail["profile"] = array();
				include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
				$membershipObj = new Membership;
				$memHandlerObj = new MembershipHandler;
				$offlineCallCountArr = $memHandlerObj->getAllCount($self_profileid);
				$smarty->assign("offlineCallCountArr", $offlineCallCountArr);
				if($offlineCallCountArr["TOTAL"])
				{
					//$introCallDetail = getIntroCallHistory($data["PROFILEID"],$offlineCallCountArr["EXPIRED"]);
					$introCallDetail = getIntroCallHistory($data["PROFILEID"]);
					$smarty->assign("introCallDetail",$introCallDetail);
				}
			}
			/********Ends here********/

			//Horoscope
			$HOROSCOPE_ARRAY=array();
			//Nudge contact
			$nudgeContact = array();
			//Parameters for date search			
			$date_search=0;
			$end_date='';
			$start_date='';

			if($date_search_submit)
			{
				$date1xxlist=explode("/",$date1xx);
				$date2xxlist=explode("/",$date2xx);
				if(!$date1xx)
					$date1xx=$date1xxdate."/".$date1xxmonth."/".$date1xxyear;
				if(!$date2xx)
					$date2xx=$date2xxdate."/".$date2xxmonth."/".$date2xxyear;
				$smarty->assign("date1",$date1xx);
				$smarty->assign("date2",$date2xx);
				$SHOW_DATE_SEARCH = 1;
				$smarty->assign("DATE_SEARCH_SUBMIT",1);
				$dateSplit=explode("/",$date1xx);
				$item["start_date"]=$dateSplit[2]."-".$dateSplit[1]."-".$dateSplit[0]." 00:00:00";
				$dateSplit=explode("/",$date2xx);
                                $item["end_date"]=$dateSplit[2]."-".$dateSplit[1]."-".$dateSplit[0]." 23:59:59";
				$item["date_search"]=1;
			}
			$resultDetail = array_merge($_REQUEST, $item);	
			$resultDetail['SUBSCRIPTION'] = $data['SUBSCRIPTION'];		//For ASTRO
			$pageDetail = getting_profiles_based_on_type($resultDetail,$offlineCallCountArr);
			
			//To display total eoi count at the top of search results
			if(!$date_search_submit) //Variable coming from date search
			{
				if(!$initialCount)
					$initialCount = count($pageDetail["ALLOW_PROFILES"]);
			}
	
			//Assigns variables for creating category search gadget
			$SHOW_CATEGORY_SEARCH = $item["SHOW_CATEGORY_SEARCH"];
			$SHOW_DATE_SEARCH = $item["SHOW_DATE_SEARCH"];
			if($SHOW_CATEGORY_SEARCH && $initialCount>20)
			{
				show_hide_search($pageDetail);
			}
			if($search_submit)
                        {
				$pageDetail = getCategorySearchResults($pageDetail);
			}

			//added by manoranjan for online bookmark
			$pageDetail = getOnlineUsersDetail($pageDetail);
			if(!empty($pageDetail["ALLOW_PROFILES"]))
				$pageDetail = sort_profiles($pageDetail);

			$ALLOW_PROFILES = $pageDetail["ALLOW_PROFILES"];
			$DATA_3D = $pageDetail["DATA_3D"];
			$NUDGES = $pageDetail["NUDGES"];
			$total_cnt=count($ALLOW_PROFILES);
		}
		$paid = isPaid($data["SUBSCRIPTION"]);
		$pageDetail["paid"] = $paid;
		$smarty->assign("ISPAID",$paid);

		/*********Direct call changes******/
		global $CALL_DIRECT;
		$pageDetail["CALL_DIRECT"] = $CALL_DIRECT;
		if($CALL_DIRECT && $paid)
		{
			$viewedContactDetail = contacts_left_to_view($self_profileid);
			$pageDetail["viewedAllotted"] = $viewedContactDetail["ALLOTED"];
			$pageDetail["viewedContacts"] = $viewedContactDetail["VIEWED"];
			$pageDetail["viewedLeft"] = $viewedContactDetail["ALLOTED"] - $viewedContactDetail["VIEWED"];
			$viewedContactDetail["VIEWED_LEFT"] = $pageDetail["viewedLeft"];
			$smarty->assign("viewedContactDetail",$viewedContactDetail);
		}
		/*******Ends here*******/

		/*******Offline member check having addOn to call *****/
		if($apMember)
		{
			if(($offlineCallCountArr["TOTAL"]+$offlineCallCountArr["EXPIRED"]) <= $introCallDetail["TOTAL_COUNT"])
			{
				$pageDetail["removeAddMemberLink"] = true;
			}
			$pageDetail["offlineCallCountArr"] = $offlineCallCountArr;
			$pageDetail["introCallDetail"] = $introCallDetail;
			$pageDetail["addedToIntroProfiles"] = $introCallDetail["profile"];
		}
		/******Ends here**************/


		$smarty->assign("STYPE",$pageDetail["stype"]);
		//Needed for filter by viewed/unviewed eoi in People yet to respond to me page
		$smarty->assign("filterBy",$filterBy);
		if($total_cnt>0)
		{
			$totalrec=$total_cnt;

			pagination($j,$totalrec,$PAGELEN,$MORE_URL);
			global $cc_navigator;
			$cc_navigator='';
			$navigation_type = $pageDetail["navigation_type"];
			navigation($navigation_type,"page__$page@filter__$filter@j__$j@date_search_submit__$date_search_submit@date1xxdate__$date1xxlist[0]@date1xxmonth__$date1xxlist[1]@date1xxyear__$date1xxlist[2]@date2xxdate__$date2xxlist[0]@date2xxmonth__$date2xxlist[1]@date2xxyear__$date2xxlist[2]@search_submit__$search_submit@more_or_less__$more_or_less@religion__$religion@caste__$caste@lage__$lage@hage__$hage@mtongue__$mtongue@mstatus__$mstatus@city_res__$city_res@havephoto__$havephoto@filterBy__$filterBy","");

			$smarty->assign("CUR_PAGE",$_SERVER['PHP_SELF']);
			$smarty->assign("CHECKBOX", $pageDetail["CHECKBOX"]);
			$smarty->assign("NAV_TYPE",$navigation_type);
			$profile_start=($j)*$PAGELEN-$PAGELEN;
                                                                                                                             
			if($profile_start+($PAGELEN+1)<$totalrec)
				$profile_end=$profile_start+($PAGELEN+1);
			else
				$profile_end=$totalrec;

			// IVR-Callnow Start, Feature added to show link in headline for Received and Missed Calls
			// Missed calls are shown in red colour
			$callnow_tophead_link =false;
			if($page=='callnow' && (($pageDetail['type']=='R' || $pageDetail['type']=='M') || $filter=='A')){
				$callnow_tophead_link =true;
				if($pageDetail['type']=='M')
					$callnowMissed =true;
			}
			$smarty->assign("callnow_tophead_link",$callnow_tophead_link);	
			$smarty->assign("callnowMissed",$callnowMissed);

			// Callnow privacy settings set by the viewed profiles
	                global $CALL_NOW;
	                $callnowAccess_Arr=array();
	                if($CALL_NOW && $data){
				//$ALLOW_PROFILES_STR =implode("','",$ALLOW_PROFILES);
				//$callnowAccess_Arr = callAccess("'".$ALLOW_PROFILES_STR."'");
				$callnowAccess_Arr = callAccess($ALLOW_PROFILES);
	                }
			// End Callnow privacy settings
			// IVR-Callnow Ends 
		}
		if($ALLOW_PROFILES)
		{
			$bookmarkee=array();
			$bookmark_array=array();
			if($chatBar != 1){//condition added by manoranjan
				//for preventing double execution of loop code....it is already executed before
					//echo "coming here";
					$online_array=array();
					$gtalk_online_array=array();
					//get_bookmark_and_online_users($pageDetail);
				}
			//$invalid_phone_array=array();
			get_bookmark_and_online_users($pageDetail);
			//get_invalid_phone_users($pageDetail);
		}
		if(count($ALLOW_PROFILES)>$profile_start+$PAGELEN)
			$upto=$profile_start+$PAGELEN;
		else
			$upto=count($ALLOW_PROFILES);

		$pageDetail = getResultArray($pageDetail); //Refined result array
		$data_3d = $pageDetail["data_3d"];
		$ACTION = $pageDetail["ACTION"];
		$CON_TIME = $pageDetail["CON_TIME"];

		if(!count($data_3d))
		{
			$sqlviewerdata="SELECT PROFILEID,ENTRY_DT,AGE,HEIGHT,HANDICAPPED,BTYPE,CASTE,ISD,COUNTRY_RES,CITY_RES,DIET,DRINK,EDU_LEVEL,EDU_LEVEL_NEW,INCOME,MANGLIK,MSTATUS,OCCUPATION,SMOKE,COMPLEXION,MTONGUE,RELATION,STD,PHONE_MOB,PHONE_RES,EMAIL,SOURCE,INCOMPLETE,ACTIVATED,MOB_STATUS,LANDL_STATUS,PHONE_FLAG FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$data[PROFILEID]'";
			$resviewerdata=mysql_query_decide($sqlviewerdata) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlviewerdata,"ShowErrTemplate");
			$viewerdata=mysql_fetch_assoc($resviewerdata);	
		}
		if(count($data_3d))
		{
		$CON_DETAILS=get_profile_details_all($data_3d,$profile_start,0,$item["show_all_results"]);
		if($CON_DETAILS){

		
		/********Photo optimizations - Modified by Tanu*******/
		$pIndex=1;
		$pTotal = 0;
		$photoProfileArr = array();
		$profileAlbumArr = array();
                foreach($data_3d as $keyP=>$valP)
                {
                        if($pIndex>$profile_start && $pTotal< ($PAGELEN+2))
                        {
                                $photoProfileArr[] = $valP["PROFILEID"];
                                $pTotal++;
                        }
                        $pIndex++;
                }
		//Symfony Photo Modification.
		$profilePicUrls = SymfonyPictureFunctions::getPhotoUrls_nonSymfony($photoProfileArr,"SearchPicUrl,ThumbailUrl");	
		$profileAlbumArr = SymfonyPictureFunctions::checkMorePhotosMultipleIds($photoProfileArr,"",true);
		/*******Ends here***************/

		//Get global horo array from get_profile_details_all function
		$smarty->assign("my_horo_astro",$HOROSCOPE_ARRAY[$data["PROFILEID"]]["ASTRO_DETAILS"]);

		/* IVR- Verification, Verified icons */ 
		if($page!= "decline"){
			//$validPhoneArr = getPhoneValidityArr($CON_DETAILS);
			$chkPhone =1;	
		}
			
		//Contact message
		if($page=="accept" || $page=="decline" || $page=="eoi" || $page=="messages" || $page=="filtered_eoi" || $page=="intro_call")//|| $page=="archive_eoi")
		{
			if($page == "intro_call"){
				$pageDetail["CALL_COMMENTS"] = getTeleCallerComments($self_profileid, $ALLOW_PROFILES);
				$ex_detail_form_arr = getExDetailForm($ALLOW_PROFILES);
			}
		        $MESSAGE_EACH=check_message($pageDetail,$profile_start);
		}
		if($page=="favorite" || $page=="photo" || $page=="horoscope" || $page=="chat" || $page=="matches" || $page=="visitors" || $page=="messages" || $page=="callnow" || $page=="accept" || $page=="viewed_contacts" || $page=="intro_call" || $page=="viewed_contacts_by" || $page == "decline" || $page=="eoi" || $page=="kundli")
			$CONTACT_STATUS=get_contact_status_profiles($data_3d,$profile_start);
		$sqlviewerdata="SELECT PROFILEID,ENTRY_DT,AGE,HEIGHT,HANDICAPPED,BTYPE,CASTE,ISD,COUNTRY_RES,CITY_RES,DIET,DRINK,EDU_LEVEL,EDU_LEVEL_NEW,INCOME,MANGLIK,MSTATUS,OCCUPATION,SMOKE,COMPLEXION,MTONGUE,RELATION,STD,PHONE_MOB,PHONE_RES,EMAIL,SOURCE,INCOMPLETE,ACTIVATED,MOB_STATUS,LANDL_STATUS,PHONE_FLAG FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$data[PROFILEID]'";
		$resviewerdata=mysql_query_decide($sqlviewerdata) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sqlviewerdata,"ShowErrTemplate");
		$viewerdata=mysql_fetch_assoc($resviewerdata);	
		$smarty->assign("my_caste",$viewerdata["CASTE"]);

		$jprofile_result["viewer"]["PROFILEID"] = $viewerdata["PROFILEID"];
		$jprofile_result["viewer"]["ISD"] = $viewerdata["ISD"];
		$jprofile_result["viewer"]["PHONE_MOB"] = $viewerdata["PHONE_MOB"];
		$jprofile_result["viewer"]["PHONE_RES"] = $viewerdata["PHONE_RES"];
		$jprofile_result["viewer"]["COUNTRY_RES"] = $viewerdata["COUNTRY_RES"];
		$jprofile_result["viewer"]["EMAIL"] = $viewerdata["EMAIL"];
		$jprofile_result["viewer"]["SUBSCRIPTION"] = $data["SUBSCRIPTION"];
		$jprofile_result["viewer"]["RELATION"] = $viewerdata["RELATION"];
		$jprofile_result["viewer"]["STD"] = $viewerdata["STD"];
		$jprofile_result["viewer"]["ACTIVATED"] = $viewerdata["ACTIVATED"];
		$jprofile_result["viewer"]["INCOMPLETE"] = $viewerdata["INCOMPLETE"];
		$jprofile_result["viewer"]["PROFILEID"] = $self_profileid;
		$jprofile_result["viewer"]["MOB_STATUS"] = $viewerdata["MOB_STATUS"];
		$jprofile_result["viewer"]["LANDL_STATUS"] = $viewerdata["LANDL_STATUS"];
		$jprofile_result["viewer"]["PHONE_FLAG"] = $viewerdata["PHONE_FLAG"];

		/*$verifiedPhone = isValidPhone($jprofile_result["viewer"]["PROFILEID"], $jprofile_result["viewer"]["PHONE_MOB"]);
		$jprofile_result["viewer"]["INVALID_PHONE_CHECKED"] = true;
		if($verifiedPhone)
			$jprofile_result["viewer"]["CONTACT_LOCKED"] = 1;*/
		$selfOffline = false;
		if($viewerdata["SOURCE"] == "ofl_prof")
			$selfOffline = true;
		$pageDetail["selfOffline"] = $selfOffline;
/**************************************
trac 886 display phone verfiication layer on click view contact added by esha*/
                if($jprofile_result['viewer']['PROFILEID'])
                {
                        $ph_layer_status =get_phoneVerifyLayer($jprofile_result['viewer']);
			$overall_cont=get_dup_overall_cnt($data[PROFILEID]);
			$limits=set_contact_limit($data[SUBSCRIPTION]);
			$notvalidnumber_limit=$limits[4];
			if($overall_cont>=$notvalidnumber_limit)
	                        $smarty->assign("PH_LAYER_STATUS_EOI",$ph_layer_status);
                        $smarty->assign("PH_LAYER_STATUS",$ph_layer_status);
                }
/***trac 886 end by esha
**************************************/


                /************Incomplete/Screened profiles already contacted status*********/
                $tempContactParam = temporaryInterestSuccess($viewerdata["INCOMPLETE"], $viewerdata["ACTIVATED"]);
                if($tempContactParam)
                {
                        $PROFILES_ARR = array();
			$PROFILES_ARR = $ALLOW_PROFILES;
                        $tempContactStatus = ifTempContactExists($self_profileid, $PROFILES_ARR);
                }
                /********************Ends here*****************************/

		$pageDetail["apMember"] = $apMember;

		include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
		if(!$jpartnerObj)
			$jpartnerObj=new Jpartner;
		if(!$mysqlObj)
			$mysqlObj=new Mysql;
		$index=0;

		//Array whether to show the contact detail if call has been made between the users
		if($CALL_NOW)
			//$callArray = getCallerProfiles($self_profileid, $ALLOW_PROFILES, $CALL_NOW);
		$ignoreProfiles = ignore_profile($pageDetail);
		$photo_req = photo_request($data_3d,$profile_start);

		$phoneArray=array();
		for($k=$profile_start;$k<$upto;$k++)
		{
			$phoneArray[$data_3d[$k]['PROFILEID']]["PHONE_FLAG"] = $CON_DETAILS[$data_3d[$k]['PROFILEID']]["PHONE_FLAG"];
			$phoneArray[$data_3d[$k]['PROFILEID']]["MOB_STATUS"] = $CON_DETAILS[$data_3d[$k]['PROFILEID']]["MOB_STATUS"];
			$phoneArray[$data_3d[$k]['PROFILEID']]["LANDL_STATUS"] = $CON_DETAILS[$data_3d[$k]['PROFILEID']]["LANDL_STATUS"];
			$phoneArray[$data_3d[$k]['PROFILEID']]["PHONE_MOB"] = $CON_DETAILS[$data_3d[$k]['PROFILEID']]["PHONE_MOB"];
			$phoneArray[$data_3d[$k]['PROFILEID']]["PHONE_RES"] = $CON_DETAILS[$data_3d[$k]['PROFILEID']]["PHONE_RES"];
		
		}

		$countOfPhotosUploaded=SymfonyPictureFunctions::getUserUploadedPictureCount($data['PROFILEID']);
		for($k=$profile_start;$k<$upto;$k++)
		{
			/* IVR-Callnow,  New feature added 
			 * Get All the calls (missed/received/called) for the viewer 	
			*/
			$callDataArray =array();
			$offline_profile=0;
			$online=0;
			$show_contacts="";
			if($page=='callnow' && $filter=='A')
				$callDataArray =getCallDataArray($self_profileid,$data_3d[$k]["PROFILEID"]);
			// Ends

			$contact_details= $CON_DETAILS[$data_3d[$k]['PROFILEID']];


			if($contact_details){
			unset($sub);
			$sub=array();
			if($contact_details['SUBSCRIPTION'])
				$sub=explode(",",$contact_details["SUBSCRIPTION"]);
			if($contact_details['SOURCE']=='ofl_prof' || isOfflineMember($contact_details["SUBSCRIPTION"]))
				$offline_profile=1;

			$subscription=explode(",",$contact_details["SUBSCRIPTION"]);
			if(in_array('D',$subscription) && in_array("F",$subscription))// && !in_array('S',$subscription)) 
				$show_contacts = "Y";

			unset($member_101);
			if(in_array("1",$sub))
				$member_101=1;
			else
				$member_101=0;

			if(in_array("1",$sub) && $data["PROFILEID"]!='' && isPaid($data["SUBSCRIPTION"]))
			{
				$jpartnerObj->setPROFILEID($data_3d[$k]['PROFILEID']);
                                $myDbName=getProfileDatabaseConnectionName($data_3d[$k]['PROFILEID']);
                                $myDb=$mysqlObj->connect("$myDbName");
                                $jpartnerObj->setPartnerDetails($data_3d[$k]['PROFILEID'],$myDb,$mysqlObj);
                                if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$data_3d[$k]['PROFILEID']))
                                        $show_contacts=member_101_details_show($viewerdata,$jpartnerObj);
                                else
				{
                                        $show_contacts=1;
				}
				if($show_contacts)
					$show_contacts='Y';
			}

			 //Photo dispaly is corrected by lavesh on revamp.
			if($contact_details["HAVEPHOTO"]=="Y")
				$havephoto=get_user_photo_details($contact_details["PROFILEID"],$checksum_forp,$contact_details["HAVEPHOTO"],$contact_details["PRIVACY"],$contact_details["PHOTO_DISPLAY"],$CONTACT_STATUS[$data_3d[$k]["PROFILEID"]]);
			elseif($contact_details["HAVEPHOTO"]=="U")
				$havephoto="U";				
			else	
				$havephoto="N";				
			
			//Determine whether this user is online
			if(is_array($online_array) && in_array($data_3d[$k]['PROFILEID'],$online_array))
				$online=1;
			
			//added by manoranjan for gtalk
			if(is_array($gtalk_online_array) && in_array($data_3d[$k]['PROFILEID'],$gtalk_online_array))
				$gtalk_online=1;
			else
				$gtalk_online=0;
			
			//if(!$chat)	
			{
				unset($date_arr);
				unset($date_display);
				//Get the contact date in the desired format	
				$date_arr=explode("-",substr($data_3d[$k]["TIME"],0,10));
				$date_display=$date_arr[2]."/".$date_arr[1]."/".substr($date_arr[0],2,2);	
			}
			
			if($data_3d[$k]["COUNT"] <=0)
				$contactcount=0;
			else 
				$contactcount=($data_3d[$k]["COUNT"] - 1);
			$nudgeContact[$data_3d[$k]["PROFILEID"]] = 0;
			if(isNudgeProfile($data_3d[$k]['PROFILEID'],$NUDGES))	
			{
				$nudgeContact[$data_3d[$k]["PROFILEID"]] = 1;
				$NUDGE_CONTACT.='yes#';
			}
			else
				$NUDGE_CONTACT.='no#';
			$conMess = getContactMessage($pageDetail,$k,$MESSAGE_EACH);
			$message_org = $conMess["MESSAGE"];
			

			//If sent eoi has been viewed by receiver
			if($page == "eoi" && $filter == "M")
	 			$eoiViewed['date'] = $pageDetail["eoi_viewed_date"][$data_3d[$k]["PROFILEID"]];

			//Photo upload date for Photo requests sent
			//$photoDate[$data_3d[$k]["PROFILEID"]] = $contact_details["PHOTODATE"];

	 			

                        $age=$contact_details["AGE"];
                        $gothra=trim($contact_details["GOTHRA"]);
			$nakshatra=trim($contact_details["NAKSHATRA"]);
			$height2=$contact_details["HEIGHT"];
			$my_income=$contact_details["INCOME"];
                        //$my_income=$income_map["$income"];
                        $gender=$contact_details['GENDER'];
			$myCaste=$contact_details["CASTE"];
			$subcaste=trim($contact_details["SUBCASTE"]);
			$mtongue=$contact_details["MTONGUE"];
			$mtongue_s=$contact_details["MTONGUE_S"];
			$occupation=$contact_details["OCCUPATION"];
			$edu_level=(new MailerService())->getEducationDetails($contact_details["PROFILEID"]);// to show all the education degrees in the contact center tuples

			$residence=$contact_details["RESIDENCE"];
			$religion=$contact_details["RELIGION"]; 
			                                                                       
			//2 change for photo
			$photochecksum=md5($data_3d[$k]['PROFILEID']+5)."i".($data_3d[$k]['PROFILEID']+5);
			$photochecksum_new=$contact_details["PHOTOCHECKSUMNEW"];
			$profilechecksum=md5($data_3d[$k]["PROFILEID"]) . "i" . $data_3d[$k]["PROFILEID"];
			$username=$contact_details["NAME"];

			//Symfony Photo Modification
			$profilePicUrlArr = $profilePicUrls[$contact_details['PROFILEID']];                       
                	if ($profilePicUrlArr)
                	{
                        	$searchPicUrl = $profilePicUrlArr["SearchPicUrl"];
                        	$thumbnailUrl = $profilePicUrlArr["ThumbailUrl"];
                	}
                	else
                	{
                	        $searchPicUrl = null;
				$thumbnailUrl = null;
                	}
			//Symfony Photo Modification
			if($isMobile)
				$image_file=getPhotoImage_mobile($havephoto,$gender);
			else
				$image_file=getPhotoImage($havephoto, $gender);
			if($havephoto=='L' || $havephoto=='C' || $havephoto=='F' || $havephoto=='H' || $havephoto=='P' || $havephoto=='U')
                        {
                                if($printPage || $contact_details["ACTIVATED"] == "D"){
                                        //$my_photo="<div style=\" float:left; margin:0 3px 3px 0;\" align='left'><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"100\" height=\"133\" border=\"0\" ></div>";
					$my_photo="<div style=\" float:left; margin:0 3px 3px 0;\" align='left'><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"100\" height=\"133\" border=\"0\" ></div>";
				}
                                else{
                                        //$my_photo="<div style=\" float:left; margin:0 3px 3px 0; background:url($IMG_URL/profile/ser4_images/$image_file) no-repeat\" align='left'><img src=\"$IMG_URL/profile/ser4_images/transparent_img.gif\" width=\"100\" height=\"133\" border=\"0\" ></div>";
									if($isMobile)
										$my_photo="<div style=\" float:left; margin:0 3px 3px 0;\" align='left'><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"75\" height=\"100\" border=\"0\" ></div>";
									else
										$my_photo="<div style=\" float:left; margin:0 3px 3px 0; background:url($IMG_URL/profile/ser4_images/$image_file) no-repeat\" align='left'><img src=\"$IMG_URL/profile/ser4_images/transparent_img.gif\" width=\"100\" height=\"133\" border=\"0\" ></div>";
				}
                        }
                        elseif($havephoto=='Y')
                        {
                                if($printPage || $contact_details["ACTIVATED"] == "D"){
									if($isMobile)
                                        $my_photo="<div style=\"float:left; margin:0 3px 3px 0;\" align='auto'><img src=\"$searchPicUrl\" width=\"75\" height=\"100\" border=\"0\" ></div>";
									else
										$my_photo="<div style=\"float:left; margin:0 3px 3px 0;\" align='left'><img src=\"$searchPicUrl\" width=\"100\" height=\"133\" border=\"0\" ></div>";
				}
                                else{
									//Symfony Photo Modification
									if($isMobile)
									{
										$my_photo="<a  href=\"$SITE_URL/profile/layer_photocheck.php?checksum=$checksum&profilechecksum=$profilechecksum&seq=1&nav_type=$navigation_type&$cc_navigator\" ><img src=\"$searchPicUrl\" width=\"75\" height=\"100\" border=\"0\" ></a>";
									}
						else
							$my_photo="<a  href=\"#\" onClick=\"show_thickbox('$SITE_URL/profile/layer_photocheck.php?checksum=$checksum&profilechecksum=$profilechecksum&seq=1');return false;\" oncontextmenu=\"return false;\" ><div style=\" cursor:pointer;float:left; margin:0 3px 3px 0; background-image:url($searchPicUrl)\" align='left'><img src=\"$IMG_URL/profile/ser4_images/transparent_img.gif\" width=\"100\" height=\"133\" border=\"0\" ></div></a>";
				}
                        }
                        else
                        {
                                if($crmback)
                                {
                                        if($printPage || $contact_details["ACTIVATED"] == "D"){
                                                //$my_photo="<div style=\" float:left; margin:0 3px 3px 0;\" align='left'><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"100\" height=\"133\" border=\"0\" ></div>";
						$my_photo="<div style=\" float:left; margin:0 3px 3px 0;\" align='left'><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"100\" height=\"133\" border=\"0\" ></div>";
					}
                                        else{
                                                //$my_photo="<a href=\"/profile/viewprofile.php?profilechecksum=$profilechecksum&crmback=admin&cid=~$cid`&inf_checksum=~$inf_checksum\" target=\"_blank\"><div style=\" float:left; margin:0 3px 3px 0; background:url($IMG_URL/profile/ser4_images/$image_file) no-repeat\" align='left'><img src=\"$IMG_URL/profile/ser4_images/transparent_img.gif\" width=\"100\" height=\"133\" border=\"0\" ></div></a>";
						$my_photo="<a href=\"/profile/viewprofile.php?profilechecksum=$profilechecksum&crmback=admin&cid=~$cid`&inf_checksum=~$inf_checksum\" target=\"_blank\"><div style=\" float:left; margin:0 3px 3px 0; background:url($IMG_URL/profile/ser4_images/$image_file) no-repeat\" align='left'><img src=\"$IMG_URL/profile/ser4_images/transparent_img.gif\" width=\"100\" height=\"133\" border=\"0\" ></div></a>";
					}
                                }
                                else
                                {
                                        if($printPage || $contact_details["ACTIVATED"] == "D"){
                                                //$my_photo="<div style=\" float:left; margin:0 3px 3px 0;\" align='left'><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"100\" height=\"133\" border=\"0\" ></div>";
											if($isMobile)
												$my_photo="<div style=\" float:left; margin:0 3px 3px 0;\" align='left'><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"75\" height=\"100\" border=\"0\" ></div>";
											else
												$my_photo="<div style=\" float:left; margin:0 3px 3px 0;\" align='left'><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"100\" height=\"133\" border=\"0\" ></div>";
					}
                                        else{
											if($isMobile)
											{
												if($photo_req[$data_3d[$k]["PROFILEID"]])
												{
													$my_photo="<div style=\" float:left; margin:0 3px 3px 0;\" align='left'><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"75\" height=\"100\" border=\"0\" ></div> <span class=\"photoRequestSent\"> Photo Requested </span>";
												}
												else
													$my_photo="<div style=\" float:left; margin:0 3px 3px 0;\" align='left'><img src=\"$IMG_URL/profile/ser4_images/$image_file\" width=\"75\" height=\"100\" border=\"0\" ></div> <a href=\"$SITE_URL/social/photoRequest?newPR=1&amp;profilechecksum=$profilechecksum\" class=\"requestPhoto\"> Request photo </a> ";
												}
											else
												$my_photo="<a class=\"thickbox\" href=\"$SITE_URL/social/photoRequest?showtemp=Y&other_username=$username&checksum=$checksum&profilechecksum=$profilechecksum&height=300\"><div style=\" float:left; margin:0 3px 3px 0; background:url($IMG_URL/profile/ser4_images/$image_file) no-repeat\" align='left'><img src=\"$IMG_URL/profile/ser4_images/transparent_img.gif\" width=\"100\" height=\"133\" border=\"0\" ></div></a>";
					}
                                }
                        }
			 if($havephoto=='Y')
			{
				//Symfony Photo Modification
                            	//$is_album = SymfonyPictureFunctions::checkMorePhotos($data_3d[$k]["PROFILEID"]);
				$is_album = $profileAlbumArr[$data_3d[$k]["PROFILEID"]];
			}
        	        else
                	        $is_album=0;
			if($isMobile)
			{
				$small_tag=$age." yrs, ".$height2;
				$small_tag.="<BR>".$religion.", ".$myCaste;
				$small_tag.="<BR>".$mtongue_s."<BR>";
				if($edu_level)
					$small_tag.=$edu_level;
				if($edu_level && $occupation)
					$small_tag.=", ";
				if($occupation)
					$small_tag.=$occupation;
				if($edu_level || $occupation)
					$small_tag.="<BR>";
				if($residence)
					$small_tag.=$residence;
				if($residence && $my_income)
					$small_tag.=", ";
				if($my_income)
					$small_tag.=$my_income;
			}
			else
			{
				$small_tag="$age, $height2,$religion";
				$small_tag.="<BR>$mtongue_s,<BR>$myCaste";
				if($subcaste && isFlagSet("SUBCASTE",$contact_details['SCREENING']))
				$small_tag.=" ($subcaste)";
				$small_tag.=",<BR>";
				if($nakshatra && $nakshatra!="i don't know" && $nakshatra!="Don't Know")
					$small_tag.=$nakshatra." (Nakshatra),<BR>";
				if($gothra && $gothra!="i don't know" && isFlagSet("GOTHRA",$contact_details['SCREENING']))
					$small_tag.=$gothra." (Gothra),<BR>";
				if($edu_level)
					$small_tag.="$edu_level, ";
				if($my_income)
					$small_tag.=$my_income.", " ;
				if($occupation)
					$small_tag.="<BR> $occupation";
				if($residence)
					$small_tag.=" in ".$residence;
			}
                        $yourinfo="";
			$yourinfo=$contact_details["YOURINFO"];

                        //Addition Ends ere.
                         
			// IVR-Callnow
			if($page=='callnow')
			{
				$TOI=$CON_TIME[$data_3d[$k]["PROFILEID"]];
				$dateTimeArr = datetime_format($TOI);
				$calltime =$dateTimeArr[1];
				$year=substr($TOI,0,4);
				$month=substr($TOI,5,2);
				$day=substr($TOI,8,2);
				$TOI=my_format_date($day,$month,$year,1);
				$TOI =$TOI." at ".$calltime;
			}
			// Ends

			/*if($data_3d[$k]['SEEN'] != "Y")
			{
				$css["style"] = "";
				$css["pStyle"] = "";
				$css["messageBackGroundTopCurves"] = "<div style=\"margin:0 5px; border-width:1px 0 0 0; margin-bottom:-1px;\" class=\"c\"></div><s class=\"c c1\"></s> <s class=\"c c2\"></s> <s class=\"c c3\"></s> <s class=\"c c4\"></s>";
				$css["messageBackGroundBottomCurves"] = "<s class=\"c c4\"></s> <s class=\"c c3\"></s> <s class=\"c c2\"></s> <s class=\"c c1\"></s>";
				$css["messageBackGround"] = "style=\"padding:5px 10px; _float:left; border:1px solid #f4ff61; border-width:0 1px; margin-top:0; _margin-top:-1px; background:#faffb9;\"";
				$css["messageBackGroundDiv"] = "<div style=\"margin:0 5px; border-width:1px 0 0 0; margin-top:0;\" class=\"c\"></div>";
				$css["boldClass"] = "class=\"red_c fl\"";
				$css["curveFirstClass"] = "class=\"wh_8_22 y_t_l p_abs t0 l0 sprt_cn_ctr\"";
				$css["curveSecondClass"] = "class=\"wh_8_22 y_t_r p_abs t0 r0 sprt_cn_ctr\"";
				$css["curveThirdClass"] = "class=\"wh_5 y_b_l p_abs b0 l0 sprt_cn_ctr\"";
				$css["curveFourthClass"] = "class=\"wh_5 y_b_r p_abs b0 r0 sprt_cn_ctr\"";
			}
			else
			{*/
				$css["messageBackGroundTopCurves"] = "<div style=\"margin:0 5px; border-width:1px 0 0 0; margin-bottom:-1px;\" class=\"d\"></div><s class=\"d d1\"></s> <s class=\"d d2\"></s> <s class=\"d d3\"></s> <s class=\"d d4\"></s>";
				$css["messageBackGroundBottomCurves"] = "<s class=\"d d4\"></s> <s class=\"d d3\"></s> <s class=\"d d2\"></s> <s class=\"d d1\"></s>";
				$css["messageBackGround"] = "style=\"padding:9px 10px 1px; _float:left; margin-top:0; _margin-top:-1px; _padding:0 10px;border:1px solid #a9a9a9\"";
				$css["messageBackGroundDiv"] = "<div class=\"d\" style=\"border-width: 1px 0pt 0pt; margin: 0pt 5px;\"></div>";
				$css["boldClass"] = "class=\"gray fl\"";
				$css["style"] = "style=\"border:1px solid #c8c8c8; padding-bottom:10px\"";
				$css["pStyle"] = "style=\"background-position:0 -52px!important;\"";
				$css["curveFirstClass"] = "class=\"wh_8_22 y_t_l p_abs t0 l0 sprt_cn_ctr gry_t_l\"";
				$css["curveSecondClass"] = "class=\"wh_8_22 y_t_r p_abs t0 r0 sprt_cn_ctr gry_t_r\"";
				$css["curveThirdClass"] = "class=\"wh_5 y_b_l p_abs b0 l0 sprt_cn_ctr gry_b_l\"";
				$css["curveFourthClass"] = "class=\"wh_5 y_b_r p_abs b0 r0 sprt_cn_ctr gry_b_r\"";
			//}
		
			/*********Icons table parameters*******/
			//Determine whether this user has invalid phone or not
			
			$INV_PROF=0;
			$jprofile_result["viewed"]["INVALID_PHONE"] = 0;
			/*
			if(is_array($invalid_phone_array) && in_array($data_3d[$k]['PROFILEID'],$invalid_phone_array))
			{
				$jprofile_result["viewed"]["INVALID_PHONE"] = 1;
				$INV_PROF=1;
			}
			*/

			$bookmarked=0;

			//Determine whether this user is fav or not
			if($page=="favorite")
				$bookmarked=1;
			else
			{
                               	if(is_array($bookmark_array))// && array_key_exists($data_3d[$k]['PROFILEID'],$bookmark_array))
                                if (isset($bookmark_array[$data_3d[$k]['PROFILEID']])) $bookmarked=1;
					
				
			}

			//Determine whether this user is ignored member or not
			$ignore=0;
			if($ignoreProfiles){
				if(in_array($data_3d[$k]['PROFILEID'],$ignoreProfiles))
					$ignore=1;
			}

			//Determine the HIV status of the user
			$Hiv = $contact_details["HIV"];

			// IVR- Verification, Phone check
			$show_phone=$contact_details["SHOW_PHONE"];
			$validPhone = "N";
			if($show_phone && $chkPhone)
			{

				//if($validPhoneArr[$contact_details["PROFILEID"]]["MOB"] || $validPhoneArr[$contact_details["PROFILEID"]]["LANDLINE"])
				if (!is_array($chk_phoneStatus))
				$chk_phoneStatus =getPhoneStatusAll($phoneArray);
				if($chk_phoneStatus[$data_3d[$k]['PROFILEID']] =='Y')
					$validPhone = "Y";
				elseif($chk_phoneStatus[$data_3d[$k]['PROFILEID']]=='I'){
					$jprofile_result["viewed"]["INVALID_PHONE"] = 1;
					$INV_PROF='I';
				}
			}
			$pageDetail["validPhone"][$contact_details["PROFILEID"]] = $validPhone;
			// IVR- Verification Ends

			$membership_image='';
			$membership_text = "";

			if(CommonFunction::isErishtaMember($contact_details["SUBSCRIPTION"])){
				$membership_image="e_rshta_icon";
				$membership_text=mainMem::ERISHTA_LABEL;
			}

                        if(CommonFunction::isJsExclusiveMember($contact_details["SUBSCRIPTION"])){
				$membership_image="jsexlusivebg";
				$membership_text=mainMem::JSEXCLUSIVE_LABEL;
                                
			}
			$eValueMember = CommonFunction::isEvalueMember($contact_details["SUBSCRIPTION"]);
			if($eValueMember){
				$membership_image="e_vlu_icon";
				$membership_text=mainMem::EVALUE_LABEL;
			}

			$horoscope[$data_3d[$k]["PROFILEID"]]=$HOROSCOPE_ARRAY[$data_3d[$k]["PROFILEID"]]["HOROSCOPE"];
			$horoscope_astro=$HOROSCOPE_ARRAY[$data_3d[$k]["PROFILEID"]]["ASTRO_DETAILS"];


			//Check if the profile is viewed by Login User.
			$viewer=$pageDetail["self_profileid"];
			$viewed=$data_3d[$k]['PROFILEID'];
			$viewed_by_user='no';
			$icons_table="";//set_icons($INV_PROF,$bookmarked,$ignore,$validPhone,$membership_image,$horoscope,$viewed_by_user,$Hiv,'',$sno,$data_3d[$k]["PROFILEID"],$horoscope_astro);
			 
			/**********Ends here***********/

			//$pageDetail["photoDate"] = $photoDate;
			$contact_line=get_contact_line($page,$ACTION[$data_3d[$k]["PROFILEID"]],$gender,$data["GENDER"],$CONTACT_STATUS[$data_3d[$k]["PROFILEID"]],$data_3d[$k]["PROFILEID"],$data_3d[$k]["UPLOADED"],$pageDetail,$isMobile);
			if($message_org && !strstr($contact_line[0],'message'))
				$contact_line[0].=" with the following message:";	
 			$eoiViewed['title'] = $gender=='M'?'He viewed your Expression of interest':'She viewed your Expression of interest';
			/*In/Out image removed
			if($contact_line[1]=="in")
				$image_in_out="fl sprt_cn_ctr";
			elseif($contact_line[1]=="out")
				$image_in_out="fl sprt_cn_ctr";
			else
				$image_in_out="";
			*/
			$image_in_out="";
			$viewed_profile_checksum=md5($data_3d[$k]['PROFILEID'])."i".$data_3d[$k]['PROFILEID'];





			/* IVR-Callnow 
			 * New feature added
			 * code added to gets all calls to-from to a single user
			*/
			if($page=='callnow' && $filter=='A'){
                        	$contact_line_calls_arr=get_contact_line_calls($page,$ACTION[$data_3d[$k]["PROFILEID"]],$gender,$data["GENDER"],$CONTACT_STATUS[$data_3d[$k]["PROFILEID"]],$data_3d[$k]["PROFILEID"],$data_3d[$k]["UPLOADED"],$pageDetail,$callDataArray);
				$contact_line_arr  =$contact_line_calls_arr[0];
				$in_out_arr 	   =$contact_line_calls_arr[1];
				$status_arr   	   =$contact_line_calls_arr[2];	
				$callsarray =array();		
				for($i=0;$i<count($contact_line_calls_arr);$i++){
					$callsarray[$i] =array(
							"CONTACT_LINE"=>$contact_line_arr[$i],
							"IN_OUT_IMAGE"=>$in_out_arr[$i],	
							"CALL_STATUS" =>$status_arr[$i]	
						);
				}
			}
			/* Code Ends */

			/********Contacts Gadget*****/
			$displayContactGadget = displayContactGadget($page, $CONTACT_STATUS[$data_3d[$k]["PROFILEID"]],$pageDetail,$callDataArray, $callArray[$data_3d[$k]["PROFILEID"]],$eValueMember);
			$contactsGadget = "";
			$contact_locked = 0;
			$reason = "";
			$filteredProfile = 0;
			$displayContactGadget=false;
			if($displayContactGadget)
			{
                                $fieldsArray="*";
                                $loginProfile = LoggedInProfile::getInstance("newjs_master",$data['PROFILEID']);
                                //To prevent firing multiple queries
                                if(!$loginProfile->getGENDER())
                                        $loginProfile->getDetail('', '', $fieldsArray);
                                $otherProfile = new Profile("newjs_master",$data_3d[$k]["PROFILEID"]);
				$otherProfile->getDetail('', '', $fieldsArray);
				$contact = new Contacts($loginProfile, $otherProfile);
				$contactHandlerObj = new ContactHandler($loginProfile,$otherProfile,"INFO",$contact,'CONTACT_DETAIL',ContactHandler::PRE);
				$smarty->assign("other_sub",false);
				if($loginProfile->getPROFILE_STATE()->getPaymentStates()->isPAID()==false)
				{
					if($otherProfile->getPROFILE_STATE()->getPaymentStates()->getEVALUE()==true)
					{
						$smarty->assign("other_sub","freeUserToEvalue");
					}
					elseif($otherProfile->getPROFILE_STATE()->getPaymentStates()->getERISHTA()==true)
					{
						if($CONTACT_STATUS[$data_3d[$k]["PROFILEID"]]['ACTION']=="RECEIVER") 
							$smarty->assign("other_sub","freeUserToErishtaDisabled"); 
						else 
							$smarty->assign("other_sub","freeUserToErishta"); 
					}
					elseif($otherProfile->getPROFILE_STATE()->getPaymentStates()->isPAID()==false)
					{
						$smarty->assign("other_sub","freeUserToFree");
					}
				}
				$substate=$loginProfile->getPROFILE_STATE()->getFTOStates()->getSubState();
				$FTOFlag = $loginProfile->getPROFILE_STATE()->getFTOStates()->getAcceptanceFlag();
				$sender = $contactHandlerObj->getContactInitiator();
				$privArr=$contactHandlerObj->getPrivilegeObj()->getPrivilegeArray();
					$smarty->assign("CONTACT_LOCKED",'1');
					$smarty->assign("CONTACT_USERNAME",$otherProfile->getUSERNAME());
					$smarty->assign("CONTACT_HIS_HER",$otherProfile->getGENDER()=="M"?"his":"her");
					$smarty->assign("CONTACT_HIM_HER",$otherProfile->getGENDER()=="M"?"him":"her");
					$showContactWidget = true;
					$contactEngineObj=ContactFactory::event($contactHandlerObj);
						
					if($contactEngineObj->getComponent()->innerTpl=="profile_cd_error" || $contact->getType() == 'D' || $contact->getType() == 'E' || $contact->getType() == 'C')
					{
						$showContactWidget = false;
						$evalue = false;
						//$smarty->assign("CONTACT_LOCKED",'5');
						//$smarty->assign("CON_DET_MES",$contactEngineObj->getComponent()->errorMessage);
					}
					elseif($privArr[0]['CONTACT_DETAIL']['VISIBILITY']=='N')
					{
						if (($substate=="C1" || $substate=="C2" || $substate=="C3"||$substate=="D1" || $substate=="D2" || $substate=="D3"|| $substate=="D4" || $substate=="E3" ) && $FTOFlag == "I" && $sender == "R" )
						{
							$smarty->assign("CONTACT_LOCKED",'4');
						}	
						elseif($substate=="C1" || $substate=="C2" || $substate=="C3" )
						{
							$smarty->assign("CONTACT_LOCKED",'2');
							$smarty->assign("expirydate",$loginProfile->getPROFILE_STATE()->getFTOStates()->getExpiryDate('y'));
						}
						
						elseif($substate=="E3" && $sender == "S" )
						{
							$smarty->assign("CONTACT_LOCKED",'3');
							$inboundAcceptLimit = $loginProfile->getPROFILE_STATE()->getFTOStates()->getInboundAcceptLimit();
							$smarty->assign("LIMIT",$inboundAcceptLimit);
						}
						elseif($substate=="E1" || $substate=="E2" ||$substate=="F" || $substate=="G" )
						{
							$smarty->assign("CONTACT_LOCKED",'4');
						}
						elseif($substate=="E4" && $sender == "R" && $FTOFlag == "T")
						{
							$smarty->assign("CONTACT_LOCKED",'6');
							$outboundAcceptLimit = $loginProfile->getPROFILE_STATE()->getFTOStates()->getOutboundAcceptLimit();
							$smarty->assign("LIMIT",$outboundAcceptLimit);
						}
						elseif($substate=='E5' && $FTOFlag == "T")
						{
							$smarty->assign("CONTACT_LOCKED",'7');
							$TotalAcceptLimit = $loginProfile->getPROFILE_STATE()->getFTOStates()->getTotalAcceptLimit();
							$smarty->assign("LIMIT",$TotalAcceptLimit);
						}
						else
						{
							$showContactWidget = false;
							$evalue = false;
							$smarty->assign("CONTACT_LOCKED",'5');
						}
						
					}									
					else
					{ 
						$contactDetailsArr=$contactEngineObj->getComponent()->contactDetailsObj->getContactDetailArr();
						foreach($contactDetailsArr as $key=>$value)
						{
							if(substr($value["LABEL"],0,6)=="Mobile")
							$smarty->assign("SHOW_MOBILE",$value["VALUE"]);
							elseif(substr($value["LABEL"],0,5)=="Email")
							$smarty->assign("EMAIL_ID",$value["VALUE"]);
							elseif(substr($value["LABEL"],0,8)=="LandLine")
							$smarty->assign("PHONE_NO",$value["VALUE"]);
							elseif(substr($value["LABEL"],0,9)=="Messanger")
							$smarty->assign("SHOW_MESSENGER",$value["VALUE"]);
							elseif(substr($value["LABEL"],0,7)=="Address")
							$smarty->assign("SHOW_ADDRESS",$value["VALUE"]);
							elseif(substr($value["LABEL"],0,6)=="Parent")
							$smarty->assign("SHOW_PARENTS_ADDRESS",$value["VALUE"]);
							
						}
					}
				
				if($showContactWidget) 
					$contactsGadget =  $smarty->fetch("contactDetailGadget.htm");
				$smarty->assign("SHOW_MOBILE","");
				$smarty->assign("EMAIL_ID","");
				$smarty->assign("PHONE_NO","");
				$smarty->assign("SHOW_MESSENGER","");
				$smarty->assign("SHOW_ADDRESS","");
				$smarty->assign("SHOW_PARENTS_ADDRESS","");
				
				
						
				/*
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
				$NUDGES = array();
				$contact_status_new = $CONTACT_STATUS[$data_3d[$k]["PROFILEID"]];
				if($CONTACT_STATUS[$data_3d[$k]["PROFILEID"]]["ACTION"] == "RECEIVER")
					$contact_status_new["R_TYPE"] = "R".$CONTACT_STATUS[$data_3d[$k]["PROFILEID"]]["TYPE"];
				if($CONTACT_STATUS[$data_3d[$k]["PROFILEID"]]["TYPE"] == "ACC")
					$contact_status_new["TYPE"] = "A";
				$jprofile_result["viewed"] = $contact_details;	
				$jprofile_result["viewed"]["VIEW_CONTACT_DETAILS"] = $callArray[$data_3d[$k]["PROFILEID"]];
				$jprofile_result["viewed"]["CALLED_CHECKED"] = true;
				$jprofile_result["viewed"]["VALID_PHONE_CHECKED"] = true;

				// IVR- Verification
				if($chkPhone){
					$validPhoneArr =array();
					$chk_mobStatus =getPhoneStatus($contact_details,'','M');
					$chk_landlStatus =getPhoneStatus($contact_details,'','L');
					if($chk_mobStatus =='Y')
						$validPhoneArr[$data_3d[$k]["PROFILEID"]]['MOB']=$chk_mobStatus;
					if($chk_landlStatus =='Y')
						$validPhoneArr[$data_3d[$k]["PROFILEID"]]['LANDLINE']=$chk_landlStatus;
					$jprofile_result["viewed"]["VALID_PHONE"] = $validPhoneArr[$data_3d[$k]["PROFILEID"]];
				}
				// Ends IVR- Verification				

				$samegender = false;
				if($contact_details["GENDER"] == $self_details["GENDER"])
					$samegender = true;
				elseif(check_spammer_filter($jprofile_result,'viewContactDetails'))
			                $filteredProfile=1;

				$jprofile_result["viewer"]["NO_DRAFTS"] = true;
				$from_search=1;
				$jprofile_result["viewed"]["CALLNOW_CHECKED"] = true;
				$jprofile_result["viewed"]["CALL_ANONYMOUS"] = $callnowAccess_Arr[$jprofile_result["viewed"]["PROFILEID"]];
				express_page($jprofile_result,$data,$contact_status_new,$NUDGES,$spammer='',$filteredProfile,$contact_limit_reached='',$samegender);
				if($contact_details["ACTIVATED"] == "D")
				{
					$contact_locked = 0;
					$reason = "Profile $contact_details[NAME] has been deleted";
				}
				if($contact_details["ACTIVATED"] == "H")
				{
					$contact_locked = 0;
					$reason = "Profile $contact_details[NAME] has been hidden";
				}
				elseif($CONTACT_STATUS[$data_3d[$k]["PROFILEID"]]["TYPE"] == "D" && $CONTACT_STATUS[$data_3d[$k]["PROFILEID"]]['ACTION'] == "RECEIVER")
				{
					$contact_locked = 0;
					$reason = "Profile $contact_details[NAME] has declined you";
					//elseif($CONTACT_STATUS[$data_3d[$k]["PROFILEID"]]['ACTION'] == "SENDER")
					//	$reason = "You have declined profile $contact_details[NAME]";
				}
				/*if($eValueMember && $contact_locked)
					$contact_locked = 1;
				$smarty->assign("reason", $reason);
				$smarty->assign("CONTACT_LOCKED",$contact_locked);
				$smarty->assign("CONTACT_USERNAME",$contact_details["NAME"]);
				$contactsGadget =  $smarty->fetch("contactAddressGadget.htm");
				*/
			}
			/*********Ends here**********/
			
			$viewContactDetail = false;
			if($paid || isPaid($contact_details["SUBSCRIPTION"]))
				$viewContactDetail = true;
		if($isMobile)	
			$linksLabel=get_links_mobile($page,$ACTION[$data_3d[$k]["PROFILEID"]],$sub,$data["SUBSCRIPTION"],$data_3d[$k]["PROFILEID"],$checksum_forp,$viewed_profile_checksum,$CONTACT_STATUS[$data_3d[$k]["PROFILEID"]],$index,$contact_details["NAME"],$show_contacts, $pageDetail, $contact_details["SUBSCRIPTION"], $tempContactStatus[$data_3d[$k]["PROFILEID"]],$contact_locked,$callDataArray);
		else
			$linksLabel=get_links($page,$ACTION[$data_3d[$k]["PROFILEID"]],$sub,$data["SUBSCRIPTION"],$data_3d[$k]["PROFILEID"],$checksum_forp,$viewed_profile_checksum,$CONTACT_STATUS[$data_3d[$k]["PROFILEID"]],$index,$contact_details,$show_contacts, $pageDetail, $tempContactStatus[$data_3d[$k]["PROFILEID"]],$contact_locked,$callDataArray);
			$links = $linksLabel["links"];
			$callAccess = $linksLabel["callNow"];
			$evalue = "";//$linksLabel["evalue"];
			
			if($evalue)
				{
					if ($page != "accept" || $page != "viewed_contacts" || $page != "messages") 
					{
						$contactTypeEvalue = $CONTACT_STATUS[$data_3d[$k]["PROFILEID"]]["TYPE"];
						if($contactTypeEvalue == "D" || $contactTypeEvalue == "C" || $contactTypeEvalue=="E" || $contact_details["GENDER"] == $self_details["GENDER"])
							$evalue = false;
					}
				}
			// IVR-Callnow, Setting condition check
			$callNow =false;
	                if($callnowAccess_Arr[$contact_details["PROFILEID"]] =='Y' && $callAccess)
       		        	$callNow =true;
			// End

			$show_ex_detail ='';
			if($page == "intro_call"){
				if(in_array($data_3d[$k]["PROFILEID"],$ex_detail_form_arr))
					$show_ex_detail ='Y';
			}

			$currentKey = getCurrentKey($data_3d[$k]["PROFILEID"], $data_3d);
			$profileids = getProfileString($data_3d, $currentKey);
			$cntProfiles = count($data_3d);
			$index++;
			$seal=seal_status($data_3d[$k]['PROFILEID']);
			$profile_checksum=createChecksumForSearch($data_3d[$k]['PROFILEID']);
			//getUploadLink($page);
			$contacts[]=array( "NAME" =>$contact_details["NAME"],
					"CALLS_ARRAY"=>$callsarray,
					//"TOP_LEFT"=>$top_left,
					//"TOP_BG"=>$top_bg,
					//"TOP_RIGHT"=>$top_right,
					//"TOP_BORDER"=>$top_border,
					//"BOTTOM_BG"=>$bottom_bg,
					//"BOTTOM_RIGHT"=>$bottom_right,
					//"BOTTOM_LEFT"=>$bottom_left,
					//"WIDTH"=>$width,
					//"MARGIN"=>$margin,
					"PROFILEID"=>$data_3d[$k]['PROFILEID'],//Added By lavesh
					"IS_NUDGE_CONTACT"=>$nudgeContact[$data_3d[$k]['PROFILEID']],
					//"INFO" =>$info,//Added By lavesh
					"MY_PHOTO"=>$my_photo,//Added By lavesh
					"VIEW_CONTACT_DETAIL"=>$viewContactDetail,
					//"SUBSCRIPTION"=>$subscription[0],//added by lavesh
					"PHOTO_REQ" =>$photo_req[$data_3d[$k]['PROFILEID']]?1:0,//added by lavesh
					"TOI" =>$TOI,//ADDED by lavesh
					//"AGE" => $contact_details["AGE"],
					//"HEIGHT" => $contact_details["HEIGHT"],
					//"CASTE" => $contact_details["CASTE"],
					//"MTONGUE" => $contact_details["MTONGUE"],
					//"DEGREE" => $contact_details["EDUCATION"],
					//"OCCUPATION" => $contact_details["OCCUPATION"],
					//"RESIDENCE" => $contact_details["RESIDENCE"],
					//"INCOME" => $contact_details["INCOME"],
					//"BOLDLISTING" => $bold_listing,
					//"SHOW_CONTACTS" => $show_contacts,
					//"ERISHTA_MEMBER" => $erishta_member,
					"PHOTOCHECKSUM" => md5($data_3d[$k]['PROFILEID']+5)."i".($data_3d[$k]['PROFILEID']+5),
					"PROFILECHECKSUM" =>$viewed_profile_checksum,
					//"PROFILE_CHECKSUM"=>$profile_checksum,
					"YOURINFO" => $yourinfo,
					//"PROTITLE" => $protitle,
					//"TIME" =>$date_display,
					//"PHOTO" =>$photo,
					"HAVEPHOTO" =>$havephoto,
					//"MARRIAGE_BUREAU" =>$contact_details["MARRIAGE_BUREAU"],
					//"STAT_UNAME" =>$stat_uname,
					//"GENDER" =>$contact_details["GENDER"],
					//"SUBCASTE" => $contact_details["SUBCASTE"],
					//"GOTHRA"=> $contact_details["GOTHRA"],
					//"NAKSHATRA"=>$contact_details["NAKSHATRA"],
					"PHOTOCHECKSUM_NEW"=>$contact_details["PHOTOCHECKSUMNEW"],
					"BOOKMARK" =>$bookmarked,
					//"BKNOTE"=>$bknote,
					//"BKNOTEDIS"=>$bknotedis,
					//"COUNT" =>$contactcount,
					"ONLINE" => $online,
					"GTALK_ONLINE" => $gtalk_online,//added by manoranjan
					//"CONTACTID"=>$contactid,
					//"MESSAGE_OPERATOR"=> $message_operator,//sadaf
					//"FLAG_MSG_POPUP"=>$flag_msg_popup,
					//"CHAT_REQUEST"=>$chat,
					//"PROFILEIDCHAT"=>$contact_details["PROFILEID"],
					//"IGNORE"=>$ignore,//by lavesh
					//"HOROSCOPE"=>$horoscope,
					//"HOROSCOPE_ASTRO" =>$horoscope_astro,
					//"HORO_LINK" =>$contact_details["HORO_LINK"],
					//"COMPATIBILITY_LINK" => $compatibility_link,
					"VIEWED_BY_USER"=>$data_3d[$k]['SEEN'],
					"MOB_VERIFIED"=>$contact_details["MOB_VERIFIED"],
					//"DAYS_TO_RESPOND"=>$days_to_respond,
					//"SHOW_THUMB"=>$trends_logic[$data_3d[$k]['PROFILEID']],
					//"COLOR" =>$COLOR,
					"MEMBER_101"=>$member_101,
					"OFFLINE_PROFILE"=>$offline_profile,
					"MEMBERSHIP_IMAGE"=>$membership_image,
					"MEMBERSHIP_TEXT"=>$membership_text,
					"ISALBUM"=>$is_album,
					"SMALL_TAG"=>$small_tag,
					"icons_table"=>$icons_table,
					"image_in_out"=>$image_in_out,
					"contact_line"=>$contact_line[0],
					"eoi_viewed"=>$eoiViewed,
					"MESSAGE"=>$message_org,
					"EX_DETAIL_FORM"=>$show_ex_detail,
					"READ_MORE"=>$readMoreLink,
					"LINKS"=>$links,
					"EVALUE"=>$evalue,
					"SEAL"=>$seal,
					"lastLogin"=>$contact_details["LAST_LOGIN_DT"],
					//"SPECIAL_MSG_DIV"=>$special_msg_div,
					"CSS"=>$css,
					"ENABLE_CALL_NOW"=>$callNow,
					"VIEWPROFILE_LINK"=>$SITE_URL.'/profile/viewprofile.php?checksum='.$CHECKSUM.'&profilechecksum='.$viewed_profile_checksum.'&profileids='.$profileids.'&total_rec='.$cntProfiles.'&actual_offset='.$currentKey.'&contact='.$pageDetail["contact"].'&self='.$pageDetail["self"].'&self_profileid='.$pageDetail["self_profileid"].'&flag='.$pageDetail["flag"].'&type='.$pageDetail["type"].'&flag='.$pageDetail["flag"].'&date_search='.$pageDetail["date_search"].'&start_date='.$pageDetail["start_date"].'&end_date='.$pageDetail["end_date"].'&page='.$pageDetail["page"].'&stype='.$pageDetail["stype_mobile"].'&fromPage=contacts',
					"CONTACT_GADGET"=>$contactsGadget,
					"MsgStripped"=>$msgTrunkated
					);
					//print_r($contacts);die;
                                        $smarty->assign("HOROSCOPE",$horoscope);
			unset($days_to_respond);
                            
		}


		set_address_details($jprofile_result['viewer']['PHONE_MOB'],$jprofile_result['viewer']['PHONE_RES'],$jprofile_result['viewer']['STD'],$jprofile_result['viewer']['ISD'],$jprofile_result['viewer']['EMAIL']);
		$pageDetail = get_buttons($pageDetail,$countOfPhotosUploaded);	
		$smarty->assign("uploadPhotoLink",$pageDetail["uploadPhotoLink"]);
		$smarty->assign("uploadHoroLink",$pageDetail["uploadHoroLink"]);
		}


		get_all_drafts($paid,$viewerdata["RELATION"]);

		}
		unset($data_3d);
		}
		$gotItBandObj = new GotItBand($data['PROFILEID']);
		if($page=="matches")
		{
			$showGotItBand = $gotItBandObj->showBand(GotItBand::$MATCHALERT,$viewerdata['ENTRY_DT']);
			$smarty->assign("GotItBandPage",GotItBand::$MATCHALERT);
			$smarty->assign("GotItBandMessage",GotItBand::$educationMATCHALERT);
		}
		elseif($page=="kundli")
		{
			$showGotItBand = $gotItBandObj->showBand(GotItBand::$KUNDLI_MATCHES,$viewerdata['ENTRY_DT']);
			$smarty->assign("GotItBandPage",GotItBand::$KUNDLI_MATCHES);
			$smarty->assign("GotItBandMessage",GotItBand::$educationKUNDLI_MATCHES);
		}
		else
		{
			$showGotItBand = $gotItBandObj->showBand(GotItBand::$CONTACTS,$viewerdata['ENTRY_DT']);
			$smarty->assign("GotItBandPage",GotItBand::$CONTACTS);
			$smarty->assign("GotItBandMessage",GotItBand::$educationCONTACTS);
		}
		$smarty->assign("showGotItBand",$showGotItBand);
		$smarty->assign("GOT_IT_BAND",$smarty->fetch(JsConstants::$docRoot."/../apps/jeevansathi/templates/_gotItBand.tpl"));
		//Calculation ends ehere
		//unset($partner_array);
		if($contacts)
			$lp_cnt=COUNT($contacts);
		else
			$lp_cnt=0;
			//$new_len=12;
		//Added By lavesh
                $smarty->assign("RESULTS_ARRAY_COUNT",$lp_cnt);
                
        if($isMobile)
        getTopPanelButton($page,$filter,$data["PROFILEID"]);
			
		if($SHOW_CATEGORY_SEARCH && $initialCount>20)
		{
			if($search_submit)
				$searchResultsCount = $totalrec;
			$smarty->assign("searchResultsCount", $searchResultsCount);
			$searchTitle = getSearchTitle($initialCount, $page);
			$clearSearchUrl = getClearSearchUrl($page,$loggedInChecksum,$SITE_URL);
			$smarty->assign("searchTitle",$searchTitle);
			$smarty->assign("clearSearchUrl",$clearSearchUrl);
			$smarty->assign("SHOW_CATEGORY_SEARCH",$SHOW_CATEGORY_SEARCH);
			$categorySearchGadget = $smarty->fetch("contacts_category_search_gadget.htm");
			$smarty->assign("categorySearchGadget", $categorySearchGadget);
		}
                if($SHOW_DATE_SEARCH && $initialCount)
                {
                        if($date_search_submit)
                                $searchResultsCount = $totalrec;
                        $smarty->assign("searchResultsCount", $searchResultsCount);
                        $searchTitle = getSearchTitle($initialCount, $page);
                        $clearSearchUrl = getClearSearchUrl($page,$loggedInChecksum,$SITE_URL);
			$search_string = $date1xx." - ".$date2xx;
			$smarty->assign("search_string",$search_string);
			$smarty->assign("initialCount", $initialCount);
                        $smarty->assign("searchTitle",$searchTitle);
                        $smarty->assign("clearSearchUrl",$clearSearchUrl);
                        $smarty->assign("SHOW_DATE_SEARCH",$SHOW_DATE_SEARCH);
                        $dateSearchGadget = $smarty->fetch("contacts_date_search_gadget.htm");
                        $smarty->assign("dateSearchGadget", $dateSearchGadget);
                }

                get_title($pageDetail,$isMobile,$data["PROFILEID"]);

		//For Right panel
		$smarty->assign("NO_SECOND_BANNER",1);
		
		//added by nikhil for chat links:
                $smarty->assign("checksum",$data["CHECKSUM"]);
                $smarty->assign("LOGGED_PERSON_PROFILEID",$data["PROFILEID"]);
                $smarty->assign("LOGGED_PERSON_USERNAME",$data["USERNAME"]);
		$smarty->assign("CONTACT_TYPE",$type.$flag);
		$smarty->assign("TYPE",$type);
		$smarty->assign("type",$type);
		$smarty->assign("VFLAG",$vflag);
		$smarty->assign("USERSUBSCRIPTION",$data["SUBSCRIPTION"]);
		$smarty->assign("CONTACTS_ARR",$contacts);
		$smarty->assign("INVOKE_LAYER",1);
		$smarty->assign("length",$PAGELEN);

		$NUDGE_CONTACT=trim($NUDGE_CONTACT,"#");
		$smarty->assign("NUDGE_CONTACT",$NUDGE_CONTACT);
		contact_center_leftpanel($pageDetail);
		$smarty->assign("contact_center_leftpanel",$smarty->fetch("contact_center_leftpanel.htm"));

		/**********Detail needed for common items: Header/ Footer etc*********/
		$smarty->assign("data",$data["PROFILEID"]);
		$smarty->assign("bms_topright",18);
		$smarty->assign("bms_right",28);
		$smarty->assign("bms_bottom",19);
		$smarty->assign("bms_left",24);
		$smarty->assign("con_chk",2);

		if($data)
		{
			$smarty->assign("bms_right",28);
			rightpanel($data);
			$smarty->assign("REVAMP_RIGHT_PANEL",$smarty->fetch("revamp_rightpanel.htm"));
		}
		//include_once("express_interest.php");

		/* Tracking Contact Center, as per Mantis 4724 Starts here */
		$end_time=microtime(true)-$start_tm;
		$smarty->assign("TRACK_FOOT",BrijjTrackingHelper::getTailTrackJs($end_time,true,2,"https://track.99acres.com/images/zero.gif"));
		/* Ends Here */
		if($printPage)
			$smarty->assign("small_header",true);
		$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
		$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
		//$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
		$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
		if($printPage)
			$smarty->display("contacts_accepted_print.htm");	
		else{
			if($isMobile){
				assignHamburgerSmartyVariables($data[PROFILEID]);
				$smarty->display("mobilejs/jsmb_contacts_made.html");
			}
			else
				$smarty->display("contact_center.htm");
		}
	}
	else 
	{
		$smarty->assign("login_mes","Please log in to continue");
		Timedout();
	}
// flush the buffer
if($zipIt && !$dont_zip_now)
        ob_end_flush();
?>
