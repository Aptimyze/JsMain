<?php
/***************************************************************************************************************************
FILE NAME		: show_matri_allot.php
DESCRIPTION		: This file shows the profile(s) allotted/onhold/to send first draft/followup 
			: to(with) a particular user.
MODIFICATION DATE	: July 11th 2007.
MODIFIED BY		: Sriram Viswanathan.
***************************************************************************************************************************/
include("connect.inc");
include("time.php");
include("matri_functions.inc");
if(authenticated($checksum))
{
   	$user=getname($checksum);
        $privilage=getprivilage($checksum);
        $priv=explode("+",$privilage);
        if(in_array('MPU',$priv))
        {
		$ts=time();
		$today=date('Y-m-d G:i:s',$ts);
		//if upload button is clicked.
		if($Upload)
		{
			for($i=0;$i<count($profileid);$i++)
			{
				$pid = $profileid[$i];
				if($to_verify[$pid] == "Y")
				{
					//upload the file.
					$return_value = upload_matri_profile("file_name",$pid,$username[$pid]);
					if($return_value == "NO_FILE")
						$no_file_selected_str .= $username[$pid].", ";
					elseif($return_value == "INVALID_FILE")
						$invalid_file_type_str .= $username[$pid].", ";
					elseif($return_value == "UPLOAD_PROBLEM")
						$upload_problem_str .= $username[$pid].", ";
					elseif($return_value == "SUCCESSFUL")
					{
						$successful_upload_str .= $username[$pid].", ";
						$sql_upd = "UPDATE billing.MATRI_PROFILE SET STATUS='Y',COMPLETION_TIME=now() WHERE PROFILEID='$pid'";
						mysql_query_decide($sql_upd) or die($sql_upd.mysql_error_js());
					}
				}
			}
			if($successful_upload_str)
				$MSG = "File(s) successfully uploaded for ".rtrim($successful_upload_str,", ")." and has been sent to team leader for verification.\n";
			if($invalid_file_type_str)
				$MSG .= "Please select a valid file for ".rtrim($invalid_file_type_str,", ").". \n";
			if($no_file_selected_str)
				$MSG .= "No file(s) selected for ".rtrim($no_file_selected_str,", ").".";
			if($upload_problem_str)
				$MSG .= "File could not be uploaded for ".rtrim($upload_problem_str,", ")." due to some problem.";
			$smarty->assign("MSG",nl2br($MSG));
			$smarty->assign("UPLOADED",1);
		}
		else
		{
			//finding the count of profiles on progress for a particular user.
			$count['ON_PROGRESS'] = get_matri_count("billing","MATRI_PROFILE","N",$user);

			//finding the count of profiles on followup for a particular user.
			$count['FOLLOW_UP'] = get_matri_count("billing","MATRI_PROFILE","F",$user);

			//finding the count of profiles on hold for a particular user.
			$count['ON_HOLD'] = get_matri_count("billing","MATRI_ONHOLD","H",$user);

			//finding the count of profiles completed.
			$count['COMPLETED'] = get_matri_count("billing","MATRI_COMPLETED","",$user);

			//finding details of profiles on progress.
			$sql_progress = "SELECT PROFILEID,USERNAME,ENTRY_DT,ALLOT_TIME,STATUS,RATING,RATED_BY FROM billing.MATRI_PROFILE WHERE ALLOTTED_TO='$user' and STATUS ='N' ORDER BY ENTRY_DT ASC";
			$res_progress = mysql_query_decide($sql_progress) or die("$sql_progress".mysql_error_js());
			$i=0;
			while($row_progress = mysql_fetch_array($res_progress))
			{
				$allotted[$i]['SNo'] = $i+1;
				$allotted[$i]['ID'] = $row_progress['ID'];
				$allotted[$i]['PROFILEID'] = $row_progress['PROFILEID'];
				$allotted[$i]['USERNAME'] = $row_progress['USERNAME'];
				$allotted[$i]['ENTRY_DT'] = $row_progress['ENTRY_DT'];

				$allotted[$i]['ALLOT_TIME'] = $row_progress['ALLOT_TIME'];
				//newtime function returns date after 4 working days, the parameter 4 is the number of days.
				$allotted[$i]['SCHEDULED_TIME'] = newtime($row_progress['ENTRY_DT'],4,0,0);

				if($allotted[$i]['SCHEDULED_TIME'] < $today)
					$allotted[$i]['scheduled']=1;

				$allotted[$i]['STATUS'] = $row_progress['STATUS'];
				$allotted[$i]['RATING'] = $RATING[$row_progress['RATING']];
				if($row_progress['RATED_BY']=="")
					$allotted[$i]['RATING'] = "Not rated yet";

				//finding the contact details.
				$sql_con = "SELECT EMAIL,PHONE_MOB,PHONE_RES FROM newjs.JPROFILE WHERE PROFILEID='$row_progress[PROFILEID]'";
				$result_con = mysql_query_decide($sql_con) or die("$sql_con".mysql_error_js());
				$myrow_con = mysql_fetch_array($result_con);

				$allotted[$i]['EMAIL']=$myrow_con['EMAIL'];
				$allotted[$i]['PHONE_MOB']=$myrow_con['PHONE_MOB'];
				$allotted[$i]['PHONE_RES']=$myrow_con['PHONE_RES'];

				//finding billid.
				//$sql_bill = "SELECT BILLID FROM billing.PURCHASES WHERE PROFILEID='$row_progress[PROFILEID]' AND STATUS='DONE' ORDER BY BILLID DESC";
				$sql_bill = "SELECT a.BILLID FROM billing.MATRI_PURCHASES a JOIN billing.PURCHASES b on b.BILLID=a.BILLID WHERE a.PROFILEID='$row_progress[PROFILEID]' AND b.STATUS='DONE' ORDER BY a.ID DESC";
				$res_bill = mysql_query_decide($sql_bill) or die($sql_bill.mysql_error_js());
				$row_bill = mysql_fetch_array($res_bill);
				$allotted[$i]['BILLID'] = $row_bill['BILLID'];

				$i++;

				$smarty->assign("ON_PROGRESS",1);
			}

			//finding details of profiles which are verfied by the TL.
			$sql_verified = "SELECT PROFILEID,USERNAME,ENTRY_DT,ALLOT_TIME,STATUS FROM billing.MATRI_PROFILE WHERE ALLOTTED_TO='$user' and STATUS = 'NY' ORDER BY ENTRY_DT ASC";
			$res_verified = mysql_query_decide($sql_verified) or die("$sql_verified".mysql_error_js());
			$i=0;
			while($row_verified = mysql_fetch_array($res_verified))
			{
				$allotted_verified[$i]['SNo'] = $i+1;
				$allotted_verified[$i]['ID'] = $row_verified['ID'];
				$allotted_verified[$i]['PROFILEID'] = $row_verified['PROFILEID'];
				$allotted_verified[$i]['USERNAME'] = $row_verified['USERNAME'];
				$allotted_verified[$i]['ENTRY_DT'] = $row_verified['ENTRY_DT'];

				$allotted_verified[$i]['ALLOT_TIME'] = $row_verified['ALLOT_TIME'];
				//newtime function returns date after 4 working days, the parameter 4 is the number of days.
				$allotted_verified[$i]['SCHEDULED_TIME'] = newtime($row_verified['ENTRY_DT'],4,0,0);

				if($allotted_verified[$i]['SCHEDULED_TIME'] < $today)
					$allotted_verified[$i]['scheduled']=1;

				$allotted_verified[$i]['STATUS'] = $row_verified['STATUS'];

				//finding the contact details.
				$sql_con = "SELECT EMAIL,PHONE_MOB,PHONE_RES FROM newjs.JPROFILE WHERE PROFILEID='$row_verified[PROFILEID]'";
				$result_con = mysql_query_decide($sql_con) or die("$sql_con".mysql_error_js());
				$myrow_con = mysql_fetch_array($result_con);

				$allotted_verified[$i]['EMAIL']=$myrow_con['EMAIL'];
				$allotted_verified[$i]['PHONE_MOB']=$myrow_con['PHONE_MOB'];
				$allotted_verified[$i]['PHONE_RES']=$myrow_con['PHONE_RES'];
				$i++;

				$smarty->assign("VERIFIED",1);
			}
			$smarty->assign("count",$count);
			$smarty->assign("allotted",$allotted);
			$smarty->assign("allotted_verified",$allotted_verified);

			//finding details of profiles on follow up.
			$sql_followup = "SELECT * FROM billing.MATRI_PROFILE WHERE STATUS='F' and ALLOTTED_TO='$user' ORDER BY ENTRY_DT ASC";
			$res_followup = mysql_query_decide($sql_followup) or die("$sql_followup".mysql_error_js());
			$x=0;
			while($row_followup = mysql_fetch_array($res_followup))
			{
				$followup[$x]['PROFILEID'] = $row_followup['PROFILEID'];
				$followup[$x]['USERNAME'] = $row_followup['USERNAME'];
				$followup[$x]['ENTRY_DT'] = $row_followup['ENTRY_DT'];
				$followup[$x]['ALLOTTED_TO'] = $row_followup['ALLOTTED_TO'];
				$followup[$x]['ALLOT_TIME'] = $row_followup['ALLOT_TIME'];
				$followup[$x]['COMPLETION_TIME'] = $row_followup['COMPLETION_TIME'];
				$followup[$x]['STATUS'] = $row_followup['STATUS'];
				$followup[$x]['SNO'] = $x+1;

				//finding the contact details
				$sql_con="Select EMAIL,PHONE_MOB,PHONE_RES from newjs.JPROFILE where PROFILEID='$row_followup[PROFILEID]'";
				$result_con = mysql_query_decide($sql_con) or die("$sql_con".mysql_error_js());
				$myrow_con = mysql_fetch_array($result_con);
				$followup[$x]['EMAIL']=$myrow_con['EMAIL'];
				$followup[$x]['PHONE_MOB']=$myrow_con['PHONE_MOB'];
				$followup[$x]['PHONE_RES']=$myrow_con['PHONE_RES'];

				$sql_response = "SELECT MAX(ENTRY_DT) AS ENTRY_DT FROM billing.MATRI_CUTS WHERE PROFILEID='$row_followup[PROFILEID]' AND UPLOADED_BY='USER'";
				$res_response = mysql_query_decide($sql_response) or die($sql.mysql_error_js());
				$row_response = mysql_fetch_array($res_response);
				$followup[$x]['RESPONSE_TIME'] = $row_response['ENTRY_DT'];
				if($followup[$x]['RESPONSE_TIME']=="")
					$followup[$x]['RESPONSE_TIME'] = "Not responded yet";
				$x++;
				$smarty->assign("FOLLOW_UP",1);
			}

			//finding details of profiles on hold.
			$sql_hold = "SELECT mo.PROFILEID, mo.USERNAME, mo.ALLOTTED_TIME, mo.HOLD_TIME, mo.HOLD_REASON, jp.EMAIL, jp.PHONE_MOB, jp.PHONE_RES FROM billing.MATRI_ONHOLD AS mo, newjs.JPROFILE jp WHERE mo.PROFILEID=jp.PROFILEID AND mo.STATUS='H' AND mo.ALLOTTED_TO='$user' ORDER BY mo.ENTRY_DT ASC";
			$res_hold = mysql_query_decide($sql_hold) or die("$sql_hold".mysql_error_js());
			$m=0;
			while($row_hold = mysql_fetch_array($res_hold))
			{
				$onhold[$m]['PROFILEID'] = $row_hold['PROFILEID'];
				$onhold[$m]['USERNAME'] = $row_hold['USERNAME'];
				$onhold[$m]['ALLOTTED_TIME'] = $row_hold['ALLOTTED_TIME'];
				$onhold[$m]['HOLD_TIME'] = $row_hold['HOLD_TIME'];
				$onhold[$m]['REASON'] = $row_hold['HOLD_REASON'];
				$onhold[$m]['EMAIL'] = $row_hold['EMAIL'];
				$onhold[$m]['PHONE_MOB'] = $row_hold['PHONE_MOB'];
				$onhold[$m]['PHONE_RES'] = $row_hold['PHONE_RES'];
				$onhold[$m]['SNO'] = $m+1;
				$m++;

				$smarty->assign("ON_HOLD",1);
			}

			$smarty->assign("onhold",$onhold);
			$smarty->assign("followup",$followup);
			$smarty->assign("allotted_to",$user);
		}
		$smarty->assign("scriptname","show_matri_allot.php");
		$smarty->assign("checksum",$checksum);
		$smarty->assign("SER6_URL",$SER6_URL);
		$smarty->assign("SEARCH_BAND",$smarty->fetch("search_matri_profile.htm"));
		$smarty->assign("MATRI_MESSAGE",$smarty->fetch("matri_message.htm"));
                $smarty->display("show_matri_allot.htm");		
        }
        else
        {
                echo "You don't have permission to view this mis";
                die();
        }
}
else
{
        $smarty->display("jsconnectError.tpl");
}

?>

