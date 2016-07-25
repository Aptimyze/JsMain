<?php
require_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
if (!trim($_GET['file_id'])) {
  die("Please specify file name for download.");
}
$db=connect_slave();
$lead_id=trim($_GET['lead_id']);
$file_id=trim($_GET['file_id']);
$fileFetchQuery="SELECT * FROM sugarcrm.lead_files WHERE ";
if($lead_id)
	$whereArr[]="lead_id='$lead_id' ";
if($file_id)
	$whereArr[]="file_id='$file_id' ";
$fileFetchQuery.=implode(" AND ",$whereArr);
$fileFetchRes=mysql_query($fileFetchQuery,$db);
$count=mysql_num_rows($fileFetchRes);
if($count)
{
	$fileFetchRow=mysql_fetch_assoc($fileFetchRes);
	$mtype=$fileFetchRow["file_type"];	
	$fname=$fileFetchRow["file_name"];
	$fcontent=$fileFetchRow["file_content"];
	$fsize=$fileFetchRow["file_size"];
}
else
	die("File not found");
header("Content-Type: $mtype");
header("Content-Disposition: attachment; filename=\"$fname\"");
header("Content-Length: " . $fsize);
if($fcontent){echo $fcontent;exit();}
?>
