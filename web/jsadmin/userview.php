<?php

include("time.php");                                                                                                 
include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");
if(authenticated($cid))
{
	$name = getname($cid); 
	$tdate=date("Y-m-d"); 
	$lastweek_date=strftime("%Y-%m-%d",JSstrToTime("$tdate-7days "));
//	$sql="SELECT jsadmin.MAIN_ADMIN.PROFILEID, jsadmin.MAIN_ADMIN.USERNAME, ALLOT_TIME, SUBMIT_TIME, SUBSCRIPTION_TYPE FROM jsadmin.MAIN_ADMIN, newjs.JPROFILE WHERE jsadmin.MAIN_ADMIN.PROFILEID = newjs.JPROFILE.PROFILEID AND jsadmin.MAIN_ADMIN.RECEIVE_TIME>='$lastweek_date' AND (ACTIVATED ='U' OR (ACTIVATED='H' AND PREACTIVATED in ('U','N'))) AND ALLOTED_TO = '$name' AND SUBMITED_TIME =0";
	$sql="SELECT jsadmin.MAIN_ADMIN.PROFILEID, jsadmin.MAIN_ADMIN.USERNAME, ALLOT_TIME, SUBMIT_TIME, SUBSCRIPTION_TYPE FROM jsadmin.MAIN_ADMIN, newjs.JPROFILE WHERE jsadmin.MAIN_ADMIN.PROFILEID = newjs.JPROFILE.PROFILEID AND (ACTIVATED ='U' OR (ACTIVATED='H' AND PREACTIVATED in ('U','N'))) AND ALLOTED_TO = '$name' AND SUBMITED_TIME =0 AND SCREENING_TYPE='O'";
	$result=mysql_query_decide($sql) or die(mysql_error_js()); 
	$i=1;
	if(mysql_num_rows($result)>0)
	{
		while($myrow=mysql_fetch_array($result))
		{
			$profileid=$myrow['PROFILEID'];
			$username=$myrow['USERNAME'];
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
					  "remaining_time" => $time_diff,
					  "status_color" => $status_color,
				  "bandcolor"=>$color,
        			 );
		$i++;
		}
		$smarty->assign("ROW",$values);
	}
	$smarty->assign("for","U");
	
	$sum=setAllFlags();
//	$sql="SELECT jsadmin.MAIN_ADMIN.PROFILEID, jsadmin.MAIN_ADMIN.USERNAME, ALLOT_TIME, SUBMIT_TIME, SUBSCRIPTION_TYPE FROM jsadmin.MAIN_ADMIN, newjs.JPROFILE WHERE jsadmin.MAIN_ADMIN.PROFILEID = newjs.JPROFILE.PROFILEID AND jsadmin.MAIN_ADMIN.ALLOT_TIME>='$lastweek_date' AND (ACTIVATED = 'Y' OR (ACTIVATED = 'H' AND PREACTIVATED ='Y')) AND ALLOTED_TO = '$name' AND SUBMITED_TIME =0 AND SCREENING!=$sum";
	$sql="SELECT jsadmin.MAIN_ADMIN.PROFILEID, jsadmin.MAIN_ADMIN.USERNAME, ALLOT_TIME, SUBMIT_TIME, SUBSCRIPTION_TYPE FROM jsadmin.MAIN_ADMIN, newjs.JPROFILE WHERE jsadmin.MAIN_ADMIN.PROFILEID = newjs.JPROFILE.PROFILEID AND (ACTIVATED = 'Y' OR (ACTIVATED = 'H' AND PREACTIVATED ='Y')) AND ALLOTED_TO = '$name' AND SUBMITED_TIME =0 AND SCREENING<1099511627775 AND SCREENING_TYPE='O'";
	$result1=mysql_query_decide($sql);
	$i=1;
	if(mysql_num_rows($result1)>0)
	{
		while($myrow1=mysql_fetch_array($result1))
		{
			$profileid=$myrow1['PROFILEID'];
			$username=$myrow1['USERNAME'];
			$receivetime_est=$myrow1['ALLOT_TIME'];
			$receivetime=getIST($receivetime_est);
			$submittime_est=$myrow1['SUBMIT_TIME'];
			$status_color = get_status_color($submittime_est,$time_diff);
			$submittime=getIST($submittime_est);
			
			if($myrow1["SUBSCRIPTION_TYPE"]=="")
				$color="fieldsnew";
			else
				$color="fieldsnewgreen";
			$values1[] = array("sno"=>$i,
					  "profileid"=>$profileid,
					  "username"=>$username,
					  "receive_time"=>$receivetime,
					  "submit_time"=>$submittime,
				  "remaining_time" => $time_diff,
				  "status_color" => $status_color,
				  "bandcolor"=>$color,
        			 );
		$i++;
		}
		$smarty->assign("ROW1",$values1);
	}
	$smarty->assign("for","Y");


	$smarty->assign("cid",$cid);
	$smarty->assign("user",$name);	
	$smarty->display("user_view.tpl");
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
