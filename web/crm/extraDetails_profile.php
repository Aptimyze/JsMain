<?php
        include("connect.inc");
        include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
        include_once($_SERVER['DOCUMENT_ROOT']."/profile/contacts_functions.php");
        include_once($_SERVER['DOCUMENT_ROOT']."/profile/sphinx_search_function.php");
        include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
        include_once($_SERVER['DOCUMENT_ROOT']."/profile/sphinxclusterGlobalarrays.inc");
include_once(JsConstants::$docRoot."/commonFiles/connect_dd.inc");
        include_once($_SERVER['DOCUMENT_ROOT']."/profile/ntimes_function.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/profile/SymfonySearchFunctions.class.php");

$data =authenticated($cid);
// Query string: cid,checksum,table_name,paid_str       

if($data && $checksum)
{
	$checksumArr =explode("i",$checksum);	
	$profileid =$checksumArr[1];

	$profileCnt =300;
	$contacts_init_arr 	=array();
	$contacts_filtered_arr  =array();
	$contacts_accepted_arr  =array();
	$contacts_received_arr  =array();
	$free_profileidArr 	=array(); 
	$contacts_Cnt		=0;

	$contactResult_recdsum = getResultSet("SENDER","","","$profileid","","'I'","","TIME BETWEEN DATE_SUB(NOW(),INTERVAL 90 DAY) AND NOW()","","","","","","","$table_name");
        for($i=0;$i<count($contactResult_recdsum);$i++)
        	$contacts_init_arr[] = $contactResult_recdsum[$i]["SENDER"];
	$contacts_Cnt =count($contacts_init_arr);	

	if($contacts_Cnt<=$profileCnt)
	{	
		// New Filtered EOI Received
        	$contactResult_recdsum=getResultSet("SENDER","","",$profileid,"","'I'",'',"TIME BETWEEN DATE_SUB(NOW(), INTERVAL 90 DAY) AND NOW()","","","","","","","$table_name","","","'Y'","");
        	for($i=0;$i<count($contactResult_recdsum);$i++)
        		$contacts_filtered_arr[] = $contactResult_recdsum[$i]["SENDER"];
		$contacts_filtered_arr_Cnt =count($contacts_filtered_arr);	
		$contacts_Cnt +=$contacts_filtered_arr_Cnt;
        	// Filtered EOI Ends 
	}

	if($contacts_Cnt<=$profileCnt)
	{
		if($paid_str)
			$contactResult_recdsum = getResultSet("SENDER","","","$profileid","","'A'","","$paid_str","","","","","","","$table_name");
        	else
        		$contactResult_recdsum = getResultSet("SENDER","","","$profileid","","'A'","","","","","","","","","$table_name");

        	for($i=0;$i<count($contactResult_recdsum);$i++)
        		$contacts_accepted_arr[] = $contactResult_recdsum[$i]["SENDER"];
			$contacts_accepted_arr_Cnt =count($contacts_accepted_arr);
			$contacts_Cnt +=$contacts_accepted_arr_Cnt;
	}
	if($contacts_Cnt<=$profileCnt)
	{
		$contacts_received_arr =array_merge($contacts_init_arr,$contacts_filtered_arr,$contacts_accepted_arr);
       		$profile_str = "'".@implode("','",$contacts_received_arr)."'";

		if($profile_str)
		{
        		$sql="select PROFILEID,USERNAME from newjs.JPROFILE WHERE PROFILEID IN($profile_str) AND SUBSCRIPTION=''";
        		$res= mysql_query_decide($sql) or die("$sql".mysql_error_js());
        		while($row=mysql_fetch_array($res))
			{
        			$free_profileidArr[] = $row['PROFILEID'];
				$usernameArr[$row['PROFILEID']] =$row['USERNAME'];
			}
		}
		for($i=0; $i<count($free_profileidArr); $i++){
			$pid =$free_profileidArr[$i];
			$ntimes = ntimes_count($pid,"SELECT");	
			$newArray_viewedCnt[$pid]=$ntimes;	
			$newArray_viewedPid[$ntimes]=$pid;
		}
		if(isset($newArray_viewedCnt))
			rsort($newArray_viewedCnt);

		$max_viewed1		=$newArray_viewedCnt[0];
		$max_viewed2		=$newArray_viewedCnt[1];
		$max_viewed_profileid1	=$newArray_viewedPid[$max_viewed1];
		$max_viewed_profileid2  =$newArray_viewedPid[$max_viewed2];
		$max_viewed_username1 	=$usernameArr[$max_viewed_profileid1];
		$max_viewed_username2 	=$usernameArr[$max_viewed_profileid2];
	}
	else
	{
		$max_viewed_username1='';
		$max_viewed_username2='';
	}
	$smarty->assign("max_viewed_username1",$max_viewed_username1);
	$smarty->assign("max_viewed_username2",$max_viewed_username2);
        $smarty->assign("profileid1",$max_viewed_profileid1);
        $smarty->assign("profileid2",$max_viewed_profileid2);
	
	// Added by Reshu for highest profile view non contacted matches start

	// retrieving gender of the profile
	$sql="select GENDER from newjs.JPROFILE WHERE PROFILEID='$profileid'";
        $res= mysql_query_decide($sql) or die("$sql".mysql_error_js());
        while($row=mysql_fetch_array($res))
        {
            $profileGender= $row['GENDER'];
        }
	if($profileGender=='M')
		$searchTable="SEARCH_FEMALE";
	else 
		$searchTable="SEARCH_MALE";

	// retrieving contacted and ignored profiles and merging with all the contacted profiles array
	$contact_rcvd_profile = getResultSet("SENDER","","","$profileid","","","","","","","","","","","$table_name");
	$contact_made_profile = getResultSet("RECEIVER","$profileid","","","","","","","","","","","","","$table_name");
	for($i=0;$i<count($contact_rcvd_profile);$i++)
                $contacted_arr[] = $contact_rcvd_profile[$i]["SENDER"];
	for($i=0;$i<count($contact_made_profile);$i++)
                $contacted_arr[] = $contact_made_profile[$i]["RECEIVER"];
	unset($contact_rcvd_profile);
	unset($contact_made_profile);

	$ignored_profiles_arr      =array();
	$sql="SELECT IGNORED_PROFILEID FROM newjs.IGNORE_PROFILE WHERE PROFILEID =$profileid UNION SELECT PROFILEID as  IGNORED_PROFILEID FROM newjs.IGNORE_PROFILE WHERE IGNORED_PROFILEID ='$profileid'";
        $res= mysql_query_decide($sql) or die("$sql".mysql_error_js());
        while($row=mysql_fetch_array($res))
        {
              $ignored_profiles_arr[] = $row['IGNORED_PROFILEID'];
        }
	if(is_array($contacted_arr) && is_array($ignored_profiles_arr))
	{
		$contacted_ignored_arr=array_merge($contacted_arr,$ignored_profiles_arr);
	
	}
	elseif(is_array($contacted_arr))
		 $contacted_ignored_arr=$contacted_arr;
	else
		$contacted_ignored_arr=$ignored_profiles_arr;
	unset($ignored_profiles_arr);
	// retrieving profile ids of 2 highest NTIMES profiles from log and search table which are not contacted nor ignored 
	 $db=connect_81();
        $sql="select L.USER,S.NTIMES from matchalerts.LOG AS L INNER JOIN newjs.$searchTable AS S ON L.USER=S.PROFILEID AND L.RECEIVER='$profileid'";
	if(is_array($contacted_ignored_arr))
	{
		 $h_profile_str = "'".implode("','",$contacted_ignored_arr)."'";
		 $sql=$sql." AND L.USER NOT IN($h_profile_str) ";
	}
	$sql=$sql."ORDER BY S.NTIMES DESC LIMIT 0,2";
        $res= mysql_query_decide($sql) or die("$sql".mysql_error_js());
        while($row=mysql_fetch_array($res))
        {
             $h_profileidArr[] = $row['USER'];
        }
	$db=connect_db();
	
	// retrieving user name for the 2 highest NTIMES times profile 
	if(is_array($h_profileidArr))
	{
		 $h1_profile_str = "'".implode("','",$h_profileidArr)."'";

		$sql="select PROFILEID,USERNAME from newjs.JPROFILE WHERE PROFILEID IN ($h1_profile_str) ORDER BY FIELD(PROFILEID,$h1_profile_str) ";
        	$res= mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$count=0;
        	while($row=mysql_fetch_array($res))
       		{
                	$h_usernameArr[$count]['USERNAME'] =$row['USERNAME'];
			$h_usernameArr[$count]['PROFILEID'] =$row['PROFILEID'];
			$count++;
	        }
	}
	$smarty->assign("high_viewed_username",$h_usernameArr);
  
	// #########################   Matche Count for the DPP  Start ###########################

	$from_myjs=1;
	$MEM_LOOK=1;
	$crmback='admin';

	$jpartnerObj=new Jpartner;
	$mysqlObj=new Mysql;
	$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
	$myDb=$mysqlObj->connect("$myDbName");
	$jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);
	if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$profileid))
	{
		// Count for Religion+Age Match	
        	$params_array =array("gender","religionArr","casteArr","mtongueArr","lage","hage","withphotoArr","manglikArr","mstatusArr","havechildArr","lheight","hheight","btypeArr","complexionArr","dietArr","smokeArr","drinkArr","handicappedArr","occupationArr","country_resArr","city_resArr","edu_levelArr","edu_level_newArr","sortArr","onlineArr","incomeArr","relationArr","nriArr","page","bread_crumb","original_sid","caste_mapping","force","searchid","STYPE","live_parents","Sub_caste","horoscopeArr","sampradayArr","urduArr","hijabArr","mathabArr","amritdhariArr","cut_hairArr","turbanArr","zarathustriArr","wstatusArr","handicappedArr","nhandicappedArr","hiv","keywords","kwd_rule","Login","Contact_visible","subscriptionArr");
        	foreach($params_array as $key=>$val)
        	{
        	        unset($$val);
        	}

        	$religionArr=search_display_format($jpartnerObj->getPARTNER_RELIGION());
        	$lage=$jpartnerObj->getLAGE();
        	$hage=$jpartnerObj->getHAGE();
		$religionAgeArrCnt = SymfonySearchFunctions::crm_extraDetails_profile_search('',$lage,$hage,$religionArr);
//        	search($gender,$religionArr,$casteArr,$mtongueArr,$lage,$hage,$withphotoArr,$manglikArr,$mstatusArr,$havechildArr,$lheight,$hheight,$btypeArr,$complexionArr,$dietArr,$smokeArr,$drinkArr,$handicappedArr,$occupationArr,$country_resArr,$city_resArr,$edu_levelArr,$edu_level_newArr,$sortArr,$onlineArr,$incomeArr,$relationArr,$nriArr,$page,$bread_crumb,$original_sid,$caste_mapping,$force,$searchid,$STYPE,$live_parents,$Sub_caste,$horoscopeArr,$sampradayArr,$urduArr,$hijabArr,$mathabArr,$amritdhariArr,$cut_hairArr,$turbanArr,$zarathustriArr,$wstatusArr,$handicappedArr,$nhandicappedArr,$hiv,$keywords,$kwd_rule,$Login,$Contact_visible,$subscriptionArr);
        //	$religionAgeArr =explode("@_$",$myjs_partnerprofilerecords);
        //	$religionAgeArrCnt =$religionAgeArr[0];
        	$smarty->assign("religionAgeArrCnt",$religionAgeArrCnt);
        //	unset($myjs_partnerprofilerecords);
        	unset($lage);
        	unset($hage);

		// Count for religion+Income
        	$incomeArr=search_display_format($jpartnerObj->getPARTNER_INCOME());
		$religionIncomeArrCnt = SymfonySearchFunctions::crm_extraDetails_profile_search('','','',$religionArr,$incomeArr);
        	//search($gender,$religionArr,$casteArr,$mtongueArr,$lage,$hage,$withphotoArr,$manglikArr,$mstatusArr,$havechildArr,$lheight,$hheight,$btypeArr,$complexionArr,$dietArr,$smokeArr,$drinkArr,$handicappedArr,$occupationArr,$country_resArr,$city_resArr,$edu_levelArr,$edu_level_newArr,$sortArr,$onlineArr,$incomeArr,$relationArr,$nriArr,$page,$bread_crumb,$original_sid,$caste_mapping,$force,$searchid,$STYPE,$live_parents,$Sub_caste,$horoscopeArr,$sampradayArr,$urduArr,$hijabArr,$mathabArr,$amritdhariArr,$cut_hairArr,$turbanArr,$zarathustriArr,$wstatusArr,$handicappedArr,$nhandicappedArr,$hiv,$keywords,$kwd_rule,$Login,$Contact_visible,$subscriptionArr);
        //	$religionIncomeArr =explode("@_$",$myjs_partnerprofilerecords);
        //	$religionIncomeArrCnt =$religionIncomeArr[0];
        	$smarty->assign("religionIncomeArrCnt",$religionIncomeArrCnt);
        //	unset($myjs_partnerprofilerecords);
        	unset($incomeArr);

		// Count for Religion+Caste
        	$casteArr=search_display_format($jpartnerObj->getPARTNER_CASTE());
		$religionCasteArrCnt = SymfonySearchFunctions::crm_extraDetails_profile_search('','','',$religionArr,'',$casteArr);
        	//search($gender,$religionArr,$casteArr,$mtongueArr,$lage,$hage,$withphotoArr,$manglikArr,$mstatusArr,$havechildArr,$lheight,$hheight,$btypeArr,$complexionArr,$dietArr,$smokeArr,$drinkArr,$handicappedArr,$occupationArr,$country_resArr,$city_resArr,$edu_levelArr,$edu_level_newArr,$sortArr,$onlineArr,$incomeArr,$relationArr,$nriArr,$page,$bread_crumb,$original_sid,$caste_mapping,$force,$searchid,$STYPE,$live_parents,$Sub_caste,$horoscopeArr,$sampradayArr,$urduArr,$hijabArr,$mathabArr,$amritdhariArr,$cut_hairArr,$turbanArr,$zarathustriArr,$wstatusArr,$handicappedArr,$nhandicappedArr,$hiv,$keywords,$kwd_rule,$Login,$Contact_visible,$subscriptionArr);
        //	$religionCasteArr =explode("@_$",$myjs_partnerprofilerecords);
        //	$religionCasteArrCnt =$religionCasteArr[0];
        	$smarty->assign("religionCasteArrCnt",$religionCasteArrCnt);
        //	unset($myjs_partnerprofilerecords);
        	unset($casteArr);

		// Count for Religion+Education
        	$edu_level_newArr=search_display_format($jpartnerObj->getPARTNER_ELEVEL_NEW());
		$religionEduArrCnt = SymfonySearchFunctions::crm_extraDetails_profile_search('','','',$religionArr,'','',$edu_level_newArr);
        	//search($gender,$religionArr,$casteArr,$mtongueArr,$lage,$hage,$withphotoArr,$manglikArr,$mstatusArr,$havechildArr,$lheight,$hheight,$btypeArr,$complexionArr,$dietArr,$smokeArr,$drinkArr,$handicappedArr,$occupationArr,$country_resArr,$city_resArr,$edu_levelArr,$edu_level_newArr,$sortArr,$onlineArr,$incomeArr,$relationArr,$nriArr,$page,$bread_crumb,$original_sid,$caste_mapping,$force,$searchid,$STYPE,$live_parents,$Sub_caste,$horoscopeArr,$sampradayArr,$urduArr,$hijabArr,$mathabArr,$amritdhariArr,$cut_hairArr,$turbanArr,$zarathustriArr,$wstatusArr,$handicappedArr,$nhandicappedArr,$hiv,$keywords,$kwd_rule,$Login,$Contact_visible,$subscriptionArr);
        //	$religionEduArr =explode("@_$",$myjs_partnerprofilerecords);
        //	$religionEduArrCnt =$religionEduArr[0];
        	$smarty->assign("religionEduArrCnt",$religionEduArrCnt);
	//	unset($myjs_partnerprofilerecords);
		unset($edu_level_newArr);	
	}

	// ###########   Matche Count for the DPP  Ends #############
	$smarty->assign("cid",$cid);
	$smarty->assign("checksum",$checksum);
	$smarty->display("extraDetails_profile.htm");
}
else
{
        $msg="Your session has been timed out<br> <br> ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

?>
