<?php
/***************************************************************************************************************************
FILE NAME		: matri_upload_user.php
DESCRIPTION		: This file called from the link which is sent to the user by mail.
			: This file is used to upload/add comments to the matri-profile, by the user.
DATE			: July 24th 2007.
CREATED BY		: Sriram Viswanathan.
***************************************************************************************************************************/
include("connect.inc");
include("../jsadmin/matri_functions.inc");
$db = connect_db();

$new_checksum = to_mail_checksum($profileid);
if($new_checksum==$recd_checksum)
{
	if($Submit)
	{
		$is_error=0;
		//upload the file.
		$return_value = upload_matri_profile("uploaded_file",$profileid,$username);

		if($return_value=="NO_FILE" && trim($comments) == "")
		{
			$is_error++;
                        $smarty->assign("MSG","Please select a file to upload or write your comments in the box provided.");
		}
                elseif($return_value=="INVALID_FILE")
		{
			$is_error++;
                        $smarty->assign("MSG","You can upload only .doc or .rtf or .txt files.");
		}
                elseif($return_value=="UPLOAD_PROBLEM")
		{
			$is_error++;
                        $smarty->assign("MSG","There was a problem in uploading the requested file. Please try after some time.");
		}
		if($is_error==0)
		{
			$sql_cuts = "SELECT MAX(CUTS) CUTS FROM billing.MATRI_CUTS WHERE PROFILEID='$profileid'";
			$res_cuts = mysql_query_decide($sql_cuts) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_cuts,"ShowErrTemplate");
			$row_cuts = mysql_fetch_array($res_cuts);
			//if user has uploaded a file.
			if($return_value != "NO_FILE")
				$cut = $row_cuts['CUTS']+1;
			else
				$cut = $row_cuts['CUTS'];

			$sql_ins = "INSERT INTO billing.MATRI_CUTS (PROFILEID, CUTS, ENTRY_DT, UPLOADED_BY, COMMENTS) VALUES('$profileid','$cut',now(),'USER','".addslashes(stripslashes($comments))."')";
			mysql_query_decide($sql_ins) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_ins,"ShowErrTemplate");

			$smarty->assign("DONE",1);
		}
	}
/*	else
	{
		//checking if the user has already uploaded once.
		$sql_check = "SELECT MAX(ENTRY_DT) ENTRY_DT,UPLOADED_BY FROM billing.MATRI_CUTS WHERE PROFILEID='$profileid' GROUP BY UPLOADED_BY ORDER BY ENTRY_DT DESC LIMIT 1";
		$res_check = mysql_query_decide($sql_check) or die($sql_check.mysql_error_js());
		$row_check = mysql_fetch_array($res_check);
		if($row_check["UPLOADED_BY"]=="USER")
		{
			$smarty->assign("MSG","You have already responded to this mail.<br>Jeevansathi executive will get back to you within 4 working days.<br>Thank you.");
			$smarty->assign("ERROR",1);
		}
	}*/

	$smarty->assign("recd_checksum",$recd_checksum);
	$smarty->assign("profileid",$profileid);
	$smarty->assign("username",$username);
	$smarty->assign("cut",$cut);
        $smarty->display("matri_upload_user.htm");
}
else
{
	TimedOut();
}
?>
