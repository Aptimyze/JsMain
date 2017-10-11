<?php
/***************************************************************************************************************************
FILE NAME		: matri_attach.php
DESCRIPTION		: This file is used to upload a file to a particular location.
MODIFICATION DATE	: July 14th 2007.
MODIFIED BY		: Sriram Viswanathan.
***************************************************************************************************************************/
include("connect.inc");
include("matri_functions.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");

if(authenticated($checksum))
{
	//when Upload button is clicked.
	if($Upload)
	{
		//upload the file.
		$return_value = upload_matri_profile("uploaded",$profileid,$username,$status);

		if($return_value=="NO_FILE")
			$smarty->assign("MSG","File doesn't exist.");
		elseif($return_value=="INVALID_FILE")
			$smarty->assign("MSG","You can upload only .doc or .rtf or .txt files.");
		elseif($return_value=="UPLOAD_PROBLEM")
			$smarty->assign("MSG","There was a problem in uploading the requested file.");
		elseif($return_value == "SUCCESSFUL")
		{
			if($status=='F' or $status=='H' or $status=='NY')
			{
				$sql="SELECT USERNAME,EMAIL from newjs.JPROFILE where PROFILEID='$profileid'";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$row=mysql_fetch_array($res);

				if($template==1)
					$content="Dear $username,\n\nThank you for choosing to avail of the services of Jeevansathi.com\n\nWe enclose herein the detailed profile as developed by us based on the inputs provided by you. Please peruse the same and should you require any modifications, please feel free to get in touch with us within the next fifteen days from today.\n\nPlease fill up the areas in blue (if asked) with the requisite information in a third font colour.\n\nDo let us know if you want us to wait.\n\nWith regards,\n$SR_EXECUTIVE";
				elseif($template==2)
					$content="Dear $username,\n\nPlease find attached the modified profile based on your inputs. Kindly peruse the same and should you require any further modifications, please feel free to get in touch with us.\n\nDo let us know if you want us to wait.\n\nWith regards,\n$SR_EXECUTIVE";
				else
					$content="Dear $username,\n\nAs per your consent we are uploading the profile on our site.\n\nThanks for availing the services of jeevansathi.com\n\nRegards,\n$SR_EXECUTIVE";

				$smarty->assign("content",$content);
				$smarty->assign("template",$template);
				$smarty->assign("SUCCESSFUL",1);
				$smarty->assign("SENDMAIL",1);
				$smarty->assign("EMAIL",$row1['EMAIL']);
				$smarty->assign("to",$row['EMAIL']);
				$smarty->assign("MSG","The file ".basename($_FILES['uploaded']['name'])." has been succesfully uploaded");
			}
			//file being sent to team leader for verification.
			elseif($status=='N')
			{
				$sql1="UPDATE billing.MATRI_PROFILE SET STATUS='Y',COMPLETION_TIME=now() WHERE PROFILEID='$profileid'";
				mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
				$smarty->assign("SUCCESSFUL",1);
				$smarty->assign("SHOW_CLOSE_BUTTON",1);
				$smarty->assign("MSG","Profile sent to team leader for verfication.");
			}
		}
	}
	//when sendmail button is clicked.
	elseif($sendmail) 
	{
		//finding the file to send.
		$filename = get_file_name($profileid,$username);
		$cut = explode("_",$filename);

		//path to the file.
		$path = $FILE_PATH.$filename.".doc";

		//link to be sent to user to upload the modified matri-profile(if any change is required.)
		if($template==1 || $template==2)
		{
			$to_send_checksum = to_mail_checksum($profileid);
			$msg .= "\n\nNote: Please do not reply to this mail at matriprofile@jeevansathi.com.\n\n To reply and upload your modified Matri-profile, please <a href=\"$SITE_URL/profile/matri_upload_user.php?recd_checksum=$to_send_checksum&profileid=$profileid&username=$username\" target=\"_blank\">click here</a>.";
		}

		//if sending profile for the first time.
		if($status=='NY')
		{
			//setting profile status for followup.
			$sql_upd = "UPDATE billing.MATRI_PROFILE SET COMPLETION_TIME=now(),STATUS='F' WHERE PROFILEID='$profileid'";
			mysql_query_decide($sql_upd) or die("$sql_upd".mysql_error_js());
			$status='F';
		}

		if(send_doc_email("",$path,$to,nl2br($msg),$filename,$cc1,$cc2,$bcc,$profileid))
		{
			//insert into MATRI_CUTS for file(s) naming and tracking.
			$uploaded_by = getname($checksum);
			$sql_ins = "INSERT INTO billing.MATRI_CUTS(PROFILEID,CUTS,ENTRY_DT,UPLOADED_BY,COMMENTS) VALUES('$profileid','$cut[0]',now(),'$uploaded_by','".addslashes(stripslashes($msg))."')";
			mysql_query_decide($sql_ins) or die($sql_ins.mysql_error_js());

			$smarty->assign("SUCCESSFUL",1);
			$smarty->assign("SHOW_CLOSE_BUTTON",1);
			$smarty->assign("MAILSENT","Mail sent to $username successfully.");
		}
		else
			$smarty->assign("MSG","Sorry, There was an error in sending mail.");
		
	}
	$smarty->assign("profileid",$profileid);
	$smarty->assign("username",$username);
	$smarty->assign("status",$status);
	$smarty->assign("checksum",$checksum);
	$smarty->assign("SER6_URL",$SER6_URL);
	$smarty->assign("MATRI_MESSAGE",$smarty->fetch("matri_message.htm"));
	$smarty->display("matri_attach.htm");
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>

