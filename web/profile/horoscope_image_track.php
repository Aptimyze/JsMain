<?php
/*Created by sriram to track weather the astro chart image from matchsro is loaded properly.*/

///to zip the file before sending it
$zipIt = 0;
if(strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it

include("connect.inc");
$db = connect_db();

$data = authenticated($checksum);
if($data)
{
	//here we are getting viewed_profiled from script which displays image.
	$profileid = $data['PROFILEID'];
	$sql = "INSERT INTO MIS.ASTRO_IMAGE_TRACK(PROFILEID,VIEWED_PROFILEID,ENTRY_DT) VALUES('$profileid','$viewed_profileid',now())";
	mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
}

// flush the buffer
if($zipIt)
	ob_end_flush();

?>
