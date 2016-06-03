<?php

include("connect.inc");
$db=connect_db();

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

	$sql="SELECT HOROSCOPE from HOROSCOPE where PROFILEID='$profileid'";
	$result=mysql_query_decide($sql) or die(mysql_error_js()); 
	$myrow= mysql_fetch_array($result);

	header('Content-type: image/jpg');

	if($myrow['HOROSCOPE'] != '')
		echo $myrow['HOROSCOPE'];
	else
	{
		$sql="SELECT HOROSCOPE from HOROSCOPE_FOR_SCREEN where UPLOADED != 'D' AND PROFILEID='$profileid'";
        	$result=mysql_query_decide($sql) or die(mysql_error_js());
        	$myrow= mysql_fetch_array($result);

        	header('Content-type: image/jpg');

		echo $myrow['HOROSCOPE'];
	}
?>
