<?php

include("../jsadmin/connect.inc");
$db2=connect_master();
$sql = "select PROFILEID from billing.UPLOAD_MATRI_STATUS where PROFILEID = '$id'";

$result = mysql_query_decide($sql,$db2);

if(mysql_num_rows($result))
{
	$smarty->assign("FLAG","2");
	$smarty->assign("Already","File has been already Uploaded for this profile");
}

$smarty->assign("id",$id);
$smarty->assign("checksum",$cid);
$smarty->display("matriprofile_attach_status.htm");

?>
