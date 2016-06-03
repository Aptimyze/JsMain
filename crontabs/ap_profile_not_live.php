<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/***************************************************************************************************************
* FILE NAME     : ap_profile_not_live.php 
* DESCRIPTION   : Cron script, this script sends the email to the manager if the profile is not live even after 3 days of 
		: registration				
*****************************************************************************************************************/

	$flag_using_php5=1;
	include "connect.inc";
	$db=connect_db();

	$todays_date =date("Y-m-d");
	$source ='onoffreg';
	$day_3day_before =date("Y-m-d",strtotime("$todays_date -3 days"));

	$sql ="SELECT r.PROFILEID,r.EXECUTIVE FROM newjs.OFFLINE_REGISTRATION AS r LEFT JOIN Assisted_Product.AP_PROFILE_INFO AS p ON r.PROFILEID=p.PROFILEID where (p.PROFILEID IS NULL OR p.STATUS!='LIVE') AND r.SOURCE='$source' AND ((r.DATE like '%$day_3day_before%') OR (r.DATE<='$day_3day_before'))";
	$result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");		
	$i=0;
	while($row=mysql_fetch_assoc($result))
	{
		$profileid =$row['PROFILEID'];
		$profileidArr[$i] =$row['PROFILEID'];
		$executiveArr[$profileid] =$row['EXECUTIVE'];
		$i++;	
	}

	if($profileidArr)
		$profileidArr =array_unique($profileidArr);
	$usernameArr =getUsername($profileidArr);
	$managerArr =getManagerEmail($executiveArr);	

	$bodyArr =array();
	$j=0;
	if($profileidArr){
		foreach($profileidArr as $key=>$val)
		{
			$executive = $executiveArr[$val];
			$manager =$managerArr[$executive];
			$content ="<a href='$SITE_URL/jsadmin/ap_auto_login.php?username=$executive&profileid=$val&auto=dpp'> ".$usernameArr[$val]." registered by ".$executiveArr[$val]." </a>";
			if($content){
				if(array_key_exists($manager,$bodyArr))
				{
					$content1 = $bodyArr[$manager];
					$content1 .=", ".$content;
					$bodyArr[$manager] =$content1;
				}
				else
					$bodyArr[$manager] =$content;
			}
		}
	}	

	if($bodyArr){
		foreach($bodyArr as $key=>$val)
		{
			// send email to manager
			sendMail($key, $val);	
		}
	}

	// function to get the Email of the Manager
	function getManagerEmail($executiveArr)
	{
		if(!is_array($executiveArr))
			return;
		$executiveArr =array_unique($executiveArr);
		$executiveStr =implode("','",$executiveArr);

        	$sql ="SELECT `HEAD_ID`,`USERNAME` FROM jsadmin.PSWRDS WHERE `USERNAME` IN('$executiveStr')";
        	$res =mysql_query($sql) or logError("Erro while getting records from jsadmin.PSWRDS table ");
		$i=0;
		while($row=mysql_fetch_assoc($res))
		{
        		$headID =$row['HEAD_ID'];
			if($headID){
				$username =$row['USERNAME'];

        			$sql ="SELECT `EMAIL` FROM jsadmin.PSWRDS WHERE `EMP_ID`='$headID'";
        			$res =mysql_query($sql) or logError("Error while inserting info in AD_REQUEST_HISTORY ");                		     $row=mysql_fetch_assoc($res);
        			$email =$row['EMAIL'];
				$emailArr[$username] =$email;
			}
		}
		return $emailArr;
	}

	// function to get the Usernames of profiles 
	function getUsername($profileidArr)
	{
		if(!is_array($profileidArr))
			return;
        	$profileidStr =implode(",",$profileidArr);
        	$sql ="SELECT `USERNAME`,`PROFILEID` from newjs.JPROFILE where PROFILEID IN($profileidStr)";
        	$result=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        	while($row=mysql_fetch_array($result))        
		{
                	$profileid      		=$row['PROFILEID'];
                	$usernameArr["$profileid"]	=$row['USERNAME'];
        	}
        	return $usernameArr;
	}

	// function to send the mail to the Manager 
	function sendMail($to,$bodyStr="")
	{
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
        	$from 	="matchpoint@jeevansathi.com";
        	$subject="Profile NOT LIVE after 3 days";
        	$body 	=" Dear Manager, <br>
        		The profiles ".$bodyStr." are not live 3 or more days after they have been registered in the system.";
		send_email($to,$body,$subject,$from);
	}

?>
