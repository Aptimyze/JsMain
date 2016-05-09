<?php
/************************************************************************************
Filename    : voucher_id.php
Description : Script to enter story id's of SUCCESS_STORIES into VOUCHER_SUCCESSSTORY
Created by  : Sadaf Alam 
Date        : 16 July 2007
************************************************************************************/

include("../jsadmin/connect.inc");
$db=connect_db();

$sql="SELECT SUCCESS_STORIES.ID,SUCCESS_STORIES.USERNAME_H,SUCCESS_STORIES.USERNAME_W FROM newjs.SUCCESS_STORIES,billing.VOUCHER_SUCCESSSTORY WHERE SUCCESS_STORIES.USERNAME_H=VOUCHER_SUCCESSSTORY.USERNAME_H AND SUCCESS_STORIES.USERNAME_W=VOUCHER_SUCCESSSTORY.USERNAME_W AND SELECTED='A'";
$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
while($row=mysql_fetch_assoc($res))
{
	$id=$row['ID'];
	$user_h=$row['USERNAME_H'];
	$user_w=$row['USERNAME_W'];
	$sqlins="UPDATE billing.VOUCHER_SUCCESSSTORY SET STORYID='$id' WHERE USERNAME_H='$user_h' AND USERNAME_W='$user_w'";
	mysql_query_decide($sqlins) or die("$sqlins".mysql_error_js());
}
?>
