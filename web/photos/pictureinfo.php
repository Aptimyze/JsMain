<?php
/**
*       Filename        :       pictureinfo.php
*       Included        :       top.php, pictureinc.php
*       Description     :       displays and saves the employee picture
*       Created by      :       Tilak
*       Changed by      :       Om Prakash
*       Changed on      :       02-04-2004
*       Changes         :       partitioned in PHP code and HTML code
**/

include("../profile/connect.inc");
$db = connect_db();
mysql_select_db("jsadmin",$db);

/**
*       Included        :       pictureinc.php
*       Description     :       contains all functions related to picture save and update
**/
include_once('pictureinc.php');

                global $max_filesize;
                global $file;
if($skip){
        header("Location: probationinfo.php?cid=$cid&empno=$empno");
        die;
}

$file1 = "PHOTO";

$smarty->assign('profileid', $profileid);

$path = "/usr/local/apache/htdocs/hris/pics/";
$upload_file_name = "userfile";
$acceptable_file_types = "image/gif|image/jpeg|image/pjpeg|image/jpg";
$default_extension = ".jpg";
$mode = 1;

if ($submitted){ // After the form is submitted
	$max_filesize = 153600;
	if(upload($upload_file_name, $acceptable_file_types, $default_extension)){
		$success = save_file($path, $mode);
	}
	// If the image upload is successful
	if ($success){// Successful upload!
		$smarty->assign('message', "<br><br><center><h3><b>Image uploaded sucessfully </b></h3><br><br>Click <a href=\"empinfo.php?cid=".$cid."&empno=".$empno."\"  style=\"text-decoration:none;\"><b>Here</b></a> to Continue</center>");
		// Print all the array details...
		$file_name = $file[name];
		$ty = explode(".",$file_name);
		$image_type = $ty[1];
		// save the info
		$fp = fopen($_FILES["userfile"]["tmp_name"],"rb");
		$fcontent = fread($fp,filesize($_FILES["userfile"]["tmp_name"]));
		fclose($fp);
		$fcontent = addslashes($fcontent);
		$picarr = array("file_name"=>$file_name, "image_type"=>$image_type, "fcontent"=>$fcontent);
		pictureload($profileid, $picarr);
	}else{
			$smarty->display("uploadimg_error.htm");
			die;
	}
}elseif($acceptable_file_types)
		$smarty->assign('message', "This form only accepts following files <br><b>" . str_replace("|", " <br> ", $acceptable_file_types) ."</b>\n");

$filename = picturedisplay($profileid, $img);
$smarty->assign('filename', $filename);
$smarty->assign('image',$img);
$smarty->display("picture.htm");
die;
?>
