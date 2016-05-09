<?php
/*****************************************************************************************************************
Filename    : voucher_backend_display.php
Description : Display images in voucher backend module
Created On  : 10 September 2007
Created By  : Sadaf Alam
*****************************************************************************************************************/
include("connect.inc");

$db=connect_737();
$sql="SELECT SQL_CACHE $file FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$clientid'";
$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
$row=mysql_fetch_assoc($result);
header("Content-Type:image/jpeg");
echo $row[$file];
?>
