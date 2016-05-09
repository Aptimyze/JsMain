<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/*****************************************************************************************************************
Filename    : remove_mailphotos.php
Description : Remove all screened photos from mail photos
Created By  : Sadaf Alam
Created On  : 17 Jan 2008
******************************************************************************************************************/
include("connect.inc");

$db=connect_db();

$path="/usr/local/mailphotos";

//$sql="SELECT MAILID FROM jsadmin.SCREEN_PHOTOS_FROM_MAIL WHERE STATUS IN('APPROVED','DELETED')";
$sql="SELECT MAILID FROM jsadmin.SCREEN_PHOTOS_FROM_MAIL WHERE STATUS LIKE 'APPROVED%' OR STATUS LIKE 'DELETED%'";
$res=mysql_query($sql) or logError("$sql");
if(mysql_num_rows($res)>0)
{
	while($row=mysql_fetch_assoc($res))
	{
		$sqlatt="SELECT FILENAME FROM jsadmin.PHOTO_ATTACHMENTS WHERE MAILID='$row[MAILID]'";
		$resatt=mysql_query($sqlatt) or logError("$sqlatt");
		if(mysql_num_rows($resatt)>0)
		{
			while($rowatt=mysql_fetch_assoc($resatt))
			{
				$filename=$rowatt["FILENAME"];
				if(file_exists("$path/$filename"))
				passthru("rm $path/$filename");
			}
		}
	}
}
?>
