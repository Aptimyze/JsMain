<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include("connect.inc");
$db=connect_db();

/* Removing the unwanted data from the table PHOTO_DATA_RETAIN on daily basis */
$ddate=date("Y-m-d",time()-86400);
$sql="DELETE FROM newjs.PHOTO_DATA_RETAIN WHERE PHOTODATE<'$ddate 00:00:00'";
$res=mysql_query($sql,$db) or die(mysql_error());


/* Deleting the data from the table BLOCK_IP */

$sql="DELETE FROM newjs.BLOCK_IP WHERE TIME <= '$ddate 00:00:00'";
$res=mysql_query($sql,$db) or die(mysql_error());

$sql="delete from ASTRO_PULLING_REQUEST where ENTRY_DT < date_sub(curdate(), interval 1 MONTH)";
mysql_query($sql,$db) or die (mysql_error());

?>

