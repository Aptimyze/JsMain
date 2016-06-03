<?php

include("connect.inc");
$db=connect_db();
$checksum = $_GET['checksum'];
if(!$checksum)
  $checksum = $_POST['checksum'];

if($checksum)
{
	$data=authenticated($checksum);
	$profileid=$data["PROFILEID"];
}
elseif($profilechecksum)
{
	$temp=explode("i",$profilechecksum);
	$profileid=$temp[1];
}

	$sql="SELECT HOROSCOPE from newjs.HOROSCOPE_FOR_SCREEN where UPLOADED != 'D' AND PROFILEID='$profileid'";
	$result=mysql_query_decide($sql) or die(mysql_error_js()); 
	$myrow= mysql_fetch_array($result);

	header('Content-type: image/jpg');

	echo $myrow['HOROSCOPE'];
?>
