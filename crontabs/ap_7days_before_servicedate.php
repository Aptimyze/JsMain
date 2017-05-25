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
$seArr=array();
$profileArr=array();
$serviceDateStamp=mktime(0,0,0,date("m"),date("d")+7,date("Y"));
$serviceDate=date("d-m-Y",$serviceDateStamp);

$sql ="SELECT s.PROFILEID,p.SE from Assisted_Product.AP_PROFILE_INFO AS p,Assisted_Product.AP_SERVICE_TABLE AS s where p.STATUS='LIVE' AND s.SERVICED!='Y' AND s.NEXT_SERVICE_DATE=DATE_ADD(CURDATE(),INTERVAL 7 DAY) AND p.PROFILEID=s.PROFILEID";
$result=mysql_query($sql) or die("Error while fetching profiles    ".mysql_error());
while($row=mysql_fetch_array($result))
{
	$profileid=$row['PROFILEID'];
	$se=$row['SE'];
	$resultArr[$se][]=$profileid;
	if(!in_array($se,$seArr))
		$seArr[]=$se;
	if(!in_array($profileid,$profileArr))
		$profileArr[]=$profileid;
}
$usernameArr =getUsername($profileArr);
$emailArr =getEmail($seArr);

$se_nameArr =array();
if($resultArr){
	foreach($resultArr as $key=>$val)
	{
		$se_name =$key;
		$profileidArr =array();
		foreach($resultArr as $key1=>$val1){
			//if($se_name ==$val1)
			$profileidArr[] =$resultArr1[$key1][$se_name];	
		}
		$profileidStr =implode(",",$profileidArr);
		$se_nameArr[$se_name] =$profileidStr;
	}
}
if($resultArr){
	foreach($resultArr as $key=>$val)
	{
		$se_name =$key;
		$se_email =$emailArr[$se_name];	
		$bodyStr="";
		$allUsernames ="";
		foreach($resultArr[$key] as $key2=>$val2)
		{
		        $username =$usernameArr["$val2"];
		        $bodyStr .="<br>".$username." <a href='$SITE_URL/jsadmin/ap_auto_login.php?username=$se_name&profileid=$val2&auto=profile'> Detailed profile page </a> ";
			if($allUsernames)
				$allUsernames .=",".$username;
			else
				$allUsernames .=$username;
		}
		sendMail($se_name,$se_email,$serviceDate,$bodyStr,$allUsernames);
	}
}


// function to get the usernames of the profiles
function getUsername($profileidArr)
{
	if(!is_array($profileidArr) || !count($profileidArr))
		return;	
	$profileidStr =implode(",",$profileidArr);
	$sql ="SELECT `USERNAME`,`PROFILEID` from newjs.JPROFILE where PROFILEID IN($profileidStr)";
	$result=mysql_query($sql) or die("Error while fetching username  ".mysql_error());
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
	if(!is_array($usernameArr) || !count($usernameArr))
		return ;
	$usernameStr =implode("','",$usernameArr);
	$sql ="SELECT `USERNAME`,`EMAIL` from jsadmin.PSWRDS where USERNAME IN('$usernameStr')";
	$result=mysql_query($sql) or die("Error while fetching email  ".mysql_error());
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
	$subject=" 7 days to go for service. Are there enough profiles in the shortlist of $allUsernames?";
	$body 	=" Dear $se_name,<br>
		Users $allUsernames is/are to be serviced on $date. Should you feel that the response is not enough you can raise a profile ad request by clicking on the button in the page here.<br>";
	$body .=$bodyStr;
	send_email($to_email,$body,$subject,$from);
}
mail('nikhil.dhiman@jeevansathi.com','ap 7 before service date ',date("y-m-d"));

?>
