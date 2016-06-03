<?php
/**************************************************************************************************************************
FILE		: matri_functins.inc
DESCRIPTION	: This file contains functions related to matri-profile module.
CREATED BY	: Sriram Viswanathan.
CREATION DATE	: July 13th 2007.
**************************************************************************************************************************/
$RATING = array("0" => "No Rework",
		"1" => "Minor Rework",
		"2" => "Major Rework",
		"3" => "Re-do");

//senior executive name, this is the name sent in the mail to the user.
//$SR_EXECUTIVE = "Ms. Santvana Dass";
$SR_EXECUTIVE = "Jeevansathi Team";

//path where matri-profile doc file is stored.
$FILE_PATH = "/usr/local/matri_profiles/";

$SER6_URL = "http://ser6.jeevansathi.com";

//this function is used to find the counts of matri-profile with various status's.
//this function returns count.
function get_matri_count($dbname,$table_name,$status="",$allotted_to="")
{
	if($dbname=="")
		$dbname="billing";

	$sql = "SELECT COUNT(DISTINCT PROFILEID) AS COUNT FROM $dbname.$table_name WHERE";

	if($status)
		$sql_arr[] = " STATUS='$status'";
	if($allotted_to)
		$sql_arr[] = " ALLOTTED_TO='$allotted_to'";

	if(count($sql_arr)> 0)
		$sql .= implode(" AND ",$sql_arr);
	else
		$sql .= " 1";

	$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$row = mysql_fetch_array($res);

	return $row['COUNT'];
}

//this function is used to send mail to user with doc file in attachment.
function send_doc_email($sbj,$path,$to,$msg,$file_name,$cc1,$cc2,$bcc,$profileid) 
{
	global $smarty;
	global $FILE_PATH;
	$subject = "Matri Profile";
	$from = "Jeevansathi.com <matriprofile@jeevansathi.com>";
	$type = "application/msword";
	$name = $FILE_PATH.$file_name.".doc";
	$toemail="$to";
	$bcc = "sriram.viswanathan@jeevansathi.com";
	// generate a random string to be used as the boundary marker
	$mime_boundary="==Multipart_Boundary_x".md5(mt_rand())."x";
	$message = $msg;
	// open and read the file as binary
	$file = fopen($name,'rb');
	$data = fread($file,filesize($name));
	fclose($file);
	// encode and split it into acceptable length lines
	$data = chunk_split(base64_encode($data));
	// message headers
	$headers = "From: $from\r\n" ."Cc: $cc1,$cc2\r\n".
			"Bcc: $bcc\r\n".
		   "MIME-Version: 1.0\r\n" .
		   "Content-Type: multipart/mixed;\r\n" .
		   " boundary=\"{$mime_boundary}\"";
	// message body
	$message = "This is a multi-part message in MIME format.\n\n" .
		   "--{$mime_boundary}\n" .
		   "Content-Type: text/html; charset=\"iso-8859-1\"\n" .
		   "Content-Transfer-Encoding: 7bit\n\n" .
		   $message . "\n\n";
	// insert a boundary to indicate start of the attachment
	// specify content type, file name, and attachment
	// then add file content and set another boundary
	$message .= "--{$mime_boundary}\n" .
		    "Content-Type: {$type};\n" .
		    " name=Matri-Profile.doc\n" .
		    "Content-Transfer-Encoding: base64\n\n" .
		    $data . "\n\n" .
		    "--{$mime_boundary}--\n";
	// send the message
	//$toemail = "sriram.viswanathan@jeevansathi.com";
	//$subject = "Testing_matri";
	if (@mail($toemail, $subject, $message, $headers))
		return true;
	else
	{
		$sql = "UPDATE billing.MATRI_PROFILE SET STATUS='N' where PROFILEID = '$profileid'";
		mysql_query_decide($sql) or die("$sql".mysql_error_js());
		return false;
	}
}

//this function is used to upload the selected file to a specfied location.
//this function returns SUCCESSFUL, if file is successfully uploaded.
function upload_matri_profile($field_name,$profileid,$username,$status="")
{
	$allowed_formats = array("doc","rtf","txt");
	$target = "/usr/local/matri_profiles/";

	if(is_array($_FILES[$field_name]['name']))
	{
		$file_name = $_FILES[$field_name]['name'][$profileid];
		$file_size = $_FILES[$field_name]['size'][$profileid];
		$file_tmp_name = $_FILES[$field_name]['tmp_name'][$profileid];
	}
	else
	{
		$file_name = $_FILES[$field_name]['name'];
		$file_size = $_FILES[$field_name]['size'];
		$file_tmp_name = $_FILES[$field_name]['tmp_name'];
	}

	$to_store_file = get_file_name($profileid,$username);

	$file_extension = substr($file_name,-3,3);

	if($file_size > 0)
	{
		if(in_array($file_extension, $allowed_formats))
		{
			$location = $target.$to_store_file.".".$file_extension;
			if(move_uploaded_file($file_tmp_name,$location))
			{
				$command = "chmod 777 $location";
				passthru($command);

				return "SUCCESSFUL";
			}
			else
				return "UPLOAD_PROBLEM";
		}
		else
			return "INVALID_FILE";
	}
	else
		return "NO_FILE";
}

//this function is used to find the file name, depending on profileid and username.
function get_file_name($profileid,$username)
{
	$sql = "SELECT MAX(CUTS) CUTS FROM billing.MATRI_CUTS WHERE PROFILEID='$profileid'";
	$res = mysql_query_decide($sql) or die($sql.mysql_error_js());
	$row = mysql_fetch_array($res);

	if($row['CUTS'] > 0)
	{
		$cuts = $row['CUTS']+1;
		return $cuts."_".$username."_".$profileid;
	}
	else
		return "1_".$username."_".$profileid;
}

//function to set checksum to be sent in mail.
function to_mail_checksum($profileid)
{
	return md5($profileid)."i".$profileid;
}
?>
