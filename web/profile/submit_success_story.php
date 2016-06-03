<?php
include("connect.inc");
include("../profile/uploadphoto_inc.php");
$acceptable_file_types = "image/gif|image/jpeg|image/pjpeg|image/jpg";
$default_extension = ".jpg";
global $max_filesize;
global $file;
$max_filesize=1048576;

$db=connect_db();

$data=authenticated($checksum);


if($submit_ss_flag)
{
	$spouse_name=trim($spouse_name);
	$spouse_id=trim($spouse_id);
	$spouse_email=trim($spouse_email);
	$photo_error=0;
        if($wedding_photo)
        {
		$filename="wedding_photo";

		if(upload($filename,$acceptable_file_types,$default_extension))
		{
			$fp = fopen($file["tmp_name"],"rb") or $flag_error=1;
			$fcontent = fread($fp,filesize($file["tmp_name"]));
			fclose($fp);
			$photo_content = addslashes($fcontent);

		}
		else
		{
			$smarty->assign("MSG","photo");
			$smarty->display("submit_success_story.htm");
			die;
		}
	}
	$check_email=checkemail1($spouse_email);
	if(!$check_email)
	{
		$smarty->assign("MSG","email_invalid");
		$smarty->display("submit_success_story.htm");
		die;
	}
	$sql="SELECT GENDER,PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$spouse_id'";
	$res=mysql_query_decide($sql) or logError("Error while submitting success story form",$sql);
	if(mysql_num_rows($res)==0)
	{
		$smarty->assign("MSG","user_invalid");
		$smarty->display("submit_success_story.htm");
		die;
	}
	else
	{
		$row=mysql_fetch_assoc($res);
		$spouse_gender=$row["GENDER"];
		$spouse_profileid=$row["PROFILEID"];
		mysql_free_result($res);
		$sql="SELECT GENDER FROM newjs.JPROFILE WHERE PROFILEID='$data[PROFILEID]'";
		$res=mysql_query_decide($sql) or logError("Error while submitting success story form",$sql);
		$row=mysql_fetch_assoc($res);
		if($row["GENDER"]==$spouse_gender)
		{
			$smarty->assign("MSG","same_gender");
			$smarty->display("submit_success_story.htm");
			die;
		}
		mysql_free_result($res);
	}
	$sql="SELECT PROFILEID FROM billing.PURCHASES WHERE PROFILEID IN ('$data[PROFILEID]','$spouse_profileid') AND SERVICEID!='M'";
	$res=mysql_query_decide($sql) or logError("Error while submitting success story form",$sql);
	if(mysql_num_rows($res)==0)
	{
		$smarty->assign("MSG","not_compatible");
		$smarty->display("submit_success_story.htm");
		die;
	}
	else
	{
		$contact_address=trim($contact_address);
		$ss_story=trim($ss_story);
		$spouse_name=htmlspecialchars(stripslashes($spouse_name),ENT_QUOTES);
		$contact_address=htmlspecialchars(stripslashes($contact_address),ENT_QUOTES);
		$ss_story=htmlspecialchars(stripslashes($ss_story),ENT_QUOTES);
		if($data["GENDER"]=="F")
		{
			$username_w=$username;
			$name_w=$my_name;
			$email_w=$email;
			$username_h=$spouse_id;
			$name_h=$spouse_name;
			$email_h=$spouse_email;
		}
		else
		{
			$username_w=$spouse_id;
			$name_w=$spouse_name;
			$email_w=$spouse_email;
			$username_h=$username;
			$name_h=$my_name;
			$email_h=$email;
		}

		$message.="NAME: ".$name_h."\t".$name_w."\t\r\n";
                $message.="USERNAME: ".$username_h."\t".$username_w."\t\r\n";//previously HUSBAND'S USERNAME
                //$message.="WIFE'S USERNAME: ".$username_w."\n";
                $message.="EMAIL: ".$email_h."\t".$email_w."\t\r\n";
                $message.="CONTACT DETAILS: ".$contact_details."\t\r\n";
                $message.="STORY: ".$comments."\t\r\n";

		//Sending email when anyone posts a success story
                send_email('vivek1804@gmail.com,nikhil.dhiman@jeevansathi.com',$message,"Success Story Received",$from="",$cc="",$bcc="",$fcontent,$_FILES[$filename]['type'],$_FILES[$filename]['name']);
		
		if($data)
                {
                        $gender=$data['GENDER'];

                        if($gender=='F')
                        {
                                $send_email=$email_h;
                                $EMAIL=$email_w;
                                $EMAIL1=$email_h;
                        }
                        else
                        {
                                $send_email=$email_w;
                                $EMAIL=$email_h;
                                $EMAIL1=$email_w;
                        }
                }
                else
                {
                        $send_email=$email_w;
                        $EMAIL=$email_h;
                        $EMAIL1=$email_w;
                }

		$date=$w_year."-".$w_month."-".$w_day;

		$sql="insert into SUCCESS_STORIES(`NAME_H`,`NAME_W`,`USERNAME`,`WEDDING_DATE`,`CONTACT_DETAILS`,`EMAIL`,`EMAIL_W`,`COMMENTS`,`DATETIME`,`USERNAME_H`,`USERNAME_W`,`PHOTO`,`UPLOADED`,`SEND_EMAIL`) values('$name_h','$name_w','$username','$date','$contact_address','$EMAIL','$EMAIL1','$ss_story',now(),'$username_h','$username_w','$photo_content','N','$send_email')";

                mysql_query_decide($sql) or logError("Error while submitting success story form",$sql);

		delete_profile($data["PROFILEID"],"I found my match on JeevanSathi.com");

		$MSG="verified";
		$smarty->assign("MSG",$MSG);
		$smarty->display("submit_success_story.htm");
		die;
	}
	

}

$sql="SELECT NAME FROM incentive.NAME_OF_USER WHERE PROFILEID='$data[PROFILEID]'";
$res=mysql_query_decide($sql) or logError("Error in displaying success story submit form",$sql);
$row=mysql_fetch_assoc($res);
$smarty->assign("NAME",$row["NAME"]);
mysql_free_result($res);

$sql="SELECT USERNAME,EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$data[PROFILEID]'";
$res=mysql_query_decide($sql) or logError("Error in displaying success story submit form",$sql);
$row=mysql_fetch_assoc($res);
$smarty->assign("USERNAME",$row["USERNAME"]);
$smarty->assign("EMAIL",$row["EMAIL"]);
$smarty->assign("CHECKSUM",$checksum);
$smarty->display("submit_success_story.htm");
die;
?>
