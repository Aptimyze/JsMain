<?php
/************************************************************************************************************************
*    FILENAME           : ignore_profile.php
*    INCLUDED           : connect.inc,contact.inc
*    DESCRIPTION        : ignore profiles by user.
*    Created            : For Revamp
*    Created By         : Lavesh,Gaurav
***********************************************************************************************************************/
//ignore_profile.php
// common include file
include("connect.inc");
include("contact.inc");
// connect to database
$db=connect_db();

/**************************** CODE FOR BMS DISPLAY ***********************************/
$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("bms_topright",18);
$smarty->assign("bms_bottom",19);
$smarty->assign("bms_left",24);
$smarty->assign("bms_right",28);
$smarty->assign("bms_new_win",32);
/*****************************************************************************************/

$data=authenticated($checksum);
if(isset($data))
{
	$smarty->assign("USERNAME",$data["USERNAME"]);

	$chkprofilechecksum=explode("i",$profilechecksum);
	$profileid=$data['PROFILEID'];

	if(!is_numeric($chkprofilechecksum[1]) || ($chkprofilechecksum[1]==''))
	{
		$http_msg = "User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self : ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n";
		$http_msg .= implode(",",$_POST);
		mail('lavesh.rawat@jeevansathi.com','ignore_lav - is invalid',$http_msg);
	}
	//query to find the name from profileid from JPROFILE table
	$sql_name="SELECT USERNAME,GENDER FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$chkprofilechecksum[1]'";
	$result_name=mysql_query_decide($sql_name) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_name,"ShowErrTemplate");
	$myrow_name=mysql_fetch_array($result_name);

	$username=$myrow_name['USERNAME'];
	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("PROFILECHECKSUM",$profilechecksum);
	$gender=$myrow_name['GENDER'];
				
	if($gender==$data['GENDER'])
	{
		$nmessage="Sorry! You cannot ignore $username as your gender is the same as that of $username.";
	}
	else
	{
		$flag_show_template=ignore_profile_common($profileid,$chkprofilechecksum[1]);
		if($flag_show_template=='E')
		{
			if($username)
				$nmessage="Profile ($username) is already ignored by you.";
			else
				$nmessage="Selected profile is already ignored by you.";
		}
		else
		{
			if($username)
				$ymessage="You have successfully ignored 1 Profile : ".$username;
			else
				$ymessage="Selected profile is successfully ignored by you";
		}
	}
	if($scriptname=='simcontacts_search.php')
                $from_index=1;
                                                                                                                             
        header("Location: ".$SITE_URL."/P/$scriptname?checksum=$checksum&redirect=1&nmessage=$nmessage&ymessage=$ymessage&action=IG&j=$pageno&contact=$contact&searchorder=$searchorder&searchchecksum=$searchchecksum&label_select_no=$label_select_no&from_index=$from_index");

		
}	
else	
{
	TimedOut();
}
function ignore_profile_common($profileid,$chkprofilechecksum)
{
	$sql_chk="SELECT count(*) as cnt FROM IGNORE_PROFILE WHERE PROFILEID='$profileid' and IGNORED_PROFILEID='$chkprofilechecksum'";
	$result_chk=mysql_query_decide($sql_chk) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_chk,"ShowErrTemplate");//or die($sql_chk.mysql_error_js());
	$myrow_chk=mysql_fetch_array($result_chk);
	if($myrow_chk['cnt']==1)
	{
		//error mssg that you have already ignored this profile
		$flag_show_template='E';
	}
	else
	{
		//insert into IGNORE_PROFILE
		$sql_insert="INSERT INTO IGNORE_PROFILE(PROFILEID,IGNORED_PROFILEID,DATE) VALUES ('$profileid','$chkprofilechecksum',now())";
		mysql_query_decide($sql_insert) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_insert,"ShowErrTemplate");//or die($sql_insert.mysql_error_js());
		// show the template
		$flag_show_template='N';	//Thank you mssg that ur request has been made
	}
return $flag_show_template;
}

?>

