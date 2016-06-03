<?php
/**
*       Filename        :       uploadphoto.php
*       Included        :       uploadphoto_inc.php
*       Description     :       upload and saves the user's photos in database against profile id
*       Created by      :       Alok
*       Changed by      :       
*       Changed on      :       16-10-2004
*       Changes         :       
**/

/**
*       Included        :       connect.inc
*       Description     :       contains database connect functions and other common functions
**/
include("../profile/connect.inc");
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
include_once(JsConstants::$docRoot."/commonFiles/flag.php");

	global $max_filesize;
	global $file;

$smarty->assign('profileid', $profileid);

//$upload_file_name = "userfile";
$acceptable_file_types = "image/gif|image/jpeg|image/pjpeg|image/jpg";
$default_extension = ".jpg";
$mode = 1;

if ($submitted){ // After the form is submitted
	$max_filesize = 153600;
	$flag_error = 1;
	$flag_upload = 0;

	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
        $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
        $smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
        $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
        $smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));

	//For main Photo
	if(upload($main_photo, $acceptable_file_types, $default_extension)){
		$fp = fopen($file["tmp_name"],"rb");
		$fcontent = fread($fp,filesize($file["tmp_name"]));
		fclose($fp);
		$main_photo_content = addslashes($fcontent);
		$flag_upload = 1;
		$flag_error = 0;

	}else{
		;//$flag_error = 0;
	}

	//For Album Photo 1
	if(upload($album_photo_one, $acceptable_file_types, $default_extension)){
		$fp = fopen($file["tmp_name"],"rb");
		$fcontent = fread($fp,filesize($file["tmp_name"]));
		fclose($fp);
		$album_photo1_content = addslashes($fcontent);
		$flag_upload = 1;
		$flag_error = 0;

	}else{
		;//$flag_error = 0;
	}

	//For Album Photo 2
	if(upload($album_photo_two, $acceptable_file_types, $default_extension)){
		$fp = fopen($file["tmp_name"],"rb");
		$fcontent = fread($fp,filesize($file["tmp_name"]));
		fclose($fp);
		$album_photo2_content = addslashes($fcontent);
		$flag_upload = 1;
		$flag_error = 0;

	}else{
		;//$flag_error = 0;
	}
	if($flag_error){
			$smarty->assign("MSG","Photo Not Uploaded.<BR>Please Try Again.");
			$smarty->display("uploadimg_error.htm");
			die;
	}

	if($flag_upload){
		$picarr = array("main_photo"=>$main_photo_content, "album_photo1"=>$album_photo1_content, "album_photo2"=>$album_photo2_content);
		photo_save($profileid, $picarr);
	}

	$smarty->assign("MSG","Photo Uploaded");
        $smarty->display("uploadimg_error.htm");

}
else{

	echo $sql = "select PHOTO_DISPLAY, HAVEPHOTO, PHOTOSCREEN from JPROFILE where PROFILEID = '$profileid'";
	$res = mysql_query_decide($sql);
	$myrow = mysql_fetch_array($res);

	$sql_photo = "select MAINPHOTO, ALBUMPHOTO1, ALBUMPHOTO2 from PICTURE where PROFILE_ID = '$profileid'";
	$res_photo = mysql_query_decide($sql_photo);
	$myrow_photo = mysql_fetch_array($res_photo);

	if(!$myrow["PHOTO_DISPLAY"] || ($myrow["PHOTO_DISPLAY"] == "A"))
		$smarty->assign("PHOTODISPLAY","A");
	else
		$smarty->assign("PHOTODISPLAY",$myrow["PHOTO_DISPLAY"]);

	echo "<br>PHOTOSCREEN : ".$myrow["PHOTOSCREEN"]."  ".$myrow["HAVEPHOTO"];
	if(($myrow["HAVEPHOTO"] != "N") && ($myrow["PHOTOSCREEN"] < 31))
	{//condition true when photo is under screening
	
		if(!isFlagSet("PHOTO1",$myrow["PHOTOSCREEN"]))
			$smarty->assign("MAINPHOTOSTATUS","Under processing");
		elseif($myrow_photo["MAINPHOTO"])
			$smarty->assign("MAINPHOTOSTATUS","Attached");
		else
			$smarty->assign("MAINPHOTOSTATUS","Not Uploaded");

		if(!isFlagSet("PHOTO2",$myrow["PHOTOSCREEN"]))
			$smarty->assign("ALBUMPHOTO1STATUS","Under processing");
		elseif($myrow_photo["ALBUMPHOTO1"])
			$smarty->assign("ALBUMPHOTO1STATUS","Attached");
		else
			$smarty->assign("ALBUMPHOTO1STATUS","Not Uploaded");

		if(!isFlagSet("PHOTO3",$myrow["PHOTOSCREEN"]))
			$smarty->assign("ALBUMPHOTO2STATUS","Under processing");
		elseif($myrow_photo["ALBUMPHOTO2"])
			$smarty->assign("ALBUMPHOTO2STATUS","Attached");
		else
			$smarty->assign("ALBUMPHOTO2STATUS","Not Uploaded");

		$smarty->assign("SHOWUPLOADPHOTO","N");
		$smarty->assign("SHOWALTERNATEWAY","N");
		$smarty->assign("SHOWDELETEBUTTON","N");

	}
	elseif($myrow["PHOTOSCREEN"] == 31)
	{
		if($myrow["HAVEPHOTO"] == 'N')
		{//condition is true when photo is not available

			$smarty->assign("MAINPHOTOSTATUS","Not Uploaded");
			$smarty->assign("ALBUMPHOTO1STATUS","Not Uploaded");
			$smarty->assign("ALBUMPHOTO2STATUS","Not Uploaded");
			
			$smarty->assign("SHOWUPLOADPHOTO","Y");
			$smarty->assign("SHOWALTERNATEWAY","Y");
			$smarty->assign("SHOWDELETEBUTTON","N");

		}
		elseif($myrow["HAVEPHOTO"] == 'Y')
		{//condition is true when photo is available and screened

			if($myrow_photo["MAINPHOTO"])
				$smarty->assign("MAINPHOTOSTATUS","Attached");
			else
				$smarty->assign("MAINPHOTOSTATUS","Not Uploaded");

			if($myrow_photo["ALBUMPHOTO1"])
				$smarty->assign("ALBUMPHOTO1STATUS","Attached");
			else
				$smarty->assign("ALBUMPHOTO1STATUS","Not Uploaded");

			if($myrow_photo["ALBUMPHOTO2"])
				$smarty->assign("ALBUMPHOTO2STATUS","Attached");
			else
				$smarty->assign("ALBUMPHOTO2STATUS","Not Uploaded");
		
			$smarty->assign("SHOWUPLOADPHOTO","Y");
			$smarty->assign("SHOWALTERNATEWAY","Y");
			$smarty->assign("SHOWDELETEBUTTON","Y");
		}
	}
	else
	{
		mail("alok@naukri.com","photo screening","PHOTOSCREEN VALUE is : $myrow[PHOTOSCREEN]\nProfile ID : $profileid");
	}

	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));

	$smarty->display("upload_photo.htm");
}
?>
