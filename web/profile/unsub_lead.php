<?php

include("connect.inc");
$db_slave  = connect_slave();
$db_master = connect_db();

/*************Portion of Code added for display of Banners*****************************/
$smarty->assign("NO_BOTTOM_ADSENSE","1");
$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("bms_topright",11);
$smarty->assign("bms_middle",12);
$smarty->assign("bms_bottom",13);
$smarty->assign("bms_new_win",38);
/***********************End of Portion of Code*****************************************/

if($leadid)
{
	$sql="UPDATE MIS.REG_LEAD SET UNSUB_LEADMAIL='Y' WHERE LEADID='$leadid'";
        mysql_query($sql,$db_master) or die(mysql_error1($db_master));
}

$smarty->assign("var_in","0");
$smarty->assign("LOGOUT","1");
$smarty->assign("CURRENTUSERNAME","");
$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
//$smarty->assign("REVAMP_SEARCH_PANEL",$smarty->fetch("revamp_top_search_band.htm"));
//$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
$smarty->assign("head_tab",'my jeevansathi');
$smarty->display('unsub_lead.htm');
?>
