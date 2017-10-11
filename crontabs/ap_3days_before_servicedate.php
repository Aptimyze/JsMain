<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/***************************************************************************************************************
* FILE NAME     : ap_3days_before_servicedate.php 
* DESCRIPTION   : Cron script.
*****************************************************************************************************************/

$flag_using_php5=1;
include "connect.inc";
$db=connect_db();

$todays_date =date("Y-m-d");
$service_date =date("Y-m-d",strtotime("$todays_date +3 days"));

$sql ="SELECT s.PROFILEID,s.NEXT_SERVICE_DATE,p.SE from Assisted_Product.AP_PROFILE_INFO AS p,Assisted_Product.AP_SERVICE_TABLE AS s where p.STATUS='LIVE' AND s.SERVICED!='Y' AND s.NEXT_SERVICE_DATE >'$todays_date' AND p.PROFILEID=s.PROFILEID";
$result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");		
$i=0;
while($row=mysql_fetch_array($result))
{
	$profileid 		=$row['PROFILEID'];
	$se 		       	=$row['SE'];
	$next_service_date 	=$row['NEXT_SERVICE_DATE'];
	if($next_service_date ==$service_date)
	{	
		$resultArr_se[$i]	=$se;
		$resultArr1[$i][$se] 	=$profileid;
		$resultArr_pid[$i] 	=$profileid;
	}
	$i++;
}

if($resultArr_se)
	$resultArr_se =array_unique($resultArr_se);
if($resultArr_pid)
	$resultArr_pid =array_unique($resultArr_pid);
$usernameArr =getUsername($resultArr_pid);
$emailArr =getEmail($resultArr_se);

$se_nameArr =array();
if($resultArr_se){
	foreach($resultArr_se as $key=>$val)
	{
		$se_name =$val;
		$profileidArr =array();
		foreach($resultArr1 as $key1=>$val1){
			//if($se_name ==$val1)
			$profileidArr[] =$resultArr1[$key1][$se_name];	
		}
		$profileidStr =implode(",",$profileidArr);
		$se_nameArr[$se_name] =$profileidStr;
	}
}
if($se_nameArr){
	foreach($se_nameArr as $key=>$val)
	{
		$se_name =$key;
		$profileidStr =$val;
		$se_email =$emailArr[$se_name];	
	
		$bodyStr="";
		$profileids =explode(",",$profileidStr);
		$allUsernames ="";
		foreach($profileids as $key=>$val)
		{
		        $username =$usernameArr["$val"];
		        $bodyStr .="<br>".$username." <a href='$SITE_URL/jsadmin/ap_auto_login.php?username=$se_name&profileid=$val&auto=shortlist'> See the profiles shortlist </a> ";
			if($allUsernames)
				$allUsernames .=",".$username;
			else
				$allUsernames .=$username;
		}
		sendMail($se_name,$se_email,$service_date,$bodyStr,$allUsernames);
	}
}


// function to get the usernames of the profiles
function getUsername($profileidArr)
{
	if(!is_array($profileidArr))
		return;	
	$profileidStr =implode(",",$profileidArr);
	$sql ="SELECT `USERNAME`,`PROFILEID` from newjs.JPROFILE where PROFILEID IN($profileidStr)";
	$result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	while($row=mysql_fetch_array($result))
        {
                $profileid      	=$row['PROFILEID'];
                $usernameArr[$profileid]=$row['USERNAME'];
        }
        return $usernameArr;
}

// function to get the email of the SE (sales executive)
function getEmail($usernameArr="")
{
	if(!is_array($usernameArr))
		return ;
	$usernameStr =implode("','",$usernameArr);
	$sql ="SELECT `USERNAME`,`EMAIL` from jsadmin.PSWRDS where USERNAME IN('$usernameStr')";
	$result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	while($row=mysql_fetch_array($result))
	{
        	$username      		=$row['USERNAME'];
		$emailArr[$username]	=$row['EMAIL'];
	}
	return $emailArr;
}

// function to send the email to the SE(sales executive)
function sendMail($se_name,$to_email,$date,$bodyStr="",$allUsernames)
{
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
	$from 	="matchpoint@jeevansathi.com";
	$subject=" 3 days to service delivery for $allUsernames";
	$body 	=" Dear $se_name,<br>
		The following profiles are to be serviced on $date. Should you miss the deadline of $date, all the profiles in the shortlist will go to the user. <br>";
	$body .=$bodyStr;
	send_email($to_email,$body,$subject,$from);
}

?>
