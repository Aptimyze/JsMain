<?php

/************************************************************************************************************************
*    FILENAME           : add_bookmark.php
*    INCLUDED           : connect.inc,contact.inc
*    DESCRIPTION        : bookmark profiles by user.
*    MODIFIED           : For Revamp
*    MODIFIED By        : Lavesh Rawat
***********************************************************************************************************************/

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it
	
include("connect.inc");
include("contact.inc");

connect_db();

$data=authenticated($checksum);

/*****************Portion of Code added for display of Banners*******************************/
$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("bms_topright",18);
$smarty->assign("bms_right",28);
$smarty->assign("bms_bottom",19);
$smarty->assign("bms_left",24);
$smarty->assign("bms_new_win",32);
/***********************End of Portion of Code*****************************************/

connect_db();
$date=date("Y-m-d");

if(isset($data))
{
	$profileid=$data["PROFILEID"];
	
	$chkprofilechecksum=explode("i",$profilechecksum);

        if(!is_numeric($chkprofilechecksum[1]) || ($chkprofilechecksum[1]==''))
        {
                $http_msg = "User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self : ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n";
                $http_msg .= implode(",",$_POST);
                mail('lavesh.rawat@jeevansathi.com','bookmark_lav - is invalid',$http_msg);
        }

	$sql="SELECT USERNAME,GENDER from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$chkprofilechecksum[1]'";
        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$myrow=mysql_fetch_array($result);
	
	if($myrow["GENDER"]!=$data["GENDER"])
	{
		$ymessage="You have successfully added 1 profile to your shortlist : ".$myrow["USERNAME"];

		if($chkprofilechecksum[0]==md5($chkprofilechecksum[1]))
		{
			$sql="REPLACE INTO BOOKMARKS VALUES('$profileid','$chkprofilechecksum[1]','$date')";
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","$sql","ShowErrTemplate");
		}
	}
	else
		$nmessage="Sorry! You cannot add 1 profile to your shortlist as your gender is the same as that of receiver : ".$myrow["USERNAME"];

	if($scriptname=='/simcontacts_search.php')
		$from_index=1;
	header("Location: ".$SITE_URL."/P/$scriptname?checksum=$checksum&redirect=1&ymessage=$ymessage&nmessage=$nmessage&action=B&j=$pageno&contact=$contact&searchorder=$searchorder&searchchecksum=$searchchecksum&label_select_no=$label_select_no&from_index=$from_index");		

}
else
{
	TimedOut();
}

// flush the buffer
if($zipIt)
	ob_end_flush();
		
?>
