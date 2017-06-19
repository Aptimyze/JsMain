<?php                                                                                                                             
include('connect.inc');
$db2=connect_master();


$target = "/usr/local/matri_profiles/";
//$target = "/home/sriram/sriram/";

$target1 = $target.basename($_FILES['uploaded']['name']);

$last = substr("$target1",-3,3);

$target = $target.$id.".doc";

if($id && !$sendmail)                                                                                                        {
	if($_FILES['uploaded']['size']>0)
	{

		if($last != "doc")
		{
	        	$smarty->assign("Invalidfile","The selected file is not a valid .doc file");
			$smarty->assign("FLAG","1");
			$smarty->assign("id",$id);
			$smarty->display("matriprofile_attach_status.htm");
		}

		else
		{

			$date=date('Y-m-d');

			$date .= date('G-i-s');
		

			$sql="SELECT USERNAME,EMAIL from newjs.JPROFILE where PROFILEID='$id' ";
	
			$res=mysql_query_decide($sql,$db2) or die("error".mysql_error_js());
	
			$row=mysql_fetch_array($res);

			$user=$row["USERNAME"];

			$smarty->assign("to",$row['EMAIL']);

			$uploaded_by = getname($checksum);

       			$sql = "insert into billing.UPLOAD_MATRI_STATUS(USERNAME,PROFILEID,STATUS,UPLOAD_DATE,UPLOADED_BY) values('$user','$id','Y','$date','$uploaded_by')";
                                                                                                                            
	      		$result=mysql_query_decide($sql,$db2) or die("error".mysql_error_js());
	
			if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target))
		       	{
	       		        $smarty->assign("Done","The file ".basename($_FILES['uploaded']['name'])." has been succesfully uploaded");
				$cmd="chmod 777 $target";
       				passthru("$cmd");
				$smarty->assign("FLAG","0");
				$smarty->assign("id",$id);
				$smarty->assign("EMAIL",$row1['EMAIL']);
				$smarty->display("matriprofile_attach_status.htm");
	
		        }
		        else
		        {
			        $smarty->assign("Error","There was a problem in uploading the requested file");
				$smarty->assign("FLAG","3");
		 		$smarty->assign("id",$id);	
		 		$smarty->display("matriprofile_attach_status.htm");
	       		}
		}
	}
	else
	{	
		$smarty->assign("FLAG","5");
		$smarty->assign("id",$id);
		$smarty->display("matriprofile_attach_status.htm");
	}	
}

if($sendmail)
{
	$path = "usr/local/matri_profiles/".$id.".doc";
	$bcc="sriram.viswanathan@jeevansathi.com";
	send_doc_email("Matri Profile",$path,$to,$msg,$id,$cc1,$cc2,$bcc);
}

function send_doc_email($sbj,$path,$to,$msg,$id,$cc1,$cc2,$bcc)
{
                                                                                                                             
global $smarty;
                                                                                                                             
$subject=$sbj;
$from = "webmaster@jeevansathi.com";
$toname=$id;
$type = "application/msword";
$name = "/usr/local/matri_profiles/".$id.".doc";
$toemail="$to";
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
           "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
           "Content-Transfer-Encoding: 7bit\n\n" .
           $message . "\n\n";
                                                                                                                             
// insert a boundary to indicate start of the attachment
// specify content type, file name, and attachment
// then add file content and set another boundary
$message .= "--{$mime_boundary}\n" .
            "Content-Type: {$type};\n" .
            " name=Matri-Profile.doc\n" .
                " name=\"{$name}\"\n" .
            "Content-Transfer-Encoding: base64\n\n" .
            $data . "\n\n" .
            "--{$mime_boundary}--\n";
                                                                                                                             
// send the message
//mail($email, $sub, $msg1,"From: $from\r\n"."Cc: $cc\r\n"."Bcc: $bcc\r\n"."X-Mailer: PHP/" . phpversion());
        if (@mail($toemail, $subject, $message, $headers))
        {
                $sql = "select USERNAME from billing.UPLOAD_MATRI_STATUS where PROFILEID = '$id'";
                $res = mysql_query_decide($sql) or die("user error".mysql_error_js()) ;
                $row = mysql_fetch_array($res);
                $dispname = $row['USERNAME'];
                $smarty->assign("MailSent","Mail Sent to $dispname, with attachment.");
                $smarty->assign("FLAG","7");
                $smarty->display("matriprofile_attach_status.htm");
        }
        else
        {
                                                                                                                           
                $sql = "delete from billing.UPLOAD_MATRI_STATUS where PROFILEID = '$id'";
                                                                                                                             
                $result = mysql_query_decide($sql) or die("Error in deleting record".mysql_error_js());

                echo "Sorry, failed to send Email, please try again in a few minutes.";
        }
}
?>
