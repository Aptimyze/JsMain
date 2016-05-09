<?php
/* File includes the function used in CALLNOW functionality used in the JS site unsing IVR */

$_SERVER['DOCUMENT_ROOT'] =JsConstants::$docRoot;
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect_functions.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");

/* Gets the data of the all the calls held between the viewer profile and viewed profile
 * return: array
*/
function getCallDataArray($viewer_profileid,$viewed_profileid)
{
	$callStatus =array();
        $sql ="SELECT CALL_DT,CALL_STATUS,SEEN FROM newjs.CALLNOW where RECEIVER_PID='$viewer_profileid' AND CALLER_PID='$viewed_profileid' AND (CALL_STATUS='R') ORDER BY CALLNOWID DESC limit 1";
        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        while($row=mysql_fetch_array($result))
        {
		$status =$row['CALL_STATUS'];
                $callStatus[$status]["CALL_STATUS"] = $row["CALL_STATUS"];
		$callStatus[$status]["CALL_DT"] = $row["CALL_DT"];
		$callStatus[$status]["SEEN"] = $row["SEEN"];
        }

        $sql ="SELECT CALL_DT,CALL_STATUS,SEEN FROM newjs.CALLNOW where RECEIVER_PID='$viewer_profileid' AND CALLER_PID='$viewed_profileid' AND (CALL_STATUS='M') ORDER BY CALLNOWID DESC limit 1";
        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        while($row=mysql_fetch_array($result))
        {
                $status =$row['CALL_STATUS'];
                $callStatus[$status]["CALL_STATUS"] = $row["CALL_STATUS"];
                $callStatus[$status]["CALL_DT"] = $row["CALL_DT"];
                $callStatus[$status]["SEEN"] = $row["SEEN"];
        }

        $sql ="SELECT CALL_DT,CALL_STATUS,SEEN FROM newjs.CALLNOW where RECEIVER_PID='$viewed_profileid' AND CALLER_PID='$viewer_profileid' AND (CALL_STATUS='R') ORDER BY CALLNOWID DESC limit 1";
        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        while($row=mysql_fetch_array($result))
        {
                $callStatus['I']["CALL_STATUS"] ='I'; 
                $callStatus['I']["CALL_DT"] = $row["CALL_DT"];
                $callStatus['I']["SEEN"] = $row["SEEN"];
        }
	//print_r($callStatus);
        return $callStatus; 
}

/* Gets the total count of all the calls of the profile:
 * Total calls :Receiver calls + Missed calls + calls made	
 * Total New calls: (Received calls + Missed calls ), thses calls include profile which are unviewed
 * return: array
*/
function getTotalCallCount($profileid)
{
        global $CALL_NOW;
        if(!$CALL_NOW)
                return;

	$action1=array();
	$action2=array();
        $sql ="SELECT distinct CALLER_PID,SEEN FROM newjs.CALLNOW where RECEIVER_PID='$profileid' AND (CALL_STATUS='R' OR CALL_STATUS='M')";
        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$caller_pid_arr =array();
        while($row=mysql_fetch_array($result))
        {
		$val = $row['CALLER_PID'];
		if(!in_array("$val",$caller_pid_arr))
		{ 
               		if($row["SEEN"]=="Y" )
                	        $action1[$val]["SEEN"] = $row["CALLER_PID"];
                	else
                	        $action2[$val]["NEW"] = $row["CALLER_PID"];
			$caller_pid_arr[] =$val;
		}
        }

       	$sql ="SELECT distinct RECEIVER_PID,SEEN FROM newjs.CALLNOW where CALLER_PID='$profileid' AND CALL_STATUS='R'";
	if(count($caller_pid_arr)>0){
		$str =implode(",",$caller_pid_arr);
		$sql .=" AND RECEIVER_PID NOT IN($str)";
	}
       	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
       	while($row=mysql_fetch_array($result))
       	{
       	        $receiver_pid_arr[] = $row['RECEIVER_PID'];
       	}
	$tot_new=count($action2);
	$tot =count($action1)+count($action2)+count($receiver_pid_arr);
	return array("TOTAL"=>$tot,"NEW"=>$tot_new);
}

/* Gets the total count of the calls of the profile (either Received or Missed or Called Ones)
 * return: array
*/
function getCallnowResultCount($profileid,$self_field,$status)
{
	global $CALL_NOW;
	if(!$CALL_NOW)
		return;

	if($self_field=='CALLER_PID')
		$field ="RECEIVER_PID";
	else
		$field ="CALLER_PID";
	$sql ="SELECT distinct $field,SEEN FROM newjs.CALLNOW where $self_field='$profileid'";
	if($status=='I')	// Only those initiated calls which have been received successfully
		$sql .=" AND CALL_STATUS='R' ORDER BY CALLNOWID DESC";
	else
		$sql .=" AND CALL_STATUS='$status' ORDER BY CALLNOWID DESC";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$caller_pid_arr =array();
        while($row=mysql_fetch_array($result))
        {
                $val = $row[$field];
		if(!in_array("$val",$caller_pid_arr))
		{
                	if($row["SEEN"]=="Y" )
                	        $action1[$val]["SEEN"] = $row["$field"];
                	else
                	        $action2[$val]["NEW"] = $row["$field"];
			$caller_pid_arr[] =$val;
		}
	}
	$action["callnow"]["$status"]["SEEN"]=count($action1);
	$action["callnow"]["$status"]["NEW"] =count($action2);
	$action["callnow"]["$status"]["TOTAL"] =count($action1)+ count($action2);
	return $action;	
}

/* Get the result set of the calls (either Received or Missed or Called ones) 
 * order by descending order
 * return: array   
*/ 
function callnowResultSet($profileid,$select_fields,$self_field,$type='')
{	
	$resultArr =array();
        $sql ="SELECT $select_fields from newjs.CALLNOW WHERE $self_field='$profileid'";

        if($type=='R' || $type=='M')
                $sql .=" AND CALL_STATUS='$type' ORDER BY CALLNOWID desc";
	elseif($type=='I') 
		$sql .=" AND CALL_STATUS='R' ORDER BY CALLNOWID desc";
	elseif($type=='RM')
		$sql .=" AND (CALL_STATUS='R' OR CALL_STATUS='M') ORDER BY CALLNOWID desc";		
	
        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$i=0;
        while($row =mysql_fetch_array($result))
        {
		if($self_field=='CALLER_PID')
			$resultArr[$i]['RECEIVER']      =$row['RECEIVER_PID'];
		elseif($self_field=='RECEIVER_PID')
        		$resultArr[$i]['SENDER'] 	=$row['CALLER_PID'];
		$resultArr[$i]['TIME']		=$row['CALL_DT'];	
		$resultArr[$i]['SEEN'] 		=$row['SEEN'];
		$i++;
        }
	//echo $sql;
	//print_r($resultArr);
	return $resultArr;
} 

// get the Indian Standard Time
if(!function_exists('getIST'))
{
	function getIST($dateTime='')
	{
		if($dateTime=='')
			$dateTime =date("Y-m-d H:i:s");
		$sql="SELECT CONVERT_TZ('$dateTime','SYSTEM','right/Asia/Calcutta')";
		$res=mysql_query_decide($sql);
		if($row=mysql_fetch_array($res))
			$dateTime=$row[0];
		return $dateTime;
	}
}
/* function gets the caller profileid and receiver profileid corresponding to unique callid
 * return array
*/
function getDialcodeContent($dialcode)
{
	$dataArr =array();
	$sql ="select `CALLER`,`RECEIVER` FROM DIALCODE_GENERATE WHERE DIALCODE='$dialcode'";
        $res =mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        $row = mysql_fetch_array($res);
        $caller = $row['CALLER'];
	$receiver =$row['RECEIVER'];
	if($caller && $receiver)
		$dataArr =array("CALLER"=>$caller,"RECEIVER"=>$receiver);
	return $dataArr;	
}

// function manipulates the datetime format ,return array(0=>date,1=>time)
function datetime_format($dateTime)
{
	$dateTimeArr 	=array();
	$dateTime 	=trim($dateTime);
	//$dateTime 	=getIST($dateTime);
	$arr = explode(" ",$dateTime);
	$date =$arr['0'];
	if($date){
		$dateArr 	=explode("-",$date);	
		$dateTimestamp 	= mktime(0,0,0,$dateArr[1],$dateArr[2],$dateArr["0"]);
		$date 		= date("dS M Y",$dateTimestamp);	
	}
        $time =$arr['1'];
	if($time){
        	$timeArr 	=explode(":",$time);
	       	$Timestamp 	= mktime($timeArr[0],$timeArr[1],0);
        	$time 		= date("g.i A",$Timestamp);
	}
	
	$dateTimeArr 	= array("$date","$time");
	return $dateTimeArr;
}

// function check the indian phone number
function isIndianPhoneNumber($isd)
{
	$isd = trim($isd);
	if($isd =='91' || $isd=='+91')
		return true;
	return false;
}

// function check the phone verification status
function phoneVerificationCheck($profileid,$mobile)
{
	$phoneValid = getPhoneValidity($profileid);
	if($phoneValid)
		return true;
	else{
		$sql_mob="SELECT count(*) as COUNT  FROM newjs.MOBILE_VERIFICATION_SMS  WHERE MOBILE IN ('0$mobile','+91$mobile','91$mobile','$mobile')";
		$res =mysql_query_decide($sql_mob) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_mob,"ShowErrTemplate");
		$row = mysql_fetch_array($res);
	        $count = $row['COUNT'];
	        if($count >0)
        	        return true;
	}
}	return false;

// function checks the preffered call timing set by the user 
function CallTimingCheck($startTime="",$endTime="")
{
	$dateTime =getIST();
	$dateTimeArr =explode(" ",$dateTime);
	$dateArr =explode("-",$dateTimeArr[0]);
	$timeArr =explode(":",$dateTimeArr[1]);
	$currentTime = mktime($timeArr[0],$timeArr[1],0,$dateArr[1],$dateArr[2],$dateArr[0]);
        $addTime =12;

	// start time
	if($startTime){
        	$startTimeArr =explode(" ",$startTime);
        	if($startTimeArr[1] =='PM' || $startTimeArr[1] =='pm')
        	        $startTime = $addTime+$startTimeArr[0];
        	else
        	        $startTime = $startTimeArr[0];
        	$mktimeStart = mktime($startTime,0,0,date("m"),date("d"),date("Y"));
	}
	// end time
	if($endTime){
        	$endTimeArr =explode(" ",$endTime);
        	if($endTimeArr[1] =='PM' || $startTimeArr[1] =='pm')
        	        $endTime = $addTime+$endTimeArr[0];
        	else
        	        $endTime = $endTimeArr[0];
        	$mktimeEnd = mktime($endTime,0,0,date("m"),date("d"),date("Y"));
	}
	if(($currentTime>=$mktimeStart && $currentTime<=$mktimeEnd) || ($mktimeStart==$mktimeEnd))
                return true;
        return false;
}

// function returns the call records(call between the caller and receiver)
function getCallnow($caller_profileid,$receiver_profileid,$status="")
{
	$dataArr =array();
	$sql ="SELECT `CALL_DT` from newjs.CALLNOW WHERE CALLER_PID='$caller_profileid' AND RECEIVER_PID='$receiver_profileid'";
	if($status)
		$sql .=" AND CALL_STATUS='$status' ORDER BY CALLNOWID desc";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	while($row =mysql_fetch_array($result))	
	{
		$dataArr[] =$row['CALL_DT'];	
	}
	return $dataArr;
	//$dataArr[]= "2009-07-13 15:42:23";
	//return $dataArr;
}

function getServiceActivated($profileID)
{
	$sql ="SELECT `ACTIVATED_ON` FROM billing.SERVICE_STATUS WHERE PROFILEID='$profileID' AND ACTIVATED='Y' AND SERVEFOR='F'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row =mysql_fetch_array($result);
	$date =$row['ACTIVATED_ON'];	
	return $date;
}

function getQuotaDates($profileID)
{
	$todaysDateTime =getIST();
	$todaysDateArr = explode(" ",$todaysDateTime);
	$todaysDate =$todaysDateArr[0];
	
	$sql ="SELECT ACTIVE_ON FROM newjs.CALLNOW_QUOTA where PROFILEID='$profileID'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row =mysql_fetch_array($result);
	$startDate =$row['ACTIVE_ON'];

	if($startDate==''){
		$startDate =getServiceActivated($profileID);
                $sql ="REPLACE into newjs.CALLNOW_QUOTA(`PROFILEID`,`ACTIVE_ON`) value('$profileID','$startDate')";
                mysql_query_decide($sql);
	}
	$endDate =date("Y-m-d", JSstrToTime("$startDate +30 days"));

	// Set Start Date when Caller Monthly Quota expires
	if($todaysDate >=$endDate){
		$todaysDateArr =explode("-",$todaysDate);
		$todaysMonth =$todaysDateArr[1];
		$startDateArr =explode("-",$startDate);
		$startMonth =$startDateArr[1];
		if($todaysMonth>$startMonth)
			$days =($todaysMonth-$startMonth)*30;
		else
			$days =30;
		$setStartDate=date("Y-m-d", JSstrToTime("$startDate +$days days"));
		$sql ="UPDATE newjs.CALLNOW_QUOTA SET ACTIVE_ON='$setStartDate' WHERE PROFILEID='$profileID'";
		mysql_query_decide($sql); 
		$startDate =$setStartDate;
		$endDate =date("Y-m-d", JSstrToTime("$startDate +30 days"));	
	}
	// End Set Start Date
	return array("START_DATE"=>$startDate,"END_DATE"=>$endDate);
}

/* function checks the quota limit of the user
 * Quota Limits: (Daily-> Caller-20),(Daily-> Receiver-10), (Monthly-> Caller-500) 
*/
function callnowQuotaStatus($profileID='',$userType,$quotaType,&$endDate)
{
	if($profileID=='')
		return false;
	$caller_dailyQuota  ='20';
	$receiver_DailyQuota ='10';
	$caller_MonthlyQuota ='500';

	if($userType=='RECEIVER'){
		$field='RECEIVER_PID';
	}
	else if($userType=='CALLER'){
		$field='CALLER_PID';
	}
        $todaysDateTime =getIST();
        $todaysDateArr = explode(" ",$todaysDateTime);
	$todaysDate = $todaysDateArr[0];
	if($quotaType=='M'){
		$quotaDatesArr  =getQuotaDates($profileID);
		$startDate 	=$quotaDatesArr['START_DATE'];
		$endDate 	=$quotaDatesArr['END_DATE'];
		$startDate	=date("Y-m-d", JSstrToTime("$startDate -1 days"));
		$sql ="SELECT COUNT(*) as CNT from newjs.CALLNOW where `$field`='$profileID' AND `CALL_STATUS`='R' AND `CALL_DT` BETWEEN '$startDate' AND '$endDate'"; 
	}
	elseif($quotaType=='D'){
		$sql ="SELECT COUNT(*) as CNT from newjs.CALLNOW where `$field`='$profileID' AND `CALL_STATUS`='R' AND `CALL_DT` like '$todaysDate%'";
	}
        $res =mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        $row = mysql_fetch_array($res);
        $count = $row['CNT'];
	
	if($userType=='RECEIVER' && $count<$receiver_DailyQuota)
		return true;
	if($userType=='CALLER'){
		if($quotaType=='D' && $count<$caller_dailyQuota)
			return true;
		elseif($quotaType=='M' && $count<$caller_MonthlyQuota)
			return true;
	}
	return false;
}


// function record Callnow status between the caller and the receiver
/* callFlag status
 * I: Call initiated from the caller to the receiver
 * R: Call has been patched between the caller and receiver and call is received
 * M: Call missed(receiver phone is busy or call could not be patched between caller and receiver due to technical prob.) 
 */
function recordCallnowStatus($caller_profileid,$receiver_profileid,$caller_phone,$callFlag,$callid="")
{
        $time =getIST();
	if($callFlag =='I'){
		$sql ="INSERT INTO newjs.CALLNOW(`CALLER_PID`,`RECEIVER_PID`,`CALLER_PHONE`,`CALL_DT`,`CALL_STATUS`) value('$caller_profileid','$receiver_profileid','$caller_phone','$time','$callFlag')";
		$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$callID = mysql_insert_id_js();
		if($callID)
			return $callID;
	}
	else{
		$sql ="update newjs.CALLNOW set CALL_STATUS='$callFlag' WHERE CALLER_PID='$caller_profileid' AND RECEIVER_PID='$receiver_profileid' AND CALL_STATUS='I' AND CALLNOWID='$callid'";
		mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	}
	return;
}

// function to check profile is blocked/ignored  
function isProfileIgnored($profileid, $ignored_profileid) 
{
	$COUNT =array();
	$sql="SELECT IGNORED_PROFILEID FROM newjs.IGNORE_PROFILE WHERE PROFILEID='$profileid' AND IGNORED_PROFILEID IN($ignored_profileid)";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	while($myrow=mysql_fetch_array($result))
	{
		$COUNT[$myrow[0]]=$myrow[0];
	}
	if(count($COUNT) >0)
		return true;
	return false;		
}

// function check the conditions required for the caller to access callnow feature 
function profile_Callable($profileDataArr,&$error_msg)
{
	$caller_gender		=$profileDataArr['viewer']["GENDER"];
	$caller_activated     	=$profileDataArr['viewer']["ACTIVATED"];
	$caller_incomplete     	=$profileDataArr['viewer']["INCOMPLETE"];	
	$receiver_gender	=$profileDataArr['viewed']["GENDER"];
	$receiver_activated 	=$profileDataArr['viewed']["ACTIVATED"];

	//first check : male -> female or female -> male only
	if($caller_gender == $receiver_gender){
		$error_msg="SAME_GENDER";
		return false;
	}
	//second check : Is the profile of the receiver activated
	if($receiver_activated !='Y')
	{
		if($receiver_activated =='H')		// check for direct ivr call only
			$error_msg="RECEIVER_HIDDEN";	
                elseif($receiver_activated =='D')	// check for direct ivr call only
                	$error_msg="RECEIVER_DELETED";
                elseif($receiver_activated =='N'|| $receiver_activated =='U'|| $receiver_activated =='P')
                	$error_msg="RECEIVER_SCREENING";
                return false;
	}
	//third check : Is the profile of the caller activated/incomplete
        if($caller_activated !='Y')
        {
	        if($caller_activated=='H')
        	        $error_msg="CALLER_HIDDEN";
                elseif($caller_activated=='D')	// check for ivr call only  
                	$error_msg="CALLER_DELETED";
                elseif($caller_activated=='N' || $caller_activated =='U'|| $caller_activated =='P')
                	$error_msg="CALLER_SCREENING";
                return false;
	}
	if($caller_incomplete=='Y'){
		$error_msg="CALLER_INCOMPLETE";	
		return false;
	}
        return true;
}

// function check the privacy filters applied by the receiver for the caller 
function filtersCheck($myprofileid,$hisprofileid)
{
	$filterPass = check_privacy_filtered1($myprofileid,$hisprofileid);	
	if($filterPass)
		return true;
	return false;
}

/* function returns the profile data of the user
 * returns: array
 * parameter: string 
*/
function getProfileData($caller='',$receiver='')
{
	$dataArr =array();
	$profileidStr ='';
	if($caller && $receiver){
		$profileidStr =$caller.",".$receiver;	
		$profileidStr =trim($profileidStr);
	}
	else
		return false;

	$sql ="select PROFILEID,GENDER,INCOMPLETE,ACTIVATED,ISD,PRIVACY,TIME_TO_CALL_START,TIME_TO_CALL_END,PHONE_MOB,PHONE_RES,STD,SUBSCRIPTION,SHOWPHONE_MOB,SHOWPHONE_RES from newjs.JPROFILE where  activatedKey=1 and PROFILEID in($profileidStr)";
        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        while($myrow=mysql_fetch_array($result))
	{	
		$profileid =$myrow['PROFILEID'];
		if($profileid == $caller)
			$type ='viewer';
		elseif($profileid == $receiver)
			$type ='viewed';

		$dataArr[$type]['PROFILEID']            = $myrow['PROFILEID'];
		$dataArr[$type]['GENDER'] 		= $myrow['GENDER'];					
		$dataArr[$type]['ISD']			= $myrow['ISD'];
		$dataArr[$type]['PRIVACY']		= $myrow['PRIVACY'];
		$dataArr[$type]['TIME_TO_CALL_START'] 	= $myrow['TIME_TO_CALL_START'];
		$dataArr[$type]['TIME_TO_CALL_END']   	= $myrow['TIME_TO_CALL_END'];
		$dataArr[$type]['PHONE_MOB']  	   	= $myrow['PHONE_MOB'];
		$dataArr[$type]['PHONE_RES']            = $myrow['PHONE_RES'];
		$dataArr[$type]['STD'] 		        = $myrow['STD'];
		$dataArr[$type]['SUBSCRIPTION']       	= $myrow['SUBSCRIPTION'];
		$dataArr[$type]['INCOMPLETE']           = $myrow['INCOMPLETE'];
		$dataArr[$type]['ACTIVATED']            = $myrow['ACTIVATED'];
		$dataArr[$type]['SHOWPHONE_MOB']        = $myrow['SHOWPHONE_MOB'];
		$dataArr[$type]['SHOWPHONE_RES']        = $myrow['SHOWPHONE_RES'];
	}
	return $dataArr;
}

/* function called by JS application to meet conditions to access Callnow feature
 * function called by the IVR to meet conditions for making call on phone(mobile/landline)
 * return true/false true:all the required conditions are met, false:required conditions fails with the error message
 * parameters accept : $caller_profileid: caller profileid, $receiver_profileid:receiver_profileid, $err: contains message returned, $JSapp: variable set when function called from JS application  
*/
function makeCall_status($caller_profileid,$receiver_profileid,&$err,$JSapp='',&$profileDataArr)
{
        // testcase 
        //$err = "CALLED_TWICE";
        //return false;
        //return true;

	global $jprofile_result;
	$profileDataArr =array();

	// Quota limits check for the Caller and Receiver (daily limit and overall limit)
	$caller_callnowQuotaDaily =callnowQuotaStatus($caller_profileid,'CALLER','D',$quotaStartDate);
	if(!$caller_callnowQuotaDaily){
		$err ="CALLER_DAILY_QUOTA_EXPIRED";
		return false;
	}
	$caller_callnowQuotaMonthly =callnowQuotaStatus($caller_profileid,'CALLER','M',$quotaStartDate);
	if(!$caller_callnowQuotaMonthly){
		$profileDataArr['viewer']['QUOTA_START_DATE']="$quotaStartDate";
		$err ="CALLER_MONTHLY_QUOTA_EXPIRED";
		return false;
	}
	$receiver_callnowQuotaDaily =callnowQuotaStatus($receiver_profileid,'RECEIVER','D',$quotaStartDate);
	if(!$receiver_callnowQuotaDaily){
		$err ="RECEIVER_DAILY_QUOTA_EXPIRED";
		return false;
	}
	// Quota limit check ends
	$newSearch=true;
	if(isset($jprofile_result)){
		$profileDataArr = $jprofile_result;
		$viewer_profileid = $profileDataArr['viewer']['PROFILEID'];
		$viewed_profileid = $profileDataArr['viewed']['PROFILEID'];
		if($caller_profileid == $viewer_profileid && $receiver_profileid == $viewed_profileid)
			$newSearch = false;
	}
	if($newSearch){
		$profileDataArr = getProfileData($caller_profileid,$receiver_profileid);
	}

	// Check profileid of caller and receiver exist in database
	if($profileDataArr['viewer']['PROFILEID']=='' || $profileDataArr['viewed']['PROFILEID']==''){
		$err ="ERROR";
		return false;	
	}
	
	// checks callnow accessibility (receiver is accessible through callnow feature by the caller) 
	$error_msg ="";
	$callableStatus = profile_Callable($profileDataArr,$error_msg);
	if(!$callableStatus){
		$err = trim($error_msg);
		return false;		
	}

	// filters & privacy check applied by call Receiver
	if($newSearch){
		$receiverPrivacy = $profileDataArr['viewed']['PRIVACY'];
		if($receiverPrivacy =='F'){
			$filtered = filtersCheck($caller_profileid,$receiver_profileid);	
			if(!$filtered){
				$err = "ERROR_FILTER";
				return false;
			}
		}		
	}else{
		global $IVR_filtersCheck;
		if($IVR_filtersCheck){
			$err = "ERROR_FILTER";
			$IVR_filtersCheck ='';
			return false;	
		}	
	}

	// Ignored profile status of the Caller set by Receiver
	$profileIgnored = isProfileIgnored($receiver_profileid,$caller_profileid);
	if($profileIgnored){
		$err ="ERROR_CALLER_BLOCKED";
		return false;
	}

        // Indian phone number check for Caller 
        $callerISD = $profileDataArr['viewer']['ISD'];
        $callerIndainNo = isIndianPhoneNumber($callerISD);
        if(!$callerIndainNo){
                $err ="CALLER_NRI";
                return false;
        }       

	// Indian phone number check for Receiver
	$receiverISD = $profileDataArr['viewed']['ISD'];
	$receiverIndainNo = isIndianPhoneNumber($receiverISD);
	if(!$receiverIndainNo){
		$err ="ERROR_NOT_INDIAN_PHONE";
		return false;
	}	
/*
	// phone verification check for the Caller
	// Only Indian numbers gets verified 
	$mobile = $profileDataArr['viewer']['PHONE_MOB'];
	$phoneVerify = phoneVerificationCheck($caller_profileid,$mobile);
	if(!$phoneVerify){
		$err ="ERROR_CALLER_UNVERIFIED";
		return false;
	}
*/
	// Membership payment check for the Caller 
	$my_rights=explode(",",$profileDataArr["viewer"]["SUBSCRIPTION"]);
	if(!in_array("F",$my_rights)){
		$err ="ERROR_UNPAID_MEMBER";
		return false;
	}
	
	// Total number of successful calls made by the Caller to the Receiver   
	$callnowData = getCallnow($caller_profileid,$receiver_profileid,'R');	
	$count 	      = count($callnowData);
	if($count >=2){
		$err ="CALLED_TWICE";
		return false;
	}
	
	// call timing filter set by the call Receiver
	$startTime =$profileDataArr['viewed']['TIME_TO_CALL_START'];
	$endTime =$profileDataArr['viewed']['TIME_TO_CALL_END']; 
	if($startTime && $endTime){
		$callTimeCheck = CallTimingCheck($startTime,$endTime);		
		if(!$callTimeCheck){
			$err ="ERROR_CALLTIME";
			return false;
		}
	}

	// Message warnng of only 1 Call made by Caller (Warning message, case : JS Application)
        if($JSapp){
                if($count ==1){
                        $err ="CALLED_ONCE";
                        return false;
                }
        }
/*
        // phone verification check for the call Receiver (Warning message, case : JS Application)
        if($JSapp){
                $mobile = $profileDataArr['viewed']['PHONE_MOB'];
                $phoneVerify = phoneVerificationCheck($receiver_profileid,$mobile);
                if(!$phoneVerify){
                        $err ="ERROR_RECEIVER_UNVERIFIED";
                        return false;
                }
        }
*/
	/* all conditions are satisfied to make Call or access the callnow feature */
	return true;
	/* end */	
}
/* makeCall function End */ 


?>
