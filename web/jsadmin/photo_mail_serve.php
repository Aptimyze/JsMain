<?php

include(JsConstants::$docRoot."/jsadmin/connect.inc");
include_once(JsConstants::$docRoot."/profile/SymfonyPictureFunctions.class.php");

$db=connect_db();

if($mailid)
{
        /*$sql="SELECT CONTENT FROM jsadmin.PHOTO_ATTACHMENTS WHERE MAILID='$mailid' AND FILENAME='$filename'";
        $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
        $myrow=mysql_fetch_assoc($result);*/
	
	$path=sfConfig::get("sf_upload_dir")."/MailImages";
	$fp =fopen("$path/$filename","rb");
	if($fp)
	{
		$fcontent=fread($fp,filesize("$path/$filename"));
		fclose($fp);
	}
        header('Content-type: image/jpeg');
	echo $fcontent;
}
if($id)
{
	
	$sql="SELECT PHOTO FROM newjs.SUCCESS_STORIES WHERE ID='$id'";
	$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$row=mysql_fetch_assoc($result);
	header('Content-type: image/jpeg');
	echo $row["PHOTO"];
}
if($sid)
{
	if($get_home_photo_from_photo_upload)
		$sql="SELECT HOME_PICTURE as PICTURE FROM newjs.INDIVIDUAL_STORIES WHERE SID='$sid'";
	elseif($get_home_photo_from_pool_upload) 
		$sql="SELECT HOME_PICTURE as PICTURE FROM newjs.INDIVIDUAL_STORIES WHERE STORYID='$sid'";
	else
		$sql="SELECT PICTURE FROM newjs.INDIVIDUAL_STORIES WHERE SID='$sid'";
	$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$row=mysql_fetch_assoc($result);
	header('Content-type: image/jpeg');
	echo $row["PICTURE"];
//	readfile("/var/www/html/profile/images/inter_logo_new.gif");
}

?>
