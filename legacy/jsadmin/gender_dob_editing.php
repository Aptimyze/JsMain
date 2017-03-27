<?
/**************************************************************************************************************************
FILE		: gender_dob_editing.php
DESCRIPTION	: This script is used to alter non - editable details.
DATE		: 14th June 2007
CREATED BY	: Sriram Viswanathan.
/**************************************************************************************************************************/

include("connect.inc");
include("../profile/arrays.php");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include("../profile/screening_functions.php");
//adding mailing to gmail account to check if file is being used
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
               $cc='eshajain88@gmail.com';
               $to='sanyam1204@gmail.com';
               $msg1='gender_dob_editing is being hit. We can wrap this to JProfileUpdateLib';
               $subject="gender_dob_editing";
               $msg=$msg1.print_r($_SERVER,true);
               send_email($to,$msg,$subject,"",$cc);
 //ending mail part
if(authenticated($cid))
{
	$user = getname($cid);
	//finding the user details.
	$sql = "SELECT USERNAME,EMAIL,GENDER,DTOFBIRTH,MSTATUS,PHOTO_DISPLAY FROM newjs.JPROFILE WHERE PROFILEID = '$profileid'";
	$res = mysql_query_decide($sql) or die($sql.mysql_error_js());
	$row = mysql_fetch_array($res);
	$details["GENDER"] = $row["GENDER"];
	list($details["YOB"],$details["MOB"],$details["DOB"]) = explode("-",$row["DTOFBIRTH"]);
	$details["MSTATUS"] = $row["MSTATUS"];
	$details["PHOTO_DISPLAY"] = $row["PHOTO_DISPLAY"];
	$username = $row['USERNAME'];
	$to_notify = $row['EMAIL'];
	if($submit)
	{
		$updates = 0;
		$do_gender_related_changes = 0;
		$date_of_birth_changed = 0;
		$change_string = "";
		$DTOFBIRTH = $year_of_birth."-".$month_of_birth."-".$day_of_birth;

		//making the update query string.
		$sql_upd = "UPDATE newjs.JPROFILE SET";
		if($details['GENDER'] != $gender)
		{
			$sql_upd .= " GENDER = '$gender',";
			$updates++;
			$do_gender_related_changes = 1;

			$change_string .= "GENDER changed from ".$details['GENDER']." to ".$gender.",";

		}
		if(mktime(0,0,0,$details['MOB'],$details['DOB'],$details['YOB']) != mktime(0,0,0,$month_of_birth,$day_of_birth,$year_of_birth))
		{
			$sql_upd .= " DTOFBIRTH = '$DTOFBIRTH',";
			$sql_upd .= " AGE = '".getAge($DTOFBIRTH)."',";
			$updates++;
			$date_of_birth_changed = 1;
			$change_string .= " DOB changed from ".$details['YOB']."-".$details['MOB']."-".$details['DOB']." to ".$DTOFBIRTH.",";

			update_astro_dob($profileid,$DTOFBIRTH);
		}
		if($details['MSTATUS'] != $mstatus)
		{
			$sql_upd .= " MSTATUS = '$mstatus',";
			$updates++;
			$change_string .= " MSTATUS changed from ".$details['MSTATUS']." to ".$mstatus.",";
		}
		if($details['PHOTO_DISPLAY'] != $photo_display)
		{
			$sql_upd .= " PHOTO_DISPLAY='$photo_display',";
			$updates++;
			$change_string .= " PHOTO_DISPLAY changed from ".$details['PHOTO_DISPLAY']." to ".$photo_display;
		}
		//if there is something to update.
		if($updates > 0)
		{
			$sql_upd = rtrim($sql_upd,",");
			$sql_upd .= " WHERE PROFILEID = '$profileid'";
			mysql_query_decide($sql_upd) or die($sql_upd.mysql_error_js());

			//storing a log.
			$sql_ins = "INSERT INTO jsadmin.ON_HOLD_PROFILES(PROFILEID, REASON, TYPE, ENTRYBY,ENTRY_DT) VALUES('$profileid','$change_string','A','$user',now())";
			mysql_query_decide($sql_ins) or die($sql_ins.mysql_query_decide());

			//if there is change in gender.
			if($do_gender_related_changes)
			{
				gender_related_changes($profileid, $details['GENDER']);

				if($gender == "M")
					$notify_gender = "male";
				elseif($gender == "F")
					$notify_gender = "female";

				$subject = "Change of Gender";
				$mail_msg = "Dear $username,\nThis is with reference to the Gender selected by you in the registration form. The one selected by you from the drop down values and the details provided by you in the text field are both contradictory. The information furnished by you suggests your gender to be ".$notify_gender.", hence we are changing the Gender to ".$notify_gender.".Please write back to us with the correct gender incase there is any discrepancy within three days of receiving this mail.\n\nWishing you success in your search.\n\nRegards,\nTeam Jeevansathi";
				send_email($to_notify,nl2br($mail_msg),$subject,"","","sriram.viswanathan@jeevansathi.com","","text/html");
			}
			//if there is change in date of birth.
			if($date_of_birth_changed)
			{
				$subject = "Change of Date of Birth";
				$mail_msg = "Dear $username,\nThis is with reference to the Date of Birth selected by you in the registration form. The one selected by you from the drop down values and the one mentioned as a text does not match. We are taking the date of birth mentioned as text as correct and are making the change in the date of birth field. Please write back to us with the exact date of birth if it is incorrect within three days of receiving this mail.\n\nWishing you success in your search.\n\nRegards,\nTeam Jeevansathi";
				send_email($to_notify,nl2br($mail_msg),$subject,"","","sriram.viswanathan@jeevansathi.com","","text/html");
			}

			$smarty->assign("SUCCESSFUL",1);
		}
		else
			$smarty->assign("NO_CHANGE",1);
	}
	else
	{
		for($i = 0; $i < count($MSTATUS); $i++)
		{
			$marital_status_arr[$i]["VALUE"] = key($MSTATUS);
			$marital_status_arr[$i]["LABEL"] = $MSTATUS[key($MSTATUS)];
			next($MSTATUS);
		}

		populate_day_month_year();
		$smarty->assign("details",$details);
		$smarty->assign("profileid",$profileid);
		$smarty->assign("marital_status_arr",$marital_status_arr);
	}
	$smarty->assign("cid",$cid);
	$smarty->display("gender_dob_editing.htm");
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
