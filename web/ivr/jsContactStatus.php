<?php
/*
* This file gets the status of the Caller to the Receiver to access Callnow feaure   
* param returns xml to the third party. 
*/
$host =FetchClientIP();

$_SERVER['DOCUMENT_ROOT'] =JsConstants::$docRoot;
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsContactVerify.php");
include_once($_SERVER['DOCUMENT_ROOT']."/ivr/ivrMailers.php");
include_once($_SERVER['DOCUMENT_ROOT']."/ivr/ivr_errorcodes.php");
if(!connect_db())
        connect_db();

//set GET parameter variables
$var_dialcode	="dialcode";
$var_msisdn	="msisdn";
$var_callStatus	="status";
$var_callID	="callid";

$dialcode 	= $_GET["$var_dialcode"];
$callStatus 	= $_GET["$var_callStatus"];
$callID 	= $_GET["$var_callID"];
$msisdn 	= $_GET["$var_msisdn"];

if($dialcode && $msisdn)
{
	// http request to JS successful
	$requestState ="1";
	$err ="";
	$errSet ="";	

	/* call status flag received
	 * I: Call Initiated from the caller to the receiver (By default the status of the call first time)
	 * R: Call Patched & Received (call has been patched between the caller and receiver)
	 * M: Call Patched & Missed (receiver phone is busy or call has been missed/cancelled) 
	 * F: Call could not be patched (either due to technical error or receiver number invalid)
	*/
	$callFlag ="I";		
	if($callID && $callStatus=='1')
		$callFlag="R";
	else if($callID && ($callStatus =='2' || $callStatus ==''))	// condition added temporary
		$callFlag ="M";
	else if($callID && $callStatus =='')
		$callFlag ="F";
		
	/* End */

	$callArr =getDialcodeContent($dialcode);
	$caller_profilecode =$callArr['CALLER'];
	$rec_profilecode = $callArr['RECEIVER'];

	if($caller_profilecode && $rec_profilecode)
	{
	        /* Check added on the IVR-phone call
        	 * User has set 'DO NOT CALL' in the settings. 
        	*/
		$callAllowedArr = callAccess($rec_profilecode);		
		if($callAllowedArr[$rec_profilecode] !='Y' ){
			$callValid =0;
			$err ="CALL_RECEIVING_BLOCKED";
			$errCode = $IVR_errorCodeArr["$err"];
			$errSet =1;
		}				

		/* checks the callnow contact conditions to patch the call between the caller and receiver */
		if($callFlag =='I' && $errSet==''){
			$makeCall = makeCall_status($caller_profilecode,$rec_profilecode,$err,'',$profileDataArr);
        		if($makeCall){
				$callValid =1;	// flag set 1 when call criteria is satisfied
				$callID = recordCallnowStatus($caller_profilecode, $rec_profilecode,$msisdn,$callFlag);
				$phoneArr =getPhone($rec_profilecode,$profileDataArr);
				if($phoneArr['MOBILE'])
					$phoneNo =$phoneArr['MOBILE'];
				else if($phoneArr['LANDLINE'])
					$phoneNo =$phoneArr['LANDLINE'];
        		}
        		else if($err!=''){
				$callValid =0;	// flag set 0 when call criteria is not satisfied
			        $err =trim($err);
        			$errCode =$IVR_errorCodeArr["$err"];
				if($errCode=='20')
					$quotaDate =$profileDataArr['viewer']['QUOTA_START_DATE'];
			}
			else
				$errorGeneral =1;
			/* ends */
		}
		else if($callID && $errSet==''){
			recordCallnowStatus($caller_profilecode,$rec_profilecode,$msisdn,$callFlag,$callID);
			/* condition exist to send sms to receiver when Call has been call patched */
			$phoneArr = getPhone($rec_profilecode,'','M',$caller_profilecode);
			$mobile =$phoneArr['MOBILE'];
			$callerUsername =$phoneArr['USERNAME'];
			if($mobile && $callFlag)
				callReceiver_sms($rec_profilecode,$mobile,$callerUsername,$msisdn,$callFlag);
			/* ends */
		
		}
	}else
	{
		$callValid =0;
	        $err ="ERROR_DIALCODE";
	        $errCode = $IVR_errorCodeArr["$err"];
	}
}
else{
	// http request to JS failed
        $requestState ="0";
}
if($errorGeneral){
	$callValid =0;  // flag set 0 when call criteria is not satisfied
	$err ="ERROR";
	$err =trim($err);
	$errCode = $IVR_errorCodeArr["$err"];
}

$str = generateXML($caller_profilecode,$callFlag,$callValid,$phoneNo,$callID,$errCode,$requestState,$quotaDate);
echo $str;

// function generate xml format
function generateXML($caller_profilecode,$callFlag,$callValid,$phoneNo,$callID,$errCode,$requestState,$quotaDate)
{
        header('content-type: text/xml');
        $xmlStr ="";
        $xmlStr='<?xml version="1.0" encoding="ISO-8859-1"?>';
        $xmlStr.="\n\t<PROFILE>\n\t\t";
	if($callFlag =='I'){
		$xmlStr.="\n\t\t<CALLVALID>$callValid</CALLVALID>\n\t\t";
		$xmlStr.="\n\t\t<PHONENO>$phoneNo</PHONENO>\n\t\t";
		$xmlStr.="\n\t\t<CALLID>$callID</CALLID>\n\t\t";
		$xmlStr.="\n\t\t<ERRORMSG>$errCode</ERRORMSG>\n\t\t";
		$xmlStr.="\n\t\t<QUOTADATE>$quotaDate</QUOTADATE>\n\t\t";
	}
	else
		$xmlStr.="\n\t\t<STATUS>$requestState</STATUS>\n\t\t";
        $xmlStr.="\n\t</PROFILE>";
        return $xmlStr;
}

function getPhone($profilecode="",$profileDataArr="",$type="",$caller_profilecode="")
{
	$phoneNoArr = array();
	if($profilecode && $type=='M'){
		if($caller_profilecode)
			$profilecodeStr =$profilecode.",".$caller_profilecode;
		$sql ="select PROFILEID,USERNAME,PHONE_MOB from newjs.JPROFILE where  activatedKey=1 and PROFILEID IN($profilecodeStr) AND (SHOWPHONE_MOB='Y' OR SHOWPHONE_MOB='S')";
		$res =mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        	while($row = mysql_fetch_array($res)){
			$pid = $row['PROFILEID']; 
	        	$mobile[$pid] = $row['PHONE_MOB'];
			$username[$pid] = $row['USERNAME'];
		}
		$mobileNo = mobileformat($mobile[$profilecode]);
		$phoneNoArr = array("MOBILE"=>$mobileNo,"USERNAME"=>$username[$caller_profilecode]);
		return $phoneNoArr;
	}
	else{
                $showphone = $profileDataArr['viewed']['SHOWPHONE_RES'];
                $showmob = $profileDataArr['viewed']['SHOWPHONE_MOB'];
		if($showmob=='Y' || $showmob=='S')
			$mobile = $profileDataArr['viewed']['PHONE_MOB'];
		if($showphone=='Y' || $showphone=='S'){
			$landline = $profileDataArr['viewed']['PHONE_RES'];
			$std = $profileDataArr['viewed']['STD'];
		}
	}
	$mobileNo = mobileformat($mobile);
	$landline = landlineformat($landline,$std);
	if($mobileNo){
		$sql1 ="SELECT count(*) COUNT from newjs.MOBILE_VERIFICATION_IVR where PROFILEID='$profilecode' AND STATUS='Y'";
                $res1 =mysql_query_decide($sql1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql1,"ShowErrTemplate");
                $row1 = mysql_fetch_array($res1);
                $count1 = $row1['COUNT'];

                $sql_mob="SELECT count(*) as cnt  FROM newjs.MOBILE_VERIFICATION_SMS  WHERE MOBILE IN ('0$mobileNo','+91$mobileNo','91$mobileNo','$mobileNo','$mobile')";
                $result_mob=mysql_query_decide($sql_mob) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_mob,"ShowErrTemplate");
                $row_mob=mysql_fetch_array($result_mob);
		if($count1 >0 || $row_mob["cnt"] >0){
			$phoneNoArr = array("MOBILE"=>$mobileNo);
			return $phoneNoArr;
		}		
	}
	if($landline){
                $sql2 ="SELECT count(*) COUNT from newjs.LANDLINE_VERIFICATION_IVR where PROFILEID='$profilecode' AND STATUS='Y'";
                $res2 =mysql_query_decide($sql2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql2,"ShowErrTemplate");
                $row2 = mysql_fetch_array($res2);
                $count2 = $row2['COUNT'];
		if($count2 >0){
			$phoneNoArr = array("LANDLINE"=>$landline);
			return $phoneNoArr;
		}
	}
	$phoneNoArr = array("MOBILE"=>$mobileNo,"LANDLINE"=>$landline);
	return $phoneNoArr;
}

?>
