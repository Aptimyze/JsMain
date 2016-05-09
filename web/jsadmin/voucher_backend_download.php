<?php
/***************************************************************************************************
Filename    : voucher_backend_download.php
Description : Download image zip by design / series file by tech team for voucher backend [2177]
Created On  : 7 September 2007
Created By  : Sadaf Alam
***************************************************************************************************/

include("connect.inc");

$db=connect_db();

$path=$_SERVER['DOCUMENT_ROOT'];

if($design)
{
	$sql="SELECT IMAGEFILE_CONTENT FROM billing.VOUCHER_CLIENTS WHERE IMAGE_FILE='$filename' AND CLIENTID='$clientid'";
	$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$row=mysql_fetch_assoc($result);
	$fcontent=$row["IMAGEFILE_CONTENT"];
	$type="application/zip";
}

if($tech)
{
	if(strpos($filename,"csv"))
	$type="text/csv";
	else
	$type="application/ms-excel";
	$sql="SELECT SERIESFILE_CONTENT FROM billing.VOUCHER_CLIENTS WHERE SERIES_FILE='$filename' AND CLIENTID='$clientid'";
	$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$row=mysql_fetch_assoc($result);	
	$fcontent=$row["SERIESFILE_CONTENT"];
}

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Type: $type");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: " . $fsize);
echo $fcontent;

?>
