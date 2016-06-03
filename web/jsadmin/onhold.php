<?php
/**************************************************************************************************************************
FILE 		: onhold.php
DESCRIPTION	: This script is used to put the profile on hold for new/edit screening module.
CREATED BY 	: Sriram Viswanathan
DATE		: May 29th 2007. 
**************************************************************************************************************************/
include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
if(authenticated($cid))
{
	$is_error = 0;
	if($reason == "Other" && trim($oth_reason) == "")
	{
		$smarty->assign("CHECK_OTH_REASON","Y");
	}

	if($is_error > 0)
	{
		$smarty->assign("open_fields",$open_fields);
		$smarty->assign("cid",$cid);
		$smarty->assign("pid",$pid);
		$smarty->assign("val",$val);
		$smarty->assign("user",$user);
		$smarty->assign("reason",$reason);
		$smarty->display("onhold.htm");
	}
	else
	{
		//array of open fields.
		$OPEN_FIELDS = explode(",",$open_fields);
		$count_screen = count($OPEN_FIELDS);

		//fetching details to send email notification to user, when profile is put on hold.
		$sql_jp = "SELECT USERNAME,EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
		$res_jp = mysql_query_decide($sql_jp) or die("$sql_jp".mysql_error_js());
		$row_jp = mysql_fetch_array($res_jp);

		$username = $row_jp["USERNAME"];
		$to = $row_jp['EMAIL'];

		if($reason == "M")
		{
			$subject = "Clarification required regarding your Marital Status";

			$mail_msg .= "Dear $username,\nThis is with regard to the marital status mentioned by you in the registration form and the information provided by you in the open fields is contradictory to each other. Hence, we are unable to screen your profile and make it LIVE on the site. The profile is on hold now. We need further clarification from you to authenticate your marital status and make your profile LIVE.\n\nPlease email us the correct information so that we can make your profile LIVE.\n\nWe wish you success in your search for partner.\nRegards,\nTeam Jeevansathi";

			$reason = $subject."\n".$mail_msg;
		}
		elseif($reason == "G")
		{
			$subject = "Clarification required regarding your Gender";
			$mail_msg = "Dear $username,\nThis is with regard to the Gender mentioned by you in the registration form and the information provided by you in the open fields is contradictory to each other. Hence, we are unable to screen your profile and make it LIVE on the site. The profile is on hold now. We need further clarification from you to authenticate your Gender and make your profile LIVE.\n\nPlease email us the correct information so that we can make your profile LIVE.\n\nWe wish you success in your search for partner.\nRegards,\nTeam Jeevansathi";

			$reason = $subject."\n".$mail_msg;
		}
		elseif($reason == "Other")
		{
			$oth_reason = addslashes(stripslashes($oth_reason));
			$subject = "Clarification required regarding your Profile";
			$mail_msg = "Dear $username,\n$oth_reason";

			$reason = $subject."\n".$mail_msg;
		}

		//inserting into database to keep track of number of profiles put on hold.
		$sql_reason = "INSERT INTO jsadmin.ON_HOLD_PROFILES(PROFILEID, REASON, TYPE, ENTRYBY,ENTRY_DT) VALUES('$pid','$reason','H','$user',now())";
		mysql_query_decide($sql_reason) or die("$sql_reason".mysql_error_js());

		//upddating MAIN_ADMIN, so that it is not shown in screen edit profile until it is edited by user.
		$sql = "UPDATE jsadmin.MAIN_ADMIN SET SKIP_FLAG='H',SKIP_COMMENTS='$reason' WHERE PROFILEID='$pid' and SCREENING_TYPE='O'";
		mysql_query_decide($sql) or die("$sql".mysql_error_js());

		//inserting into screening log for maintaining log.
		$sql = "INSERT into jsadmin.SCREENING_LOG(PROFILEID,USERNAME,$open_fields,SCREENED_BY,SCREENED_TIME,ENTRY_TYPE,FIELDS_SCREENED) select PROFILEID,USERNAME,$open_fields,'$user',now(),'H','$count_screen' from newjs.JPROFILE where PROFILEID = '$pid' ";

		mysql_query_decide($sql) or die("$sql".mysql_error_js());

		//mail($row_jp['EMAIL'],"Profile on Hold",$mail_msg);
		send_email($to,nl2br($mail_msg),$subject,"","","sriram.viswanathan@jeevansathi.com","","text/html");

		$msg = "You have successfully put the profile On Hold<br><br>";
		$smarty->assign("SUCCESSFUL","Y");
		$smarty->assign("cid",$cid);
		$smarty->assign("user",$user);
		$smarty->assign("val",$val);
		$smarty->assign("msg",$msg);
	}
	$smarty->display("onhold.htm");
}
else
{
        $msg="Your session has been timed out<br><br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

?>
