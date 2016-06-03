<?php
//echo "<center>Temporarily disabled.<br>Please check this section in 10 min.</center>";
//exit;
/**
*       Filename        :       uploadphoto.php
*       Included        :       uploadphoto_inc.php
*       Description     :       upload and saves the user's photos in database against profile id
*       Created by      :       Alok
*       Changed by      :       
*       Changed on      :       16-10-2004
*       Changes         :       
**/

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

/**
*       Included        :       flag.php
*       Description     :       contains all functions related to photo screening status flag
**/
include_once('flag.php');

global $max_filesize;
global $file;

$acceptable_file_types = "image/gif|image/jpeg|image/pjpeg|image/jpg";
$default_extension = ".jpg";
$mode = 1;
$errorMsg = "Due to a temporary problem your request could not be processed. Please try after a couple of minutes";

if($mbureau="bureau" || $_COOKIE['JSMBLOGIN'])
{
        $fromprofilepage=1;
        mysql_select_db_js('marriage_bureau');
        include('../marriage_bureau/connectmb.inc');
        $mbdata = authenticatedmb($mbchecksum);
        if(!$mbdata)timeoutmb();
        $smarty->assign('mbchecksum',$mbdata["CHECKSUM"]);
        $smarty->assign('source',$mbdata["SOURCE"]);
        mysql_select_db_js('newjs');
        $mbureau="bureau";
}
$data=authenticated($checksum);

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
	$profileid=$data["PROFILEID"];
	if ($submitted)// After the form is submitted
	{ 
		$max_filesize = 512000; //500KB
		$flag_error = 0;
		$flag_upload = 0;
	
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
		$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
		
		//Make an entry in the photo_display field to control photos' visibility
		$sql="select PHOTO_DISPLAY from newjs.JPROFILE where newjs.JPROFILE.PROFILEID='$profileid'";
		$res=mysql_query_decide($sql);
		$myrow=mysql_fetch_array($res);
		if($photo_display!=$myrow["PHOTO_DISPLAY"])
			$photo_display_changed=1;
		else 
			$photo_display_changed=0;
			
		$sql="update newjs.JPROFILE set PHOTO_DISPLAY = '$photo_display', MOD_DT = NOW() where newjs.JPROFILE.PROFILEID='$profileid'";
		$result=mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");

		//upload main photo
		if(upload("mainphotofile", $acceptable_file_types, $default_extension)){
			$fp = fopen($file["tmp_name"],"rb") or $flag_error=1;
			$fcontent = fread($fp,filesize($file["tmp_name"]));
			fclose($fp);
			$main_photo_content = addslashes($fcontent);
			$flag_upload = 1;
			//$flag_error=0;
		}elseif($mainphotofile){
			$flag_error = 1;
			$flag_upload=0;
		}
		elseif($main_photo_not_uploaded){
			$msg="<div align=center class=mediumred><b>The main photo has to be uploaded</b></div>";
			//$msg .="&nbsp;&nbsp;";
			//$msg .="<a href=\"uploadphoto.php?checksum=$checksum\">";
			//$msg .="Upload again</a>";
			$smarty->assign("MSG",$msg);
			$smarty->display("uploadimg_error.htm");
			die;
		}

		//upload album photo one
		if(upload("albumphoto1file", $acceptable_file_types, $default_extension)){
			$fp = fopen($file["tmp_name"],"rb") or $flag_error=1;
			$fcontent = fread($fp,filesize($file["tmp_name"]));
			fclose($fp);
			$album_photo1_content = addslashes($fcontent);
			$flag_upload = 1;
			//$flag_error=0;
		}elseif($albumphoto1file){
			$flag_error = 1;
			$flag_upload=0;
		}

		//upload album photo two
		if(upload("albumphoto2file", $acceptable_file_types, $default_extension)){
			$fp = fopen($file["tmp_name"],"rb") or $flag_error=1;
			$fcontent = fread($fp,filesize($file["tmp_name"]));
			fclose($fp);
			$album_photo2_content = addslashes($fcontent);
			$flag_upload = 1;
			//$flag_error=0;
		}elseif($albumphoto2file){
			$flag_error = 1;
			$flag_upload=0;
		}
			
		if($flag_error)//if any of the photos could not be uploaded to temp location
		{
			if($photo_display_changed)//if the user has changed his photo display option							  //changing any of the photos	
			{
				$msg="<div align=center class=mediumblack>Your photo display option has been changed</div>";
				//$msg.="&nbsp;&nbsp;";
				//$msg.="<a href=\"uploadphoto.php?checksum=$checksum\">";
				//$msg .="Continue&gt;&gt;</a>";
				$smarty->assign("MSG",$msg);
				$smarty->display("img_msg_confirm.htm");
			}
			else
			{
				//$msg="The image(s) could not be uploaded ";
				//$msg .="&nbsp;&nbsp;";
				//$msg .="<a href=\"uploadphoto.php?checksum=$checksum\">";
				//$msg .="Upload again</a>";
				$smarty->assign("MSG",$msg);
				$smarty->display("uploadimg_error.htm");
				die;
			}	
		}
		elseif($flag_upload)//successful upload of photos to a temporary location
		{
			$picarr = array("main_photo"=>$main_photo_content, "album_photo1"=>$album_photo1_content, "album_photo2"=>$album_photo2_content);				
			photo_save($profileid, $picarr);//Save the photos in the database
			$msg="<div align=center class=mediumred><B>Your photo(s) has been uploaded and is now under processing! </B><br><br>The photo(s) will be attached to your profile and go online within 2-3 working days.</div>";
			//$msg .="&nbsp;&nbsp;";
			//$msg .="<a href=\"uploadphoto.php?checksum=$checksum\">";
			//$msg .="Continue&gt;&gt;</a>";
			$smarty->assign("MSG",$msg);
			$smarty->display("img_msg_confirm.htm");		
		}
		else
		{
			if($photo_display_changed)//if the user has changed his photo display option							  //changing any of the photos	
			{
				$msg="<div align=center class=mediumblack>Your photo display option has been changed</div>";
				//$msg.="&nbsp;&nbsp;";
				//$msg.="<a href=\"uploadphoto.php?checksum=$checksum\">";
				//$msg .="Continue&gt;&gt;</a>";
				$smarty->assign("MSG",$msg);
				$smarty->display("img_msg_confirm.htm");
			}
		}
	}
	//delete the selected photo from the user's profile
	elseif($Pdelete) {
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
		$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
		if($delete_photo=="all")
		{
			$sql="delete from newjs.PICTURE where PROFILEID='$profileid'";
			mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");
			$sql="update newjs.JPROFILE set PHOTOSCREEN='31',HAVEPHOTO='N',MOD_DT=NOW(), PHOTODATE=NOW() where PROFILEID='$profileid'";
			mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");
		}
		elseif($delete_photo=="albumphoto1")
		{
			$sql="update newjs.PICTURE, newjs.JPROFILE set newjs.PICTURE.ALBUMPHOTO1='', newjs.JPROFILE.PHOTODATE=NOW() where newjs.PICTURE.PROFILEID = newjs.JPROFILE.PROFILEID and newjs.JPROFILE.PROFILEID='$profileid'";
			mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");
		}
		elseif($delete_photo=="albumphoto2")
		{
			$sql="update newjs.PICTURE, newjs.JPROFILE set newjs.PICTURE.ALBUMPHOTO2='', newjs.JPROFILE.PHOTODATE=NOW() where newjs.PICTURE.PROFILEID = newjs.JPROFILE.PROFILEID and newjs.JPROFILE.PROFILEID='$profileid'";
			mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");
		}
		$msg="<div align=center class=mediumblack>You have deleted the photos</div>";
		//$msg .="&nbsp;&nbsp;";
		//$msg .="<a href=\"uploadphoto.php?checksum=$checksum\">";		
		//$msg .="Continue&gt;&gt;</a>";
		$smarty->assign("MSG",$msg);
		$smarty->display("img_msg_confirm.htm");
	}	
	else{
	
		$sql = "select PHOTO_DISPLAY,HAVEPHOTO,PHOTOSCREEN from newjs.JPROFILE where PROFILEID = '$profileid' ";
//		$sql = "select PHOTO_DISPLAY,HAVEPHOTO from newjs.JPROFILE where PROFILEID = '$profileid' ";
		$res = mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");
		$myrow = mysql_fetch_array($res);
	
		$sql_photo = "select MAINPHOTO, ALBUMPHOTO1, ALBUMPHOTO2 from newjs.PICTURE where PROFILEID = '$profileid'";
		$res_photo = mysql_query_decide($sql_photo) or logError($errorMsg,$sql,"ShowErrTemplate");
		$myrow_photo = mysql_fetch_array($res_photo);
	
		if(!$myrow["PHOTO_DISPLAY"])// || ($myrow["PHOTO_DISPLAY"] == "A"))
			$smarty->assign("PHOTODISPLAY","A");
		else
			$smarty->assign("PHOTODISPLAY",$myrow["PHOTO_DISPLAY"]);
	
		//Get the current status for the main photo , album photo 1 and album photo 2
//		if( (($myrow["HAVEPHOTO"] == "U") || $myrow["HAVEPHOTO"] == "E")&& ($myrow["PHOTOSCREEN"] !=31) && ($myrow["PHOTOSCREEN"] !=23) && ($myrow["PHOTOSCREEN"] !=15) && ($myrow["PHOTOSCREEN"] !=7) )
		if( ($myrow["PHOTOSCREEN"] !=31) && ($myrow["PHOTOSCREEN"] !=23) && ($myrow["PHOTOSCREEN"] !=15) && ($myrow["PHOTOSCREEN"] !=7) )
//		if($myrow_photo['MAINPHOTO_T']!='' || $myrow_photo['ALBUMPHOTO1_T']!='' || $myrow_photo['ALBUMPHOTO2_T']!='')
		{//condition true when photo is under screening
			$smarty->assign("SHOWDELETEBUTTON","N");
			if(!isFlagSet("MAINPHOTO",$myrow["PHOTOSCREEN"]))
//			if($myrow_photo['MAINPHOTO_T']!='' && $myrow_photo['MAINPHOTO']=='')
				$smarty->assign("MAINPHOTOSTATUS","Under processing");
			elseif($myrow_photo['MAINPHOTO'])//!='' && $myrow_photo['MAINPHOTO_T']=='')
			{
				$smarty->assign("MAINPHOTOSTATUS","Activated");
				$smarty->assign("SHOWDELETEBUTTON","Y");
			}
			else
				$smarty->assign("MAINPHOTOSTATUS","Not uploaded");

			if(!isFlagSet("ALBUMPHOTO1",$myrow["PHOTOSCREEN"]))
//			if($myrow_photo['ALBUMPHOTO1_T']!='' && $myrow_photo['ALBUMPHOTO1']=='')
				$smarty->assign("ALBUMPHOTO1STATUS","Under processing");
			elseif($myrow_photo['ALBUMPHOTO1'])//!='' && $myrow_photo['ALBUMPHOTO1_T']=='')
			{
				$smarty->assign("ALBUMPHOTO1STATUS","Activated");
				$smarty->assign("SHOWDELETEBUTTON","Y");
			}
			else
				$smarty->assign("ALBUMPHOTO1STATUS","Not uploaded");

			if(!isFlagSet("ALBUMPHOTO2",$myrow["PHOTOSCREEN"]))
//			if($myrow_photo['ALBUMPHOTO2_T']!='' && $myrow_photo['ALBUMPHOTO2']=='')
				$smarty->assign("ALBUMPHOTO2STATUS","Under processing");
			elseif($myrow_photo['ALBUMPHOTO2'])//!='' && $myrow_photo['ALBUMPHOTO2_T']=='')
			{
				$smarty->assign("ALBUMPHOTO2STATUS","Activated");
				$smarty->assign("SHOWDELETEBUTTON","Y");
			}
			else
				$smarty->assign("ALBUMPHOTO2STATUS","Not uploaded");

			$smarty->assign("SHOWUPLOADPHOTO","N");
			$smarty->assign("SHOWALTERNATEWAY","N");
	
		}
		elseif( ($myrow["PHOTOSCREEN"] == 31) || ($myrow["PHOTOSCREEN"] == 23) || ($myrow["PHOTOSCREEN"] == 15) || ($myrow["PHOTOSCREEN"] == 7) )
//		elseif($myrow_photo['MAINPHOTO_T']=='' || $myrow_photo['ALBUMPHOTO1_T']=='' || $myrow_photo['ALBUMPHOTO2_T']=='')
		{
			if($myrow["HAVEPHOTO"] == 'N')
			{//condition is true when photo is not available

				$smarty->assign("MAINPHOTOSTATUS","Not uploaded");
				$smarty->assign("ALBUMPHOTO1STATUS","Not uploaded");
				$smarty->assign("ALBUMPHOTO2STATUS","Not uploaded");
				
				$smarty->assign("SHOWUPLOADPHOTO","Y");
				$smarty->assign("SHOWALTERNATEWAY","Y");
				$smarty->assign("SHOWDELETEBUTTON","N");
			}
			elseif($myrow["HAVEPHOTO"] == 'Y')
			{//condition is true when photo is available and screened
	
				if($myrow_photo["MAINPHOTO"])
					$smarty->assign("MAINPHOTOSTATUS","Activated");
				else
					$smarty->assign("MAINPHOTOSTATUS","Not uploaded");

				if($myrow_photo["ALBUMPHOTO1"])
					$smarty->assign("ALBUMPHOTO1STATUS","Activated");
				else
					$smarty->assign("ALBUMPHOTO1STATUS","Not uploaded");

				if($myrow_photo["ALBUMPHOTO2"])
					$smarty->assign("ALBUMPHOTO2STATUS","Activated");
				else
					$smarty->assign("ALBUMPHOTO2STATUS","Not uploaded");

				$smarty->assign("SHOWUPLOADPHOTO","Y");
				$smarty->assign("SHOWALTERNATEWAY","Y");
				$smarty->assign("SHOWDELETEBUTTON","Y");
			}
		}
		else
		{
			$sql="";
			$errorMsg="There is a temporary problem.Please check after some time";
			logError($errorMsg,$sql,"ShowErrTemplate");
			//mail("alok@naukri.com","photo screening","PHOTOSCREEN VALUE is : $myrow[PHOTOSCREEN]\nProfile ID : $profileid");
		}
		if($mbureau=="bureau")
		{
			$smarty->assign('checksum',$checksum);               
	                $smarty->assign('cid',$checksum);
			$smarty->assign('source',$data["SOURCE"]);
	                $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
			$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));                                $smarty->assign("againstprofileid",$pid);
		}
		else
		{

			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
			$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
			$smarty->assign('profileid', $profileid);
		}
		$smarty->display("upload_photo_aj.htm");
	}
}
else 
{
	TimedOut();	
}

// flush the buffer
if($zipIt)
	ob_end_flush();
?>
