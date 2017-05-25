<?php

include("connect.inc");

if(authenticated($cid))
{
	$str = " ";
        if ($total_hits)
                $str = $str." TH";
        if ($profile_com)
                $str = $str." CP";
        if ($profile_incom)
                $str = $str." IP";
	if ($profile_del)
                $str = $str." DP";

        $str = trim($str);
        $priv = explode(" ",$str);
        $privilege = implode("+",$priv);

	$sql = "Update tieups.PSWRDS set PRIVILAGE = '$privilege' where RESID = $resid ";
	$result = mysql_query_decide($sql,$db) or die(mysql_error_js());

	$display = "Updation of privileges successfully done.";

	$smarty->assign("CID",$cid);
	$smarty->assign("DISPLAY",$display);
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->display("tieups_result.htm");

}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
