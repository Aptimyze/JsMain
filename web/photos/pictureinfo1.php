<?php
/**
*       Filename        :       pictureinfo1.php
*       Included        :       top.php, pictureinc.php
*       Description     :       displays and saves the employee picture
*       Created by      :       Tilak
*       Changed by      :       Om Prakash
*       Changed on      :       02-04-2004
*       Changes         :       partitioned in PHP code and HTML code
**/
include("connect.inc");
include ("flag1.php");
/**
*       Included        :       pictureinc.php
*       Description     :       contains all functions related to picture save and update
**/
include_once("pictureinc1.php");

global $max_filesize;
global $file;
$smarty->assign('profileid', $profileid);
$acceptable_file_types = "image/gif|image/jpeg|image/pjpeg|image/jpg";
$default_extension = ".jpg";
$mode = 1;
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");

function uploadfile($upload_file_name,$photo)
{
	global $acceptable_file_types,$default_extension,$path,$mode,$smarty,$profileid;
	global $file,$max_filesize,$username,$cid;
	$max_filesize = 153600;
	if(upload($upload_file_name, $acceptable_file_types, $default_extension))
	{
		$success = save_file($path, $mode);
	}
	// If the image upload is successful
	if ($success)
	{
		$fp = fopen($_FILES[$upload_file_name]["tmp_name"],"rb");
		$fcontent = fread($fp,filesize($_FILES[$upload_file_name]["tmp_name"]));
		fclose($fp);
		$fcontent = addslashes($fcontent);
		pictureload($profileid, $fcontent,$photo);
		$sqlget="SELECT PHOTOSCREEN FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
		$result=mysql_query_decide($sqlget);
		$myrow=mysql_fetch_array($result);
		
		$newphotoscreen=setFlag($photo,$myrow["PHOTOSCREEN"]);
		$sql= " UPDATE newjs.JPROFILE SET PHOTOSCREEN='$newphotoscreen' WHERE PROFILEID='$profileid' ";
		mysql_query_decide($sql);
		JProfileUpdateLib::getInstance()->removeCache($profileid);
		$msg="You have successfully uploaded the photos";
		$msg .="&nbsp;&nbsp;";
		$msg .="<a href=\"showprofilestoscreen.php?username=$username&cid=$cid\">";		
		$msg .="Continue&gt;&gt;</a>";
		$smarty->assign("MSG",$msg);
		$smarty->assign("name",$username);
		$smarty->display("jsadmin_msg.tpl");		
	}
	else
	{
		$msg="The image could not be uploaded ";
		$msg .="&nbsp;&nbsp;";
		$msg .="<a href=\"photo_display.php?username=$username&profileid=$profileid&cid=$cid\">";
		$msg .="Try again</a>";
		$smarty->assign("MSG",$msg);
		$smarty->assign("name",$username);
		$smarty->display("jsadmin_msg.tpl");
		die;
	}
}

if(authenticated($cid))
{	
	$count_photos_for_upload=0;
	if($mainphotofile)
	{
		$count_photos_for_upload=$count_photos_for_upload+1;
	}
	if($albumphoto1file)
	{
		$count_photos_for_upload=$count_photos_for_upload+1;
	}
	if($albumphoto2file)
	{
		$count_photos_for_upload=$count_photos_for_upload+1;
	}
	if($thumbnailfile)
	{
		$count_photos_for_upload=$count_photos_for_upload+1;
	}
	if($profilephotofile)
	{
		$count_photos_for_upload=$count_photos_for_upload+1;
	}	
	if($count_photos_for_upload==$count_photos)
	{
		if($mainphotofile)
		{
			$upload_file_name = "mainphotofile";
			$photo="MAINPHOTO";
			uploadfile($upload_file_name,$photo);			
		}
		if($albumphoto1file)
		{
			$upload_file_name = "albumphoto1file";
			$photo="ALBUMPHOTO1";
			uploadfile($upload_file_name,$photo);			
		}
		if($albumphoto2file)
		{
			$upload_file_name = "albumphoto2file";
			$photo="ALBUMPHOTO2";
			uploadfile($upload_file_name,$photo);			
		}
		if($thumbnailfile)
		{
			$upload_file_name = "thumbnailfile";
			$photo="THUMBNAIL";
			uploadfile($upload_file_name,$photo);
		}
		if($profilephotofile)
		{
			$upload_file_name = "profilephotofile";
			$photo="PROFILEPHOTO";
			uploadfile($upload_file_name,$photo);
		}
	}
	else
	{
		if($count_photos_for_upload==0)
			$msg="No images have been selected";
		else
			$msg="All the images have to be uploaded simultaneously";
		$msg .="&nbsp;&nbsp;";
		$msg .="<a href=\"photo_display.php?username=$username&profileid=$profileid&cid=$cid\">";
		$msg .="Upload again</a>";
		$smarty->assign("MSG",$msg);
		$smarty->assign("name",$username);
		$smarty->display("jsadmin_msg.tpl");		
	}
}	
else
{
	$msg="Your session has been timed out";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";	
	$smarty->assign("MSG",$msg);	
	$smarty->display("jsadmin_msg.tpl");
}	

?>
