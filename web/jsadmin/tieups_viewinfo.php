<?php
include("connect.inc");

if(authenticated($cid))
{
	$sql = "Select * from tieups.PSWRDS";
	$result = mysql_query_decide($sql,$db);
	while($myrow = mysql_fetch_array($result))
	{
		$priv_comma_separated = str_replace("+",",",$myrow["PRIVILAGE"]);

		$values[] = array("RESID"=>$myrow["RESID"], 
				"USERNAME"=>$myrow["USERNAME"],
				"PASSWORD"=>$myrow["PASSWORD"],
				"GROUPNAME"=>$myrow["GROUPNAME"],
				"PRIVILAGE"=>$priv_comma_separated);
	}		
	
	$smarty->assign("CID",$cid);
	$smarty->assign("ROWS",$values);
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->display("tieups_viewinfo.htm");
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
