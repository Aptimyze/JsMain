<?php
require_once('connect.inc');

if(authenticated($cid))
{
	$user	= getname($cid);

	if ($reply)
	{
		if ($id)
		{
	        	include('replyrequest.php');
			die;
		}
		
		else
		{
			$norecords	= 1;
                	$msg = "<div><b>No record selected !!!</b></div>";
               		$smarty->assign("msg",$msg);
			$smarty->assign("cid",$cid);
			$smarty->assign("user",$user);
			$smarty->assign("norecords",$norecords); 
		}
	}

	if (!$reply || $norecords)
	{
		$edit_requests_sql="SELECT ID , PROFILEID , ORIG_USERNAME , ORIG_GENDER , ORIG_DTOFBIRTH ,ORIG_RELIGION,ORIG_CASTE, MEMBERSHIP_STATUS , USER , REQUEST_DT, CHANGE_DETAILS  FROM jsadmin.PROFILE_CHANGE_REQUEST WHERE CHANGE_STATUS='' ORDER BY REQUEST_DT ASC ";
        $edit_requests_res = mysql_query_decide($edit_requests_sql) or die("$edit_requests_sql".mysql_error_js());
                                                                                                                             
        	$i=0;

        	while($edit_requests_row= mysql_fetch_array($edit_requests_res))
        	{
                	$edit_requests[$i]['ID']                = $edit_requests_row['ID'];
                	$edit_requests[$i]['USERNAME']          = $edit_requests_row['ORIG_USERNAME'];
                	$edit_requests[$i]['ORIG_GENDER']       = $edit_requests_row['ORIG_GENDER'];
                	$edit_requests[$i]['ORIG_DTOFBIRTH']    = $edit_requests_row['ORIG_DTOFBIRTH'];
                        $edit_requests[$i]['ORIG_RELIGION']     = $edit_requests_row['ORIG_RELIGION'];
                        $edit_requests[$i]['ORIG_CASTE']        = $edit_requests_row['ORIG_CASTE'];
                	$edit_requests[$i]['MEMBERSHIP_STATUS'] = $edit_requests_row['MEMBERSHIP_STATUS'];
                	$edit_requests[$i]['USER']              = $edit_requests_row['USER'];
                	$edit_requests[$i]['CHANGE_DETAILS']    = $edit_requests_row['CHANGE_DETAILS'];

                	$request_dt                             = substr($edit_requests_row['REQUEST_DT'],0,10);
                	list($yy,$mm,$dd)                       = explode("-",$request_dt);

                	$edit_requests[$i]['REQUEST_DT']        = my_format_date($dd,$mm,$yy);
		
                	$i++;
        	}
		$smarty->assign("cid",$cid);
		$smarty->assign("user",$user);
        	$smarty->assign("edit_requests",$edit_requests);
        	$smarty->display("show_editprofile_request.htm");	
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
