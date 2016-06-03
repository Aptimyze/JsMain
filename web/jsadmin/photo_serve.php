<?php
/**
*       Included        :       connect.inc
*       Description     :       contains functions related to database connection and login authentication
**/
include("connect.inc");
if(authenticated($cid))
{
	$sql = "select $photo DATA from newjs.NOTHUMBNAIL where PROFILEID=$profileid";
	$result = mysql_query_decide($sql);
	if(mysql_num_rows($result) > 0)
	{
		$ro = mysql_fetch_array($result);
		$img = $ro[DATA];
		// We'll be outputting a GIF
		header('Content-type: image/jpeg');
		// The GIF source is in given Url
		echo $img;
	}
}
else//user timed out
{
	$msg="Your session has been timed out<br>";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";	
	$smarty->assign("MSG",$msg);	
	$smarty->display("jsadmin_msg.tpl");
}
?>
