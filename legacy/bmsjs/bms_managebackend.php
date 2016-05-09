<?php

/************************************************************************************************************************
*   FILENAME   :  managebackend.php
*   INCLUDE    :  connect.inc 
*   DESCRIPTION  : To manage the backend login viz display and modification of the privilages etc
***********************************************************************************************************************/

include ("includes/bms_connect.php");
$ip=FetchClientIP();
if ($site == 'JS')
	$data=authenticatedBms($id,$ip,"banadmin");
else
	$data=authenticatedBms($id,$ip,"99acresadmin");
$site = $data['SITE'];
$smarty->assign("site",$site);
if ($data)
{
	$bmsheader=fetchHeaderBms($data);
        $bmsfooter=fetchFooterBms();
        $smarty->assign("bmsheader",$bmsheader);
        $smarty->assign("bmsfooter",$bmsfooter);

	$linkarr[]="<a href=\"bms_showuser.php?id=$id&site=$site\"> Display User List</a>";
	if ($site == 'JS')
		$linkarr[]="<a href=\"bms_addnew_user.php?id=$id&site=$site\"> Add New User</a>";
	$linkarr[]="<a href=\"bms_addnew_client.php?id=$id&site=$site\"> Add Campaign Clients</a>";

	$smarty->assign("linkarr",$linkarr);
	$smarty->assign("id",$id);
	$smarty->display("./$_TPLPATH/bms_managebackend.htm");
}
else
{
	TimedOutBms();
}
?>

