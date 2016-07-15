<?php 
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include("$docRoot/crontabs/connect.inc");
//INCLUDE FILES HERE
$db=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
$sql="select PHOTO,ID from newjs.SUCCESS_STORIES where PHOTO<>'' order by PHOTO desc";
$res=mysql_query($sql,$db) or die($sql);
while($row=mysql_fetch_assoc($res))
{
	$photo=$row[PHOTO];
	$id=$row[ID];
	$picUrl=updatePicId($photo,$id."S","NonScreenedImages");
	if($picUrl)
	{
		$upSql="update newjs.SUCCESS_STORIES set PIC_URL='$picUrl' where ID='$id'";
		mysql_query($upSql,$db) or die(mysql_error().$sql);
                $cl_sql="insert into IMAGE_SERVER.LOG set MODULE_NAME='SUCCESS_STORY', MODULE_ID='$id', IMAGE_TYPE='S_P',STATUS='N'";
                mysql_query($cl_sql) or die(mysql_error().$sql);
	}
	
}

$sql="select SID,PICTURE,HOME_PICTURE from newjs.INDIVIDUAL_STORIES where PICTURE<>'' and HOME_PICTURE<>''";
$res=mysql_query($sql,$db) or die($sql);
while($row=mysql_fetch_assoc($res))
{
	$id=$row[SID];
	$mainpic=updatePicId($row[PICTURE],$id."M","ScreenedImages");
	$homepic=updatePicId($row[HOME_PICTURE],$id."H","ScreenedImages");
	
	$framepic = updatePicId(file_get_contents("$docRoot/web/success/images_06_05/".$id."_sm.jpg"),$id."F","ScreenedImages");
	
	if($mainpic && $homepic && $framepic)
	{
		$upSql="update newjs.INDIVIDUAL_STORIES set HOME_PIC_URL='$homepic',MAIN_PIC_URL='$mainpic',FRAME_PIC_URL='$framepic' where SID='$id'";
		mysql_query($upSql,$db) or die($sql);

		$cl_sql="insert into IMAGE_SERVER.LOG set MODULE_NAME='INDIVIDUAL_STORY', MODULE_ID='$id', IMAGE_TYPE='I_M',STATUS='N'";
		mysql_query($cl_sql) or die(mysql_error().$sql);
		$cl_sql="insert into IMAGE_SERVER.LOG set MODULE_NAME='INDIVIDUAL_STORY', MODULE_ID='$id', IMAGE_TYPE='I_H',STATUS='N'";
		mysql_query($cl_sql) or die(mysql_error().$sql);
		$cl_sql="insert into IMAGE_SERVER.LOG set MODULE_NAME='INDIVIDUAL_STORY', MODULE_ID='$id', IMAGE_TYPE='I_F',STATUS='N'";
		mysql_query($cl_sql) or die(mysql_error().$sql);
		

	}
	
}
function updatePicId($photo,$id,$where)
{
	global $docRoot;
//	global $SITE_URL;
$SITE_URL="JS";
	$filepath=$docRoot."/web/uploads/$where/story/$id.jpg";

	$fileAbs=$SITE_URL."/uploads/$where/story/$id.jpg";

	$file = fopen($filepath,"w");
	if($file)
	{
	   fwrite($file, $photo);
	   fclose($file);
		return $fileAbs;
	}
	else
	return false;
}
