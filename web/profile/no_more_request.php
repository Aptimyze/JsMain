<?php
include("connect.inc");

$db=connect_db();
$data=authenticated($checksum);
if($data)
{
	$profile_no=$data['PROFILEID'];
	$sql="INSERT IGNORE INTO HOROSCOPE_REQUEST_BLOCK(PROFILEID,DATE) VALUES ($profile_no,now())";
	mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed.Please try after some time.",$sql,"ShowErrTemplate");
	$smarty->display("pop.htm"); 
}
?>
