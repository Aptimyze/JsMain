<?php
/************************************************************************************************************************
*    FILENAME           : jsivrFunctions.php 
*    DESCRIPTION        : This file contains the functions used in phone verification process
                        : Used in IVR, SMS, Backened Team, JS aplication. 
***********************************************************************************************************************/
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
	function getCallsInitiatedToday($number,$isd)
	{
		$date = date("Y-m-d");
		$sql = "SELECT count(*) AS COUNT FROM newjs.PHONE_VERIFICATION_SENT WHERE PHONE='".$isd.$number."' AND ENTRY_DT BETWEEN '".$date." 00:00:00' AND '".$date." 23:59:59'";
		$res =mysql_query_decide($sql) or logError("Could not get the select count from table newjs.PHONE_VERIFICATION_SENT",$sql);
		if($row =mysql_fetch_array($res))
		return $row['COUNT'];
	}

        /* Gets the complete phone number by adding the isd/std/mobile/landline
        *  @return int (phone number)
        */
	function checkNumber($number)
	{
		return removeAllSpecialChars($number);
	}
        function mobileformat($mobile,$ivr="")
        {
		$mobile =removeAllSpecialChars($mobile);
	      	if(strlen($mobile)==10){
 			$mobile="91".$mobile;
		}
                if(strlen($mobile)>12)
	                $mobile=substr($mobile,-12,12);
		if($ivr){
			$mobile=substr($mobile,-10,10);
			$mobile ="0".$mobile;
		}
                return $mobile;
        }

        /* landline number format
	 * does not include ISD code, as no IVR call goes to ISD number and messages cannot be through landline(international messages are applicable)
        *  @return int (std+phone number ) 
        */
        function landlineformat($landline,$std='',$ivr='')
        {
		if($landline){
			$phoneArr =explode("-",$landline);	
			if(count($phoneArr)==2){	
				$std =trim($phoneArr[0]);
				$landline =trim($phoneArr[1]);
				$landline =removeAllSpecialChars($landline);
			}
		}			
                if($std){
			$std =removeAllSpecialChars($std);
			$std=str_replace('+','', $std);
                        if(substr($std,0,1)=='0')
                                $std =substr($std,1);
                        $landline =$std.$landline;
                }
		if($ivr)
			$landline ="0".$landline;
                return $landline;
        }

        // function to check the duration between the two sms is greater than 12 hours
        function sms_sent_status($profileid,$mobile)
        {
                $sql ="SELECT `ENTRY_DT` from newjs.IVR_HIT where PROFILEID='$profileid' AND PHONE IN('0$mobile','$mobile','+91$mobile','91$mobile') AND TEXT='validity=busy' ORDER BY ID DESC limit 0,2";
                $res =mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $i=0;
                $dateTime =array();
                while($row = mysql_fetch_array($res))
                {
                        $dateTime[$i] = $row['ENTRY_DT'];
                        $dateTimeEnd[$i] = date("Y-m-d H:i:s", JSstrToTime("$dateTime[$i] +12 hours"));
                        $current[$i] =date("Y-m-d H:i:s");
                        $i++;
                }
                if(count($dateTime)==2)
                {
                        $j=1;
                        if( ($current[$j] >=$dateTime[$j]) && ($current[$j]<=$dateTimeEnd[$j]) )
                        {
                                return false;
                        }
                }
                return true;
        }

	//*****************************   NEW FUNCTIONS ADDED     *********************************************  	

	// function to check junk number from the junk number list
	function chkJunkNumberList($phone_num,$type)
	{
		if($type =='M'){
			$phone_num = mobileformat($phone_num);
			if(strlen($phone_num)>10)
				$phone_num =substr($phone_num,-10,10);
		}
		else if($type =='L')
			$phone_num = landlineformat($phone_num);
		if(!$phone_num)
			return true;	
		$sql ="SELECT COUNT(*) AS CNT from newjs.PHONE_JUNK WHERE `PHONE_NUM` IN('$phone_num','0$phone_num','91$phone_num')";
		$res =mysql_query_decide($sql) or logError("Could not get the select count from table newjs.PHONE_JUNK",$sql);
		$row =mysql_fetch_array($res);
		$cnt =$row['CNT'];
		if($cnt >0)
			return true;
		return false; 
	}	

	/* Get the privacy layer status of the profile
	 * Phone Status indication: Y:layer should be shown, N: otherwise
	*/
	function getPrivacySettingLayer($profile_params='',$profileid='')
	{
		if(!$profileid)
			$profileid = $profile_params['PROFILEID'];
		if(getPrivacySettingProfile($profileid)=="N")
			return "N";
		if(!$profile_params || !isset($profile_params['SHOWPHONE_RES']) || !isset($profile_params['SHOWPHONE_MOB']) || !isset($profile_params['PHONE_MOB']) || !isset($profile_params['PHONE_RES']) || !isset($profile_params['ENTRY_DT']) || !isset($profile_params['MOB_STATUS']) || !isset($profile_params['LANDL_STATUS']))
		{
			$sql ="SELECT `MOB_STATUS`,`LANDL_STATUS`,PHONE_MOB,PHONE_RES,SHOWPHONE_RES,SHOWPHONE_MOB,ENTRY_DT from newjs.JPROFILE where `PROFILEID`='$profileid' AND activatedKey=1";
			$res =mysql_query_decide($sql) or logError("Could not get profile details from JPROFILE in phone status",$sql);
			$res_row = mysql_fetch_array($res);
			$profile_params["SHOWPHONE_MOB"] = $res_row["SHOWPHONE_MOB"];
			$profile_params["SHOWPHONE_RES"] = $res_row["SHOWPHONE_RES"];
			$profile_params["MOB_STATUS"] = $res_row["MOB_STATUS"];
			$profile_params["LANDL_STATUS"] = $res_row["LANDL_STATUS"];
			$profile_params["PHONE_MOB"] = $res_row["PHONE_MOB"];
			$profile_params["PHONE_RES"] = $res_row["PHONE_RES"];
			$profile_params['ENTRY_DT']= $res_row['ENTRY_DT'];
		}
		$sqlAlt ="SELECT ALT_MOBILE,ALT_MOB_STATUS FROM newjs.JPROFILE_CONTACT WHERE PROFILEID='".$profileid."'";
		$resAlt =mysql_query_decide($sqlAlt) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlAlt);
		if($rowAlt=@mysql_fetch_array($resAlt))
		{
			$profile_params['ALT_MOB_STATUS']=$rowAlt['ALT_MOB_STATUS'];////
       	    $profile_params['ALT_MOBILE']=$rowAlt['ALT_MOBILE'];////
       	    $profile_params['SHOWALT_MOBILE']=$rowAlt['SHOWALT_MOBILE '];
		}
		if(	($profile_params['MOB_STATUS']=='Y' || $profile_params['LANDL_STATUS']=='Y' || $profile_params['ALT_MOB_STATUS']=='Y') 
			&&
			($profile_params['ENTRY_DT']<"2013-10-22 00:00:00")
			&&
			(($profile_params['PHONE_MOB'] && $profile_params['SHOWPHONE_MOB']=='N') ||
			($profile_params['PHONE_RES'] && $profile_params['SHOWPHONE_RES']=='N') ||
			($profile_params['ALT_MOBILE'] && $profile_params['SHOWALT_MOBILE']=='N')))
				$return='Y';
		else
				$return='N';
		insertPrivacySettingProfile($profileid);
		return $return;
	}
	function getPrivacySettingProfile($profileid)
	{
                $sqlPrivacyLayer="SELECT * FROM newjs.PrivacySettingLayer WHERE PROFILEID='".$profileid."'";
                $resPrivacyLayer = mysql_query_decide($sqlPrivacyLayer) or logError("Could not get details from PrivacySettingLayer in phone status",$sql);
                if(mysql_num_rows($resPrivacyLayer)>0)
                        return "N";
	}
	function insertPrivacySettingProfile($profileid)
	{
		$sqlPrivacyLayer = "INSERT INTO  `PrivacySettingLayer` (  `PROFILEID` ,  `DATE` ) VALUES ('".$profileid."',  now())";
		$resPrivacyLayer = mysql_query_decide($sqlPrivacyLayer) or logError("Could not insert details in PrivacySettingLayer in phone status",$sqlPrivacyLayer);
	}
	/* Get the phone status of the profile
	 * Phone Status indication: Y:VERIFIED, I:INVALID, J:JUNK, N:UNVERIFIED, L:LANDLINE NO, M:MOBILE NO, O:OVERRIDE 
	*/
	function hidePhoneLayer($profileid)
	{
		$db = connect_db();
		$sql = "SELECT  PROFILEID, ENTRY_DT ,`PHONE_FLAG`,`MOB_STATUS`,`LANDL_STATUS`,PHONE_MOB,PHONE_RES from newjs.JPROFILE where `PROFILEID`='$profileid' AND activatedKey=1";
		$res =mysql_query_decide($sql) or logError("Could not get profile details from JPROFILE in phone status",$sql);
		$res_row = mysql_fetch_array($res);
		$profile_params['PROFILEID'] = $res_row['PROFILEID'];
		$profile_params["PHONE_FLAG"] = $res_row["PHONE_FLAG"];
		$profile_params["MOB_STATUS"] = $res_row["MOB_STATUS"];
		$profile_params["LANDL_STATUS"] = $res_row["LANDL_STATUS"];
		$profile_params["PHONE_MOB"] = $res_row["PHONE_MOB"];
		$profile_params["PHONE_RES"] = $res_row["PHONE_RES"];
		$profile_params["ENTRY_DT"] = $res_row["ENTRY_DT"];
		$phoneMandatoryLivedate = DateConstants::PhoneMandatoryLive;
		if($profile_params["ENTRY_DT"]< $phoneMandatoryLivedate)
			return "Y";
		else
			return getPhoneStatus($profile_params);
	}
	function getPhoneStatus($profile_params='',$profileid='',$phone_type='',$chk_type='',$checkedAlternate='N')
	{
		//return;
		if(!$profileid)
			$profileid=$profile_params['PROFILEID'];
		if((!$profile_params || !isset($profile_params['PHONE_MOB']) || !isset($profile_params['PHONE_RES']) || !isset($profile_params['PHONE_FLAG']) || !isset($profile_params['MOB_STATUS']) || !isset($profile_params['LANDL_STATUS'])) && $profileid){
			$sql ="SELECT `PHONE_FLAG`,`MOB_STATUS`,`LANDL_STATUS`,PHONE_MOB,PHONE_RES from newjs.JPROFILE where `PROFILEID`='$profileid' AND activatedKey=1";
			$res =mysql_query_decide($sql) or logError("Could not get profile details from JPROFILE in phone status",$sql);
			$res_row = mysql_fetch_array($res);
			$profile_params["PHONE_FLAG"] = $res_row["PHONE_FLAG"];
			$profile_params["MOB_STATUS"] = $res_row["MOB_STATUS"];
			$profile_params["LANDL_STATUS"] = $res_row["LANDL_STATUS"];
			$profile_params["PHONE_MOB"] = $res_row["PHONE_MOB"];
			$profile_params["PHONE_RES"] = $res_row["PHONE_RES"];
		}

		if($checkedAlternate!='Y' && (!isset($profile_params['ALT_MOB_STATUS']) || !isset($profile_params['ALT_MOBILE'])) && $profileid)
		{
			$sqlAlt ="SELECT ALT_MOBILE,ALT_MOB_STATUS FROM newjs.JPROFILE_CONTACT WHERE PROFILEID='".$profileid."'";
			$resAlt =mysql_query_decide($sqlAlt) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlAlt);
			if($rowAlt=@mysql_fetch_array($resAlt))
			{
        	        	$profile_params['ALT_MOB_STATUS']=$rowAlt['ALT_MOB_STATUS'];////
        	        	$profile_params['ALT_MOBILE']=$rowAlt['ALT_MOBILE'];////
			}
		}
		$phone_flag 	=$profile_params['PHONE_FLAG'];
		if($profile_params['PHONE_MOB']!='')
			$mob_status=$profile_params['MOB_STATUS'];




		if($mob_status=='N'||$mob_status=='')
			$mob_status	='N';
		if($profile_params['PHONE_RES']!='')
			$landl_status 	=$profile_params['LANDL_STATUS'];
		if($landl_status=='N'||$landl_status=='')
			$landl_status	='N';
		if($profile_params['ALT_MOBILE']!='')
			$alt_status	=$profile_params['ALT_MOB_STATUS'];
		if($alt_status=='N'||$alt_status=='')
			$alt_status	='N';
		if($phone_flag =='I')
			return $phone_flag;
		if($phone_type =='L')
			return $landl_status;
		else if($phone_type =='M')
			return $mob_status;
		else if($phone_type=='A')
			return $alt_status;
		else{
			if($mob_status =='J' || $landl_status=='J' || $alt_status=='J')  
			{
				$junk_status ='1'; 
			}
			if($mob_status =='Y' || $landl_status=='Y' || $alt_status=='Y')
			{	
				$verified_status ='1';
			}
			if($chk_type=='J' && ($junk_status && $verified_status)){
				return 'J';
			}
			else if($junk_status && $verified_status){ 
				return 'Y';
			}
			else if($verified_status)
				return 'Y';
			else if($junk_status)
				return 'J';
			else 	
				return 'N'; 	
		}				
	}



function getPhoneStatusAll($profile_params='')
	{
		//return;
		if (!$profile_params) return null;
		
		$queryString='';
		foreach($profile_params as $k=>$v){
		if((!isset($v['PHONE_MOB']) || !isset($v['PHONE_RES']) || !isset($v['PHONE_FLAG']) || !isset($v['MOB_STATUS']) || !isset($v['LANDL_STATUS'])))
		$queryString= $queryString.$k.",";	
		}
		
		if ($queryString){
			$queryString=substr($queryString, 0, -1);
			$queryString='('.$queryString.')';	
				
			$sql ="SELECT `PROFILEID`, `PHONE_FLAG`,`MOB_STATUS`,`LANDL_STATUS`,PHONE_MOB,PHONE_RES from newjs.JPROFILE where `PROFILEID` IN $queryString AND activatedKey=1";
			$res =mysql_query_decide($sql) or logError("Could not get profile details from JPROFILE in phone status",$sql);
			while($res_row = mysql_fetch_row($res)) {
			$profile_params[$res_row['PROFILEID']]["PHONE_FLAG"] = $res_row["PHONE_FLAG"];
			$profile_params[$res_row['PROFILEID']]["MOB_STATUS"] = $res_row["MOB_STATUS"];
			$profile_params[$res_row['PROFILEID']]["LANDL_STATUS"] = $res_row["LANDL_STATUS"];
			$profile_params[$res_row['PROFILEID']]["PHONE_MOB"] = $res_row["PHONE_MOB"];
			$profile_params[$res_row['PROFILEID']]["PHONE_RES"] = $res_row["PHONE_RES"];
			}
		}


		$queryString='';
	foreach($profile_params as $k=>$v){
		$queryString.= $k.',';	
		}

		if ($queryString){

			$queryString=substr($queryString, 0, -1);
			$queryString='('.$queryString.')';	
			$sqlAlt ="SELECT PROFILEID, ALT_MOBILE,ALT_MOB_STATUS FROM newjs.JPROFILE_CONTACT WHERE PROFILEID IN $queryString";
			$resAlt =mysql_query_decide($sqlAlt) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlAlt);
			while($rowAlt=mysql_fetch_row($resAlt))
			{
        	        	$profile_params[$rowAlt['PROFILEID']]['ALT_MOB_STATUS']=$rowAlt['ALT_MOB_STATUS'];////
        	        	$profile_params[$rowAlt['PROFILEID']]['ALT_MOBILE']=$rowAlt['ALT_MOBILE'];////
			}
		}


		$returnArray=array();
		foreach($profile_params as $k=>$v){
		$phone_flag 	=$v['PHONE_FLAG'];
		if($v['PHONE_MOB']!='')
			$mob_status 	=$v['MOB_STATUS'];
		if($mob_status=='N'||$mob_status=='')
			$mob_status	='N';
		if($v['PHONE_RES']!='')
			$landl_status 	=$v['LANDL_STATUS'];
		if($landl_status=='N'||$landl_status=='')
			$landl_status	='N';
		if($v['ALT_MOBILE']!='')
			$alt_status	=$v['ALT_MOB_STATUS'];
		if($alt_status=='N'||$alt_status=='')
			$alt_status	='N';
		if($phone_flag =='I')
			$returnArray[$k]= $phone_flag;
		
			if($mob_status =='J' || $landl_status=='J' || $alt_status=='J')  
			{
				$junk_status ='1'; 
			}
			if($mob_status =='Y' || $landl_status=='Y' || $alt_status=='Y')
			{	
				$verified_status ='1';
			}
			if($junk_status && $verified_status){ 
				$returnArray[$k]= 'Y';
			}
			else if($verified_status)
				$returnArray[$k]= 'Y';
			else if($junk_status)
				$returnArray[$k]= 'J';
			else 	
				$returnArray[$k]= 'N'; 

			
		}				
return $returnArray;

	}

	




	/* check the duplicate numbers and verified status 
	 * return values notification: D_Y: duplicate verified, D_N: duplicate unverified, U_Y: unique verified, U_N: unique unverified 
	*/	
	function chkDuplicatePhone($phone_num='',$phone_type='',$profileid='')
	{
		if(!$phone_num && !$profileid)
			return false;
		$profileArr = duplicatePhoneHander($phone_num,$phone_type);
		$profileCnt = count($profileArr); 		 	
		if($profileCnt =='0' || $profileCnt =='1'){
			$unique ='1';
			$duplicate ='';
			$profileExst ='0';
			if($profileCnt=='1')
				$profileExst ='1';
		}			 
		else{
			$duplicate ='1';
			$unique =''; 
		}
		if($profileCnt >'0')
		{
                        if($profileid)
                        {
                                if($profileArr[$profileid]=='Y')
                                        $verified='1';
                                else
                                        $verified='';
                        }
                        // check verified status of the phone 
                        else
                        {
                                if(in_array('Y',$profileArr))
                                        $verified ='1';
                                else
                                        $verified ='';
                        }

			// handling for the duplicate more than 1 and verified itself only is considered as unique verified but not as duplicate verified
			$x=0;
			foreach($profileArr as $key=>$value){
				if($value=='Y')
					$x++;
			}
			if($x==1 && $profileArr[$profileid]=='Y'){
				$unique ='1';
				$duplicate ='';
			}
		}
		if($duplicate && $verified)
			return 'D_Y';
		else if ($duplicate)
			return 'D_N';
		else if ($unique && $verified)
			return 'U_Y';
		else if($unique && $profileExst)
			return 'U_N';
		else
			return 'U';
	}	

	// return an array of profilieds
	function duplicatePhoneHander($phone_num,$phone_type)
	{
		$phone_num =trim($phone_num);
		$phone_type=trim($phone_type);
		if((!$phone_num) || (!$phone_type))
			return;
		$profileArray	=array();
		$execute	='';
		$date_1year 	=date("Y-m-d", JSstrToTime("-12 months"));
		$date_5month 	=date("Y-m-d", JSstrToTime("-5 months"));
			
		if($phone_type =='M')
		{
			$PHONE_STATUS 	='MOB_STATUS';
			$phone 		=trim(substr($phone_num,-10,10));
			if($phone){
				$sql ="SELECT `PROFILEID`,`MOB_STATUS`,DATE(LAST_LOGIN_DT) LAST_LOGIN_DT from newjs.JPROFILE WHERE `PHONE_MOB` IN ('$phone','0$phone') AND DATE(LAST_LOGIN_DT) >='$date_1year' AND ACTIVATED IN('H','Y') AND activatedKey=1";
				$execute =1;
			}
		}
		else if($phone_type =='L')
		{
			$PHONE_STATUS 	='LANDL_STATUS';
			$phoneArr 	= explode("-",$phone_num);
			$landline_std	='';
			if(count($phoneArr)=='2'){
				$std 		= landlineformat($phoneArr[0]);
				$landline 	= landlineformat($phoneArr[1]);
				if($landline)
					$landline_std	= landlineformat($landline,$std);
			}
			else{
				$landline_std   = landlineformat($phone_num);
			}
			$cnt_no =strlen($landline_std);
			if($cnt_no >5){
				$sql ="SELECT `PROFILEID`,`LANDL_STATUS`,DATE(LAST_LOGIN_DT) LAST_LOGIN_DT  from newjs.JPROFILE WHERE `PHONE_WITH_STD` IN ('$landline_std','0$landline_std') AND DATE(LAST_LOGIN_DT) >='$date_1year' AND ACTIVATED IN('H','Y') AND activatedKey=1";
				$execute =1;
			}
				
		}
                else if($phone_type =='A')
                {
                        $PHONE_STATUS   ='ALT_MOB_STATUS';
                        $phone          =trim(substr($phone_num,-10,10));
                        if($phone){
                                $sql ="SELECT `PROFILEID`,`ALT_MOB_STATUS` from newjs.JPROFILE_CONTACT WHERE `ALT_MOBILE` IN ('$phone','0$phone')";
                                $execute =1;
                        }
                }

		if(!$execute)
			return $profileArray;
		$res =mysql_query_decide($sql) or logError("Could not get profile details from JPROFILE in duplicate handling ",$sql);
		while($row=mysql_fetch_array($res))
		{	
			$profileid 	=$row['PROFILEID'];	
			$phone_status 	=$row["$PHONE_STATUS"];
			$login_dt 	=$row['LAST_LOGIN_DT'];

			if($login_dt < $date_5month){
				if($phone_status=='Y')
					$profileArray[$profileid] = 'Y';
			}
			else{
				$profileArray[$profileid] = $phone_status;
			}	 
		}
		return $profileArray;		
	}	
	function checkVerificationCodeExists($profileid)
	{
                $sql ="SELECT `CODE` from newjs.PHONE_VERIFY_CODE where PROFILEID='$profileid'";
                $res =mysql_query_decide($sql) or logError("Could not get profile details from JPROFILE in verification code",$sql);
                $row=mysql_fetch_array($res);
                $verificationCode =$row['CODE'];
                if($verificationCode)
                        return $verificationCode;
		else
			return false;
	}
	/* Verification code generated is valid for 7 days only,
	 * Every week the table - PHONE_VERIFY_CODE  gets flushed  	
	 * return numeric code	
	*/	
	function getVerificationCode($profileid)
	{
		$number=checkVerificationCodeExists($profileid);
		if($number)
			return $number;
		else{			
			//generate New Verification Code (logic used)
			$number =rand(0, pow(10,10));
			$number=substr($number,0,4);
			if(substr($number,0,1)=='0')
				$number=str_replace('0','1',$number);

			$sql ="INSERT IGNORE INTO newjs.PHONE_VERIFY_CODE(`CODE`,`PROFILEID`) VALUES('$number','$profileid')";
			mysql_query_decide($sql) or logError("Insert record into newjs.PHONE_VERIFY_CODE",$sql);
			return $number;
		}		
	}	

	// Check the verification code validity for the 2 cases. 1. IVR 2.SMS
	function validate_verificationCode($verificationCode="",$profileid='',$phone_num='',$action="")		  
	{
		if($action =='IVR')
		{	
			if(!$profileid)
				return;
			$getCode =getVerificationCode($profileid);	
			if($getCode == $verificationCode)
				return true;
		}
		else if($action =='SMS')	
		{
			$profileidArr=array();
			$sql ="SELECT `PROFILEID` from newjs.PHONE_VERIFY_CODE where CODE='$verificationCode'";	
			$res =mysql_query_decide($sql) or logError("Could not get profile details from JPROFILE in verification code",$sql);
			while($row=mysql_fetch_array($res))
				$profileidArr[]=$row['PROFILEID'];
			if(is_array($profileidArr))
			{
			foreach($profileidArr as $k=>$profileid)
			{
				$phone =trim(substr($phone_num,-10,10));
				if(!$phone)
					return;
				$sql ="SELECT PROFILEID from newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid' AND PHONE_MOB IN('$phone','0$phone','$phone_num')";
				$res =mysql_query_decide($sql) or logError("Could not get profile details from JPROFILE in verification code",$sql);
				if($row=mysql_fetch_array($res))
					$pid =$row['PROFILEID'];
				if($pid)	
					return $pid;
					
				$sqlAlt ="SELECT PROFILEID FROM newjs.JPROFILE_CONTACT WHERE PROFILEID='$profileid' AND ALT_MOBILE in('$phone','0$phone','$phone_num')";
				$resAlt =mysql_query_decide($sqlAlt) or logError("Could not get profile details from JPROFILE in verification code",$sqlAlt);
				if($rowAlt=mysql_fetch_array($resAlt))
					$pid =$rowAlt['PROFILEID'];
				if($pid)
					return $pid;
			}
			}
		}			
		return false;
	}
	function anyPhoneVerified($profileid)
	{
		$sql= "SELECT MOB_STATUS,LANDL_STATUS FROM newjs.JPROFILE WHERE PROFILEID='".$profileid."'";
		$res =mysql_query_decide($sql) or logError("select query on jprofile error",$sql);
		$row =mysql_fetch_array($res);
		$mob_status = $row['MOB_STATUS'];
		$landl_status = $row['LANDL_STATUS'];
		if($mob_status=='Y'||$landl_status=='Y')
			return true;
		$sqlAlt = "SELECT ALT_MOB_STATUS FROM newjs.JPROFILE_CONTACT WHERE PROFILEID='".$profileid."'";
		$resAlt =mysql_query_decide($sqlAlt) or logError("select query on jprofile error",$sqlAlt);
		$rowAlt =mysql_fetch_array($resAlt);
		$alt_mob_status = $rowAlt['ALT_MOB_STATUS'];
		if($alt_mob_status == 'Y')
			return true;
		return false;
	}
	function sendPhoneVerificationMailer($profileid, $phone_num)
	{
		include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
		$ftoStateArray = SymfonyFTOFunctions::getFTOStateArray($profileid);
		if($ftoStateArray['SUBSTATE']==FTOSubStateTypes::FTO_ELIGIBLE_HAVE_PHONE_NO_PHOTO|| $ftoStateArray['STATE']==FTOStateTypes::FTO_ACTIVE)
		{
			$email_sender=new EmailSender(MailerGroup::PHONE_VERIFY);
			$tpl = $email_sender->setProfileId($profileid);
			$p_list = new PartialList;
			$suggested_matches_array=SearchCommonFunctions::getDppMatches($profileid,'fto_offer',SearchSortTypesEnums::popularSortFlag);
			$p_list->addPartial('suggested_profiles','suggested_profiles',$suggested_matches_array["SEARCH_RESULTS"],false);
			$p_list->addPartial('jeevansathi_contact_address','jeevansathi_contact_address');
			$tpl->setPartials($p_list);
			$profile=$tpl->getSenderProfile();
			$fto_state=JsCommon::getProfileState($profile);
			$smartyObj = $tpl->getSmarty();
			$smartyObj->assign("PHONE_NUMBER_VERIFIED",$phone_num);
			$smartyObj->assign("FTO_REMAINING_DAYS",$ftoStateArray['FTO_REMAINING_DAYS']);
			if($fto_state != 'IU')
				$email_sender->send();
		}
	}
	// function update the phone status for the profile depending upon the verification status
	// for landline phone = [std+landline]
	function phoneUpdateProcess($profileid,$phone_num='',$phoneType='',$actionStatus="",$message="",$username='',$isd='')
	{
		if(!trim($profileid))
			return false;
		include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
		$jprofileUpdateObj = JProfileUpdateLib::getInstance(); 
		if($actionStatus == 'Y')	// Verified marked status
		{
			$anyPhoneVerified = anyPhoneVerified($profileid);
			$profileArr =array();
			$newProfileArr =array();
			if($phoneType=='M'){
				//$query_param = "MOB_STATUS='Y',PHONE_FLAG=''";
				$arrFields[ "MOB_STATUS"]='Y';
				$arrFields[ "PHONE_FLAG"]='';
			}
			elseif($phoneType=='L'){
				//$query_param = "LANDL_STATUS='Y',PHONE_FLAG=''";
				$arrFields[ "LANDL_STATUS"]='Y';
				$arrFields[ "PHONE_FLAG"]='';
			}
			elseif($phoneType=='A')
			{
				deleteCachedJprofile_Contact($profileid);
				
				$profileid=$profileid;
				$arrParams = array('ALT_MOB_STATUS'=>'Y');
				$jprofileUpdateObj->updateJPROFILE_CONTACT($profileid, $arrParams);
        
				//$query_param = "PHONE_FLAG=''";
				$arrFields[ "PHONE_FLAG"]='';
				//$sqlAlt="UPDATE newjs.JPROFILE_CONTACT SET `ALT_MOB_STATUS`='Y' WHERE `PROFILEID` =  '".$profileid."'";
				//mysql_query_decide($sqlAlt) or logError("Could not update profile details in JPROFILE_CONTACT ",$sqlAlt);
				//$profileContactRowAffected = mysql_affected_rows();
			}
			$exrtaWhereCond = "activatedKey=1";
			//$sql ="update newjs.JPROFILE SET $query_param where PROFILEID='".$profileid."' AND activatedKey=1";
			//mysql_query_decide($sql) or logError("Could not update profile details in JPROFILE ",$sql);
			//$jprofileRowAffected =mysql_affected_rows();
			$jprofileUpdateObj->editJPROFILE($arrFields,$profileid,"PROFILEID",$exrtaWhereCond);
			
			$action = FTOStateUpdateReason::NUMBER_VERIFY;
			SymfonyFTOFunctions::updateFTOState($profileid,$action);
			include_once "../profile/InstantSMS.php";
                        $sms= new InstantSMS("PHONE_VERIFY",$profileid);
                        $sms->send();
			
			//Send Instant Notification 
			if($phoneType=='M' || $phoneType=='A'){
				$mobileAppRegObj =new MOBILE_API_REGISTRATION_ID('newjs_slave');
				$appRegProfile =$mobileAppRegObj->appRegisteredProfile($profileid);
			}
			// Check app login in last 7 days:
			if($appRegProfile){
		                $loginTrackingObj=new MIS_LOGIN_TRACKING('crm_slave');
                		$profileArr      =$loginTrackingObj->getLast7DaysLoginProfiles($profileid);
			}
			if($appRegProfile && count($profileArr)>0){
				$notificationKey='BUY_MEMB';
				$smsMemb 	= new InstantSMS("$notificationKey",$profileid);
				$message 	=$smsMemb->getSmsMessage();
				if($message){
					$messageArr 	=explode("Choose",$message);
					$actualMsg 	=$messageArr[0]."Choose your plan now.";

					// Send Notification
					$instantNotificationObj =new InstantAppNotification($notificationKey);	
					$instantNotificationObj->sendNotification($profileid,'',$actualMsg);
				}
			}
			else{		
				$smsMemb = new InstantSMS("BUY_MEMB",$profileid);
				$smsMemb->send();
			}

	//		if(!$anyPhoneVerified && ($profileContactRowAffected||$jprofileRowAffected))
	//			sendPhoneVerificationMailer($profileid, $phone_num);
			$sql="SELECT COUNT(*) AS COUNT FROM jsadmin.PHONE_VERIFIED_LOG WHERE PROFILEID='".$profileid."'";
			$res = mysql_query_decide($sql) or logError("Could not update profile details in JPROFILE ",$sql);
			$row =mysql_fetch_array($res);
			$noOfTimesVerified = $row['COUNT'];
			$sql ="update jsadmin.REPORT_INVALID_PHONE SET VERIFIED='Y' where `SUBMITTEE`='$profileid'";
			mysql_query_decide($sql) or logError("Could not update profile details in JPROFILE ",$sql);

			$sql ="update incentive.MAIN_ADMIN_POOL SET TIMES_TRIED='0' where `PROFILEID`='$profileid'";
			mysql_query_decide($sql) or logError("Could not update profile details in incentive.MAIN_ADMIN_POOL ",$sql);

                        // update OFFLINE MATCHES, with profiles having verified phone numbers
                        $sql ="update jsadmin.OFFLINE_MATCHES SET CATEGORY='6' WHERE MATCH_ID='$profileid' AND CATEGORY=''";
			mysql_query_decide($sql) or logError("Could not update profile details in jsadmin.OFFLINE_MATCHES ",$sql);	
	
			/* Start-- logging process for the verified and unverified profileids
			 * If a profile gets verified for a phone number,then rest all other profile gets unverified having the same phone number
			*/
                        if($phoneType=='M'){
                                $query_param = "MOB_STATUS='N',PHONE_FLAG=''";
				$phone_num_format =mobileformat($phone_num);
			}
                        elseif($phoneType=='L'){
                                $query_param = "LANDL_STATUS='N',PHONE_FLAG=''";
				$phone_num_format =landlineformat($phone_num);
			}
			elseif($phoneType=='A'){
				$phone_num_format=mobileformat($phone_num);
			}
	                $sql ="insert into jsadmin.PHONE_VERIFIED_LOG (`PROFILEID`,`PHONE_TYPE`,`PHONE_NUM`,`MSG`,`OP_USERNAME`,`ENTRY_DT`) VALUES ('$profileid','$phoneType','$phone_num_format','$message','$username',now())";
                 	mysql_query_decide($sql) or logError("Could not insert profile details in PHONE_VERIFIED_LOG ",$sql);
			
            if ($message!='OPS'){
            	
			$memObj=JsMemcache::getInstance();
			$showConsentMsg=$memObj->get('showConsentMsg_'.$profileid);
			
			if (!$showConsentMsg){
				$showConsentMsg=JsCommon::showConsentMessage($profileid)? 'Y':'N';
				}

			if ($showConsentMsg=='Y'){
			JsCommon::insertConsentMessageFlag($profileid);
						}


			if (JsCommon::showDuplicateNumberConsent($profileid))
			{
			
			$duplicateObj=new newjs_DUPLICATE_NUMBER_CONSENT();
			$duplicateObj->setConsentStatus($profileid);

			}

			
				}


		}
		else if($actionStatus == 'D') // Denied marked status (profile is marked as Unverified state if user denies the request)
		{
			if($phoneType=='M'){
				$arrFields['MOB_STATUS']='N';
				$arrFields['PHONE_FLAG']='';
				$query_param = "MOB_STATUS='N',PHONE_FLAG=''";
			}
			elseif ($phoneType=='L'){
				$arrFields['LANDL_STATUS']='N';
				$arrFields['PHONE_FLAG']='';
				$query_param = "LANDL_STATUS='N',PHONE_FLAG=''";
			}
			else 
			{
				deleteCachedJprofile_Contact($profileid);
				
				
				if($phoneType!='A'){
					//$query_param = "MOB_STATUS='N',LANDL_STATUS='N',PHONE_FLAG=''";	
					$arrFields['MOB_STATUS']='N';
					$arrFields['LANDL_STATUS']='N';
					$arrFields['PHONE_FLAG']='';
				}
				//$sqlAlt="UPDATE  newjs.JPROFILE_CONTACT SET `ALT_MOB_STATUS`='N' WHERE `PROFILEID`='".$profileid."'";
				$arrParams = array('ALT_MOB_STATUS'=>'N');
				$jprofileUpdateObj->updateJPROFILE_CONTACT($profileid, $arrParams);
				//mysql_query_decide($sqlAlt) or logError("Could not update profile details from JPROFILE_CONTACT ",$sqlAlt);
			}
			if($phoneType!='A')
			{
				$exrtaWhereCond = "activatedKey=1";
				$jprofileUpdateObj->editJPROFILE($arrFields,$profileid,"PROFILEID",$exrtaWhereCond);
			//	$sql ="update newjs.JPROFILE SET $query_param where PROFILEID='$profileid' AND activatedKey=1";	
				//mysql_query_decide($sql) or logError("Could not update profile details from JPROFILE ",$sql);
			}
			$action = FTOStateUpdateReason::NUMBER_UNVERIFY;
			SymfonyFTOFunctions::updateFTOState($profileid,$action);
			if($phoneType!='L')
			{
			        include_once "../profile/InstantSMS.php";
			        $arr=array('PHONE_MOB'=>$phone_num, 'ISD'=>$isd);
					$smsViewer = new InstantSMS("PHONE_UNVERIFY",$profileid,$arr,'');
					$smsViewer->send();
			}
			$emailSender = new EmailSender(MailerGroup::PHONE_UNVERIFY, 1838);
			$tpl = $emailSender->setProfileId($profileid);
			$tpl->getSmarty()->assign("phone_num", '+'.$isd.$phone_num);
			$subject = "We were unable to reach you. Kindly authenticate your contact details.";
			$tpl->setSubject($subject);
			$emailSender->send();
			//$sql ="insert into jsadmin.PHONE_UNVERIFIED_LOG (`PROFILEID`,`ENTRY_DT`) VALUES('$profileid',now())";
			//mysql_query_decide($sql) or logError("Could not insert profile details in PHONE_UNVERIFIED_LOG in deleted case",$sql);

		}
		else if($actionStatus == 'I') // Invalid marked status
		{
			$action = FTOStateUpdateReason::NUMBER_UNVERIFY;
			SymfonyFTOFunctions::updateFTOState($profileid,$action);

			//$query_param = "MOB_STATUS='N',LANDL_STATUS='N',PHONE_FLAG='I'";
			$arrFields['MOB_STATUS'] = 'N';
			$arrFields['LANDL_STATUS'] = 'N';
			$arrFields['PHONE_FLAG'] = 'I';
			//$sql ="update newjs.JPROFILE SET $query_param where PROFILEID='$profileid' AND activatedKey=1";
			//mysql_query_decide($sql) or logError("Could not update profile details in JPROFILE ",$sql);
			$exrtaWhereCond = "activatedKey=1";
			$jprofileUpdateObj->editJPROFILE($arrFields,$profileid,"PROFILEID",$exrtaWhereCond);
      
      deleteCachedJprofile_Contact($profileid);
			$arrParams = array('ALT_MOB_STATUS'=>'N');
			$jprofileUpdateObj->updateJPROFILE_CONTACT($profileid, $arrParams);
			//$sqlAlt="UPDATE newjs.JPROFILE_CONTACT SET  `ALT_MOB_STATUS`='N' WHERE  `PROFILEID` =  '".$profileid."'";
			//mysql_query_decide($sqlAlt) or logError("Could not update profile details in JPROFILE_CONTACT ",$sqlAlt);

			$sql = "REPLACE into incentive.INVALID_PHONE (PROFILEID,MSG,OP_USERNAME,ENTRY_DT) VALUES('$profileid','$message','$username',now())";
			mysql_query_decide($sql) or logError("Could not update profile details in incentive.INVALID_PHONE ",$sql);
			
			$sql ="update jsadmin.REPORT_INVALID_PHONE SET VERIFIED='I' where `SUBMITTEE`='$profileid'";
			mysql_query_decide($sql) or logError("Could not update profile details in JPROFILE ",$sql);

	                $sql = "UPDATE incentive.MAIN_ADMIN_POOL SET ALLOTMENT_AVAIL ='N' WHERE PROFILEID='$profileid'";
        	        mysql_query_decide($sql) or logError("$sql".mysql_error_js());

		}
		else if($actionStatus =='J') // Junk marked status
		{
			if($phoneType=='M'){
				$arrFields['MOB_STATUS']='J';
				$arrFields['PHONE_FLAG']='';
				//$query_param = "MOB_STATUS='J',PHONE_FLAG=''";
			}
			elseif ($phoneType=='L'){
				$arrFields['LANDL_STATUS']='J';
				$arrFields['PHONE_FLAG']='';				
				//$query_param = "LANDL_STATUS='J',PHONE_FLAG=''";
			}
			elseif($phoneType=='A')
			{
        deleteCachedJprofile_Contact($profileid);
        
				$arrParams = array('ALT_MOB_STATUS'=>'J');
				$jprofileUpdateObj->updateJPROFILE_CONTACT($profileid, $arrParams);
				//$sqlAlt="UPDATE  newjs.JPROFILE_CONTACT SET `ALT_MOB_STATUS`='J' WHERE `PROFILEID`='".$profileid."'";
				//mysql_query_decide($sqlAlt) or logError("Could not update profile details in JPROFILE_CONTACT ",$sqlAlt);
			}
			if($phoneType!='A')
			{
				$exrtaWhereCond = "activatedKey=1";
				$jprofileUpdateObj->editJPROFILE($arrFields,$profileid,"PROFILEID",$exrtaWhereCond);
				//$sql ="update newjs.JPROFILE SET $query_param where PROFILEID='$profileid' AND activatedKey=1";
				//mysql_query_decide($sql) or logError("Could not update profile details in JPROFILE ",$sql);				
			}
		}
		else if($actionStatus =='E') // Edit marked status	
		{
			$sql ="DELETE FROM incentive.INVALID_PHONE where PROFILEID='$profileid'";
			mysql_query_decide($sql) or logError("Could not delete INVALID_PHONE in incentive.INVALID_PHONE ",$sql);

			$sql ="update jsadmin.REPORT_INVALID_PHONE SET VERIFIED='N' where `SUBMITTEE`='$profileid'";
			mysql_query_decide($sql) or logError("Could not update profile details in JPROFILE ",$sql);
			
                        $sql="SELECT count(*) as cnt from incentive.MAIN_ADMIN where PROFILEID='$profileid'";
                        $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
                        $row=mysql_fetch_array($result);

                        $sql1="UPDATE incentive.MAIN_ADMIN_POOL set TIMES_TRIED=0";
                        if($row['cnt']==0)
	                        $sql1.=",ALLOTMENT_AVAIL='Y'";
                        $sql1.=" where PROFILEID='$profileid'";
                        mysql_query_decide($sql1) or logError("Could not update the table incentive.MAIN_ADMIN_POOL",$sql1,"ShowErrTemplate");             

                        if($phoneType=='M'){
                                $arrFields['MOB_STATUS']='N';
								$arrFields['PHONE_FLAG']='';
                                //$query_param = "MOB_STATUS='N',PHONE_FLAG=''";
							}
                        elseif ($phoneType=='L'){
								$arrFields['LANDL_STATUS']='N';
								$arrFields['PHONE_FLAG']='';
                                //$query_param = "LANDL_STATUS='N',PHONE_FLAG=''";
							}
			elseif($phoneType=='A')
			{
        deleteCachedJprofile_Contact($profileid);
				$arrParams = array('ALT_MOB_STATUS'=>'N');
				$jprofileUpdateObj->updateJPROFILE_CONTACT($profileid, $arrParams);
				//$sqlAlt="UPDATE newjs.JPROFILE_CONTACT SET `ALT_MOB_STATUS`='N' WHERE `PROFILEID`='".$profileid."'";
                  //      	mysql_query_decide($sqlAlt) or logError("Could not update profile details in JPROFILE_CONTACT ",$sqlAlt);
			}
			if($phoneType!='A')
			{
				$exrtaWhereCond = "activatedKey=1";
				$jprofileUpdateObj->editJPROFILE($arrFields,$profileid,"PROFILEID",$exrtaWhereCond);
	                        //$sql ="update newjs.JPROFILE SET $query_param where PROFILEID='$profileid' AND activatedKey=1";
        	                //mysql_query_decide($sql) or logError("Could not update profile details in JPROFILE ",$sql);
			}
		}
		$value = hidePhoneLayer($profileid);
		JsMemcache::getInstance()->set($profileid."_PHONE_VERIFIED",$value);
		if($actionStatus == 'Y')	// Verified marked status
		{
			$sql = "SELECT ACTIVATED FROM newjs.JPROFILE WHERE PROFILEID='".$profileid."'";
			$res = mysql_query_decide($sql) or logError("Could not fetch JPROFILE",$sql);
			if($row=mysql_fetch_array($res))
				$activated =$row['ACTIVATED'];
			if($noOfTimesVerified==0 && $activated=="Y")
				CommonFunction::sendWelcomeMailer($profileid);
			if($noOfTimesVerified==0) 
				RegChannelTrack::insertPageChannel($profileid,PageTypeTrack::_PHONEVERIFIED); 
		}
		return true;	
	}

/* function to display auto phone verification layer on MYJS page on user login (phone verification conditions are checked) 
 * return value: indicates the status of the layer to be shown on MYJS page, if does not return a value- no layer to be shown on MYJS page 
 * M#:mobile number, L#:landline number
*/
function get_phoneVerifyLayer($profile_param)
{
        $phoneStatus = getPhoneStatus($profile_param);
        if($phoneStatus=="Y")
                return false;
        else
                return true;

}


/* Function to check the dnc number 
 * return @ true/false
 * argument @array	
*/
function checkDNCNum($phoneNumberArray)
{
	$db_dnc = connect_dnc();
	$DNCArr         =array();
	$DNC_NumberArr  =array();
	$selectedArr    =array();
	$status         =true;

	if(!is_array($phoneNumberArray) || count($phoneNumberArray)=='0')
        	return false;
        else{
        	foreach($phoneNumberArray as $key1=>$val1)
                {$val1=substr($val1,-10);
                	if($val1)
                        	$selectedArr[] =$val1;
		}
	}

	$phoneNumberStr =implode("','",$selectedArr);
        $sql="SELECT PHONE FROM DNC.DNC_LIST WHERE PHONE IN('$phoneNumberStr')";
	$res =mysql_query_decide($sql,$db_dnc) or logError("Could not get DNC number check in DNC.DNC_LIST  ",$sql);
        while($row=mysql_fetch_array($res))
        {
        	$DNC_NumberArr[] =$row['PHONE'];
        }
	$DncStatus=array();  //esha trac 519
        foreach($phoneNumberArray as $key=>$val)
        {
        	if(in_array($val, $DNC_NumberArr)){
                	$DNCArr[$key] =$val;
                        $key1 =$key."S";
                        $DNCArr[$key1] ='Y';
			$DncStatus[]='Y';//esha trac 519
                }
                else{
        	        $DNCArr[$key] =$val;
                        $key1 =$key."S";
                        $DNCArr[$key1] ='N';
			$DncStatus[]='N';//esha trac 519
                        if(in_array($val, $selectedArr))
                	        $status =false;
                }
        }
	mysql_close($db_dnc);
	$DNCArr['STATUS'] =$status;
	$DNCArr['phnStatus']=$DncStatus;//esha trac 519
	return $DNCArr;
}

///function added by Esha
function checkMobileNumber($number, $profileid='',$db='',$isd='')
{
        $number=removeAllSpecialChars($number);//remove specail chars from $this->number//for mobile need to remove everything except numbers
	if($profileid!='')
	{
		if(!notInINVALID_PHONE($profileid,$db))
			return 'N';
	}
	if($isd!='' && $isd!='91')
	{
		$length = strlen($number);
		return ($length>=6 && $length<=14)?'Y':'N';
	}
	else
	{
		return (lengthCheckMobile($number) && checkIndianMobileFormat($number) && notInJunk($number,$db))?'Y':'N';
	}
}
function notInJunk($number,$db="")
{
	$sql="SELECT count(*) AS COUNT FROM newjs.PHONE_JUNK WHERE PHONE_NUM='".$number."'";
	if($db)
		$res=mysql_query($sql,$db);
	else
	       $res=mysql_query_decide($sql); 
        if($row=mysql_fetch_array($res))
                return ($row["COUNT"]>0)?false:true;
	
}
function notInINVALID_PHONE($profileid,$db="")
{
	$sql="SELECT count(*) AS COUNT FROM incentive.INVALID_PHONE WHERE PROFILEID ='".$profileid."'";      //unique is there on prfileid
	if($db)
		$res=mysql_query($sql,$db);
	else
		$res=mysql_query($sql);
	if($row=mysql_fetch_array($res))
		return ($row["COUNT"]>0)?false:true;
}


function lengthCheckMobile($number)
{
        return (strlen($number)==10)?true:false;
}
function checkIndianMobileFormat($number)
{
        return (in_array(substr($number,0,1),array(7,8,9)))?true:false;
}

function removeAllSpecialChars($number)
{
	 return ltrim(preg_replace("/[^0-9]/","",$number),0);//remove everything except numbers
}
function removeSpecialCharsExceptHyphen($number)
{
	 return $number=ltrim(preg_replace("/[^0-9\-]/","",$number),0);//remove everything except numbers and hyphen(-)
}

function checkIndianLandlineFormat($number)
{
        return (in_array(substr($number,0,1),array(2,3,4,5,6)))?true:false;
}
function checkLandlineNumber($landline,$std,$profileid='',$db='',$isd='')
{
        if($profileid!='')
        {
                if(!notInINVALID_PHONE($profileid,$db))
                        return 'N';
        }
	$std=removeAllSpecialChars($std);
	if(!$std)
	{
		$landline=removeSpecialCharsExceptHyphen($landline);
		$numberArr=explode("-",$landline);
		$landline=$numberArr[1];
		$std=$numberArr[0];
	}
	else
		$landline=removeAllSpecialChars($landline);
	$number=ltrim($std,0).ltrim($landline,0);
        if($isd!='' && $isd!='91')
        {
                $length = strlen($number);
                return ($length>=7)?'Y':'N';
        }
        else
	        return (lengthCheckMobile($number) && checkIndianLandlineFormat($landline) && notInJunk($number,$db))?'Y':'N';
}			
function phoneLayerTracking($profileid, $sourcePage='',$fromMobile='')
{
                if($sourcePage=='EOI')
                        $impacted_factor="COUNT_EOI";
                elseif($sourcePage=='CONTACT')
                        $impacted_factor="COUNT_VIEW_CONTACT";
                else
                        $impacted_factor="COUNT_OTHERS";
                if($impacted_factor)
                {
                        $sql_tracking_update="UPDATE MIS.PHONE_LAYER_TRACKING SET ".$impacted_factor."=".$impacted_factor."+1 WHERE `PROFILEID` = '".$profileid."'";
                        $res_tracking_update=mysql_query_decide($sql_tracking_update);
                        if(@mysql_affected_rows($res_tracking_update)<1)//profileid is not dr insert
                        {

                                $sql_tracking_insert="INSERT IGNORE INTO MIS.PHONE_LAYER_TRACKING (`PROFILEID`, ".$impacted_factor.") VALUES ('".$profileid."', '1')";
                                $res_tracking_insert=mysql_query_decide($sql_tracking_insert);
                        }
                }
		if(trim($fromMobile)=='Y')
			updateLastClickOn($profileid,$sourcePage,$fromMobile);
}

function updateLastClickOn($profileid,$sourcePage='',$fromMobile='')
{
	$checkPhoneStatus=getPhoneStatus('',$profileid);
	if($checkPhoneStatus!='Y')
	{
                        if(!strcmp($sourcePage,"EOI"))
                                $last_click_on="EOI";
                        elseif(!strcmp($sourcePage,"CONTACT"))
                                $last_click_on="VIEW CONTACT";
                        else
                                $last_click_on="OTHERS";
			if($fromMobile=='Y')
				$last_click_on.=" - MOBILE";
                        $sql_tracking_update="UPDATE MIS.PHONE_LAYER_TRACKING SET LAST_CLICK_ON='".$last_click_on."',LAST_CLICK_DATE=now() WHERE `PROFILEID` = '".$profileid."'";
                        $res_tracking_update=mysql_query_decide($sql_tracking_update);
	}
}
function getIsdInFormat($isd)
{
	$isd = removeAllSpecialChars($isd);
	$length = strlen($isd);
	if($length>0 && $length<=3)
		return $isd;
	return false;
}

function UnverifyNum($profileId, $phoneType, $number)
{
	// Profile Id of Submittee, its phone type and the reported number
	$interval = 10;
	$ReportObj = new JSADMIN_REPORT_INVALID_PHONE();
	$result = $ReportObj->getReportInvalidInterval($profileId,$interval);
	// array of profile ids of Submitters
	$arrSubmitter = array();
	$ReportedDate = array();
	$arrUpdatedProfiles = array();
	if($result)
	{
		foreach ($result as $row)
		{
			if($phoneType == 'L' && $row['PHONE'] == 'Y')
			{
				array_push($arrSubmitter, $row['SUBMITTER']);
				$ReportedDate[$row['SUBMITTER']] = $row['SUBMIT_DATE'];
			}
			elseif ($phoneType == 'M' && $row['MOBILE'] == 'Y')
			{
				array_push($arrSubmitter, $row['SUBMITTER']);
				$ReportedDate[$row['SUBMITTER']] = $row['SUBMIT_DATE'];
			}
		}
		if(count($arrSubmitter) == 0)
		{
			return ;
		}
		$arrSubmitter = array_unique($arrSubmitter);
		$jobj = new Jprofile;
		$contactAllotedObj = new jsadmin_CONTACTS_ALLOTED();
		$jsCommonObj =new JsCommon();
		$ProfileIds = array('PROFILEID' => implode(",", $arrSubmitter));
		$arrSubscription = $jobj->getArray($ProfileIds,"","",'PROFILEID, SUBSCRIPTION');
    	foreach ($arrSubscription as $key => $value)
		{
			if($jsCommonObj->isPaid($value['SUBSCRIPTION']))
			{
				// Paid User
				if($contactAllotedObj->updateAllotedContacts($value['PROFILEID'],1))
				{
					// contacts allocated increased
					array_push($arrUpdatedProfiles, $value['PROFILEID']);
				}
			}
		}

		if(count($arrUpdatedProfiles) == 0)
		{
			return ;
		}
		//Todo: get allocated contacts quota for all profiles
		$arrContactQuota = $contactAllotedObj->getAllotedContactsForProfiles($arrUpdatedProfiles);

		foreach ($arrUpdatedProfiles as $value)
		{
			// send mail to user about increase in contact quota
			$top8Mailer = new EmailSender(MailerGroup::TOP8,1845);
			
			$tpl = $top8Mailer->setProfileId($value);
			$date = date('d-m-Y');
			$reportedDate = date('d-m-Y', strtotime($ReportedDate[$value]));
			$subject = "A contact has been added to your quota of contacts | $date";
			$quota = $arrContactQuota[$value];
			$tpl->setSubject($subject);
			// PogId is profile id of submittee
			$tpl->getSmarty()->assign("otherProfile", $profileId);
			$tpl->getSmarty()->assign("number", $number);
			$tpl->getSmarty()->assign("date", $reportedDate);
			$tpl->getSmarty()->assign("quota", $quota);
			$top8Mailer->send();
		}		
	}
	
}

function deleteCachedJprofile_Contact($profileid){
  return;
  $memObject=JsMemcache::getInstance();
  $memObject->delete("JPROFILE_CONTACT_".$profileid);
}
?>
