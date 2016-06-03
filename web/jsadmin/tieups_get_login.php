<?php

include("connect.inc");


if(authenticated($cid))
{
	
	$sql="SELECT Distinct GROUPNAME FROM MIS.SOURCE";
	$result=mysql_query_decide($sql) or die(mysql_error_js());

	while($myrow = mysql_fetch_array($result))
	{
		$values[] = array("GROUPNAME" => $myrow["GROUPNAME"]);
	}

	$smarty->assign("CID",$cid);
	$smarty->assign("ROWS",$values);
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->display("tieups_get_login.htm");
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
