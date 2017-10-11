<?php
include("connect.inc");
$db=connect_db();

if(authenticated($checksum))
{
	$sql = "Select MESSAGE from CONTACTS where CONTACTID = $id";
	$result	= mysql_query_decide($sql) or die(mysql_error_js());
	$myrow = mysql_fetch_array($result);
	$msg = $myrow["MESSAGE"];
	$msg = nl2br($msg);	
			
	$smarty->assign("MSG",$msg);
	$smarty->display("view_msg_popup.htm");	
}
else
{
	timedout();
}	
?>
