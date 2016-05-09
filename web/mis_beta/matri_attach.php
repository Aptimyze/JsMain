<?php
include("../mis/connect.inc");
$db=mysql_connect("localhost","root","Km7Iv80l");
mysql_select_db("billing",$db);
//$db2=mysql_connect("localhost","root","Km7Iv801");
//mysql_select_db("newjs",$db2);
$smarty->assign("exec_email",$exec_email);
$smarty->assign("checksum",$checksum);
$target = "/usr/local/matri_profiles/";
$target1 = $target.basename($_FILES['uploaded']['name']);
$last = substr("$target1",-3,3);
        if($status=='N')         {
                $filename="1_".$username."_".$id;
        }
        else
        {
                $sql1="SELECT MAX(CUTS) cut FROM MATRI_FOLLOWUP WHERE PROFILEID='$id'";
                $res1=mysql_query_decide($sql1);
                $row1=mysql_fetch_array($res1);
                $cut=$row1['cut']+1;
                $filename=$cut."_".$username."_".$id;
        }
$target = $target.$filename.".doc";
if($id && !$sendmail) 
{
        if($_FILES['uploaded']['size']>0)
        {
                if($last != "doc")
                {
                        $smarty->assign("Invalidfile","The selected file is not a valid .doc file");
                        $smarty->assign("FLAG","1");
			$smarty->assign("status",$status);
			$smarty->assign("username",$username);
                        $smarty->assign("id",$id);
                        $smarty->display("matriprofile_attach_status.htm");
                }
                else
                {
                        $date=date('Y-m-d'); 
                        $date .= date('G-i-s');
                        $sql="SELECT USERNAME,EMAIL from newjs.JPROFILE where PROFILEID='$id'";
                        $res=mysql_query_decide($sql) or die("error".mysql_error_js());
                        $row=mysql_fetch_array($res);
                        $user=$row["USERNAME"];
                        $smarty->assign("to",$row['EMAIL']);
                        //$executive = getname($checksum);
                        if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target))
                        {
                                $smarty->assign("Done","The file ".basename($_FILES['uploaded']['name'])." has been succesfully uploaded");
				$smarty->assign("status",$status);
                                $cmd="chmod 777 $target";
                                passthru("$cmd");
                                $smarty->assign("FLAG","0");
                                $smarty->assign("id",$id);
				$smarty->assign("username",$username);
                                $smarty->assign("EMAIL",$row1['EMAIL']);
                                $smarty->display("matriprofile_attach_status.htm");
                        }
                        else
                        {
                                $smarty->assign("Error","There was a problem in uploading the requested file");
				$smarty->assign("status",$status);
				$smarty->assign("username",$username);
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
		$smarty->assign("status",$status);
		$smarty->assign("username",$username);
                $smarty->display("matriprofile_attach_status.htm");
        }
}
if($sendmail) 
{
	//echo $username;
	if($status=='N')
        {
		$filename="1_".$username."_".$id;
     	}
     	else
     	{
		$sql1="SELECT MAX(CUTS) cut FROM MATRI_FOLLOWUP WHERE PROFILEID='$id'";	
		$res1=mysql_query_decide($sql1);
		$row1=mysql_fetch_array($res1);
		$cut=$row1['cut']+1;
		$filename=$cut."_".$username."_".$id;
     	}	   
	$file_name=$filename;
        $path = "usr/local/matri_profiles/".$filename.".doc";
        $bcc="tanu.gupta@jeevansathi.com";
        send_doc_email("Matri Profile",$path,$to,$msg,$file_name,$cc1,$cc2,$bcc,$id);
	$ts=time();
	$today=date('Y-m-d G:i:s',$ts);	
	if($status=='N')
	{
	        $sql = "UPDATE MATRI_PROFILE SET STATUS='F',COMPLETION_TIME='$today' WHERE PROFILEID='$id'";
        	mysql_query_decide($sql) or die("error".mysql_error_js());
                $sql_ins="INSERT INTO MATRI_FOLLOWUP(PROFILEID,FOLLOWUP_TIME,CUTS) VALUES('$id','$today',1)";
                mysql_query_decide($sql_ins) or die ("error".mysql_error_js());
	}
	if($status=='F' || $status=='H')
	{
	        $sql = "UPDATE MATRI_PROFILE SET COMPLETION_TIME='$today' WHERE PROFILEID='$id'";
        	mysql_query_decide($sql) or die("error".mysql_error_js());
		$sql_ins="INSERT INTO MATRI_FOLLOWUP(PROFILEID,FOLLOWUP_TIME,CUTS) VALUES('$id','$today','$cut')";
		mysql_query_decide($sql_ins) or die ("error".mysql_error_js());
	}
	
}

function send_doc_email($sbj,$path,$to,$msg,$file_name,$cc1,$cc2,$bcc,$id) {
global $smarty;
$subject=$sbj;
$from = "webmaster@jeevansathi.com";
//$toname=$file_name;
$type = "application/msword";
$name = "/usr/local/matri_profiles/".$file_name.".doc";
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
                $sql = "select USERNAME from billing.MATRI_PROFILE where PROFILEID = '$id'";
                $res = mysql_query_decide($sql) or die("user error".mysql_error_js()) ;
                $row = mysql_fetch_array($res);
                $dispname = $row['USERNAME'];
                $smarty->assign("MailSent","Mail Sent to $dispname, with attachment.");
		$smarty->assign("status",$status);
		$smarty->assign("username",$username);
                $smarty->assign("FLAG","7");
                $smarty->display("matriprofile_attach_status.htm");
        }
        else
        {
                $sql = "UPDATE MATRI_PROFILE SET STATUS='N' where PROFILEID = '$id'";
                $result = mysql_query_decide($sql) or die("Error in deleting record".mysql_error_js());
                echo "Sorry, failed to send Email, please try again in a few minutes.";
        }
}
?>

