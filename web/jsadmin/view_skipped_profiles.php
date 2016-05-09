<?php
/**
*	FILENAME	:	view_skipped_profiles.php
*	DESCRIPTION	:	Shows the skipped profiles to user
*	CREATED BY	:	Tripti Singh
*	Created ON	:	5th July, 2006
*	
**/
include("connect.inc");
include("time1.php"); 

if(authenticated($cid))
{
	$user=getname($cid);
	if($from=="MP")
	{
		 $sql="SELECT SCREEN_PHOTOS_FROM_MAIL.MAILID,SCREEN_PHOTOS_FROM_MAIL.PROFILEID,PHOTOS_FROM_MAIL.SENDER,PHOTOS_FROM_MAIL.SUBJECT, ALLOTED_DATE,SKIP_COMMENTS,RECEIVE_DATE,ASSIGNED_TO FROM jsadmin.SCREEN_PHOTOS_FROM_MAIL,jsadmin.PHOTOS_FROM_MAIL WHERE PHOTOS_FROM_MAIL.ID=SCREEN_PHOTOS_FROM_MAIL.MAILID AND SKIP = 'Y' ORDER BY RECEIVE_DATE";       
                $smarty->assign("FROM","MP");
	}
	elseif($from=="P")
	{
		$sql="SELECT jsadmin.MAIN_ADMIN.PROFILEID, jsadmin.MAIN_ADMIN.USERNAME, ALLOT_TIME, SUBMIT_TIME, SUBSCRIPTION_TYPE, SKIP_COMMENTS, ALLOTED_TO FROM jsadmin.MAIN_ADMIN, newjs.JPROFILE WHERE jsadmin.MAIN_ADMIN.PROFILEID = newjs.JPROFILE.PROFILEID AND SKIP_FLAG = 'Y' AND SCREENING_TYPE='P' ORDER BY jsadmin.MAIN_ADMIN.RECEIVE_TIME";	
	}
	else
	$sql="SELECT jsadmin.MAIN_ADMIN.PROFILEID, jsadmin.MAIN_ADMIN.USERNAME, ALLOT_TIME, SUBMIT_TIME, SUBSCRIPTION_TYPE, SKIP_COMMENTS, ALLOTED_TO FROM jsadmin.MAIN_ADMIN, newjs.JPROFILE WHERE jsadmin.MAIN_ADMIN.PROFILEID = newjs.JPROFILE.PROFILEID AND SKIP_FLAG = 'Y' AND SCREENING_TYPE='O' ORDER BY jsadmin.MAIN_ADMIN.RECEIVE_TIME";
	$result=mysql_query_decide($sql) or die(mysql_error_js());
	$i=1;
	if(mysql_num_rows($result)>0)
	{
		while($myrow=mysql_fetch_array($result))
		{
			if($from=="MP")
			{
				$sender=$myrow["SENDER"];
				$subject=$myrow["SUBJECT"];
				$alloted_to=$myrow["ASSIGNED_TO"];
				$comments=$myrow["SKIP_COMMENTS"];
				$alloted_date=$myrow["ALLOTED_DATE"];
				$alloted_date=getIST($alloted_date);
				$receive_date=$myrow["RECEIVE_DATE"];
				$mailid=$myrow["MAILID"];
				$profileid=$myrow["PROFILEID"];
				$values[]=array("sno"=>$i,
						"sender"=>$sender,
						"subject"=>$subject,
						"alloted_to"=>$alloted_to,
						"alloted_date"=>$alloted_date,
						"comments"=>$comments,
						"receive_date"=>$receive_date,
						"mailid"=>$mailid,
						"profileid"=>$profileid
						);
				$val = 'mail';
			}
			else
			{
				$profileid=$myrow['PROFILEID'];
				$username=$myrow['USERNAME'];
				$alloted_to=$myrow['ALLOTED_TO'];
				$comments=$myrow['SKIP_COMMENTS'];
				$receivetime_est=$myrow['ALLOT_TIME'];
				$receivetime=getIST($receivetime_est);
				$submittime_est=$myrow['SUBMIT_TIME'];
				$status_color = get_status_color($submittime_est,$time_diff);
				$submittime=getIST($submittime_est);
				if($myrow["SUBSCRIPTION_TYPE"]=="")
					$color="fieldsnew";
				else
					$color="fieldsnewgreen";
				$values[] = array("sno"=>$i,
						  "profileid"=>$profileid,
						  "username"=>$username,
						  "receive_time"=>$receivetime,
						  "submit_time"=>$submittime,
						  "alloted_to"=>$alloted_to,
						  "comments"=>$comments,
						  "remaining_time" => $time_diff,
						  "status_color" => $status_color,
						  "bandcolor"=>$color
        				 );
			}
		$i++;
		}
		$smarty->assign("ROW",$values);
	}
	$smarty->assign("cid",$cid);
	$smarty->assign("user",$user);	
	$smarty->assign("val",$val);
	$smarty->assign("FROM",$from);
	$smarty->display("view_skipped_profiles.htm");
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
