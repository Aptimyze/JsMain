<?php
if($_SERVER['DOCUMENT_ROOT'])
{
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/profile/contacts_functions.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect_functions.inc");
	include_once($_SERVER['DOCUMENT_ROOT']."/profile/sphinx_search_function.php");	
	include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
}
else
{
	$_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/profile/contacts_functions.php");
        include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect_functions.inc");
        include_once($_SERVER['DOCUMENT_ROOT']."/profile/sphinx_search_function.php");
	include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
}
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

function profileview($profileid,$checksum='',$priv='',$cid='')
{
	global $company;
	global $smarty;	
	global $db_slave;
	$mysql=new Mysql;
	if(!$priv)
		$priv =array();	

	$sql="select USERNAME,MOD_DT,GENDER,INCOMPLETE,SUBSCRIPTION,ACTIVATED , YOURINFO , FAMILYINFO , SPOUSE , JOB_INFO , SIBLING_INFO ,FATHER_INFO , ENTRY_DT, SOURCE, HAVEPHOTO, MESSENGER_ID, MOB_STATUS, LANDL_STATUS, EMAIL, SERIOUSNESS_COUNT,DATE(LAST_LOGIN_DT) LAST_LOGIN_DT from newjs.JPROFILE where PROFILEID='$profileid'";
	$result=mysql_query_decide($sql,$db_slave) or die("1".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	
	if(mysql_num_rows($result) > 0)
	{
		$myrow=mysql_fetch_array($result);
	
		if($myrow["ACTIVATED"]=="D")
		{
			$table_name="DELETED_PROFILE_CONTACTS";
		}
		else
		{
			$table_name="CONTACTS";
		}	

		//*******************************

		$USERNAME 	=$myrow['USERNAME'];
		$SOURCE 	=$myrow['SOURCE'];
		$HAVEPHOTO 	=$myrow['HAVEPHOTO'];
		$MESSENGER_ID 	=$myrow['MESSENGER_ID'];
		$MOB_STATUS 	=$myrow['MOB_STATUS'];
		$LANDL_STATUS 	=$myrow['LANDL_STATUS'];
		$EMAIL 		=$myrow['EMAIL'];
		$SERIOUSNESS_COUNT =$myrow['SERIOUSNESS_COUNT']; 
	
		// phone status verification	
                $sqlAlt ="select ALT_MOB_STATUS from newjs.JPROFILE_CONTACT WHERE PROFILEID='$profileid'";
                $resAlt = mysql_query_decide($sqlAlt,$db_slave) or die("$sqlAlt".mysql_error_js());
                $rowAlt =mysql_fetch_array($resAlt);
                $MOB2_STATUS =$rowAlt['ALT_MOB_STATUS'];

		$smarty->assign("mob1_status",$MOB_STATUS);
		$smarty->assign("mob2_status",$MOB2_STATUS);
		$smarty->assign("landl_status",$LANDL_STATUS);

		if($MESSENGER_ID)
		{
			$MESSENGER_ID =trim($MESSENGER_ID);
			$check_messenger =stristr($MESSENGER_ID,'@gmail');
			if($check_messenger)
				$messenger_id ='G';
			else
				$messenger_id ='O';
		}
		else
			$messenger_id ='';
		$smarty->assign("messenger_id", $messenger_id);		

                // Photo uploaded count
                if($HAVEPHOTO)
                {
			//Symfony Photo Modification
			$photo_upload = SymfonyPictureFunctions::getMaxOrdering($profileid);
			if($photo_upload!=null)
				$photo_upload = $photo_upload+1;
			else
				$photo_upload = 0;	
			//Symfony Photo Modification
                }
		$smarty->assign("photo_upload",$photo_upload);

		// Email check status
		$sql ="select STATUS from newjs.VERIFY_EMAIL WHERE PROFILEID='$profileid'";
		$res = mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());
		$row =mysql_fetch_array($res);
		$email =$row['STATUS'];			
		if($email=='Y')
			$email_status='Y';
		else{
			$bounced_email = bounced_emailID($profileid,$EMAIL);
			if($bounced_email)
				$email_status='B';
			else
				$email_status='N';
		}
		$smarty->assign("email_status",$email_status);

		// Address verification check
		$sql ="select SCREENED from jsadmin.ADDRESS_VERIFICATION where PROFILEID='$profileid'";
		$res = mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());
		$row =mysql_fetch_array($res);
		$addr =$row['SCREENED'];
		if($addr=='Y')
			$addr_status='Y';	
		else
			$addr_status='N';
		$smarty->assign("addr_status",$addr_status);
                // New customised username 
                $sql ="select NEW_USERNAME from newjs.CUSTOMISED_USERNAME where PROFILEID='$profileid' AND SCREENED='A'";
                $res = mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());
                $row =mysql_fetch_array($res);
                $new_username =$row['NEW_USERNAME'];
		$smarty->assign("new_username",$new_username);	

		// VD DISCOUNT	
		$tod_timestamp =JSstrToTime(date("Y-m-d"));
		$sql ="select DISCOUNT, SDATE,EDATE from billing.VARIABLE_DISCOUNT where PROFILEID='$profileid' ORDER BY EDATE DESC limit 1";
		$res = mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());
		if($row = mysql_fetch_array($res))
		{
			//$vdiscount =$row['DISCOUNT'];
			$sqlDisc="SELECT * FROM billing.VARIABLE_DISCOUNT_OFFER_DURATION WHERE PROFILEID='$profileid'";
			$resDisc = mysql_query_decide($sqlDisc,$db_slave) or die("$sqlDisc".mysql_error_js());
			$vdDiscountStr = "";
			while($rowDisc = mysql_fetch_assoc($resDisc)){
				unset($rowDisc['PROFILEID']);
				$mainService = $rowDisc['SERVICE'];
				unset($rowDisc['SERVICE']);
				$vdDiscountStr .= VariableParams::$mainMembershipNamesArr[$mainService]." :: "; 
				foreach($rowDisc as $key=>$val){
					$vdDiscountStr .= $key."M - ".$val."%";
					if($key!='L'){
						$vdDiscountStr .= ", ";
					}
				}
				$vdDiscountStr .= "<br>";
			}
			$edate_discount =$row['EDATE'];
			$sdate_discount =$row['SDATE'];
			$edate_timestamp =JSstrToTime($edate_discount);
			$sdate_timestamp =JSstrToTime($sdate_discount);
			if($edate_timestamp < $tod_timestamp || $sdate_timestamp>$tod_timestamp){
				$edate_discount ='';
				$vdiscount =0;
				$vdDiscountStr='';
			}	
			else
				$edate_discount =date("d  M  Y",JSstrToTime($edate_discount));	

		}	
		$smarty->assign("vdiscount",$vdDiscountStr);
		$smarty->assign("edate_discount",$edate_discount);
		
		// LAST VD DISCOUNT	
		$sqlDisc="SELECT * FROM billing.VARIABLE_DISCOUNT_OFFER_DURATION_LOG WHERE PROFILEID='$profileid' ORDER BY EDATE DESC";
		$resDisc = mysql_query_decide($sqlDisc,$db_slave) or die("$sqlDisc".mysql_error_js());
		while($rowDisc = mysql_fetch_assoc($resDisc))
		{
			$last_edate_discount = $rowDisc['EDATE'];
			unset($rowDisc['PROFILEID']);
			$mainService = $rowDisc['SERVICE'];
			unset($rowDisc['SERVICE']);
			$vdDiscountStr .= VariableParams::$mainMembershipNamesArr[$mainService]." :: "; 
			foreach($rowDisc as $key=>$val){
				$vdDiscountStr .= $key."M - ".$val."%";
				if($key!='L'){
					$vdDiscountStr .= ", ";
				}
			}
			$vdDiscountStr .= "<br>";
			$last_edate_discount =date("d  M  Y",JSstrToTime($last_edate_discount));	
			$smarty->assign("last_vdiscount",$last_vdiscount);
			$smarty->assign("last_edate_discount",$last_edate_discount);
		}
		
		// Online/Offline toggle
		$onlineProfile =findOnlineProfiles();
		if(!$onlineProfile)
			$onlineProfile =array();
		if(in_array($profileid, $onlineProfile))
			$onlineStatus =true;
		else
			$onlineStatus =false;	
		$smarty->assign("onlineStatus",$onlineStatus);

		// Mobile Usage message to target the right people for pushing app downloads
		$lastMonth = date('Y-m-d', strtotime('-30 days'));
		$sql ="SELECT DISTINCT `WEBSITE_VERSION` FROM MIS.`LOGIN_TRACKING` WHERE `PROFILEID`='$profileid' AND DATE>='$lastMonth 00:00:00'";	
                $res = mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());
                while($row =mysql_fetch_array($res)) {
			$website_version[] = $row['WEBSITE_VERSION'];
		}
		if(is_array($website_version) && in_array('A',$website_version))
			$mobile_usage = "Uses Mobile App";
		else if(is_array($website_version) && (in_array('M',$website_version) || in_array('N',$website_version)))
			$mobile_usage = "Uses Mobile site but no mobile app - inform about app download";
		else 
			$mobile_usage = "No Mobile Usage - inform about mobile site and app";
                $smarty->assign("mobile_usage",$mobile_usage);

		// EOIs sent through Auto Apply	
		/*$sql ="select count(distinct RECEIVER) AS COUNT from Assisted_Product.AUTOMATED_CONTACTS_TRACKING where SENDER='$profileid'";	
                $res = mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());
                $row =mysql_fetch_array($res);
                $eoiSent_autoApply =$row['COUNT'];
                $smarty->assign("eoiSent_autoApply",$eoiSent_autoApply);*/

		// EOIs sent through Auto Apply
                /*$sql ="select count(distinct SENDER) AS COUNT from Assisted_Product.AUTOMATED_CONTACTS_TRACKING where RECEIVER='$profileid'";
                $res = mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());
                $row =mysql_fetch_array($res);
                $eoiReceived_autoApply =$row['COUNT'];
                $smarty->assign("eoiReceived_autoApply",$eoiReceived_autoApply);*/

		//*****************************************************

		
		$ENTRY_DT = substr($myrow['ENTRY_DT'],0,10);
		$PROFILELENGTH = strlen($myrow['YOURINFO']) + strlen($myrow['FAMILYINFO']) + strlen($myrow['SPOUSE']) + strlen($myrow['FATHER_INFO']) + strlen($myrow['SIBLING_INFO']) + strlen($myrow['JOB_INFO']); // profile length

		$mydate=substr($myrow["MOD_DT"],0,10);
		$mydateArr=explode("-",$mydate);
	
		$mydate=my_format_date($mydateArr[2],$mydateArr[1],$mydateArr[0]);

		include_once($_SERVER['DOCUMENT_ROOT']."/profile/ntimes_function.php");

		$ntimes = ntimes_count($profileid,"SELECT");
		
		$smarty->assign("VIEWS",$ntimes);
		$smarty->assign("LAST_MODIFIED",$mydate);
		$smarty->assign("USERNAME",$myrow['USERNAME']);
		
		$gender=$myrow["GENDER"];
		
		// free the recordset
		mysql_free_result($result);
		$sub_rights=explode(',',$myrow['SUBSCRIPTION']);
		
		$ftoStateArray 		=SymfonyFTOFunctions::getFTOStateArray($profileid);
		$memMessage		=getMembershipMessage($ftoStateArray['SUBSTATE']);
		$isPaidInFtoPeriod      =FTOStateHandler::checkPaid($profileid);
		if($isPaidInFtoPeriod)
			$ftoStateStatus	='PAID';
		else
			$ftoStateStatus =$ftoStateArray['STATE'];		
		$ftoOfferStatusMsg 	=getFtoOfferStatus($ftoStateStatus,$ftoStateArray['FTO_EXPIRY_DATE']);	
		if($memMessage=="Other")
		{
			if($myrow['SUBSCRIPTION']=="")
				$actionRequired="Free member, explain membership benefits";
			else
				$actionRequired="Paid member, explain EoI, Acceptance, viewing contact details";
		}
		else
			$actionRequired=$memMessage;
		if(in_array("O",$sub_rights))	
		{
			if(in_array("F",$sub_rights) && in_array("B",$sub_rights))
			{
				$subscription="yes";
				$membership="Value Added Member";
			}
		}

		elseif(!in_array("O",$sub_rights))
		{
			// changes due to addition of new service eclassified  NEW CHANGES
			if((in_array("F",$sub_rights))||(in_array("D",$sub_rights)))
			{
				$subscription="yes";
													     
				if(in_array("F",$sub_rights) && in_array("D",$sub_rights))
					$membership="e-Value Pack";
				elseif(in_array("F",$sub_rights))
					$membership="e-Rishta";
				elseif(in_array("D",$sub_rights))
					$membership="e-Classified";
			}
			else
			{
				$subscription="no";
				$membership="Free Member";
			}
			if(in_array("B",$sub_rights) or in_array("V",$sub_rights) or in_array("H",$sub_rights) or in_array("K",$sub_rights))
			{
				$addon_subscription="yes";
				if(in_array("V",$sub_rights))
					$addon_membership_arr[]="Voice mail";
				if(in_array("H",$sub_rights))
					$addon_membership_arr[]="Horoscope";
				if(in_array("K",$sub_rights))
					$addon_membership_arr[]="Kundali";
				if(in_array("B",$sub_rights))
					$addon_membership_arr[]="Profile Highlighting";
				$addon_membership=implode("<br>&nbsp;",$addon_membership_arr);
			}
		}


		$sql_p="SELECT BILLID FROM billing.PURCHASES p WHERE p.PROFILEID = '$profileid'  and p.STATUS='DONE' ORDER BY p.BILLID desc limit 1";
		$res_p=mysql_query_decide($sql_p,$db_slave) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$myrow_p=mysql_fetch_array($res_p);

		$sql_p2="SELECT ss.ACTIVATED_ON,ss.EXPIRY_DT FROM billing.SERVICE_STATUS ss WHERE ss.BILLID='$myrow_p[BILLID]' ORDER BY ss.EXPIRY_DT desc limit 1";
		$res_p2=mysql_query_decide($sql_p2,$db_slave) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$myrow_p2=mysql_fetch_array($res_p2);
		$paid_str='';
		if($myrow_p2['EXPIRY_DT'] && $page_mail)
		{
			$ex_dt= $myrow_p2['EXPIRY_DT']." 23:59:59";
			$paid_str= "TIME<='$ex_dt'";
		}
		if(!mysql_num_rows($res_p))
		{
			$sql_p3="select EXPIRY_DT from billing.SUBSCRIPTION_EXPIRE where PROFILEID = '$profileid' AND EXPIRY_DT>=CURDATE()";
			$res_p3=mysql_query_decide($sql_p3,$db_slave) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$myrow_p3=mysql_fetch_array($res_p3);

			if(mysql_num_rows($res_p3))
			{
				$show_expiry="yes";
				list($year,$month,$day) = explode("-",$myrow_p3['EXPIRY_DT']);
				$ssexpiry_dt=my_format_date($day,$month,$year);
			}
			else
			{
				$show_expiry="no";
				$ssexpiry_dt="";
			}

		}
		elseif(in_array("F",$sub_rights) || in_array("D",$sub_rights))
		{
			$show_expiry="yes";
			list($year,$month,$day) = explode("-",$myrow_p2['EXPIRY_DT']);
			$ssexpiry_dt=my_format_date($day,$month,$year);
		}
		else
		{
			$show_expiry="no";
			$ssexpiry_dt="";
		} 

		$smarty->assign("SHOWEXPIRY",$show_expiry);
		$smarty->assign("SSEXPIRYDT",$ssexpiry_dt);

		// *********************** CONTACTS RECEIVED BY YOU SECTION START *******************	

		$contactResult_recdsum = getResultSet("COUNT(*) AS CNT","","","$profileid","","'I'","","TIME BETWEEN DATE_SUB(NOW(), INTERVAL ".CONTACTS::INTEREST_RECEIVED_UPPER_LIMIT." DAY) AND NOW()","","","","","","","$table_name","","","","'Y'");
		$smarty->assign("RECEIVED_I",$contactResult_recdsum[0]["CNT"]);
		$RECEIVEDSUM=$contactResult_recdsum[0]["CNT"];
		$RECEIVEDSUM_O=$contactResult_recdsum[0]["CNT"];
                $smarty->assign("RECEIVED_I_A",0);
		if($paid_str)
			$contactResult_recdsum = getResultSet("COUNT(*) AS CNT","","","$profileid","","'D'","","$paid_str","","","","","","","$table_name");
		else
			$contactResult_recdsum = getResultSet("COUNT(*) AS CNT","","","$profileid","","'D'","","","","","","","","","$table_name");

		$RECEIVED_D=$contactResult_recdsum[0]["CNT"];
		$RECEIVEDSUM+=$contactResult_recdsum[0]["CNT"];
		$RECEIVEDSUM_O+=$contactResult_recdsum[0]["CNT"];

		$contactResult_recdsum = getResultSet("COUNT(*) AS CNT","$profileid","","","","'E','C'","","","","","","","","","$table_name");
		$RECEIVED_D+=$contactResult_recdsum[0]["CNT"];
                $RECEIVEDSUM+=$contactResult_recdsum[0]["CNT"];
                $RECEIVEDSUM_O+=$contactResult_recdsum[0]["CNT"];

		$smarty->assign("RECEIVED_D",$RECEIVED_D);
		
			
		// New Filtered EOI Received
	       	$contactResult_recdsum=getResultSet("COUNT(*) AS CNT","","",$profileid,"","'I'",'',"TIME BETWEEN DATE_SUB(NOW(), INTERVAL ".CONTACTS::INTEREST_RECEIVED_UPPER_LIMIT." DAY) AND NOW()","","","","","","","$table_name","","","'Y'","");
		$RECEIVEDSUM+=$contactResult_recdsum[0]["CNT"];
		$RECEIVEDSUM_O+=$contactResult_recdsum[0]["CNT"];
	       	$RECEIVED_II_FF= $contactResult_recdsum[0]["CNT"];
	        $smarty->assign("RECEIVED_II_FF",$RECEIVED_II_FF);
		// Filtered EOI Ends 

		// *********************** CONTACTS RECEIVED BY YOU SECTION ENDS *******************

		// *********************** CONTACTS MADE BY YOU SECTION START **********************
		unset($ACCEPTED_DETAILS);
		$contact_made_accepted =array();
		$contact_made_initiated =array();
		$contact_made_denied =array();

		// New Query to find contacts made and are accepted (conditional base)
		if($paid_str)
			$contactResult = getResultSet("RECEIVER,TIME","$profileid","","","","'A'","","$paid_str","","","","","","","$table_name");
		else
			$contactResult = getResultSet("RECEIVER,TIME","$profileid","","","","'A'","","","","","","","","","$table_name");

		for($i=0;$i<count($contactResult);$i++){
			$temp_arr[] = $contactResult[$i]["TIME"]."+".$profileid."+".$contactResult[$i]["RECEIVER"];
			$contact_made_accepted[] = $contactResult[$i]["RECEIVER"];
		}

		if($paid_str)
		{
			$contact_made_accepted =array();
                	$contactResult = getResultSet("RECEIVER","$profileid","","","","'A'","","","","","","","","","$table_name");
                	for($i=0;$i<count($contactResult);$i++)
                	        $contact_made_accepted[] = $contactResult[$i]["RECEIVER"];
		}

               	$total_contact_made_accepted =count($contact_made_accepted);
                $smarty->assign("MADE_A",$total_contact_made_accepted);
                $MADESUM =$total_contact_made_accepted;
		// New Query ends	

                // New Query to find contacts made and are in awaited response
                $contactResult = getResultSet("RECEIVER","$profileid","","","","'I'","","","","","","","","","$table_name");
                for($i=0;$i<count($contactResult);$i++)
                        $contact_made_initiated[] = $contactResult[$i]["RECEIVER"];
                $total_contacts_made_initiated =count($contact_made_initiated);
                $smarty->assign("MADE_I",$total_contacts_made_initiated);
                $MADESUM+=$total_contacts_made_initiated;

		// Not viewed
	        $contactResult=getResultSet("count(*) as cnt",$profileid,"","","","'I'","","","","","","","","","","","'Y'");
	        if(is_array($contactResult))
	                $MADE_D_R=$contactResult[0]['cnt'];
	        else
	                $MADE_D_R=0;
	        $sql="select count(*) as CNT from jsadmin.OFFLINE_MATCHES where MATCH_ID='$profileid' and SHOW_ONLINE='Y' AND STATUS='NACC' AND SEEN=''";
	        $res11=mysql_query_decide($sql,$db_slave) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	        $row11=mysql_fetch_array($res11);
	        $MADE_D_R+=$row11["CNT"];
        	$smarty->assign("MADE_D_R",$MADE_D_R);
                // New Query ends

		// New Query to find contacts made and denied
                $contactResult = getResultSet("RECEIVER","$profileid","","","","'D'","","","","","","","","","$table_name");
                for($i=0;$i<count($contactResult);$i++)
                        $contact_made_denied[] = $contactResult[$i]["RECEIVER"];
                $total_contacts_made_denied =count($contact_made_denied);
                $smarty->assign("MADE_D",$total_contacts_made_denied);
                $MADESUM+=$total_contacts_made_denied;
		// New Query ends

		// Total contacs made (total you have contacted) 
		$contacted_total =array_merge($contact_made_initiated,$contact_made_accepted,$contact_made_denied);
		$contacted_free_profiles =getFreeProfiles($contacted_total);	
		$smarty->assign("contacted_free_profiles",$contacted_free_profiles);	

		// *********************** CONTACTS MADE BY YOU SECTION ENDS ************************


		// *********************** CONTACTS RECEIVED BY YOU SECTION START *******************
		// Contacts received and accepted by you (conditional)
		if($paid_str)
			$contactResult = getResultSet("SENDER,TIME","","","$profileid","","'A'","","$paid_str");
		else
			$contactResult = getResultSet("SENDER,TIME","","","$profileid","","'A'");

		$contacts_accepted_arr =array();
		for($i=0;$i<count($contactResult);$i++){
			$temp_arr[] = $contactResult[$i]["TIME"]."+".$contactResult[$i]["SENDER"]."+".$profileid;
			$contacts_accepted_arr[] =$contactResult[$i]["SENDER"];
		}
		// ends

		// section for contacts received and accepted (conditional)
		if($table_name!='CONTACTS')
		{
			unset($contacts_accepted_arr) ;
                        if($paid_str)
                                $contactResult_recdsum = getResultSet("SENDER","","","$profileid","","'A'","","$paid_str","","","","","","","$table_name");
                        else
                                $contactResult_recdsum = getResultSet("SENDER","","","$profileid","","'A'","","","","","","","","","$table_name");
			for($i=0;$i<count($contactResult_recdsum);$i++)
				$contacts_accepted_arr[] = $contactResult_recdsum[$i]["SENDER"];		
		}
		$tot_contacts_accepted =0;
	        $tot_contacts_accepted =count($contacts_accepted_arr);
		$smarty->assign("RECEIVED_A", $tot_contacts_accepted);

                $accepted_free_profiles =getFreeProfiles($contacts_accepted_arr);
                $smarty->assign("accepted_free_profiles",$accepted_free_profiles);
		// ends	
	
                $RECEIVEDSUM+=$tot_contacts_accepted;
                $RECEIVEDSUM_O+=$tot_contacts_accepted;
		$smarty->assign("RECEIVEDSUM_O",$RECEIVEDSUM_O);

                if($RECEIVEDSUM && $ntimes)
                        $eoi_versus_viewed =round((($RECEIVEDSUM/$ntimes)*100),1);
		$smarty->assign("eoi_versus_viewed",$eoi_versus_viewed);

		// *********************** CONTACTS RECEIVED BY YOU SECTION ENDS *******************

		if(is_array($temp_arr))
			rsort($temp_arr);
		unset($arr);
		for($i=0;$i<20;$i++)
		{
			if($temp_arr[$i])
			{
				list($time,$sender,$receiver)=explode("+",$temp_arr[$i]);
				$arr[]=array("TIME" => $time, "SENDER" => $sender, "RECEIVER" => $receiver);                                        
			}
		}
		if(is_array($arr))
			$MY_COUNT=count($arr);
		else
			$MY_COUNT=0;
		for($i=0;$i<$MY_COUNT;$i++)
		{
			if ($arr[$i]["SENDER"]==$profileid)
			{
				$pid=$arr[$i]["RECEIVER"];
				$acc_con="Contacted by you";
			}
			else
			{
				$pid=$arr[$i]["SENDER"] ;
				$acc_con="Accepted by you";
			}
			$date_con=substr($arr[$i]["TIME"],0,10);
			$date_arr=explode("-",$date_con);
			$date_contact=my_format_date($date_arr[2],$date_arr[1],$date_arr[0]);
															    
			$sql="select PROFILEID,USERNAME,EMAIL,AGE,HEIGHT,CASTE,OCCUPATION,CITY_RES,COUNTRY_RES,SUBSCRIPTION,CONTACT,SHOWADDRESS,PHONE_RES,PHONE_MOB,SHOWPHONE_RES,SHOWPHONE_MOB,PARENTS_CONTACT,SHOW_PARENTS_CONTACT,INCOME,EDU_LEVEL_NEW from newjs.JPROFILE where PROFILEID='$pid'";
			$my_accresult=mysql_query_decide($sql,$db_slave) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
			$income_map=array(
"2" => "< Rs. 1Lac",
"3" => "Rs. 1 - 2Lac",
"4" => "Rs. 2 - 3Lac",
"5" => "Rs. 3 - 4Lac",
"6" => "Rs. 4 - 5Lac",
"8" => "< $ 25K",
"9" => "$ 25 - 40K",
"10" => "$ 40 - 60K",
"11" => "$ 60 - 80K",
"12" => "$ 80K - 1lac",
"13" => "$ 1 - 1.5lac",
"21" => "$ 1.5 - 2lac",
"14" => "> $ 2lac",
"15" => "No Income",
"16" => "Rs. 5 - 7.5lac",
"17" => "Rs. 7.5 - 10lac",
"18" => "Rs. 10 - 15lac",
"20" => "Rs. 15 - 20lac",
"22" => "Rs. 20 - 25lac",
"23" => "Rs. 25 - 35lac",
"24" => "Rs. 35 - 50lac",
"25" => "Rs. 50 - 70lac",
"26" => "Rs. 70 - 1cr",
"27" => "> Rs. 1cr");

			while($my_accrow=mysql_fetch_array($my_accresult))
			{
				$email='';
				$phone='';
				$income_con='';
				$addr='';
				$parents_addr='';
				$SHOW_OPS='';
													    
				//$mem_rights=explode(",",$data["SUBSCRIPTION"]);
				if ($subscription!= 'no' || $my_accrow["SUBSCRIPTION"]!='')
				{
					$SHOW_OPS="Y";
				}
				$email=$my_accrow["EMAIL"];
				if($my_accrow["SHOWPHONE_RES"]=='Y')
					$phone=$my_accrow["PHONE_RES"];
				if($my_accrow["SHOWPHONE_MOB"]=='Y')
				{
					if($phone!='')
						$phone.="/".$my_accrow["PHONE_MOB"];
					else
						$phone=$my_accrow["PHONE_MOB"];
												    
					}
				if($my_accrow["SHOWADDRESS"]=='Y')
					$addr=$my_accrow["CONTACT"];
				if($my_accrow["SHOW_PARENTS_CONTACT"]=='Y')
				$parents_addr=$my_accrow["PARENTS_CONTACT"];
												    
				$education=label_select("EDUCATION_LEVEL_NEW",$my_accrow["EDU_LEVEL_NEW"]);
				$tmpheight=$my_accrow["HEIGHT"];
				$tmpheight1=$HEIGHT_DROP["$tmpheight"];
				$height1=explode("(",$tmpheight1);
				$tmpcaste=$my_accrow["CASTE"];
				$tmpoccupation=$my_accrow["OCCUPATION"];
				$tmpcountry=$my_accrow["COUNTRY_RES"];
				$tmpcity=$my_accrow["CITY_RES"];

				if($tmpcountry==51)
					$tmpcity=$CITY_INDIA_DROP["$tmpcity"];
				elseif($tmpcountry==128)
					$tmpcity=$CITY_USA_DROP["$tmpcity"];
				else
					$tmpcity="";
				if($gender=='F')
				{
					$income=$my_accrow["INCOME"];
					$income_con=",".$income_map["$income"];
				}
				else
					$income_con="";
				$ACCEPTED_DETAILS[]=array("USERNAME" => $my_accrow["USERNAME"],
						"HEIGHT" => $height1[0],
						"AGE" => $my_accrow["AGE"],
						"CASTE" => $CASTE_DROP["$tmpcaste"],
						"OCCUPATION" => $OCCUPATION_DROP["$tmpoccupation"],
						"EDUCATION"=>$education[0],
						"INCOME"=>$income_con,
						"CITY_RES" =>$tmpcity,
						"DATE_CON"=>$date_contact,
						"ACC_CON"=>$acc_con,
						"EMAIL"=>$email,
						"PHONE"=>$phone,
						"ADDRESS"=>$addr,
						"PARENTS_CON"=>$parents_addr,
						"COUNTRY_RES" => $COUNTRY_DROP["$tmpcountry"],
						"SHOW_OPS"=>$SHOW_OPS,
						"PROFILECHECKSUM" => md5($my_accrow["PROFILEID"]) . "i" . $my_accrow["PROFILEID"],
						"PHOTOCHECKSUM" => md5($my_accrow["PROFILEID"]+5) . "i" . ($my_accrow["PROFILEID"]+5));
			}
		}
		$ACCEPTED_COUNT= count($ACCEPTED_DETAILS);
		$smarty->assign("ACCEPTED_COUNT",$ACCEPTED_COUNT);
		$smarty->assign("ACCEPTED_ARR",$ACCEPTED_DETAILS);
		unset($ACCEPTED_DETAILS);
		unset($ACCEPTED_COUNT);
		// code ends here

		$ts=time();
                $ts-=90*24*60*60;
                $date=date("Y-m-d",$ts);
                                                                                                                            
                // 2 queries added to find login frequency and contacts viewd/received
                                                                                                                            
                // query to find the number of times user has logged on to the site in past 90 days
		$myDbName=getProfileDatabaseConnectionName($profileid);
                $myDb=$mysql->connect("$myDbName");
                $sql1 = "SELECT COUNT(*) AS CNT FROM newjs.LOGIN_HISTORY WHERE PROFILEID = '$profileid' AND LOGIN_DT >= '$date'";
		$res1=$mysql->executeQuery($sql1,$myDb);
		$row1=$mysql->fetchArray($res1);
                $LOGINCNT=$row1['CNT'];
			
		//Contacts viewed by others
                //contacts viewed without making contact
		
                $sql_con_view="select count(*) as cnt from jsadmin.VIEW_CONTACTS_LOG where VIEWED='$profileid' AND SOURCE='".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."'";
                $res_con_view=mysql_query_decide($sql_con_view,$db_slave);
                $row_con_view=mysql_fetch_assoc($res_con_view);
                $total_con_viewed=$row_con_view['cnt'];
			
		// New query: Photo request count
		$sql_photo="SELECT count(*) as cnt FROM newjs.PHOTO_REQUEST WHERE PROFILEID_REQ_BY='$profileid'";
	       	$result_photo = $mysql->executeQuery($sql_photo,$myDb);
		$row_photo=$mysql->fetchArray($result_photo);
		$PHOTO_CNT =$row_photo['cnt'];	
		$smarty->assign("PHOTO_CNT",$PHOTO_CNT);
		
		// New query: Horoscopr Request Count
                $sql_ho="SELECT count(*) as cnt FROM newjs.HOROSCOPE_REQUEST WHERE PROFILEID_REQUEST_BY='$profileid'";
                $result_ho = $mysql->executeQuery($sql_ho,$myDb);
                $row_ho=$mysql->fetchArray($result_ho);
                $HOROSCOPE_CNT =$row_ho['cnt']; 
		$smarty->assign("HOROSCOPE_CNT",$HOROSCOPE_CNT);

		$contactResult = getResultSet("SENDER","","","$profileid","","","","TIME >= '$date'");
		for($i=0;$i<count($contactResult);$i++)
			$arr_sen[] = $contactResult[$i]["SENDER"];

                if ($arr_sen)
                {
                        $arr_sen_str="'".implode("','",$arr_sen)."'";
                                                               

			//$db_211 = connect_211();
                        // query to find the number of profiles (of people who have initiated contact) viewed by user
                        $sql="SELECT COUNT(*) AS CNT FROM newjs.VIEW_LOG WHERE VIEWER = '$profileid' AND  DATE >= '$date' AND VIEWED IN ($arr_sen_str)";
                        //$res = mysql_query_decide($sql,$db_211) or die("$sql".mysql_error_js());
                        $row = mysql_fetch_array($res);
                        $CONTACT_VIEWED_CNT = $row['CNT'];
                        $RECEIVED_CNT = count($arr_sen);
			//mysql_close($db_211);

			//$db=connect_db();	
			/*if(function_exists(connect_slave))
                                $db=connect_slave();
                        else
                                $db = connect_db2();
			*/
                }
                else
                {
                        $RECEIVED_CNT = 0;
                        $CONTACT_VIEWED_CNT = 0;
                }



		$sql_score = "SELECT SCORE,ANALYTIC_SCORE,CUTOFF_DT FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID ='$profileid'";
		$res_score = mysql_query_decide($sql_score,$db_slave) or die("$sql_score".mysql_error_js());
		while($row_score = mysql_fetch_array($res_score))
		{
			$SCORE = $row_score['SCORE'];
			$ANALYTIC_SCORE = $row_score['ANALYTIC_SCORE'];
			$CUTOFF_DT = $row_score['CUTOFF_DT'];
		}

		$date=date("Y-m-d");
                list($yy,$mm,$dd)=explode("-",$date);
                $today = mktime(0,0,0,$mm,$dd,$yy);

                list($b_yy,$b_mm,$b_dd) = explode("-",$ENTRY_DT);
                $entry_dt = mktime(0,0,0,$b_mm,$b_dd,$b_yy);
                $days = ($today-$entry_dt);
                $diff = (int) ($days/(24*60*60)); // find the number of days a user has been registered with us
		$ageInMonths=round($diff*12/365,2);	
	
                if ($diff >= 90)
                        $diff = 90;
                $LOGIN_FREQ = $LOGINCNT." / ".$diff; // login frequency
                $CONTACT_VIW_FREQ = $CONTACT_VIEWED_CNT." / ".$RECEIVED_CNT; // contacts viewed frequency
                 
		$smarty->assign("SCORE",$SCORE);
		$smarty->assign("ANALYTIC_SCORE",$ANALYTIC_SCORE);
		$smarty->assign("CUTOFF_DT",$CUTOFF_DT);
	
		$smarty->assign("PROFILELENGTH",$PROFILELENGTH);
                $smarty->assign("CONTACT_VIW_FREQ",$CONTACT_VIW_FREQ);
                $smarty->assign("LOGIN_FREQ",$LOGIN_FREQ);

		$smarty->assign("MADESUM",$MADESUM);
		$smarty->assign("RECEIVEDSUM",$RECEIVEDSUM);
		$smarty->assign("GENDER",$gender);
		//$smarty->assign("TOTALCOUNT",$totalcount);
		$smarty->assign("MEMBERSHIP",$membership);
		$smarty->assign("FTO_OFFER_STATUS_MSG",$ftoOfferStatusMsg);
		$smarty->assign("ACTIONREQUIRED",$actionRequired);
		$smarty->assign("SUBSCRIPTION",$subscription);
		$smarty->assign("ADDON_MEMBERSHIP",$addon_membership);
		$smarty->assign("ADDON_SUBSCRIPTION",$addon_subscription);
                $smarty->assign("SOURCE",$SOURCE);

		$smarty->assign("CHECKSUM",$checksum);
		$smarty->assign("cid",$cid);
		$smarty->assign("company",$company);
		$smarty->assign("table_name",$table_name);
		$smarty->assign("paid_str",$paid_str);

		if(in_array('OPS',$priv)||in_array('OPM',$priv)||in_array('OSM',$priv)||in_array('OFSM',$priv))
			$smarty->assign("show_score",1);
		else
			$smarty->assign("show_score",0);

		if(in_array('SLHD',$priv))
			$smarty->assign("an_show_score",1);
		else
			$smarty->assign("an_show_score",0);

		//Code to check which profile is eligible for Response Booster as "Freeby" added by lakshay
		
		$eligibleForRB=rbEligibilityFlag($HAVEPHOTO,count($contacted_total),count($contact_made_accepted),$LOGINCNT,$diff,$total_con_viewed,$ageInMonths,$SERIOUSNESS_COUNT,$ftoStateArray['STATE']);
		$smarty->assign("eligibleForRB",$eligibleForRB);

		//Code to show the number of direct contact remaining for paid members on the 'Show Stats' page
		if($profileid)
		{
			$memObj = new JMembership();
			$isRenewable = $memObj->isRenewable($profileid);
			// top level check to see if user is within paid/renewable
			if(!empty($isRenewable) && $isRenewable != 0)
			{
				// condition to filter users who have expired but are within renew period(+10 days)
				if($isRenewable == 1 || ($isRenewable > 1 && strtotime($isRenewable) >= strtotime(date("Y-m-d H:i:s")))){
					$remainingContacts = $memObj->getRemainingContactsForUser($profileid);
					$smarty->assign("remainingContacts", $remainingContacts);				
				}
			}
		}
		
		//introduced with FTA process added by lakshay	
		include_once($_SERVER['DOCUMENT_ROOT']."/profile/functions.inc");
		$profilePercent=profile_percent($profileid);
		$date=date("Y-m-d",time()-10*24*60*60);
                if(function_exists(connect_slave81)){
                        $db_slave = connect_slave81();
                }
                else{
                        $db_slave = connect_81();
                }
		$sql_search_query ="select count(*) AS CNT from MIS.SEARCHQUERY where PROFILEID='$profileid' AND DATE>='$date'";
		$res_search_query = mysql_query_decide($sql_search_query,$db_slave) or die("$sql_search_query".mysql_error_js());
		$row_search_query = mysql_fetch_array($res_search_query);
		if($row_search_query["CNT"]>0)
			$searchFrequency=1;
		$serviceRequirement=serviceRequirement($HAVEPHOTO,count($contacted_total),$myrow['LAST_LOGIN_DT'],$profilePercent,$searchFrequency,$diff);
		$smarty->assign("serviceRequirement",$serviceRequirement);
		$db=connect_db();
		// find out whether the person whose profile is being viewed is currently online
		$sql="select count(*) from userplane.recentusers where userID='$profileid'";
		$result=mysql_query_decide($sql) or die("1".mysql_error_js());
		
		$myonline=mysql_fetch_row($result);
		
		if($myonline[0] > 0)
		{
			$smarty->assign("CHATID",$profileid);
			$smarty->assign("ISONLINE",1);
		}
	
		mysql_free_result($result);

		$rdObj = new billing_RENEWAL_DISCOUNT();
        $res =$rdObj->getDiscount($profileid);
        if($res['DISCOUNT']) {
	        $smarty->assign("variableRenewalDiscount", $res['DISCOUNT']);
	    }

		//$msg= $smarty->fetch("../../crm/templates/login1.htm");
		$msg= $smarty->fetch("../crm/login1.tpl");
		return $msg;

	}
}

function getFreeProfiles($profiles)
{
	global $db_slave;
	$profilesCnt =count($profiles);
	if($profilesCnt==0)
		return 0;
	else if($profilesCnt>=300)
		return 'N/A';

	$profile_str = "'".@implode("','",$profiles)."'";
	$sql="select count(*) as cnt from newjs.JPROFILE WHERE PROFILEID IN($profile_str) AND SUBSCRIPTION=''";
	$res= mysql_query_decide($sql, $db_slave) or die("$sql".mysql_error_js());
        $row = mysql_fetch_array($res);
        $cnt = $row['cnt'];
	return $cnt;
}
function getMembershipMessage($subState)
{
	if($subState==FTOSubStateTypes::FTO_ELIGIBLE_NO_PHONE_NO_PHOTO)
		$msg="Get photo uploaded and phone verified";
	elseif($subState==FTOSubStateTypes::FTO_ELIGIBLE_HAVE_PHONE_NO_PHOTO)
		$msg="Get photo uploaded";
	elseif($subState==FTOSubStateTypes::FTO_ELIGIBLE_NO_PHONE_HAVE_PHOTO)
		$msg="Get phone verified";
	elseif($subState==FTOSubStateTypes::FTO_ACTIVE_LEAST_THRESHOLD)
		$msg="Explain how to send EoIs";
	elseif($subState==FTOSubStateTypes::FTO_ACTIVE_BELOW_LOW_THRESHOLD)
		$msg="Ask to send more EoIs";
	elseif($subState==FTOSubStateTypes::FTO_ACTIVE_BETWEEN_LOW_HIGH_THRESHOLD)
		$msg="Ask to keep sending EoIs";
	elseif($subState==FTOSubStateTypes::FTO_ACTIVE_ABOVE_HIGH_THRESHOLD)
		$msg="Ask to add more details in profile";
	elseif($subState==FTOSubStateTypes::FTO_EXPIRED_BEFORE_ACTIVATED)
		$msg="Didn't take FTO, explain membership benefits";
	elseif($subState==FTOSubStateTypes::FTO_EXPIRED_AFTER_ACTIVATED)
		$msg="FTO time period expired, explain membership benefits";
	elseif($subState==FTOSubStateTypes::FTO_EXPIRED_INBOUND_ACCEPT_LIMIT)
		$msg="Inbound Acceptance limit expired, explain membership benefits";
	elseif($subState==FTOSubStateTypes::FTO_EXPIRED_OUTBOUND_ACCEPT_LIMIT)
		$msg="Outbound Acceptance limit expired, explain membership benefits";
	elseif($subState==FTOSubStateTypes::FTO_EXPIRED_TOTAL_ACCEPT_LIMIT)
		$msg="Total Acceptance limit expired, explain membership benefits";
	elseif($subState==FTOSubStateTypes::DUPLICATE)
		$msg="Duplicate profile, No FTO, ask to use earlier profile";
	else
		$msg="Other";
	return $msg;
}
function getFtoOfferStatus($state,$expiryDate)
{
	$expiryDate =@explode(" ",$expiryDate);
	$expiryDate=date("d-M-Y",JSstrToTime($expiryDate[0]));

        if($state==FTOStateTypes::FTO_ELIGIBLE)
                $msg="Eligible if activated till $expiryDate";
        elseif($state==FTOStateTypes::FTO_ACTIVE)
                $msg="FTO offer is Active till $expiryDate";
        elseif($state==FTOStateTypes::FTO_EXPIRED)
                $msg="FTO offer Expired on $expiryDate";
        elseif($state==FTOStateTypes::PAID)
                $msg="Paid during offer period";
        elseif($state==FTOStateTypes::DUPLICATE)
                $msg="Duplicate profile, can’t give free trial";
        else
                $msg="Never offered free trial";
        return $msg;
}	
function rbEligibilityFlag($photo,$eoiCount,$acceptance,$loginCount,$ageOfRegistration,$contactViewCount,$actualAge,$SERIOUSNESS_COUNT,$ftoState)
{
	$loginFrequency=$loginCount/$ageOfRegistration;		
	$starProfile=$contactViewCount/pow($actualAge,0.7);
	if($ftoState==FTOStateTypes::FTO_ELIGIBLE||$ftoState==FTOStateTypes::FTO_ACTIVE||$ftoState==FTOStateTypes::DUPLICATE)
		$rbEligible="Not Eligible";
	elseif($starProfile>=3)
		$rbEligible="Eligible";
	elseif($SERIOUSNESS_COUNT>1)
	{
		if($photo=='Y')
			$rbEligible="Eligible";
		else
			$rbEligible="Eligible,If photo is Uploaded";
	}
	elseif($eoiCount==0)
	{
		if($photo=='Y')
                        $rbEligible="Eligible";
                else
                        $rbEligible="Eligible,If photo is Uploaded";
	}
	elseif($eoiCount>=1 && $acceptance <=4 && $loginFrequency < 0.25)
	{
		if($photo=='Y')
                        $rbEligible="Eligible";
                else
                        $rbEligible="Eligible,If photo is Uploaded";
	}
	else
		$rbEligible="Not Eligible";
	
	return $rbEligible;
}
function serviceRequirement($photo,$eoiCount,$lastLoginDate,$profilePercent,$searchFrequency,$diff)
{
	if($diff<6)
                $requirementMessage="Explain how to use Jeevansathi.com";

	if($photo!="Y"){
		if($requirementMessage=="")
			$requirementMessage="Explain benefit of uploading photo";
		else
			$requirementMessage.=", Explain benefit of uploading photo";
	}

	if($eoiCount<=0)
	{
		if($requirementMessage=="")
			$requirementMessage="Explain how to send Eoi";
		else
			$requirementMessage.=", Explain how to send Eoi";
	}
	if(strtotime($lastLoginDate)<strtotime(date("Y-m-d",time()-10*24*60*60)))
        {
                if($requirementMessage=="")
                        $requirementMessage="Explain benefits of logging frequently";
                else
                        $requirementMessage.=", Explain benefits of logging frequently";
        }
	if($profilePercent<=60)
	{
		if($requirementMessage=="")
                        $requirementMessage="Update profile with more information";
                else
                        $requirementMessage.=", Update profile with more information";
	}	
	if($searchFrequency=="")
	{
		if($requirementMessage=="")
                        $requirementMessage="Explain benefits of actively searching";
                else
                        $requirementMessage.=", Explain benefits of actively searching";
	}
	if($requirementMessage=="")
		$requirementMessage="N/A";
	return $requirementMessage;	
}
function getProfileFTAScore($profileid,$lastLoginDt,$havePhoto,$eoi_sent)
{
	global $smarty;
	global $db_slave;

	$screeningObj=new jsadmin_SCREENING_LOG();
	$screeningCount=$screeningObj->isScreenedOneTime($profileid);
	$sql_screening = "SELECT COUNT(*) AS SCREENING_COUNT FROM jsadmin.SCREENING_LOG WHERE PROFILEID = '$profileid'";
	$res_screening = mysql_query($sql_screening,$db_slave);
	$row_screening=mysql_fetch_assoc($res_screening);
	if($row_screening["SCREENING_COUNT"]==2)
		$screeningCount=1;
	
	$today=date("Y-m-d",time());
	$today=strtotime($today);
	$lastLoginDt=strtotime($lastLoginDt);
	$diff=$today-$lastLoginDt;
	$intervalDays=$diff/86400;
	$profilePercent=profile_percent($profileid,'','1');
	$Days10Before=date("Y-m-d h:i:s",time()-10*24*60*60);
        if(function_exists(connect_slave81)){
        	$db_slave = connect_slave81();
        }
        else{
        	$db_slave = connect_81();
        }
	$sql_search_query="select count(*) AS CNT from MIS.SEARCHQUERY where PROFILEID=$profileid AND DATE>=$Days10Before";
	$res_search_query=mysql_query($sql_search_query,$db_slave);
	$row_search_query=mysql_fetch_assoc($res_search_query);
	if($row_search_query["CNT"]>0)	
		$performedSearchLast10Days=1;
	
	//if screened first time
	if($screeningCount==1)
		$score=6;
	//photo check
	if($havePhoto=="Y")
		$score+=1;
	//if sent EOI greater than 0
	if($eoi_sent>=1)
		$score+=1;
	//if logged in last 10 days
	if($intervalDays<=10)
		$score+=1;
	//profile completeness check
	if($profilePercent>60)
		$score+=1;
	//performed search in last 10 days
	if($performedSearchLast10Days>0)
		$score+=1;
	return $score;
}
?>
