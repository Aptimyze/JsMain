<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/********************************************************************************************************************
Filename    : write_pending_mailphotos.php
Description : Write the images/document attachments of pending mails to disk
Created By  : Sadaf Alam
Created On  : 17 Jan 2008
********************************************************************************************************************/
include("connect.inc");

$db=connect_db();

$path="/usr/local/mailphotos";

$sql="SELECT jsadmin.PHOTOS_FROM_MAIL.ID FROM jsadmin.PHOTOS_FROM_MAIL LEFT JOIN jsadmin.SCREEN_PHOTOS_FROM_MAIL ON jsadmin.PHOTOS_FROM_MAIL.ID = jsadmin.SCREEN_PHOTOS_FROM_MAIL.MAILID WHERE (SCREEN_PHOTOS_FROM_MAIL.MAILID IS NULL) AND ATTACHMENT='Y'";
$res=mysql_query($sql) or logError("$sql");
if(mysql_num_rows($res)>0)
{
	while($row=mysql_fetch_assoc($res))
	{
		$sqlatt="SELECT FILENAME,CONTENT FROM jsadmin.PHOTO_ATTACHMENTS WHERE MAILID='$row[MAILID]'";
		$resatt=mysql_query($sqlatt) or logError("$sqlatt");
		if(mysql_num_rows($resatt)>0)
		{
			while($rowatt=mysql_fetch_assoc($resatt))
			{
				$content=$rowatt["CONTENT"];
				$filename=$rowatt["FILENAME"];
				$fp=fopen("$path/$filename","wb");
				if($fp)
				{
					fwrite($fp,$content);
					fclose($fp);
				}
			}
		}
	}
}
?>
