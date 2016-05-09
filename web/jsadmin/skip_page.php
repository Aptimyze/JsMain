<?php
include("connect.inc");
include("time1.php");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");

//GLOBAL $screen_time;
if(authenticated($cid))
{
	
	//$pid = stripslashes($pid);
	if($c>0)
        {
		/*$sql_rt= "SELECT RECEIVE_TIME FROM jsadmin.MAIN_ADMIN where PROFILEID='$pid' and SCREENING_TYPE='O'";
		$result_rt=mysql_query_decide($sql_rt);
		$myrow=mysql_fetch_assoc($result_rt);
		$receive_time=$myrow['RECEIVE_TIME'];
		$submit_time=newtime($receive_time,0,$screen_time,0);//Submit time is +24 hours*/
		
		$comments=addslashes(stripslashes(trim($comments)));
		
		if($FROM=="SS")
		{
			$sql="UPDATE newjs.SUCCESS_STORIES SET UPLOADED='S',SKIP_COMMENTS='$comments' WHERE ID='$id'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
		}
		elseif($FROM=="MP")
		{
			$sql="UPDATE jsadmin.SCREEN_PHOTOS_FROM_MAIL SET SKIP='Y',SKIP_COMMENTS='$comments' WHERE MAILID='$mailid' AND ASSIGNED_TO='$user'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
		}
		elseif($FROM=='P')
		{
		 	$sql= "UPDATE jsadmin.MAIN_ADMIN SET SKIP_FLAG='Y',SKIP_COMMENTS='$comments' where PROFILEID='$pid' and SCREENING_TYPE='P'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
		}
		else
		{
			 $sql= "UPDATE jsadmin.MAIN_ADMIN SET SKIP_FLAG='Y',SKIP_COMMENTS='$comments' where PROFILEID='$pid' and SCREENING_TYPE='O'";
                	mysql_query_decide($sql) or die("$sql".mysql_error_js());
		}
	       	$msg = "You have successfully skipped $c record(s)<br><br>";
	}
	else
	{
		$flag_deletion = 0;
		$msg = "Please select a profile for skipping";
		$smarty->assign("MSG",$msg);
		$smarty->assign("cid",$cid);
		$smarty->assign("pid",$pid);
		$smarty->assign("c",$c);
		$smarty->assign("user",$user);
		$smarty->assign("username",$username);
		$smarty->assign("FROM","SK");
		$smarty->assign("medit",$medit);
		$smarty->display("skip_page.htm");
	}
	if($FROM=="S")
	{
		$msg .= "<a href=\"searchpage.php?user=$user&cid=$cid&SEARCH=YES&Go=Go&year1=$year1&month1=$month1&day1=$day1&year2=$year2&month2=$month2&day2=$day2&username=$username&email=$email&PAGE=$PAGE&grp_no=$grp_no\">";
		$smarty->assign("grp_no",$grp_no);
		$smarty->assign("PAGE",$PAGE);
	}
	if($FROM=="AN")
	{
		$msg .= "<a href=\"admin_new.php?name=$user&cid=$cid\">";
	}
	if($FROM=="AE")
	{
		$msg .= "<a href=\"admin_edit.php?name=$user&cid=$cid\">";
	}
	if($FROM=="U")
	{
		if($medit==1)
			$msg .= "<a href=\"view_profile_count.php?user=$user&cid=$cid&val=$val\">";
		else
			$msg .= "<a href=\"screen_new.php?user=$user&cid=$cid&val=$val\">";
	}
	if($FROM=="P")
	{
		$msg.="<a href=\"showphotostoscreen_new.php?name=$name&cid=$cid&val=$val\">";
	}
	 if($FROM=="MP")
        {
                $msg.="<a href=\"screen_photos_from_mail.php?user=$user&cid=$cid\">";
        }
	if($FROM=="SS")
	{
		$msg.="<a href=\"screen_success_story.php?user=$user&cid=$cid\">";
	}

	$msg .= "Continue</a>&nbsp;&nbsp;&nbsp;&nbsp; ";
	if($FROM=="U" && !$medit)
		$msg .= "<a href=\"mainpage.php?name=$user&cid=$cid\">Exit</a>";
	
	$smarty->assign("name",$user);
	$smarty->assign("cid",$cid);
	$smarty->assign("MSG",$msg);
	$smarty->assign("medit",$medit);
	$smarty->display("jsadmin_msg.tpl");
}
else
{
	$msg="Your session has been timed out<br>";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}
?>
