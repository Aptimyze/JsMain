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
include("connect.inc");
$db = connect_db();

/**
*       Included        :       uploadphoto_inc.php
*       Description     :       contains all functions related to photo save and update
**/
include_once('uploadphoto_inc.php');
include_once('horoscope_upload.inc');

/**
*       Included        :       flag.php
*       Description     :       contains all functions related to photo screening status flag
**/
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
/**
*       Included        :       functions.inc
*       Description     :       contain function to calculate profile percentage
**/
include_once('functions.inc');

//global $max_filesize;
//global $file;

if(strstr($_SERVER['HTTP_USER_AGENT'],'MSIE 5.5'))
	$smarty->assign("class","hand");
else
	$smarty->assign("class","pointer");	

$acceptable_file_types = "image/gif|image/jpeg|image/pjpeg|image/jpg";
$default_extension = ".jpg";
$mode = 1;
$errorMsg = "Due to a temporary problem your request could not be processed. Please try after a couple of minutes";
$data=authenticated($checksum);
/*if($data["BUREAU"]==1 && ($mbureau=="bureau" || $_COOKIE['JSMBLOGIN']))
{
        $fromprofilepage=1;
        mysql_select_db_js('marriage_bureau');
        include('../marriage_bureau/connectmb.inc');
        $mbdata = authenticatedmb($mbchecksum);
        if(!$mbdata)timeoutmb();
        $smarty->assign('mbchecksum',$mbdata["CHECKSUM"]);
        $smarty->assign('source',$mbdata["SOURCE"]);
        mysql_select_db_js('newjs');
        $mbureau="bureau1";
}*/
/*************************************Portion of Code added for display of Banners*******************************/
$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("bms_topright",18);
$smarty->assign("bms_right",28);
$smarty->assign("bms_bottom",19);
$smarty->assign("bms_left",24);
$smarty->assign("bms_new_win",32);

//$regionstr=8;
//$zonestr="18";
//include("../bmsjs/bms_display.php");
/************************************************End of Portion of Code*****************************************/
/*********************Added By Shakti for Link tracking**********************************/
link_track("uploadphoto.php");
/****************************************************************************************/
if($data)
{
	login_relogin_auth($data);
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
	
	if($submitted)// After the form is submitted
	{ 
		$filename="horoscope";
		$max_filesize = 1048576; //1MB
		$flag_error = 0;
		$flag_upload = 0;
		
		//upload horoscope
		if(upload($filename, $acceptable_file_types, $default_extension)==0)
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
			if($registration_horo)
			{
                if($fromAPI){
                    die("ERROR");
                }
				echo "<script>parent.horo('ERROR')</script>";
				die;
			}
			else
			{
				$msg="There has been an error in uploading your horoscope.<br>The error could be due to:<br>1.&nbsp; No horoscope has been chosen. OR <br>2.&nbsp; The horoscope chosen is not in .jpg or .gif format. OR <br>3.&nbsp; The horoscope is more than 1 MB in size. <br><br>";
				$smarty->assign("msg",$msg);
				$smarty->assign("url","1");
				$smarty->assign("link","<a href='$SITE_URL/P/horoscope_details.php?checksum=$CHECKSUM'>Back to Upload Horoscope</a>");
				header("Location: $SITE_URL/profile/viewprofile.php?checksum=&profilechecksum=$pchecksum");
				die;
			}
		}
		elseif($flag_upload)
		{	
			//successful upload of horoscope to a temporary location
			/*$picarr = array("main_photo"=>$main_photo_content, "album_photo1"=>$album_photo1_content, "album_photo2"=>$album_photo2_content);				
			photo_save($profileid, $picarr);//Save the photos in the database*/
			horoscope_save($profileid, $horoscope_content);//Save the horoscope in the database
			include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
      $cScoreObj = ProfileCompletionFactory::getInstance(null,null,$profileid);
      $cScoreObj->updateProfileCompletionScore();
      unset($cScoreObj);
			$msg="Your scanned horoscope has been uploaded and is now under screening!<br>The horoscope will be visible on your profile within 2-3 working days.";
			$smarty->assign("msg",$msg);

			//to show horoscope details to the user
	                $sql_fetch = "SELECT PLACE_BIRTH,DTOFBIRTH,BTIME FROM newjs.ASTRO_DETAILS WHERE PROFILEID='$profileid'";
	                $res_fetch=mysql_query_decide($sql_fetch) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_fetch,"ShowErrTemplate");
			if($row_fetch=mysql_fetch_array($res_fetch))
			{
                                $astrodata['BTIME'] = $row_fetch['BTIME'];
                                $astrodata['DTOFBIRTH'] = $row_fetch['DTOFBIRTH'];
                                $astrodata['BPLACE'] = $row_fetch['PLACE_BIRTH'];
				$smarty->assign("astrodata",$astrodata);
			}
			

		}
		else
		{
			if($registration_horo)
			{
                if($fromAPI){
                    die("ERROR");
                }
				echo "<script>parent.horo('ERROR')</script>";
				die;
			}
			else
			{
				$msg="There has been an error in uploading your horoscope.<br>The error could be due to:<br>1.&nbsp; No photo has been chosen. OR <br>2.&nbsp; The horoscope chosen is not in .jpg or .gif format. OR <br>3.&nbsp; The horoscope is more than 1 MB in size. <br><br>";
				$smarty->assign("msg",$msg);
				$smarty->assign("url","1");
				$smarty->assign("link","<a href='$SITE_URL/P/horoscope_details.php?checksum=$CHECKSUM'>Back to Upload Horoscope</a>");
				$smarty->display("error.htm");
				die;
			}
		}
	}
	if($registration_horo)
	{
        if($fromAPI){
            die("OK");
        }
		echo "<script>parent.horo('OK')</script>";
		die;
	}
	else
	{
		$profileid=$data["PROFILEID"];
		///Duplication fields update on edit///////
		///////////////////////////////////////////
		$dup_fields=array("dtofbirth","citybirth","btime");
		duplication_fields_insertion($dup_fields,$profileid);
		
		$profilechecksum=md5($data["PROFILEID"])."i".$data["PROFILEID"];
        	echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/profile/viewprofile.php?checksum=$checksum&profilechecksum=$profilechecksum\"></body></html>";
	}
	//$smarty->display("horoscope_status.htm");
	//$smarty->display("upload_photo_revamp.htm");
}
else 
{
	TimedOut();	
}

// flush the buffer
if($zipIt)
	ob_end_flush();
?>
