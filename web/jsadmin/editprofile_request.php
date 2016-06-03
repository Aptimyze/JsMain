<?php
include('connect.inc');
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");

if(authenticated($cid))
{
	$executive= getname($cid);
	$iserror  = 0;
	if ($req_submit)
	{
		if (!$dob && !$username && !$gender && !$membership_status && !$mstatus && !$religion)
		{
			$iserror++;
			$msg.="<b><font color=\"#9f4000\">Select the fields which need to be edited.Atleast one of them need to be chosen<br></font><b>";
		}
		if (trim($change_details) == "")
		{	
			$iserror++;
			$comments_clr="red";	
			$msg.= "<b><font color=\"#9f4000\">Enter Comments (details of changes) that need to be made to the profile</font><b>";
                }
		$smarty->assign("msg",$msg);
		$smarty->assign("comments_clr",$comments_clr);
		if ($iserror > 0)
		{
			$smarty->assign("user",$user);
			$smarty->assign("username",$username);
			$smarty->assign("dob",$dob);
			$smarty->assign("MS",$mstatus);
                        $smarty->assign("religion",$REL);
			$smarty->assign("gender",$gender);
			$smarty->assign("membership_status",$membership_status);
			$smarty->assign("change_details",$change_details);
			$smarty->assign("user",$user);
	                $smarty->assign("executive",$executive);
        	        $smarty->assign("cid",$cid);
			$smarty->assign("pid",$pid);
			$smarty->assign("record_id",$record_id);
			$smarty->assign("iserror","$iserror");
			$smarty->display('editprofile_request1.htm');
		}
		else
		{
			$subject="Request for change of <b> $user's :  </b>";
			if ($username)
			{
				$request_for.= "$username,";
				$subject.=" username,";
			}
			if ($gender)
			{
				$request_for.= "$gender,";
				$subject.=" gender,";
			}
			if ($dob)
			{
				$request_for.= "$dob,";
				$subject.=" date of birth,";
			}
			if($mstatus)
			{
				$request_for.="$mstatus,";
				$subject.=" Marital Status,";
			}

			if ($membership_status)
			{
				$request_for.= "$membership_status,";
				$subject.=" subscription status ,";
			}
                        
                        if ($religion)
			{
				$request_for.= "$religion,";
				$subject.=" religion ,";
			}
			

			$request_for=substr($request_for,0,strlen($request_for)-1);

			$sql 	= "SELECT EMAIL FROM jsadmin.PSWRDS WHERE USERNAME = '$executive'";
			$res	= mysql_query_decide($sql) or die(mysql_error_js());
			$row 	= mysql_fetch_array($res);
			$sendby	= $row['EMAIL'];

			$subject=substr($subject,0,strlen($subject)-1);
			$sendto = 'vivek@jeevansathi.com';

			$profile_det_sql = "SELECT GENDER , DTOFBIRTH , SUBSCRIPTION, MSTATUS, RELIGION, CASTE FROM newjs.JPROFILE WHERE PROFILEID = '$pid' ";
			$profile_det_res = mysql_query_decide($profile_det_sql) or die("$profile_det_sql".mysql_error_js());
			$profile_det_row = mysql_fetch_array($profile_det_res);

			$gender		= $profile_det_row["GENDER"];
			$dtofbirth	= $profile_det_row["DTOFBIRTH"];
			$activated	= $profile_det_row["SUBSCRIPTION"];
			$maritalstatus	= $profile_det_row['MSTATUS'];
			$relig          = $profile_det_row['RELIGION'];
                        $caste          = $profile_det_row['CASTE'];
                        
			$change_req_sql = "INSERT INTO jsadmin.PROFILE_CHANGE_REQUEST (PROFILEID,ORIG_USERNAME,ORIG_GENDER,ORIG_DTOFBIRTH,MEMBERSHIP_STATUS,MSTATUS,ORIG_RELIGION,ORIG_CASTE,CHANGE_DETAILS,USER,REQUEST_DT,REQUEST_FOR) VALUES ('$pid','$user','$gender','$dtofbirth','$activated','$maritalstatus','$relig','$caste','$change_details','$executive',NOW(),'$request_for')";
			mysql_query_decide($change_req_sql) or die("$change_req_sql".mysql_error_js());

			//send_email($sendto,$change_details,$subject,$sendby);
			if($record_id){
				$sql_get_id="SELECT ID from jsadmin.PROFILE_CHANGE_REQUEST where PROFILEID='$pid' and CHANGE_STATUS='' ORDER BY REQUEST_DT DESC LIMIT 1";
				$res=mysql_query_decide($sql_get_id) or die("$sql_get_id".mysql_error_js());
				$row=mysql_fetch_array($res);
				$id=$row[ID];
				if($id)
				echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/jsadmin/replyrequest.php?id=$id&cid=$cid&record_id=$record_id\"></body></html>";
			}
			else{
			$msg = "Change Request Submitted <br>";
                        $msg .="<a href=\"searchpage.php?cid=$cid\">";
                        $msg .="Continue </a>";

			$smarty->assign("cid",$cid);
                        $smarty->assign("MSG",$msg);
						$smarty->display("jsadmin_msg.tpl");			
			}
		}
	}
	else
	{
                $profile_det_sql = "SELECT MSTATUS FROM newjs.JPROFILE WHERE PROFILEID = '$pid' ";
                $profile_det_res = mysql_query_decide($profile_det_sql) or die("$profile_det_sql".mysql_error_js());
                $profile_det_row = mysql_fetch_array($profile_det_res);
                $mstat	= $profile_det_row['MSTATUS'];
		$smarty->assign("user",$user);
		$smarty->assign("executive",$executive);
		$smarty->assign("cid",$cid);
		$smarty->assign("pid",$pid);
		$smarty->assign("record_id",$record_id);
                //CHECK if user is not married then only change his religion
                $smarty->assign("maritalst",$mstat);
		$smarty->display('editprofile_request1.htm');
	}
}

else
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
		$smarty->assign("user",$user);
        $smarty->display("jsadmin_msg.tpl");
}
?>
