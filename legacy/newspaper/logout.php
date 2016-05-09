<?php
                                                                                                 
include ("connect.inc");
dbsql2_connect();

$lout=logout($cid);
if($lout)
{
	$msg="You have successfully logged out<br>";
	$msg .="<a href=\"index.php\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}

?>
