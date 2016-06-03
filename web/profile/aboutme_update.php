<?php
include_once('connect.inc');
$db=connect_db();
$about_me=mysql_real_escape_string($about_yourself);
$profileid=mysql_real_escape_string($profileid);
if(is_numeric($profileid)){
	$sql="INSERT INTO MIS.ABOUTME_LOG values ($profileid,'$about_me')";
	mysql_query_decide($sql);
	die;
}
?>
