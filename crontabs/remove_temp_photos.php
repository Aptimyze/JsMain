<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


include("connect.inc");
$db=connect_db();

//Code added by Sadaf on 10 Jul 2007 to delete attachments of mails that have already been screened from jsadmin.PHOTO_ATTACHMENTS 
$sql="LOCK TABLES jsadmin.PHOTO_ATTACHMENTS WRITE,jsadmin.SCREEN_PHOTOS_FROM_MAIL WRITE";
mysql_query($sql) or logError($sql);

//Symfony Photo Modification
$sql = "SELECT MAILID FROM jsadmin.SCREEN_PHOTOS_FROM_MAIL  WHERE STATUS LIKE 'APPROVED%' OR STATUS LIKE 'DELETED%'";
//Symfony Photo Modification ends

$result=mysql_query($sql) or logError($sql);
if(mysql_num_rows($result)>0)
{
	while($row=mysql_fetch_assoc($result))
	{
		$mailid=$row["MAILID"];
		$sql2="DELETE FROM jsadmin.PHOTO_ATTACHMENTS WHERE MAILID='$mailid'";
		mysql_query($sql2) or logError($sql2);
	}
}

$sql="UNLOCK TABLES";
mysql_query($sql) or logError($sql);

$sql="OPTIMIZE TABLE jsadmin.PHOTO_ATTACHMENTS";
mysql_query($sql) or logError($sql);

//End of code added by Sadaf
?>
