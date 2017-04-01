<?php

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it
	
/**
*       Included        :       connect.inc
*       Description     :       contains database connect functions and other common functions
**/
$flag_using_php5 = 1;
include("connect.inc");
$db = connect_db();

/**
*       Included        :       uploadphoto_inc.php
*       Description     :       contains all functions related to photo save and update
**/
include_once('uploadphoto_inc.php');
include_once('horoscope_upload.inc');
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");

/**
*       Included        :       flag.php
*       Description     :       contains all functions related to photo screening status flag
**/
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
/**
*       Included        :       functions.inc
*       Description     :       contain function to calculate profile percentage
**/
//include_once('functions.inc');

//global $max_filesize;
//global $file;

//print_r($_POST);

include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");

if(strstr($_SERVER['HTTP_USER_AGENT'],'MSIE 5.5'))
	$smarty->assign("class","hand");
else
	$smarty->assign("class","pointer");	

$acceptable_file_types = "image/gif|image/jpeg|image/pjpeg|image/jpg";
$default_extension = ".jpg";
$mode = 1;    
if(authenticated($cid))
{
	$operator_name=getname($cid);
	$smarty->assign("operator_name",$operator_name);
	$message="";
	/*login_relogin_auth($data);
	$profileid=$data["PROFILEID"];
	$smarty->assign("GENDER",$data['GENDER']);   //flag for headnew.htm tab
	$smarty->assign("head_tab","my jeevansathi");   //flag for headnew.htm tab
	$smarty->assign("CHECKSUM",$data["CHECKSUM"]);
	$smarty->assign("checksum",$data["CHECKSUM"]);
	$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
	$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
	*/	
	if($submitted)// After the form is submitted with upload button clicked
	{ 
		$filename="horoscope";
		$max_filesize = 1048576; //1MB
		$flag_error = 0;
		$flag_upload = 0;
		//upload horoscope
		if(upload($filename, $acceptable_file_types, $default_extension))
		{
			$fp = fopen($file["tmp_name"],"rb") or $flag_error=1;
			//echo "a=".filesize($file["tmp_name"])."b";
			$fcontent = fread($fp,filesize($file["tmp_name"]));
			fclose($fp);
			$horoscope_content = addslashes($fcontent);
			$flag_upload = 1;
			//$flag_error=0;
		}
		//elseif($mainphotofile)
		else
		{
			$flag_error = 1;
			$flag_upload=0;
		}
		/*elseif($main_photo_not_uploaded)
		{
			$msg="The main photo has to be uploaded";
			$smarty->assign("msg",$msg);
			$smarty->assign("url","1");
			$smarty->assign("link","<a href='$SITE_URL/P/uploadphoto.php?checksum=$CHECKSUM'>Back to Upload Photo</a>");
			$smarty->display("error.htm");
			die;
		}*/

		if($flag_error)//if the horoscope could not be uploaded to temp location
		{
			$msg="The horoscope could not be uploaded ";
			$msg .="&nbsp;&nbsp;";
			$msg .="<a href=\"show_horoscope.php?username=$username&profileid=$profileid&cid=$cid\">";
			$msg .="Upload again</a>";
			$smarty->assign("MSG",$msg);
			$smarty->assign("name",$username);
			$smarty->assign("cid",$cid);
			$smarty->display("jsadmin_msg.tpl");
			die;
                }

		/*if($flag_error)//if the horoscope could not be uploaded to temp location
		{
			$msg="There has been an error in uploading your horoscope.<br>The error could be due to:<br>1.&nbsp; No photo has been chosen. OR <br>2.&nbsp; The horoscope chosen is not in .jpg or .gif format. OR <br>3.&nbsp; The horoscope is more than 1 MB in size. <br><br>";
			$smarty->assign("msg",$msg);
			$smarty->assign("url","1");
			$smarty->assign("link","<a href='$SITE_URL/P/horoscope_upload.php?checksum=$CHECKSUM'>Back to Upload Horoscope</a>");
			$smarty->display("error.htm");
			die;
		}*/
		elseif($flag_upload)
		{	
			//successful upload of horoscope to a temporary location
			/*$picarr = array("main_photo"=>$main_photo_content, "album_photo1"=>$album_photo1_content, "album_photo2"=>$album_photo2_content);				
			photo_save($profileid, $picarr);//Save the photos in the database*/
			horoscope_save($profileid, $horoscope_content);//Save the horoscope in the database
      updateprofileCompletionScore($profileid);
			
			//section to send mail to the user that his/her horoscope has been successfully screened and uploaded
			$sql="select EMAIL,USERNAME,ACTIVATED from newjs.JPROFILE where PROFILEID='$profileid'";
			$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate") ;
			$myrow=mysql_fetch_array($result);
			$to=$myrow["EMAIL"];
			$to_user=$myrow["USERNAME"];
			$msg_str="and will now be visible, as the preference you have given, to other user who wishes to see your profile.";
			$message="Dear $to_user,<br><br>We thank you for your interest in Jeevansathi.com<br><br>This is to notify you that the horoscope you submitted with us have been screened through ".$msg_str.".<br><br>Here's wishing you all the best in your partner search.<br><br>With regards,<br>Jeevansathi.com Team";

			//$to="gaurav.arora@jeevansathi.com";
			send_email($to,$message);
			//mail($to,"",$message);
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
			$parameters = array("KEY"=>"AI_HORO","OTHER_PROFILEID"=>$profileid,"DATA"=>$profileid);
			//end of section to send mail to the user that his/her horoscope has been successfully screened and uploaded
			$msg="You have successfully uploaded the horoscope";
			$msg .="<a href=\"showhoroscopetoscreen.php?username=$username&cid=$cid\">";
			$msg .="Continue&gt;&gt;</a>";
			$smarty->assign("MSG",$msg);
			$smarty->assign("name",$username);
			$smarty->assign("cid",$cid);
			$smarty->display("jsadmin_msg.tpl");
		}
		/*else
		{
			$msg="There has been an error in uploading your horoscope.<br>The error could be due to:<br>1.&nbsp; No photo has been chosen. OR <br>2.&nbsp; The horoscope chosen is not in .jpg or .gif format. OR <br>3.&nbsp; The horoscope is more than 1 MB in size. <br><br>";
			$smarty->assign("msg",$msg);
			$smarty->assign("url","1");
			$smarty->assign("link","<a href='$SITE_URL/P/horoscope_upload.php?checksum=$CHECKSUM'>Back to Upload Horoscope</a>");
			$smarty->display("error.htm");
			die;

		}*/
	}
	else if($Delete)//after the form is submitted with Delete button clicked
	{
		//show confirm delete page
		$smarty->assign("name",$username);
		$smarty->assign("cid",$cid);
		$smarty->assign("profileid",$profileid);
		$smarty->display("confirm_delete_horoscope.htm");
		
		
	}
        elseif($cancel_delete)
        {
                //header("Location: http://".$_SERVER['HTTP_HOST']."/jeevansathi/jsadmin/show_horoscope.php?username=$username&profileid=$profileid&cid=$cid");
                header("Location: http://".$_SERVER['HTTP_HOST']."/jsadmin/show_horoscope.php?username=$username&profileid=$profileid&cid=$cid");
        }
	elseif($confirm_delete)
	{ 
		$reason="";
		if($delete_reason || $delete_reason_other)
		{
			$other_reason=$delete_reason_other;
			$delete_reason[]=$delete_reason_other;
			$reason1=reason($delete_reason,$other_reason);
			$reason.=$reason1;
                }

       
       if($reason)
                {
					$objUpdate = JProfileUpdateLib::getInstance();
					$result = $objUpdate->updateHOROSCOPE_FOR_SCREEN($profileid,array('UPLOADED'=>'D'));
					if(false === $result) {
						die('Issue while updating horoscope at line 199');
					}
			/*$sql="UPDATE newjs.HOROSCOPE_FOR_SCREEN SET UPLOADED='D' where PROFILEID='$profileid'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());*/

			//to update ASTRO_DETAILS table to set TYPE='S'
					$result = $objUpdate->updateASTRO_DETAILS($profileid,array('TYPE'=>'S'));
					if(false === $result) {
						die('Issue while updating horoscope at line 207');
					}
			/*$sql_update="update newjs.ASTRO_DETAILS set TYPE='S' where PROFILEID='$profileid'";
			mysql_query_decide($sql_update) or die("$sql_update".mysql_error_js());*/

			$sqlget="SELECT EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
			$result=mysql_query_decide($sqlget) or die("$sqlget".mysql_error_js());
			$myrow=mysql_fetch_array($result);
			
			//make an entry in the log
			$sql="SELECT RECEIVE_TIME FROM jsadmin.MAIN_ADMIN WHERE PROFILEID='$profileid' and SCREENING_TYPE='H'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$resf=mysql_fetch_array($res);
			$rec_time=$resf['RECEIVE_TIME'];
			$date_time=explode(" ",$rec_time);
			$date_y_m_d=explode("-",$date_time[0]);
			$time_h_m_s=explode(":",$date_time[1]);
			$timestamp=mktime($time_h_m_s[0],$time_h_m_s[1],$time_h_m_s[2],$date_y_m_d[1],$date_y_m_d[2],$date_y_m_d[0]);
			$timezone=date("T",$timestamp);
			if($timezone=="EDT")
			     $timezone="EST5EDT";
														     
			$sqladmin= "INSERT into jsadmin.MAIN_ADMIN_LOG (PROFILEID, USERNAME,      SCREENING_TYPE, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, SUBMITED_TIME, ALLOTED_TO, STATUS, SUBSCRIPTION_TYPE, SCREENING_VAL,TIME_ZONE, SUBMITED_TIME_IST) SELECT PROFILEID, USERNAME, SCREENING_TYPE, RECEIVE_TIME, SUBMIT_TIME, ALLOT_TIME, now(), ALLOTED_TO, 'DELETED', SUBSCRIPTION_TYPE, SCREENING_VAL,'$timezone', CONVERT_TZ(NOW(),'$timezone','IST') from jsadmin.MAIN_ADMIN where PROFILEID='$profileid' and SCREENING_TYPE='H'";
			mysql_query_decide($sqladmin) or die("$sqladmin".mysql_error_js());
			//delete from main_admin table
                        $sql="delete from jsadmin.MAIN_ADMIN where SCREENING_TYPE='H' AND PROFILEID='$profileid' " ;
                        mysql_query_decide($sql) or die("$sql".mysql_error_js());

			//send email
			$from="webmaster@jeevansathi.com";
			$to=$myrow['EMAIL'];
			//$to="gaurav.arora@jeevansathi.com";
			//if($confirm_sendemail=='Y')
			//mail($to,"",$reason);
			send_email($to,$reason,"",$from);
														     
			$msg="Horoscope for this profile have been deleted";
			$msg .="&nbsp;&nbsp;";
			$msg .="<a href=\"showhoroscopetoscreen.php?username=$username&cid=$cid\">";
			$msg .="Continue&gt;&gt;</a>";
			$smarty->assign("MSG",$msg);
			$smarty->assign("name",$username);
			$smarty->assign("cid",$cid);
			$smarty->display("jsadmin_msg.tpl");
      updateprofileCompletionScore($profileid);
			die;

		}
		else
		{
			$smarty->assign("name",$username);
			$smarty->assign("cid",$cid);
			$smarty->assign("profileid",$profileid);
			$smarty->assign("no_reason","true");
			$smarty->display("confirm_delete_horoscope.htm");
		}

  }    
	//$smarty->display("horoscope_status.htm");
	//$smarty->display("upload_photo_revamp.htm");
}
else 
{
	//TimedOut();	
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}

function val($reasonval,$other_reason)
{
        switch($reasonval)
        {
                case 1 : $msg="The horoscope is not clear.";
                        break;
                /*case 2 : $msg="The photo is a group photo. Please send your individual photo or identify yourself in the group.";
                        break;
                case 3 : $msg="The photo is of a well known personality. If the photo is yours then submit a proof of identity.";
                        break;
                case 4 : $msg="We find that the photo you have submitted is inappropriate.";
                        break;*/
                default :
                        if($other_reason)
                                $msg=" ".$other_reason." ";
                        break;
        }
        return $msg;
}


function reason($reasonarr,$other_reason)
{
        //$reason="JS/2004 \n";
        $reason.="Dear User, <br>";
        $reason.="Thank you for continuing interest in Jeevansathi.com. <br>";
        $reason.="Please note the following with respect to the horoscope you submitted. <br><br>";
                                                                                                                             
        $count=1;
        foreach($reasonarr as $reason1)
        {
                if(trim($reason1))
                {
                        $reason.=$count.". ".val($reason1,$other_reason)."<br>";
                        $count=$count+1;
                }
        }
                                                                                                                             
        $reason.="<br>Please reload the horoscope using a standard image format like jpg, gif, bmp, tif. <br>";
        $reason.="We regret the inconvenience caused to you. <br>";
        $reason.="Assuring you of our best services and wishing you, the very best in your endeavour. <br>";
        $reason.="<br>With Best Regards <br>Jeevansathi.com <br>";
                                                                                                                             
        return $reason;
}

function updateprofileCompletionScore($profileid){
  include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
  $cScoreObj = ProfileCompletionFactory::getInstance(null,null,$profileid);
  $cScoreObj->updateProfileCompletionScore();
  unset($cScoreObj);
}
// flush the buffer
if($zipIt)
	ob_end_flush();
?>
