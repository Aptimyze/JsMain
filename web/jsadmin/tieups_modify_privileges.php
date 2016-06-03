<?php
include("connect.inc");

if(authenticated($cid))
{
	$sql = "Select * from tieups.PSWRDS where RESID = $resid";
	$result = mysql_query_decide($sql,$db) or die(mysql_error_js());
	$myrow = mysql_fetch_array($result);

	$privilege = $myrow["PRIVILAGE"];
	$username = $myrow["USERNAME"];
	$group = $myrow["GROUPNAME"];

	$priv = explode("+",$privilege);
	
	if (in_array('TH',$priv))
		$total_hits = 1 ;

	if (in_array('CP',$priv))
		$complete_profile = 1 ;

	if (in_array('IP',$priv))
		$incomplete_profile = 1 ;
	
	 if (in_array('DP',$priv))
        $delete_profile = 1 ;
	 if (in_array('PAID',$priv))
        $paid_profile = 1 ;
	$smarty->assign("USERNAME",$username);
	$smarty->assign("GROUP",$group);
	$smarty->assign("TH",$total_hits);
	$smarty->assign("CP",$complete_profile);
	$smarty->assign("IP",$incomplete_profile);
	$smarty->assign("DP",$delete_profile);
	$smarty->assign("PAID",$paid_profile);
	$smarty->assign("RESID",$resid);

	$smarty->assign("CID",$cid);
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->display("tieups_modify_privileges.htm");

}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}

