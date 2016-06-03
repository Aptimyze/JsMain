<?php
/**************************************************************************************************************************
FILE		: matri_reminder_mail.php
DESCRIPTION	: This script is used to send a reminder mail to those profiles to whom matri-profile has been sent 
		: and no response is from their end for 10 days.
CREATED BY	: Sriram Viswanathan.
DATE		: 25th July 2007.
**************************************************************************************************************************/
include("connect.inc");
include("matri_functions.inc");

//finding current date.
$ts = time();
$today = date("Y-m-d",$ts);

//finding date 10 days before.
$ts -= (24*60*60) * 10;
$ten_days_before = date("Y-m-d",$ts);

//finding the max cut for each profileid where UPLOADED_BY is USER.
$sql = "SELECT MAX( CUTS ) CUTS, PROFILEID FROM billing.MATRI_CUTS WHERE UPLOADED_BY='USER' GROUP BY PROFILEID";
$res = mysql_query_decide($sql) or die($sql.mysql_error_js());
while($row = mysql_fetch_array($res))
{
	$profileid_arr[] = $row['PROFILEID'];
	//storing cut value uploaded by user.
	$user_upload[$row['PROFILEID']] = $row['CUTS'];
}

//finding the max cut for each profileid where UPLOADED_BY is not USER (by an executive).
$sql = "SELECT MAX( CUTS ) CUTS, PROFILEID FROM billing.MATRI_CUTS WHERE UPLOADED_BY<>'USER' GROUP BY PROFILEID";
$res = mysql_query_decide($sql) or die($sql.mysql_error_js());
while($row = mysql_fetch_array($res))
{
	if(!in_array($row['PROFILEID'], $profileid_arr))
		$profileid_arr[] = $row['PROFILEID'];

	//storing cut value uploaded by executive.
	$upload[$row['PROFILEID']] = $row['CUTS'];
}

//comparing cuts value for each user.
for($i=0;$i<count($profileid_arr);$i++)
{
	//if cuts value of user_upload is < that of uploaded by some executive.
	if($user_upload[$profileid_arr[$i]] < $upload[$profileid_arr[$i]])
		$final_profileid_string .= "'".$profileid_arr[$i]."',";
}

unset($profileid_arr);

$final_profileid_string = rtrim($final_profileid_string,",");

if($final_profileid_string)
{
	//finding profiles who have not responded in the past 10 days (profiles which are not complete).
	$sql_ct = "SELECT mct.PROFILEID FROM billing.MATRI_CUTS AS mct LEFT JOIN billing.MATRI_COMPLETED AS mcp ON mct.PROFILEID=mcp.PROFILEID WHERE mcp.PROFILEID IS NULL AND mct.PROFILEID IN ($final_profileid_string) AND mct.ENTRY_DT BETWEEN '".$ten_days_before." 00:00:00' AND '".$ten_days_before." 23:59:59'";
	$res_ct = mysql_query_decide($sql_ct) or die("$sql_ct".mysql_error_js());
	while($row_ct = mysql_fetch_array($res_ct))
		$profileid_arr[] = $row_ct['PROFILEID'];

	$profileid_str = "'".@implode(",'",$profileid_arr)."'";

	if($profileid_str)
	{
		$sql = "SELECT PROFILEID,USERNAME,EMAIL from newjs.JPROFILE where PROFILEID IN ($profileid_str)";
		$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row = mysql_fetch_array($res))
		{
			$username = $row["USERNAME"];
			$profileid = $row["PROFILEID"];
			$to_send_checksum = to_mail_checksum($profileid);

			$filename = get_file_name($profileid,$username,"Y");

			//path to the file.
			$path = $FILE_PATH.$filename.".doc";

			$link = "<a href=\"$SITE_URL/profile/matri_upload_user.php?recd_checksum=$to_send_checksum&profileid=$profileid&username=$username\">click here</a>";

			$message = "Dear $username,\n\nThis is a reminder mail regarding the matri-profile sent to you on ".date("d-m-Y",$ts).".\nWe request you to kindly approve or suggest any changes to the profile by clicking on the link provided in the previous mail.\n Incase you haven't received the previous mail, please $link to upload the modified matri-profile.\n\nWith regards,\n$SR_EXECUTIVE";

			$sql_prev = "SELECT CUTS,UPLOADED_BY FROM billing.MATRI_CUTS WHERE PROFILEID = '$profileid' ORDER BY ENTRY_DT DESC LIMIT 1";
			$res_prev = mysql_query_decide("$sql_prev".mysql_error_js());
			$row_prev = mysql_fetch_array($res_prev);

			//inserting into MATRI_CUTS for tracking.
			$sql_ins = "INSERT INTO billing.MATRI_CUTS(PROFILEID,CUTS,ENTRY_DT,UPLOADED_BY,COMMENTS) VALUES('$profileid','$row_prev[CUTS]',now(),'$row_prev[UPLOADED_BY]','".addslashes(stripslashes($message))."')";
                        mysql_query_decide($sql_ins) or die($sql_ins.mysql_error_js());

			send_doc_email("Matri Profile",$path,$to,nl2br($message),$filename,$cc1,$cc2,$bcc,$profileid);
		}
	}
}
?>
