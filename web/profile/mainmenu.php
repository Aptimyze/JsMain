<?php

//header("Location: /myjs/jspcPerform");
//$start_tm=microtime(true);
if(!$myjs_incompleteprofile)
{
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
}

if($nextPreviouscrousel)
        $_SERVER['ajax_error']=2;
include_once("connect.inc");
include_once("functions.inc");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once("arrays.php");
include_once("payment_array.php");
include_once("sphinx_search_function.php");
include_once("../classes/Membership.class.php");
include_once("mobile_detect.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/commonFiles/incomeCommonFunctions.inc");
MobileCommon::forwardmobilesite('','myjs','jsmsPerform');
if(MobileCommon::isDesktop())
{
	header("Location:".$SITE_URL."/myjs/jspcPerform");die;
}
$db=connect_db();
$yday=@mktime(0,0,0,@date("m"),@date("d")-90,@date("Y"));
$back_90_days=@date("Y-m-d",$yday);
$time_clause="TIME>='$back_90_days 00:00:00'";
$data=authenticated();

if(!$nextPreviouscrousel && !$data)
{
	TimedOut();
	exit;
}
if($nextPreviouscrousel && !$data)//no results
{
        logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",'timeout on next click on myjs',"ShowErrTemplate");
        die;
}
/*****************Portion of Code added for display of Banners*******************************/
$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("bms_topright",18);
$smarty->assign("bms_right",28);
$smarty->assign("bms_bottom",19);
$smarty->assign("bms_left",24);
$smarty->assign("bms_new_win",32);      
$smarty->assign("bms_mainmenu",43);

if (strstr($_SERVER['PHP_SELF'], 'mainmenu.php'))
	$smarty->assign("fromMainmenu",1);
link_track("mainmenu.php");
/*********************************************************************************/

$smarty->assign("con_chk",'1');

if($data) {
    $smarty->assign("LOGGEDIN", 1);
	login_relogin_auth($data);
}
if($isMobile)
{
	header("Location:/search/partnermatches");
	die;
}
$mypid=$data["PROFILEID"];
$memObj=new ProfileMemcacheService($mypid);
$subscription=$data["SUBSCRIPTION"];
$GENDER_LOGGED_IN=$data["GENDER"];
if(!$subscription)
	$paid=1;
else
	$paid=0;
$smarty->assign("paidMember",$paid);
if($mypid)
{


	// duplicate fto profile
	$smarty->assign('isDuplicate',(new incentive_NEGATIVE_TREATMENT_LIST())->isFtoDuplicate($mypid));
	$smarty->assign('username',$data['USERNAME']);


        if(!stristr($data['SUBSCRIPTION'],'F'))
                check_profile_percent();
	else
		$smarty->assign("userIsBmsPaid",1);

	$myprofilechecksum = md5($mypid)."i".($mypid);
	$smarty->assign("myprofilechecksum",$myprofilechecksum);
	$contactResultRec=getResultSet("RECEIVER,TYPE",$data[PROFILEID],'','','',"",'','','','','','',"",'','','',"",'','');
	if(is_array($contactResultRec))
	{
		foreach($contactResultRec as $k=>$v)
		{
			if($contactResultRec[$k]["RECEIVER"])
			{
				if(in_array($contactResultRec[$k]["TYPE"],array('D','C')))
					$skipProfiles[]=$contactResultRec[$k]["RECEIVER"];
				$skipProfilesMatch[]=$contactResultRec[$k]["RECEIVER"];
			}
		}
	}

	$contactResultSen=getResultSet("SENDER,TYPE",'','',$data[PROFILEID],'',"",'','','','','','',"",'','','',"",'','');
	if(is_array($contactResultSen))
	{
		foreach($contactResultSen as $k=>$v)
		{
			if($contactResultSen[$k]["SENDER"])
			{
				if(in_array($contactResultSen[$k]["TYPE"],array('D','C')))
					$skipProfiles[]=$contactResultSen[$k]["SENDER"];
				$skipProfilesMatch[]=$contactResultSen[$k]["SENDER"];
			}
		}
	}
	if($skipProfiles)
		$skipProfilesStr=implode(",",$skipProfiles);
	if($skipProfilesMatch)
		$skipProfilesStrMatch=implode(",",$skipProfilesMatch);
	if($skipProfilesMatch)
		$skipProfilesStrMatch=implode(",",$skipProfilesMatch);

	if(!$nextPreviouscrousel)
	{
		
		//---------------- Profile Completetion --------------------
		//$p_percent=profile_percent($mypid,"","","","",3);
		
		$cScoreObject = ProfileCompletionFactory::getInstance(null,null,$mypid);
		$iPCS = $cScoreObject->getProfileCompletionScore();
		$p_percent = $iPCS;
		$arrMsgDetails = $cScoreObject->GetIncompleteDetails();
		$arrLinkDetails = $cScoreObject->GetLink("MyJS");

		$smarty->assign("iPCS",$iPCS);
		$smarty->assign("arrMsgDetails",$arrMsgDetails);
		$smarty->assign("arrLinkDetails",$arrLinkDetails);
		$smarty->assign("PROFILE_PERCENT",$p_percent);
		//---------------- Profile Completetion --------------------
;
	//THIS QUERY IS MODIFIED TO CHECK FOR THE CASTE_REVAMP_LAYER TO BE DISPLAYED AT LOGIN. BY ANAND
		$sql1="select j.PROFILEID,AGE,CASTE,RELIGION,MTONGUE,COUNTRY_RES,EMAIL,VERIFY_EMAIL,HAVEPHOTO,INCOMPLETE,COUNTRY_BIRTH,CITY_BIRTH,SHOW_HOROSCOPE,YOURINFO,ENTRY_DT,RELATION,USERNAME,MSTATUS,HAVECHILD,CITY_RES,TIME_TO_CALL_START,TIME_TO_CALL_END,EDU_LEVEL_NEW,OCCUPATION,INCOME,CITIZENSHIP,ACTIVATED,STD,PHONE_RES,PHONE_MOB,SHOWPHONE_RES,SHOWPHONE_MOB,PHONE_OWNER_NAME,MOBILE_OWNER_NAME,MOB_STATUS,LANDL_STATUS,ISD,PHONE_FLAG,CASTE_REVAMP_FLAG,OLD_CASTE,OLD_PROFESSION,OLD_EDUCATION,REVAMP_VALUE,PHOTO_DISPLAY,SUBSCRIPTION from newjs.JPROFILE j LEFT JOIN MIS.REVAMP_LAYER_CHECK m ON j.PROFILEID = m.PROFILEID WHERE activatedKey=1 and j.PROFILEID='$mypid'";
	//QUERY MODIFICATION ENDS
		$res1=mysql_query_decide($sql1);
		$row1=mysql_fetch_array($res1);
		if($row1["ACTIVATED"]=='H')
			$smarty->assign("hiddenprofile",1);
//PIXEL CODE VARIABLES :
		$gotItBandObj = new GotItBand($mypid);
		$showGotItBand = $gotItBandObj->showBand(GotItBand::$MYJS,$row1['ENTRY_DT']);
		$smarty->assign("showGotItBand",$showGotItBand);
		$smarty->assign("GotItBandPage",GotItBand::$MYJS);
		$smarty->assign("GotItBandMessage",GotItBand::$educationMYJS);
		$smarty->assign("GOT_IT_BAND",$smarty->fetch(JsConstants::$docRoot."/../apps/jeevansathi/templates/_gotItBand.tpl"));
		$CITY_RES_pixel=$row1["CITY_RES"];
		$AGE_pixel=$row1["AGE"];
		$GENDER_pixel=$GENDER_LOGGED_IN;
		$PROFILEID_pixel=$row1["PROFILEID"];
		$USERNAME_pixel=$row1["USERNAME"];


		//CASTE REVAMP LAYER STATUS STARTS
		if ($row1["CASTE_REVAMP_FLAG"])
		{
			$caste_revamp_layer_status = $row1["CASTE_REVAMP_FLAG"];
		}
		else
		{
			$caste_revamp_layer_status = 0;
		}
		$smarty->assign("CASTE_REVAMP_LAYER_STATUS",$caste_revamp_layer_status);
		$smarty->assign("OLD_CASTE_VAL",$row1["OLD_CASTE"]);
		$smarty->assign("CURRENT_CASTE_VAL",$row1["CASTE"]);
		$smarty->assign("OLD_OCCUPATION_VAL",$row1["OLD_PROFESSION"]);
		$smarty->assign("CURRENT_OCCUPATION_VAL",$row1["OCCUPATION"]);
		$smarty->assign("OLD_EDUCATION_VAL",$row1["OLD_EDUCATION"]);
		$smarty->assign("CURRENT_EDUCATION_VAL",$row1["EDU_LEVEL_NEW"]);
		$smarty->assign("MTONGUE_VAL",$row1["MTONGUE"]);
		$smarty->assign("REVAMP_VALUE",$row1["REVAMP_VALUE"]);
		//CASTE REVAMP LAYER STATUS ENDS

		//CHECK FOR FTO STATE STARTS
		$ftoStateArray = SymfonyFTOFunctions::getFTOStateArray($mypid);
		if($ftoStateArray && is_array($ftoStateArray))
		{
			if($ftoStateArray["SUBSTATE"]==FTOSubStateTypes::FTO_ELIGIBLE_NO_PHONE_NO_PHOTO)	//C1
				$fto_state=1;
			elseif($ftoStateArray["SUBSTATE"]==FTOSubStateTypes::FTO_ELIGIBLE_HAVE_PHONE_NO_PHOTO)	//C2
				$fto_state=2;
			elseif($ftoStateArray["SUBSTATE"]==FTOSubStateTypes::FTO_ELIGIBLE_NO_PHONE_HAVE_PHOTO)	//C3
				$fto_state=3;
			elseif($ftoStateArray["SUBSTATE"]==FTOSubStateTypes::FTO_ACTIVE_LEAST_THRESHOLD || $ftoStateArray["SUBSTATE"]==FTOSubStateTypes::FTO_ACTIVE_BELOW_LOW_THRESHOLD || $ftoStateArray["SUBSTATE"]==FTOSubStateTypes::FTO_EXPIRED_OUTBOUND_ACCEPT_LIMIT)	//D1,D2,E4
				$fto_state=4;
			elseif($ftoStateArray["SUBSTATE"]==FTOSubStateTypes::FTO_ACTIVE_BETWEEN_LOW_HIGH_THRESHOLD || $ftoStateArray["SUBSTATE"]==FTOSubStateTypes::FTO_ACTIVE_ABOVE_HIGH_THRESHOLD)	//D3,D4
			{
				$fto_state=5;
				$linkData = SymfonyFTOFunctions::editOnFtoContactConfirmation($mypid);
				$smarty->assign("D3_D4_HREF",$linkData["HREF"]);
				$smarty->assign("D3_D4_HREF_TEXT",$linkData["TEXT"]);
			}
			$smarty->assign("FTO_STATE",$fto_state);
		}
                //CHECK FOR FTO STATE ENDS

		/*Ticket #1843: Need to rollback changes made in trac #1078*/
		/*
		if(in_array($ftoStateArray['SUBSTATE'],array(FTOSubStateTypes::FTO_EXPIRED_BEFORE_ACTIVATED,FTOSubStateTypes::FTO_EXPIRED_AFTER_ACTIVATED,FTOSubStateTypes::NEVER_EXPOSED,FTOSubStateTypes::DUPLICATE)))
		{
			$photoLayerStatus = checkPhotoPrivacy($mypid,$row1);
			$smarty->assign("PHOTO_DISPLAY_PRIVACY_LAYER",$photoLayerStatus);
		}
		*/
		/**/

		// IVR - check to display the auto phone verification layer on MYJS page on login (only login case is considered to show layer)
		if($row1['PROFILEID'])
		{
			$ph_layer_status =get_phoneVerifyLayer($row1);
			$smarty->assign("PH_LAYER_STATUS",$ph_layer_status);			
			$showPrivacySettingLayer = getPrivacySettingLayer($row1,$row1['PROFILEID']);
			$smarty->assign("SHOW_PRIVACY_LAYER",$showPrivacySettingLayer);
		}
		// IVR - ends 

		$smarty->assign("OFFER",$OFFER=(stristr($data['SUBSCRIPTION'],'F'))?"My_JS_Paid_Banner.swf":"js_banenr.gif");
		//GTalk Condn
		$selfEmail=$row1["EMAIL"];
		if(strstr($selfEmail,'@gmail'))
		{
			$sqlGtalk="SELECT COUNT(*) as CNT FROM openfire.ofRoster WHERE jid='$selfEmail' AND sub in (2,3)";
			$resGtalk=mysql_query_decide($sqlGtalk);
			$rowGtalk=mysql_fetch_array($resGtalk);
			if($rowGtalk[0]==0)
			{
				$sql_olduser="select PROFILEID from bot_jeevansathi.invite_send where PROFILEID=$data[PROFILEID]";
                	        $resold=mysql_query_decide($sql_olduser);
        	                if(!($rowold=mysql_fetch_row($resold)))
					$smarty->assign("oldgtalkuser",1);
				$smarty->assign("gtalkMsg",2);
			}
		}
		else
			$smarty->assign("gtalkMsg",1);

		//GTalk Condn

		if(!strstr($subscription,'F'))
		{
			$overall_limit=$data['OVERALL_LIMIT'];
			//if($overall_limit<500)
			//Mantis removed.
			if(false)
			{
				if($data['TOTAL_CONTACTS_MADE']>=$overall_limit)
					$smarty->assign("quotafull",1);
			}
		}

		if($row1["COUNTRY_RES"]!=51)
			$smarty->assign("NRI",1);
		else
			$smarty->assign("NRI",0);
		if($row1['HAVEPHOTO']=='Y')
		{
			$PHOTOCHECKSUM =md5($mypid+5)."i".($mypid+5);
			//Symfony Photo Modification
                     	$profilePicObjs = SymfonyPictureFunctions::getProfilePicObjs($mypid);
			$profilePicObj = $profilePicObjs[$mypid];
			if ($profilePicObj)
				$myphoto = $profilePicObj->getThumbailUrl();
			else
				$myphoto = null;	
			//Symfony Photo Modification
		}
		elseif($row1['HAVEPHOTO']=='N' || $row1['HAVEPHOTO']=='')
		{
			if($data["GENDER"]=='F')
			{
				$myphoto=$IMG_URL.'/profile/images/ic_photo_notavailable_g_60.gif';
			}
			else
			{
				$myphoto=$IMG_URL.'/profile/images/ic_photo_notavailable_b_60.gif';
			}
		}
		else
		{
			if($data["GENDER"]=='F')
				$myphoto=$IMG_URL.'/profile/images/ph_cmgsoon_sm_g.gif';
			else
				$myphoto=$IMG_URL.'/profile/images/ph_cmgsoon_sm_b.gif';
		}
		$smarty->assign("myphoto",$myphoto);

		//=============== invalid email section ===================
		$verify_email = bounced_emailID($mypid,$row1["EMAIL"],$row1["VERIFY_EMAIL"]);
		if($verify_email)
		{
			$smarty->assign("INVALID_EMAIL",1);
			$smarty->assign("invalidemail",$row1["EMAIL"]);
		}
		//=============== invalid email section ===================

		//--------------- Incomeplete Layer/section ----------------
		$yourinfo=$row1['YOURINFO'];

		if($row1['INCOMPLETE']=='Y')
		{
			if($row1['ENTRY_DT']>'2009-04-06')
			{
				$smarty->assign("PROFILE_INCOMPLETE",1);
				$incompleteAction="viewprofile.php?checksum=$CHECKSUM&profilechecksum=$myprofilechecksum&EditWhatNew=incompletProfile";
			}
			else
				$incompleteAction="viewprofile.php?checksum=$CHECKSUM&profilechecksum=$myprofilechecksum";
		}
		elseif(strlen($yourinfo)<100 && $row1['ENTRY_DT']>'2009-04-06')
		{
			$incompleteAction="viewprofile.php?checksum=$CHECKSUM&profilechecksum=$myprofilechecksum&EditWhatNew=incompletProfile";
		}

		$smarty->assign("incompleteAction",$incompleteAction);
		//--------------- Incomeplete Layer/section ----------------
                
		//=============== Login History & ip Section==================
		$ipaddr=FetchClientIP();//Gets ipaddress of user
		if(strstr($ipaddr, ","))
		{
			$ip_new = explode(",",$ip);
			$ipaddr = $ip_new[1];
		}
		$smarty->assign("IP_ADDR",$ipaddr);

		$smarty->assign("nextLoginHistory",30);
		if($row1['COUNTRY_RES']==51)
		{
			$smarty->assign("DATE_TIME_TEXT","Date &amp; Time in Indian <br>Standard Time");
			$smarty->assign("country",1);
		}
		else
		{
			$smarty->assign("DATE_TIME_TEXT","Date &amp; Time in Eastern <br>Standard/Daylight Time");
			$smarty->assign("country",0);
		}
		//=============== Login History & ip Section==================
		//===============   IVR - Start- Verify Your Phone Number / Invalid Phone check(displays invalid alert box)   ===================
		$mobile		=$row1['PHONE_MOB'];
		$phone_res	=$row1['PHONE_RES'];
		$phone_std	=$row1['STD'];	
		if($phone_std)
			$phone_res =$phone_std."-".$phone_res;

		$chk_phoneStatus =getPhoneStatus($row1,$mypid);
		if($chk_phoneStatus =='I')
		{
			$invalidPhone=1;
			$smarty->assign("INVALID_PHONE",1);
		}				
		if($mobile || $phone_res)
		{
			// if none of the numbers(mobile/landline) is not verified, link to verify phone is shown on MYJS page 
			// if any of number(mobile/landline) is verified link to verify phone is not shown  
			if($chk_phoneStatus !='Y' && $chk_phoneStatus !='I')
			{
				$smarty->assign("UNVERIFIED",'1');
				$smarty->assign("LANDLINE",$phone_res);
				$smarty->assign("MYMOBILE",$mobile);
			}		
		}
		else
			$smarty->assign("UNVERIFIED",'1');
				
		//===============   IVR - Ends- Verify Your Phone Number    ===================

		//===============   MemberShip Section  =====================
		//Gadget Check start
		$memHandlerObj = new MembershipHandler();
		$memGadgetDisplay = $memHandlerObj->checkIfUserIsPaidAndNotWithinRenew($data["PROFILEID"]);
		$smarty->assign("memGadgetDisplay",$memGadgetDisplay);
		unset($memHandlerObj);
		//Gadget Dispaly check end
		$smarty->assign("PAY_ERISHTA",$pay_erishta);
		if(CommonFunction::isPaid($subscription))
			$fpaid=1;
		else
			$paymessage='';

		$paymessage='<li>Send personalized messages</li> <li>Send contact details along with your'."<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".' messages </li> <li>Initiate online chat with Jeevansathi '."<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".'members </li> <li>Contact people instantly with direct calls</li>';

		$smarty->assign("top",135);
		$smarty->assign("topBox",-5);
		$membershipObj=new  Membership;
		$service_array=$membershipObj->lastMainExpiryDate($mypid);
		if($fpaid)
		{
			$smarty->assign("topBox",10);
			if(CommonFunction::isEvalueMember($subscription))
			{ 
				$logo=$IMG_URL.'/profile/images/evalue12.gif';	
				$paymessage='<li>Write instant messages </li> <li>View contact details of accepted members </li> <li>Initiate online chat </li> <li>Let other members see your contact details </li> <li>Contact people instantly with direct calls</li>';
				$smarty->assign("evalue",1);
				$smarty->assign("top",120);
			}
			elseif(CommonFunction::isErishtaMember($subscription))
			{
				$smarty->assign("top",120);
				$logo=$IMG_URL."/profile/images/logo_erishta_1.gif";
				$paymessage='<li>Write instant messages </li> <li>View contact details of accepted members </li> <li>Initiate online chat </li> <li>Let other members see your contact details </li> <li>Contact people instantly with direct calls</li>';
				$smarty->assign("erishta",1);
			}
			elseif(CommonFunction::isJsExclusiveMember($subscription))
			{
				$smarty->assign("top",120);
				$paymessage='<li>Instantly see Phone/Email of members </li> <li>Initiate Chat and Send Messages </li> <li>Personalized service by a matchmaking expert </li>';
				$smarty->assign("JsExclusive",1);
			}

			if(is_array($service_array))
			{
				$smarty->assign("EXPIRY_DT",$service_array["EXPIRY_DT"]);
				$smarty->assign("EXPIRY_ALERT",$service_array["EXPIRY_IN_15"]);
				$smarty->assign("SHOW_DT",$service_array["SHOW_10"]);
				$serviced=$service_array["SERVICEID"].",B".$service_array["SERVICEID"][1];	
				$smarty->assign("SHOW_SERVICE",$serviced);
			}
		}
		else
			$smarty->assign("freeMember",1);
		$smarty->assign("logo",$logo);
		$smarty->assign("paymessage",$paymessage);
		$serObj = new Services;

		if($serObj->getFestive())
			$smarty->assign('Fest',1);
		else
			$smarty->assign('Fest',0);


		$gadgetdateArr=$membershipObj->getSpecialDiscount($mypid);
		if(strlen($membershipObj->isRenewable($mypid))>2)
		{
			$memHandlerObj = new MembershipHandler();
			$renew_discount_rate = $memHandlerObj->getVariableRenewalDiscount($mypid);
			$renew_dt=$service_array["RENEW_DT"];
			if($renew_dt && $renew_dt!='L')
			{
				$smarty->assign("gadgetdateformat",$renew_dt);
				$smarty->assign("discount",$renew_discount_rate);
			}
		}
		elseif($gadgetdateArr)
		{
			$gadgetdate=$gadgetdateArr["EDATE"];
			if($gadgetdate)
			{
				// Code added for flat/upto
				$variableDiscountObj =new VariableDiscount();
				$discountLimitTextVal = $variableDiscountObj->getVdDisplayText($mypid,'small');

				list($yy,$mm,$dd)= explode("-",$gadgetdate);
				$timestamp= @mktime(0,0,0,$mm,$dd,$yy);
				$gadgetdateformat=@date('d M Y',$timestamp);
				$smarty->assign("gadgetdateformat",$gadgetdateformat);
				$smarty->assign("discount",$gadgetdateArr['DISCOUNT']);
				$smarty->assign("SpecMember",1);
				$smarty->assign("discountLimitTextVal",$discountLimitTextVal);
			}
		}
		//================= MemberShip Section =====================
	}
}
if(!$offset)
	$offset=0;

//Acceptances
if(!$nextPreviouscrousel || $nextPreviouscrousel=='acceptanceacc')	
{
	$contactResult=getResultSet("RECEIVER,TIME",$data[PROFILEID],'','','',"'A'",'','','','','','',"",'','','',"'Y'",'','',1);
	$j=$contactResult[0]['found_rows'];
	if($j>0)
	{
		$accTotalRec=$j;
		foreach($contactResult as $k=>$v)
		{
			$newcontactResult[$contactResult[$k]["RECEIVER"]]=$contactResult[$k]["TIME"];
		}
		if($offset)
			$var1=$offset;
		else
			$var1=0;
		$var2=-1;
		$sum=0;
		arsort($newcontactResult);
		foreach($newcontactResult as $k=>$v)
		{
			$var2++;
			if($var1!=$var2)
				continue;
			$accArr[]=$k;
			$globalProfileArr[]=$k;
			$var1++;
			if($sum++>1)
				break;
		}
		if($nextPreviouscrousel)
			myjsSetResults("acceptance",$accArr,$offset,3,'',$accTotalRec);
	}
	unset($newcontactResult);

}
//Acceptances

//Expressions of Interest
if(!$nextPreviouscrousel || $nextPreviouscrousel=='initialacc')
{
	/*new*/
	$contactResult=getResultSet("SENDER,TIME","","",$data[PROFILEID],"","'I'",'',$time_clause,"","","","","","","","","'Y'","","'Y'",1);
	//$contactResult=getResultSet("SENDER,TIME","","",$data[PROFILEID],"","'I'",'','',"","","","","","","","","'Y'","","'Y'",1);
	/*new*/
        $j=$contactResult[0]['found_rows'];
        if($j>0)
        {
                $iniTotalRec=$j;
                foreach($contactResult as $k=>$v)
                {
			$newcontactResult[$contactResult[$k]["SENDER"]]=$contactResult[$k]["TIME"];
                }
                if($offset)
                        $var1=$offset;
                else
                        $var1=0;
                $var2=-1;
	        $sum=0;
	        arsort($newcontactResult);

                foreach($newcontactResult as $k=>$v)
                {
                        $var2++;
                        if($var1!=$var2)
                                continue;
                        $iniArr[]=$k;
                        $globalProfileArr[]=$k;
                        $var1++;
                        if($sum++>1)
                                break;
                }
                if($nextPreviouscrousel)
			myjsSetResults("initial",$iniArr,$offset,3,'',$iniTotalRec);
        }
	
}
//Expressions of Interest

if(!$nextPreviouscrousel)
{

        //all messages 
        $mysqlObj=new Mysql;
        $myDbName=getProfileDatabaseConnectionName($mypid,'',$mysqlObj);
        $myDb=$mysqlObj->connect("$myDbName");

//------------------------------------------------------------ opt1-------------------------------------------------------------------------
	/*$sql="SELECT SENDER,SEEN FROM MESSAGE_LOG WHERE RECEIVER='$mypid' AND IS_MSG='Y' AND TYPE='R'";
	$result=$mysqlObj->executeQuery($sql,$myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	while($myrow=$mysqlObj->fetchArray($result))
	{
		if($myrow["SEEN"]<>'Y')
			$newarr[]=$myrow["SENDER"];
		$arr[]=$myrow["SENDER"];
	}
	if($arr)
	{
          	$arr=array_unique($arr);
		$subtract=0;
		if(is_array($skipProfiles))
			$subtract=count(array_intersect($arr,$skipProfiles));
		$new_message=count($arr)-$subtract;
		$smarty->assign("ALL_NEW_INBOX_CNT",$new_message);
		unset($new_message);

		$subtract=0;
		if($newarr)
		{
          		$newarr=array_unique($newarr);
			if(is_array($skipProfiles))
				$subtract=count(array_intersect($newarr,$skipProfiles));
			$new_message=count($newarr)-$subtract;
		}
		$smarty->assign("NEW_INBOX_CNT",$new_message);
	}
	else
		$smarty->assign("ALL_NEW_INBOX_CNT",0);
	

	if($skipProfilesStr)
		$sql="SELECT count(*) as cnt,SEEN FROM PHOTO_REQUEST WHERE PROFILEID_REQ_BY='$mypid'  AND PROFILEID NOT IN ($skipProfilesStr) GROUP BY SEEN";
	else
		$sql="SELECT count(*) as cnt,SEEN FROM PHOTO_REQUEST WHERE PROFILEID_REQ_BY='$mypid'  GROUP BY SEEN";
	$result = $mysqlObj->executeQuery($sql,$myDb);
	*/
			$NEW_PHOTO_REQUEST_CNT=$memObj->get('PHOTO_REQUEST_NEW');

		$ALL_PHOTO_REQUEST_CNT=$memObj->get('PHOTO_REQUEST');;
	if(!$ALL_PHOTO_REQUEST_CNT)
		$ALL_PHOTO_REQUEST_CNT=0;
	$smarty->assign("NEW_PHOTO_REQUEST_CNT",$NEW_PHOTO_REQUEST_CNT);
	$smarty->assign("ALL_PHOTO_REQUEST_CNT",$ALL_PHOTO_REQUEST_CNT);
	$smarty->assign("HAVEPHOTO",$row1["HAVEPHOTO"]);

	/*if($skipProfilesStr)
		$sql="SELECT count(*) as cnt,SEEN FROM HOROSCOPE_REQUEST WHERE PROFILEID_REQUEST_BY='$mypid' AND PROFILEID NOT IN ($skipProfilesStr) GROUP BY SEEN";
	else
		$sql="SELECT count(*) as cnt,SEEN FROM HOROSCOPE_REQUEST WHERE PROFILEID_REQUEST_BY='$mypid' GROUP BY SEEN";
	$result = $mysqlObj->executeQuery($sql,$myDb);
        while($count_horo_request=$mysqlObj->fetchArray($result))
        {
                if($count_horo_request["SEEN"]!='Y')
                        $NEW_HORO_REQUEST_CNT+=$count_horo_request["cnt"];
                $ALL_HORO_REQUEST_CNT+=$count_horo_request["cnt"];
        }*/

        $NEW_PHOTO_REQUEST_CNT=$memObj->get('HOROSCOPE');
		$ALL_PHOTO_REQUEST_CNT=$memObj->get('HOROSCOPE_NEW');;
        $smarty->assign("NEW_HOROSCOPE_REQUESTS",$NEW_HORO_REQUEST_CNT);
        $smarty->assign("ALL_HOROSCOPE_REQUEST",$ALL_HORO_REQUEST_CNT);



	$country_birth=$row1['COUNTRY_BIRTH'];
	$city_birth=$row1['CITY_BIRTH'];
	$show_horoscope=$row1['SHOW_HOROSCOPE'];
	$birthtime=$row1['BTIME'];
	if(check_astro_details($mypid,$show_horoscope))
		;
	else
		$smarty->assign("HAVEHOROSCOPE",'N');
	/*new*/
	//Horoscope Requests received

        /* IVR-Callnow fearure added, 
	 * This portion gets the count of the calls (Missed calls , Received calls, Called made ) 
	*/
	if($CALL_NOW){
	// Calls Missed
	$getMissedCallDataArr 	= getCallnowResultCount($mypid,'RECEIVER_PID','M');
	$missedCallNew	      	= $getMissedCallDataArr['callnow']['M']['NEW'];
	$missedCallTotal      	= $getMissedCallDataArr['callnow']['M']['TOTAL'];
	$smarty->assign("NEW_MISSED_CALL_CNT",$missedCallNew);
	$smarty->assign("TOTAL_MISSED_CALL_CNT",$missedCallTotal);
	
	// Calls Received
        $getReceivedCallDataArr = getCallnowResultCount($mypid,'RECEIVER_PID','R');
        $receivedCallNew        = $getReceivedCallDataArr['callnow']['R']['NEW'];
	$receivedCallTotal      = $getReceivedCallDataArr['callnow']['R']['TOTAL'];
	$smarty->assign("NEW_RECEIVED_CALL_CNT",$receivedCallNew);
        $smarty->assign("TOTAL_RECEIVED_CALL_CNT",$receivedCallTotal);

	// Calls Made
        $getCalledCallDataArr 	= getCallnowResultCount($mypid,'CALLER_PID','I');
        $calledCallTotal      	= $getCalledCallDataArr['callnow']['I']['TOTAL'];
        $smarty->assign("TOTAL_CALL_MADE_CNT",$calledCallTotal);
        /* Ends IVR-Callnow feature  */
	}

	// CONTACTS STATS PAGE
	//----------------------------------------------------------::: CONTACTS_STATUS_TRACK  :::--------------------------------------------
        /*$contactResult=getResultSet("count(*) as cnt","","",$data[PROFILEID],"","'I'",'',$time_clause,"","","","","","","","","","","'Y'");
        */
        $totalAwaiting=$memObj->get('AWAITING_RESPONSE');
        if($totalAwaiting)
                $smarty->assign("RECEIVED_II",$totalAwaiting);
        else
                $smarty->assign("RECEIVED_II",0);
	/*$contactResult=getResultSet("count(*) as cnt",$data[PROFILEID],"","","","'I'","","","","","","","","","","","'Y'");
	if(is_array($contactResult))*/
		$MADE_D_R=$memObj->get('NOT_REP');
	if(!$MADE_D_R)
		$MADE_D_R=0;

	// for Viewed
	$smarty->assign("MADE_D_R",$MADE_D_R);


	//filter contacts
	$RECEIVED_II_FF=0;
	/*$contactResult=getResultSet("COUNT(*) AS CNT, SEEN","","",$data[PROFILEID],"","'I'",'',$time_clause,"SEEN","","","","","","","","","'Y'","");
	if($contactResult)
	{
		foreach($contactResult as $k=>$v)
		{
			*/$RECEIVED_II_FF=$memObj->get('FILTERED');
			if(!$RECEIVED_II_FF)
		$RECEIVED_II_FF=0;
				$RECEIVED_II_FF_NEW=$memObj->get('FILTERED_NEW');
		if(!$RECEIVED_II_FF_NEW)
		$RECEIVED_II_FF_NEW=0;
	
	$smarty->assign("RECEIVED_II_FF",$RECEIVED_II_FF);
        $smarty->assign("RECEIVED_II_FF_NEW",$RECEIVED_II_FF_NEW);
	/*new*/
	//filter contacts
/*
	$contactResult=getResultSet("COUNT(*) AS CNT",$data[PROFILEID],'','','',"'D'",'','','','','','',"",'','','',"'Y'",'','');
*/	
	$MAD_D_NEW=$memObj->get('DEC_ME_NEW');
	if (!$MAD_D_NEW)
	 $MAD_D_NEW=0;
	$smarty->assign("MAD_D_NEW",$MAD_D_NEW);
	/*new*/

	// CONTACTS STATS PAGE
}
//--------------- Critical Action Layer section ------------
                $loggedInProfileObj = LoggedInProfile::getInstance();
                $layerToShow = CriticalActionLayerTracking::getCALayerToShow($loggedInProfileObj,$totalAwaiting);
                if ($layerToShow) {
                  $smarty->assign("CALayerShow",$layerToShow);
                }
                else {
                  $smarty->assign("CALayerShow",0);
                }
                //--------------- Critical Action Layer section ------------

$totalCnt=0;
//Matchalerts  (new Matches)
if(!$nextPreviouscrousel || $nextPreviouscrousel=='matchalertsacc')
{
	if(JsConstants::$alertServerEnable)
	{
		connect_slave81();
		/*if($skipProfilesStrMatch)
			$sql="SELECT SQL_CALC_FOUND_ROWS USER,DATE FROM matchalerts.LOG USE INDEX(RECEIVER) WHERE RECEIVER ='$mypid' AND USER NOT IN ($skipProfilesStrMatch) ORDER BY DATE DESC LIMIT 14";
		else
			$sql="SELECT SQL_CALC_FOUND_ROWS USER,DATE FROM matchalerts.LOG USE INDEX(RECEIVER) WHERE RECEIVER ='$mypid' ORDER BY DATE DESC LIMIT 14";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"continue","","");
		while($row=mysql_fetch_array($res))
		{
			$dt=$row["DATE"];
			if(!$maxdt)
				$maxdt=$dt;
			if($maxdt==$dt)
			{
				$matchalertsPids[]=$row["USER"];
			}
		}
		if($matchalertsPids)
		{
			$sql_cosmo_rows="select FOUND_ROWS() as cnt";
			$resultcosmo=mysql_query_decide($sql_cosmo_rows) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_cosmo_rows,"ShowErrTemplate","","",$db);
			$countcosmo=mysql_fetch_row($resultcosmo);
			connect_db();
			$matchalertsPids = sortByPhotoLogic($matchalertsPids);
		}*/
		$matchalertsProfile = $matchProfilesArray = SearchCommonFunctions::getMatchAlertsMatches("","",$data[PROFILEID]);
		$matchalertsPids = $matchalertsProfile["PIDS_NEW"];
		if(count($matchalertsPids)>$offset)	
		{
			$smarty->assign("totalMatchalerts",$matchalertsProfile["CNT"]);

			$totalCnt+=1;
			if($matchalertsPids[$offset])
				$matchArr[0]=$matchalertsPids[$offset];
			if($matchalertsPids[$offset+1])
				$matchArr[1]=$matchalertsPids[$offset+1];
			if($matchalertsPids[$offset+2])
				$matchArr[2]=$matchalertsPids[$offset+2];

			if($matchalertsPids[$offset])
				$globalProfileArr[]=$matchalertsPids[$offset];
			if($matchalertsPids[$offset+1])
				$globalProfileArr[]=$matchalertsPids[$offset+1];
			if($matchalertsPids[$offset+2])
				$globalProfileArr[]=$matchalertsPids[$offset+2];

			$matchTotalRec=count($matchalertsPids);
			if($nextPreviouscrousel)
			{
				myjsSetResults("matchalerts",$matchArr,$offset,3,'',$matchTotalRec);
			}
		}
		else
			connect_db();
	}
}
//Matchalerts  (new Matches)

//Horoscope  (UPLOAD CONDITION NEED TO ADD)
if(!$nextPreviouscrousel || $nextPreviouscrousel=='horoscopeacc')
{
	$mysqlObj=new Mysql;
	$myDbName=getProfileDatabaseConnectionName($mypid);
	$myDb=$mysqlObj->connect("$myDbName");

	$sql = "SELECT  SQL_CACHE SQL_CALC_FOUND_ROWS PROFILEID_REQUEST_BY FROM HOROSCOPE_REQUEST WHERE PROFILEID ='$mypid' AND UPLOAD_SEEN='U' ORDER BY DATE DESC limit $offset,3";
	$res = $mysqlObj->executeQuery($sql,$myDb);
	while($row=mysql_fetch_array($res))
	{
		$horoArr[]=$row["PROFILEID_REQUEST_BY"];
		$globalProfileArr[]=$row["PROFILEID_REQUEST_BY"];
	}
	$csql="Select FOUND_ROWS()";
	$cres=$mysqlObj->executeQuery($csql,$myDb);
	$crow =$mysqlObj->fetchRow($cres);
	$horoTotalRec = $crow[0];

	if($horoTotalRec)
	{
		$totalCnt+=1;
		if($nextPreviouscrousel)
			myjsSetResults("horoscope",$horoArr,$offset,3,'',$horoTotalRec);
	}

}
//Horoscope (UPLOAD CONDITION NEED TO ADD)

//Photo (UPLOAD CONDITION NEED TO ADD)
if(!$nextPreviouscrousel || $nextPreviouscrousel=='photoacc')
{
        $mysqlObj=new Mysql;
        $myDbName=getProfileDatabaseConnectionName($mypid);
        $myDb=$mysqlObj->connect("$myDbName");

        $sql = "SELECT  SQL_CACHE SQL_CALC_FOUND_ROWS PROFILEID_REQ_BY FROM PHOTO_REQUEST WHERE PROFILEID ='$mypid' AND UPLOAD_SEEN='U' ORDER BY DATE DESC limit $offset,3";
        $res = $mysqlObj->executeQuery($sql,$myDb);
        while($row=mysql_fetch_array($res))
        {
                $photoArr[]=$row["PROFILEID_REQ_BY"];
		$globalProfileArr[]=$row["PROFILEID_REQ_BY"];
        }
        $csql="Select FOUND_ROWS()";
        $cres=$mysqlObj->executeQuery($csql,$myDb);
        $crow =$mysqlObj->fetchRow($cres);
        $photoTotalRec = $crow[0];
	if($photoTotalRec)
	{
		$totalCnt+=1;
		if($nextPreviouscrousel)
	        	myjsSetResults("photo",$photoArr,$offset,3,'',$photoTotalRec);
	}

}
//Photo (UPLOAD CONDITION NEED TO ADD)


//Visitors
if(!$nextPreviouscrousel || $nextPreviouscrousel=='visitoralertsacc')

{
	$db=connect_737_lan();
	$viewerArrres=visitors($mypid,$data["GENDER"],'y');
	if(is_array($viewerArrres))
	{
		foreach($viewerArrres as $key=>$val)
        	{
			$row_1=explode(",",$val);
                	if($row_1[2]!='Y')
                		$viewerArr[]=$row_1[0];
        	}
		$smarty->assign("totalviewerCnt",count($viewerArrres));
	}
	$db=connect_db();
	if($viewerArr)
	{
		$totalCnt+=1;
		$visitorsTotalRec=count($viewerArr);
		if($viewerArr[$offset])
			$visitorsArr[0]=$viewerArr[$offset];
		if($viewerArr[$offset+1])
			$visitorsArr[1]=$viewerArr[$offset+1];
		if($viewerArr[$offset+2])
			$visitorsArr[2]=$viewerArr[$offset+2];
		if($viewerArr[$offset])
			$globalProfileArr[]=$viewerArr[$offset];
		if($viewerArr[$offset+1])
			$globalProfileArr[]=$viewerArr[$offset+1];
		if($viewerArr[$offset+2])
			$globalProfileArr[]=$viewerArr[$offset+2];
		if($nextPreviouscrousel)
			myjsSetResults("visitoralerts",$visitorsArr,$offset,3,'',$visitorsTotalRec);
	}
}
//Visitors

/* IVR-Callnow Start, Received Calls Statstics
*/
if($CALL_NOW){
if(!$nextPreviouscrousel || $nextPreviouscrousel=='callnowacc')
{
        $sql = "SELECT SQL_CACHE SQL_CALC_FOUND_ROWS CALLER_PID FROM newjs.CALLNOW where RECEIVER_PID='$mypid' AND CALL_STATUS='R' AND SEEN!='Y' ORDER BY CALLNOWID DESC limit $offset,3";
        $res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        while($row=mysql_fetch_array($res))
        {
                $callnowArr[]           =$row['CALLER_PID'];
                $globalProfileArr[]     =$row['CALLER_PID'];
        }
        $callnowTotalRec = $receivedCallNew;
        if($callnowTotalRec)
        {
                $totalCnt+=1;
                if($nextPreviouscrousel)
                        myjsSetResults("callnow",$callnowArr,$offset,3,'',$callnowTotalRec);
        }
}
}
/* IVR-Callnow Ends, Received Calls Statstics 
*/

//For Optimization
if(is_array($globalProfileArr))
{
	$globalProfileArrstr=implode(",",$globalProfileArr);
	$contactResult=getResultSet("RECEIVER,TYPE",$data["PROFILEID"],"",$globalProfileArrstr,"","","","","","","Y","");

	if(is_array($contactResult))
	{
		foreach($contactResult as $key=>$value)
		{
			$contacted1[$contactResult[$key]["RECEIVER"]]=$contactResult[$key]["TYPE"];
			$contacted2[$contactResult[$key]["RECEIVER"]]="R";
		}
	}
	unset($contactResult);

	$contactResult=getResultSet("SENDER,TYPE,TIME",$globalProfileArrstr,"",$data["PROFILEID"],"","","","","","","Y","");
	if(is_array($contactResult))
	{
		foreach($contactResult as $key=>$value)
		{
			$contacted1[$contactResult[$key]["SENDER"]]=$contactResult[$key]["TYPE"];
			$contacted2[$contactResult[$key]["SENDER"]]="S";
		}
	}
	unset($contactResult);



	$globalRes=myjsOptimisedRes($globalProfileArr);
	if(is_array($accArr))
		myjsSetResults("acceptance",$accArr,$offset,3,'',$accTotalRec,$globalRes);
	if(is_array($iniArr))
		myjsSetResults("initial",$iniArr,$offset,3,'',$iniTotalRec,$globalRes);
	if(is_array($matchArr))
		myjsSetResults("matchalerts",$matchArr,$offset,3,'',$matchTotalRec,$globalRes);
	if(is_array($horoArr))
		myjsSetResults("horoscope",$horoArr,$offset,3,'',$horoTotalRec,$globalRes);
	if(is_array($photoArr))
		myjsSetResults("photo",$photoArr,$offset,3,'',$photoTotalRec,$globalRes);
	if(is_array($partnerArr))
		myjsSetResults("partner",$partnerArr,$offset,3,'',$partnerTotalRec,$globalRes);
	if(is_array($visitorsArr))
		myjsSetResults("visitoralerts",$visitorsArr,$offset,3,'',$visitorsTotalRec,$globalRes);
        if(is_array($callnowArr))
                myjsSetResults("callnow",$callnowArr,$offset,3,'',$callnowTotalRec,$globalRes);

}
//For Optimization

// Start Code for schedule visit widget
$incHistObj = new incentive_HISTORY();
$purchasesObj = new BILLING_PURCHASES();
$incFieldSalesCityObj = new incentive_FIELD_SALES_CITY();
$dispositionDone = $incHistObj->get($data['PROFILEID'],'PROFILEID',"DISPOSITION = 'FVD' AND PROFILEID=$data[PROFILEID]");
$activeServices = $purchasesObj->getCurrentlyActiveService($data['PROFILEID']);
$checkFieldSalesCity = $incFieldSalesCityObj->checkFieldSalesCityCodeExists($CITY_RES_pixel);
if(!$dispositionDone && $activeServices=="FREE" && $checkFieldSalesCity){
	$fieldSalesWidgetObj = new incentive_FIELD_SALES_WIDGET();
	$count = $fieldSalesWidgetObj->checkIfProfileidExists($data['PROFILEID']);
	$smarty->assign("SCHEDULE_VISIT_COUNT", $count);
	$schedule_visit_widget = 1;
}
$smarty->assign("SCHEDULE_VISIT_WIDGET", $schedule_visit_widget);
unset($incHistObj);
unset($purchasesObj);
unset($incFieldSalesCityObj);	
// End code for schedule visit widget

if($nextPreviouscrousel)//no results
{
	echo 'A_E';
	die;
}

$smarty->assign("checksum",$checksum);
$smarty->assign("REMAINING",$totalCnt);
$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
$smarty->assign("GENDER_LOGGED_IN",$GENDER_LOGGED_IN);
if($isMobile){
	//Chat request count calculations
		$chatCount = 0;
		$sql="(SELECT count(DISTINCT(SENDER)) COUNT FROM userplane.CHAT_REQUESTS WHERE RECEIVER='".$data['PROFILEID']."') UNION (SELECT count(DISTINCT(RECEIVER))   COUNT FROM userplane.CHAT_REQUESTS WHERE SENDER='".$data['PROFILEID']."')";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes",$sql,"ShowErrTemplate");
		 while($row=mysql_fetch_array($res))
		 {
             $chatCount = $chatCount+$row["COUNT"];
	   	 }
	$smarty->assign("chatCount", $chatCount);

		/*Favorite Members count
		$sql="select count(BOOKMARKEE) COUNT from newjs.BOOKMARKS where BOOKMARKER='".$data['PROFILEID']."'";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of       minutes",$sql,"ShowErrTemplate");
	    $row=mysql_fetch_array($res);
		$favCount=$row['COUNT'];
		$smarty->assign("favCount",$favCount);*/
		$favCount=$memcacheObj->get("BOOKMARK");
		$smarty->assign("favCount",$favCount);
		//People who viewed my contact count
		$sql = "SELECT COUNT(*) cnt,SEEN FROM jsadmin.VIEW_CONTACTS_LOG WHERE VIEWED='$self_profileid' AND SOURCE='".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."' GROUP BY SEEN";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes",$sql,"ShowErrTemplate");
		while($row=mysql_fetch_array($res))
		{
			if($row["SEEN"] == "Y")
				$viewedByUser = $row["cnt"];
			else
				$notViewed = $row["cnt"];

		}
		$whoviewed_new = $notViewed;
		$whoviewed = $notViewed+$viewedByUser;
	$smarty->assign("whoviewed",$whoviewed);
		$smarty->assign("whoviewed_new",$whoviewed_new);
	$smarty->assign("HOME_ICON",1);
	$smarty->assign("LOGIN_ICON",1);	
	$jsmb_header=$smarty->fetch("mobilejs/jsmb_header.html");
	$jsmb_footer=$smarty->fetch("mobilejs/jsmb_footer.html");
	$smarty->assign("HEADER",$jsmb_header);
	$smarty->assign("FOOTER",$jsmb_footer);
	//Call pixel code in case user coming on fto offer page after mobile registration
	if($from_mob_reg){
		if(trim($groupname))
			$smarty->assign("pixelcode",pixelcode_reg($groupname,$CITY_RES_pixel,$AGE_pixel,$GENDER_pixel,$PROFILEID_pixel,$USERNAME_pixel,$adnetwork1));
	}
	$smarty->display("mobilejs/my-jeevansathi.html");
}
else
	$smarty->display("mainmenu_new.htm");//haha
	
function myjsOptimisedRes($arr)
{
	$resultprofiles=implode("','",$arr);
	if($resultprofiles)
	{
		// for on hover icon     
		$sql="select SQL_CACHE SQL_CALC_FOUND_ROWS YOURINFO,FAMILYINFO,SPOUSE,SCREENING,J.PROFILEID,USERNAME,GENDER,AGE,HEIGHT,RELIGION,MTONGUE,OCCUPATION,HAVEPHOTO,PHOTO_DISPLAY,CITY_RES,COUNTRY_RES,CASTE,SUBCASTE,GOTHRA,NAKSHATRA,EDU_LEVEL_NEW,INCOME,CITY_RES,COUNTRY_RES,PHONE_MOB,PHONE_RES,SHOW_HOROSCOPE,SUBSCRIPTION,SOURCE,MOB_STATUS,LANDL_STATUS,PHONE_FLAG,JC.ALT_MOB_STATUS,JC.ALT_MOBILE from newjs.JPROFILE J LEFT JOIN newjs.JPROFILE_CONTACT JC ON  J.PROFILEID = JC.PROFILEID where  activatedKey=1 and J.PROFILEID in('$resultprofiles')";
		$result1=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

		//ASTRO DETAILS ON MYJS HOVER
		global $astro_array,$astro_details;
                $sql_astro="SELECT PROFILEID,LAGNA_DEGREES_FULL,SUN_DEGREES_FULL,MOON_DEGREES_FULL,MARS_DEGREES_FULL,MERCURY_DEGREES_FULL,JUPITER_DEGREES_FULL,VENUS_DEGREES_FULL,SATURN_DEGREES_FULL FROM newjs.ASTRO_DETAILS WHERE PROFILEID IN ('$resultprofiles')";
                $result_astro=mysql_query_decide($sql_astro) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_astro,"ShowErrTemplate");

                if(mysql_num_rows($result_astro) > 0)
                {
                        while($myrow_astro=mysql_fetch_array($result_astro))
                        {
                                $astro_pid=$myrow_astro["PROFILEID"];
                                $astro_array[]=$astro_pid;
                                if($gender_for_icon != "")
                                {
                                        if($gender_for_icon == 'M')
                                                $gender_value = 1;
                                        else
                                                $gender_value = 2;
                                }
                                else
                                        $gender_value = "2";

                                if($astro_pid)
                                {
                                        $lagna=$myrow_astro['LAGNA_DEGREES_FULL'];
                                        $sun=$myrow_astro['SUN_DEGREES_FULL'];
                                        $mo=$myrow_astro['MOON_DEGREES_FULL'];
                                        $ma=$myrow_astro['MARS_DEGREES_FULL'];
                                        $me=$myrow_astro['MERCURY_DEGREES_FULL'];
                                        $ju=$myrow_astro['JUPITER_DEGREES_FULL'];
                                        $ve=$myrow_astro['VENUS_DEGREES_FULL'];
                                        $sa=$myrow_astro['SATURN_DEGREES_FULL'];
                                        $astro_details[$astro_pid]="$astro_pid:$gender_value:$lagna:$sun:$mo:$ma:$me:$ju:$ve:$sa";
                                }
                        }
                }
		//ASTRO DETAILS ON MYJS HOVER

		return $result1;
	}
}

function myjsSetResults($label,$resultprofilesArr,$offset,$to_show,$optional='',$cnt='',$result1='')
{

	//ADDED SEARCHTYPE WHERE ITS EARLIER SKIPPED
	if($label=='visitoralerts')
		$stype='11';
	elseif($label=='horoscope')
		$stype='12';
	elseif($label=='photo')
		$stype='13';
	elseif($label=='partner')
		$stype=14;
	elseif($label=='matchalerts')
		$stype='15';
	//ADDED SEARCHTYPE WHERE ITS EARLIER SKIPPED

	global $RELIGIONS,$HEIGHT_DROP,$OCCUPATION_DROP,$MTONGUE_DROP_SMALL,$smarty,$CASTE_DROP_SMALL;
	global $nextPreviouscrousel,$data,$contacted1,$contacted2; 	
	global $astro_array,$astro_details;
	global $IMG_URL,$PHOTO_URL;

	$resultprofiles=implode("','",$resultprofilesArr);
	if($nextPreviouscrousel && $resultprofiles)
	{
		//ASTRO DETAILS ON MYJS HOVER
		global $astro_array,$astro_details;
                $sql_astro="SELECT PROFILEID,LAGNA_DEGREES_FULL,SUN_DEGREES_FULL,MOON_DEGREES_FULL,MARS_DEGREES_FULL,MERCURY_DEGREES_FULL,JUPITER_DEGREES_FULL,VENUS_DEGREES_FULL,SATURN_DEGREES_FULL FROM newjs.ASTRO_DETAILS WHERE PROFILEID IN ('$resultprofiles')";
                $result_astro=mysql_query_decide($sql_astro) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_astro,"ShowErrTemplate");

                if(mysql_num_rows($result_astro) > 0)
                {
                        while($myrow_astro=mysql_fetch_array($result_astro))
                        {
                                $astro_pid=$myrow_astro["PROFILEID"];
                                $astro_array[]=$astro_pid;
                                if($gender_for_icon != "")
                                {
                                        if($gender_for_icon == 'M')
                                                $gender_value = 1;
                                        else
                                                $gender_value = 2;
                                }
                                else
                                        $gender_value = "2";

                                if($astro_pid)
                                {
                                        $lagna=$myrow_astro['LAGNA_DEGREES_FULL'];
                                        $sun=$myrow_astro['SUN_DEGREES_FULL'];
                                        $mo=$myrow_astro['MOON_DEGREES_FULL'];
                                        $ma=$myrow_astro['MARS_DEGREES_FULL'];
                                        $me=$myrow_astro['MERCURY_DEGREES_FULL'];
                                        $ju=$myrow_astro['JUPITER_DEGREES_FULL'];
                                        $ve=$myrow_astro['VENUS_DEGREES_FULL'];
                                        $sa=$myrow_astro['SATURN_DEGREES_FULL'];
                                        $astro_details[$astro_pid]="$astro_pid:$gender_value:$lagna:$sun:$mo:$ma:$me:$ju:$ve:$sa";
                                }
                        }
                }
		//ASTRO DETAILS ON MYJS HOVER
		$contactResult=getResultSet("RECEIVER,TYPE",$data["PROFILEID"],"","'".$resultprofiles."'","","","","","","","Y","");

		if(is_array($contactResult))
		{
			foreach($contactResult as $key=>$value)
			{
				$contacted1[$contactResult[$key]["RECEIVER"]]=$contactResult[$key]["TYPE"];
				$contacted2[$contactResult[$key]["RECEIVER"]]="R";
			}
		}
		unset($contactResult);

		$contactResult=getResultSet("SENDER,TYPE,TIME","'".$resultprofiles."'","",$data["PROFILEID"],"","","","","","","Y","");
		if(is_array($contactResult))
		{
			foreach($contactResult as $key=>$value)
			{
				$profile_time_arr[$i]["PROFILEID"]=$contactResult[$key]["SENDER"];
				$profile_time_arr[$i]["TIME"]=$contactResult[$key]["TIME"];
				$contacted1[$contactResult[$key]["SENDER"]]=$contactResult[$key]["TYPE"];
				$contacted2[$contactResult[$key]["SENDER"]]="S";
			}
		}
		unset($contactResult);
		unset($result1);
	}

	//Symfony Photo Modification
    	$profilePicUrls = SymfonyPictureFunctions::getPhotoUrls_nonSymfony($resultprofiles,"ProfilePicUrl,ThumbailUrl");
	
	if($result1)
		@mysql_data_seek($result1,0);
	else
	{
		$sql="select SQL_CACHE SQL_CALC_FOUND_ROWS YOURINFO,FAMILYINFO,SPOUSE,SCREENING,J.PROFILEID,USERNAME,GENDER,AGE,HEIGHT,RELIGION,MTONGUE,OCCUPATION,HAVEPHOTO,PHOTO_DISPLAY,CASTE,SUBCASTE,GOTHRA,NAKSHATRA,EDU_LEVEL_NEW,INCOME,CITY_RES,COUNTRY_RES,PHONE_MOB,SHOW_HOROSCOPE,SUBSCRIPTION,SOURCE,MOB_STATUS,LANDL_STATUS,PHONE_FLAG,PHONE_RES,JC.ALT_MOB_STATUS,JC.ALT_MOBILE from newjs.JPROFILE J LEFT JOIN newjs.JPROFILE_CONTACT JC ON  J.PROFILEID = JC.PROFILEID where  activatedKey=1 and J.PROFILEID in('$resultprofiles')";
		$result1=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	}

	while($myrow=mysql_fetch_array($result1))
	{
		$PROFILEID=$myrow['PROFILEID'];

		if(!in_array($PROFILEID,$resultprofilesArr))
			continue;
		$username=$myrow['USERNAME'];
		$gender=$myrow['GENDER'];
		$age=$myrow["AGE"];

		//new added
		$screening=$myrow["SCREENING"];
                if(isFlagSet("YOURINFO",$screening))
                        $yourinfo=$myrow["YOURINFO"];
                else
                        $yourinfo="";

                if(isFlagSet("FAMILYINFO",$screening))
                        $familyinfo=$myrow["FAMILYINFO"];
                else
                        $familyinfo="";

                if(isFlagSet("SPOUSE",$screening))
                        $spouseinfo=$myrow["SPOUSE"];
                else
                        $spouseinfo="";
                $length_limit=400;
                $yourinfo=substr($yourinfo . " " . $familyinfo . " " . $spouseinfo,0,$length_limit)."...";
                $yourinfo=str_replace(',',', ',$yourinfo);              //replace ',' with ', '(comma, space)
                $yourinfo=str_replace(' ,',', ',$yourinfo);             //replace ' ,' (space, comma) with ', '(comma, space);
                $yourinfo=str_replace('/','/ ',$yourinfo);              //replace '/' with '/ ' (slash, space)
                $yourinfo=str_replace('  ',' ',$yourinfo);              // replace '  ' (2 spaces) with ' ' (single space)

		//new added

		$heightn=$myrow["HEIGHT"];
		$height=$HEIGHT_DROP["$heightn"];
		$height1=explode("(",$height);
		$height2=trim($height1[0]);

		$religion=$RELIGIONS[$myrow['RELIGION']];

		$mtongue_temp=$myrow['MTONGUE'];
		$mtongue1[0] = $MTONGUE_DROP_SMALL["$mtongue_temp"];

		$caste_temp=$myrow["CASTE"];
		$caste=trim($CASTE_DROP_SMALL["$caste_temp"],'-');
		$displayInfo=$age.", ".$height2.",".$religion.".<br>".$mtongue1[0].", ".$caste;

		//PHOTO LOGIC
		if($myrow["HAVEPHOTO"]=="U")
			$havephoto="U";
		elseif($myrow["HAVEPHOTO"]=="Y")
			$havephoto="Y";
		else
			$havephoto="N";
		//$havephoto="N";
                if($havephoto=="Y" && ($myrow["PRIVACY"]=="R" || $myrow["PRIVACY"]=="F"))
                {
                        if(!$data)
                        {
                                $havephoto="L";
                        }
                        elseif($data && $myrow["PRIVACY"]=="F")
                        {
                                if(check_privacy_filtered($data["PROFILEID"],$myrow["PROFILEID"]))
                                        $havephoto="F";
                        }
                }
                if($havephoto=="Y" && ($myrow["PHOTO_DISPLAY"]=="F" || $myrow["PHOTO_DISPLAY"]=="C" || $myrow["PHOTO_DISPLAY"]=="H"))
                {
                        if($myrow["PHOTO_DISPLAY"]=="H")
                                $havephoto="H";
                        elseif(!$data && $myrow["PHOTO_DISPLAY"]=="C")
                                $havephoto="L";
                        elseif(!$data && $myrow["PHOTO_DISPLAY"]=="F")
                                $havephoto="L";
                        elseif($data && $myrow["PHOTO_DISPLAY"]=="C")
                        {
                                if(is_array($contacted1) && array_key_exists($myrow["PROFILEID"],$contacted1) && (($contacted2[$myrow["PROFILEID"]]=="S" && ($contacted1[$myrow["PROFILEID"]]=="I" || $contacted1[$myrow["PROFILEID"]]=="A" || $contacted1[$myrow["PROFILEID"]]=="D")) || ($contacted2[$myrow["PROFILEID"]]=="R" && ($contacted1[$myrow["PROFILEID"]]=="A" || $contacted1[$myrow["PROFILEID"]]=="C"))))
                                        ;
                                else
                                {
                                        $havephoto="C";
                                }
                        }
                        elseif($data && $myrow["PHOTO_DISPLAY"]=="F")
                        {
                                if(is_array($contacted1) && array_key_exists($myrow["PROFILEID"],$contacted1) && (($contacted2[$myrow["PROFILEID"]]=="S" && ($contacted1[$myrow["PROFILEID"]]=="I" || $contacted1[$myrow["PROFILEID"]]=="A" || $contacted1[$myrow["PROFILEID"]]=="D")) || ($contacted2[$myrow["PROFILEID"]]=="R" && ($contacted1[$myrow["PROFILEID"]]=="A" || $contacted1[$myrow["PROFILEID"]]=="C"))))
                                        ;
                                elseif(check_privacy_filtered($data["PROFILEID"],$myrow["PROFILEID"]))
                                        $havephoto="P";
                        }
                }

		$photochecksum = md5($myrow["PROFILEID"]+5)."i".($myrow["PROFILEID"]+5);
		$photochecksum_new = intval(intval($myrow['PROFILEID'])/1000) . "/" . md5($myrow["PROFILEID"]+5);
		$profilechecksum = md5($myrow["PROFILEID"]) . "i" . $myrow["PROFILEID"];
		$stat_uname=stat_name($PROFILEID,$username);
		$image_file=return_image_file($havephoto,$gender);

		unset($addPara);
		if($label=='acceptance')
			$addPara="NAVIGATOR=ACC%3Apage__accept%40filter__R%40j__1%2FAccepted+Members%3B%3B";
		elseif($label=='initial')
			$addPara="&NAVIGATOR=EOI%3Apage__eoi%40filter__R%40j__1%2FMembers+awaiting+my+response%3B%3B";
		elseif($label=='matchalerts')
			$addPara="NAVIGATOR=MAT%3Apage__matches%40filter__R%40j__1%2FMatch%20Alerts%3B%3B";
		elseif($label=='visitoralerts')
			$addPara="NAVIGATOR=VIS%3Apage__visitors%40filter__R%40j__1%2FProfile%20Visitors%3B%3B";
		elseif($label=='photo')
			$addPara="&NAVIGATOR=PHO%3Apage__photo%40filter__M%40j__1%2FPhoto+Requests%3B%3B";
		elseif($label=='horoscope')
			$addPara="NAVIGATOR=HOR%3Apage__horoscope%40filter__M%40j__1%2FHoroscope+Requests%3B%3B";
		elseif($label=='partner')
			$addPara="NAVIGATOR=SR%3AMEM_LOOK__1%40onlineArr__1%2FDesired%20Partner%20matches%20online%3B%3B";


		if($label=='acceptance')
		{
			$fornextPrevArr[]="self_profileid=$data[PROFILEID]";
			$fornextPrevArr[]="flag=A";
			$fornextPrevArr[]="type=R";
			$fornextPrevArr[]="profileids=1";
			$fornextPrevArr[]="fromPage=contacts";
			$fornextPrevArr[]="page=accept";
			$fornextPrevArr[]="self=SENDER";
			$fornextPrevArr[]="contact=RECEIVER";
			$fornextPrevArr[]="testlavesh=1";
			$x=implode("&",$fornextPrevArr);
			$addPara.="&".$x;
		}
		if($label=='initial')
		{
			$fornextPrevArr[]="self_profileid=$data[PROFILEID]";
			$fornextPrevArr[]="flag=I";
			$fornextPrevArr[]="type=R";
			$fornextPrevArr[]="profileids=1";
			$fornextPrevArr[]="fromPage=contacts";
			$fornextPrevArr[]="page=eoi";
			$fornextPrevArr[]="contact=SENDER";
			$fornextPrevArr[]="self=RECEIVER";
			$fornextPrevArr[]="responseTracking=".JSTrackingPageType::MYJS_AWAITING;
			$fornextPrevArr[]="testlavesh=1";
			$x=implode("&",$fornextPrevArr);
			$addPara.="&".$x;
		}
		if($label=='matchalerts')
		{
			$fornextPrevArr[]="self_profileid=$data[PROFILEID]";
			$fornextPrevArr[]="flag=M";
			$fornextPrevArr[]="type=R";
			$fornextPrevArr[]="profileids=1";
			$fornextPrevArr[]="fromPage=contacts";
			$fornextPrevArr[]="page=matches";
			$fornextPrevArr[]="contact=USER";
			$fornextPrevArr[]="self=RECEIVER";
			$fornextPrevArr[]="testlavesh=1";
			$x=implode("&",$fornextPrevArr);
			$addPara.="&".$x;
		}
		if($label=='visitoralerts')
		{
			$fornextPrevArr[]="self_profileid=$data[PROFILEID]";
			$fornextPrevArr[]="flag=V";
			$fornextPrevArr[]="type=R";
			$fornextPrevArr[]="profileids=1";
			$fornextPrevArr[]="fromPage=contacts";
			$fornextPrevArr[]="page=visitors";
			$fornextPrevArr[]="contact=VIEWER";
			$fornextPrevArr[]="self=VIEWED";
			$fornextPrevArr[]="testlavesh=1";
			$x=implode("&",$fornextPrevArr);
			$addPara.="&".$x;
		}

		// IVR- Phone Verification check
		$chk_phoneStatus =getPhoneStatus($myrow,$myrow['PROFILEID'],'','',$checkedAlternate='Y');	
		if($chk_phoneStatus =='Y')
			$phone_verified='Y';
		else
			$phone_verified='N';

                if($myrow['SHOW_HOROSCOPE']=="Y" && is_array($astro_array) && in_array($myrow["PROFILEID"],$astro_array))
                {
                        $horo_link="horoscope_astro.php";
                        $horoscope="Y";
                        $horoscope_astro="";
                        if(is_array($astro_details))
                        {
                                $apid=$myrow["PROFILEID"];
                                if($astro_details[$apid])
                                {
                                        $horoscope_astro=$astro_details[$apid];
                                }
                        }
                }
                else
                        $horoscope="N";

		//icons on hover

		//-----------------------on hover info-------------------------------------------------
		global $EDUCATION_LEVEL_NEW_DROP,$OCCUPATION_DROP,$CITY_INDIA_DROP,$CITY_DROP,$COUNTRY_DROP,$income_map;
		$heightArr=explode("(",$HEIGHT_DROP[$myrow["HEIGHT"]]);
		$height2=trim($heightArr[0]);

                $education=$EDUCATION_LEVEL_NEW_DROP[$myrow['EDU_LEVEL_NEW']];
		$income=$myrow["INCOME"];
		$occupation=$OCCUPATION_DROP[$myrow['OCCUPATION']];
		$subcaste=$myrow["SUBCASTE"];
		$nakshatra=$myrow["NAKSHATRA"];
		$gothra=$myrow["GOTHRA"];

                if($myrow["CITY_RES"]!="")
                {
                        $city=$myrow["CITY_RES"];
                        $residence=$CITY_INDIA_DROP["$city"];
                        if(!$residence)
                                $residence=$CITY_DROP["$city"];
                }
                else
                        $residence=$COUNTRY_DROP[$myrow["COUNTRY_RES"]];


	        $small_tag="$myrow[AGE], $height2, $religion";
                $small_tag.="<BR> $mtongue1[0],<BR>$caste";
		
                if($subcaste && isFlagSet("SUBCASTE",$screening))
                        if($from_similar)
                                $small_tag.=" (".wordwrap($subcaste,15,"<BR>",true).")";
                        else
                                $small_tag.=" ($subcaste)";
                $small_tag.=",<BR>";

                if($nakshatra!="Don't Know" && $nakshatra!="" && $nakshatra!="Select")
                {
                        if($from_similar)
                                $nakshatra=wordwrap($nakshatra,10,"<BR>",true);

                        $small_tag.=$nakshatra."<span class=\"no_b\"> (Nakshatra),<BR></span>";
                }
                if($gothra && isFlagSet("GOTHRA",$screening))
                        $small_tag.=$gothra."<span class=\"no_b\"> (Gothra),<BR></span>";

                if($education)
                        $small_tag.="$education, ";
                if($income)
                        $small_tag.=$income_map["$income"].", " ;

                if(!$nakshatra && !$gothra && !$income)
                {
                        if($occupation)
                                $small_tag.=" $occupation";
                }
                else
                if($occupation)
                        $small_tag.="<BR> $occupation";

                if($residence)
                        $small_tag.=" in $residence";
		//-----------------------on hover info-------------------------------------------------


		$PROFILE_CHECKSUM=createChecksumForSearch($myrow["PROFILEID"]);
		$USERNAME=$myrow["USERNAME"];
		$USERNAME1=$myrow["USERNAME"];


		//ADDED SEARCHTYPE WHERE ITS EARLIER SKIPPED
		if($stype)
			$addPara="stype=$stype&".$addPara;
		//ADDED SEARCHTYPE WHERE ITS EARLIER SKIPPED

	//	$USERNAME="<a onClick=\"javascript:window.location.href='viewprofile.php?profilechecksum=$profilechecksum&$addPara'\" class=\"nme_photo\">$USERNAME<br>";

		 $USERNAME="<span>$USERNAME<br>";
		//TEMP IN PHP


		//Profile Layer On Hover
		$uniqueIds=$uniqueIds+1;
		$uniqueIdsLabel=$label.$uniqueIds;
		$dinfo="<div class=\"img_cont\"  style=\"margin:auto;\" id=\"IMG_$uniqueIdsLabel\"><span class=\"img_cont_big_photo\" id=\"$USERNAME1\" style=\"position:absolute;display:none\"><iframe></iframe>";
		$dinfo.="<div class=\"p_tup\"><h6><b class=\"fl\">$USERNAME1</b>"; 

		/* Icons at right end of layer */
		if($myrow['SOURCE']=='ofl_prof' || isOfflineMember($myrow["SUBSCRIPTION"]))
			$dinfo.="<i class='mt_pnt_icon btn_sprte fr f2'></i>";
                
		$subscription=$myrow["SUBSCRIPTION"];
			if(CommonFunction::isJsExclusiveMember($subscription))
			$dinfo.="<i class=\"jsexlusivebg fr f2\"></i>";
			
                                                if(CommonFunction::isEvalueMember($subscription))
			$dinfo.="<i class=\"e_vlue_icon btn_sprte fr f2\"></i>";
			
                                                if(CommonFunction::isErishtaMember($subscription))
				$dinfo.="<i class=\"e_rsta_icon btn_sprte fr f2\"></i>";
		


		$dinfo.="</h6>";
		/* Icons at right end of layer */

		/* big photo */
		$dinfo.="<div class=\"cmplete_info\"><div class=\"fl\" style=\"width:227px;\">"; 
		$dinfo.="<div class=\"fl\" style=\"width:100px;\height:133px;\"><img style=\"margin-left:33px;margin-top:40px\" id=\"PHOTO_$USERNAME1\" src=\"$IMG_URL/profile/images/loader_small.gif\" class=\"fl\" width=\"32\" height=\"33\" border=\"0\" onLoad=\"imageOnLoad('$USERNAME1');\"></div>";
		/* big photo */

		/* basic info */
		$dinfo.="<div class=\"b_info\">$small_tag</div>"; 
		/* basic info */


		/* ICONS NEED TO BE IMPLEMNETED */
		$dinfo.="<div class=\"clr\"></div>";
		$dinfo.="<div class=\"icns fl mt_4\">";

		if($horoscope || $phone_verified)
		{
			if($horoscope=='Y')
			{
				$dinfo.="<i class=\"sprte_icon kndli_icon fl\"></i>";
				$useSpacerClass=" ml_6";
			}
			if($phone_verified=='Y')
				$dinfo.="<i class=\"sprte_icon vrfid_phne_icon fl$useSpacerClass\"></i>";
			unset($useSpacerClass);
			$dinfo.="<br>";
		}
		$IC_PROFILEID=$myrow["PROFILEID"];
		$dinfo.="<input type =\"hidden\" name=\"horo_astro_$IC_PROFILEID\" value=\"$horoscope_astro\"><span id=\"LAGAN_ID_$IC_PROFILEID\"></span>";
		$dinfo.="</div></div>";
		/* ICONS NEED TO BE IMPLEMNETED */
		
		/* $yourinfo */		
		$dinfo.="<p>$yourinfo</p></div><p class=\"clr_15\"></p></div>";
		/* $yourinfo */		

		//Symfony Photo Modification
		$profilePicUrlArr = $profilePicUrls[$myrow["PROFILEID"]];
             	if ($profilePicUrlArr)
               	{
			$profilePicUrl = $profilePicUrlArr["ProfilePicUrl"];
                        $thumbnailUrl = $profilePicUrlArr["ThumbailUrl"];
		}
		else
		{
			$profilePicUrl = null;
                        $thumbnailUrl = null;
		}
		//Symfony Photo Modification

		$big_photo_temp=get_big_image($havephoto,$gender);

		//Symfony Photo Modification
                if($havephoto=='Y')
                {
			$my_photo="<div style=\"margin: auto; width: 60px;float:left; height: 60px; vertical-align: top;background-repeat:no-repeat;padding-top:0px; background-image: url($thumbnailUrl);\"><img src=\"$IMG_URL/profile/images/transparent_img.gif\" width=\"60\" height=\"60\" border=\"0\" onMousemove=\"showtrail2('$photochecksum','$username',event,'$uniqueIdsLabel','$big_photo_temp','$profilePicUrl','$PHOTO_URL')\" onMouseout=\"hidetrail2()\" oncontextmenu=\"return false;\" galleryimg=\"NO\"></div>";
                }
                else
                {
                        if($gender=='F')
				$my_photo="<img src=\"$IMG_URL/profile/images/ic_g_blank_60.gif\" width=\"60\" height=\"60\" border=\"0\" onMousemove=\"showtrail2('$photochecksum','$username',event,'$uniqueIdsLabel','$big_photo_temp','$profilePicUrl','$PHOTO_URL')\" onMouseout=\"hidetrail2()\"><br>";	
                        else
				$my_photo="<img src=\"$IMG_URL/profile/images/ic_b_blank_60.gif\" width=\"60\" height=\"60\" border=\"0\" onMousemove=\"showtrail2('$photochecksum','$username',event,'$uniqueIdsLabel','$big_photo_temp','$profilePicUrl','$PHOTO_URL')\" onMouseout=\"hidetrail2()\"><br>";	
                }
		//Symfony Photo Modification
		$dinfo.="</span>$my_photo</div>";

		//Profile Layer On Hover

//$USERNAME.="</a>";
$USERNAME.="</span>";

$dinfo="<a class=\"f11 crs_pntr\" onClick=\"javascript:window.location.href='viewprofile.php?profilechecksum=$profilechecksum&$addPara'\">".$dinfo."</a>";
//$displayInfo="<div class=\"clr\"></div><a class=\"f11 crs_pntr\" onClick=\"javascript:window.location.href='viewprofile.php?profilechecksum=$profilechecksum&$addPara'\">".$displayInfo."</a>";
$displayInfo="<div class=\"clr\"></div><a class=\"f11 crs_pntr\" href='viewprofile.php?profilechecksum=$profilechecksum&$addPara' style='color:black;' >".$displayInfo."</a>";


                //------archive-----

                $activeProfilesArr[]=$myrow["PROFILEID"];
                //------archive-----


		if($nextPreviouscrousel)
		{
			$ajaxArr[]=$USERNAME."###".$my_photo."###".$displayInfo.$dinfo;
			$RESULT_ARRAY[]=array("SNO" => $sno,
						"USERNAME" => $USERNAME,
						"PROFILEID" =>$myrow["PROFILEID"],
						"DISPLAYINFO" => $displayInfo,
						"MY_PHOTO" =>$my_photo,
						"DINFO" => $dinfo,
						);
			$sno++;
		}
		else
		{
			$RESULT_ARRAY[]=array("SNO" => $sno,
						"USERNAME" => $USERNAME,
						"PROFILEID" =>$myrow["PROFILEID"],
						"DISPLAYINFO" => $displayInfo,
						"MY_PHOTO" =>$my_photo,
						"PHOTOCHECKSUM"=>$photochecksum,
						"USERNAME1" => $USERNAME1,
						"BIG_PHOTO" => $big_photo,
						"YOURINFO" => $yourinfo,
						"DINFO" => $dinfo,
						);
			$sno++;
		}
	}

	if($ajaxArr)
	{
		if(0)
		{
			$a=implode("%%%",$ajaxArr);
			echo $nextPreviouscrousel."$$$".$a;
			exit;
		}
		else
		{
			unset($ajaxArr);
			//------archive-----
			foreach($resultprofilesArr as $k=>$v)
				if(in_array($v,$activeProfilesArr))
					$resultprofilesArrnew[]=$v;
			//------archive-----
			for($z=0;$z<count($resultprofilesArrnew);$z++)
			{
				$key = array_search($RESULT_ARRAY[$z]['PROFILEID'],$resultprofilesArrnew);
				$RESULT_ARRAY_FINAL[$key]=$RESULT_ARRAY[$z];
			}
			for($ll=0;$ll<count($resultprofilesArr);$ll++)
			{
				$ajaxArr[]=$RESULT_ARRAY_FINAL[$ll]['USERNAME']."###".$RESULT_ARRAY_FINAL[$ll]['DINFO']."###".$RESULT_ARRAY_FINAL[$ll]['DISPLAYINFO'];
			}
			$a=implode("%%%",$ajaxArr);
			echo $nextPreviouscrousel."$$$".$a;
			exit;
		}
	}
	if($cnt<=$to_show)
	{
		$smarty->assign($label."disable",1);
	}
	$smarty->assign($label."TOTAL",$cnt);

        //------archive-----
        foreach($resultprofilesArr as $k=>$v)
                if(is_array($activeProfilesArr) && in_array($v,$activeProfilesArr))
                        $resultprofilesArrnew[]=$v;
        //------archive-----

	for($z=0;$z<count($resultprofilesArrnew);$z++)
	{
		$key = array_search($RESULT_ARRAY[$z]['PROFILEID'],$resultprofilesArrnew);
		$RESULT_ARRAY_FINAL[$key]=$RESULT_ARRAY[$z];
	}
	$smarty->assign($label."RESULTS_ARRAY",$RESULT_ARRAY_FINAL);
	$smarty->assign($label."NEXT_OFFSET",$offset+$to_show);
	$smarty->assign($label."to_show",$to_show);
}


function sortByPhotoLogic($matchalertsPids)
{
	$matchalertsPidsStr=implode("','",$matchalertsPids);
	unset($matchalertsPids);

	$sql="SELECT PROFILEID,HAVEPHOTO,PHOTO_DISPLAY,ENTRY_DT FROM JPROFILE WHERE PROFILEID IN ('$matchalertsPidsStr') AND ACTIVATED='Y' AND activatedKey=1 ORDER BY PROFILEID IN ('$matchalertsPidsStr')";
	$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"continue","","");
	while($row=mysql_fetch_array($res))
	{
		$havephoto = $row["HAVEPHOTO"];
		$pdisplay = $row["PHOTO_DISPLAY"];
		$timeStamp = JSstrToTime($row["ENTRY_DT"]);

		if($havephoto=='Y')
		{
			if($pdisplay=='C')
				$level2[$timeStamp] = $row["PROFILEID"];
			else
				$level1[$timeStamp] = $row["PROFILEID"];
		}
		else
			$level3[$timeStamp] = $row["PROFILEID"];
		//$matchalertsPids[]=$row["PROFILEID"];
	}

	if(is_array($level1))
	{
		krsort($level1);
		foreach($level1 as $v)
			$matchalertsPids[] = $v;
	}
	if(is_array($level2))
	{
		krsort($level2);
		foreach($level2 as $v)
			$matchalertsPids[] = $v;
	}
	if(is_array($level3))
	{
		krsort($level3);
		foreach($level3 as $v)
			$matchalertsPids[] = $v;
	}
	return $matchalertsPids;
}
function caste_revamp_layer($pid)
{

}
?>
