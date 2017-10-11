<?php
include("connect.inc");
$db=connect_db();
$data=authenticated($data);
if($data)
{
	if($TYPE)
		$ERR_MES="Please check the checkboxes corresponding to the profiles that you want to perform the action.";
	if($TYPE=='EOI')
		$ERR_MES="Please check the checkboxes corresponding to the profiles that you want to express interest in.";
	if($TYPE=='BM')
		$ERR_MES="Please check the checkboxes corresponding to the profiles that you want to add to your shortlist.";
	if($TYPE=='RF')
		$ERR_MES="Please check the checkboxes corresponding to the profiles that you want to remove from your shortlist.";
	if($TYPE=="")
		die('Logged');
	$smarty->assign("ERR_MES",$ERR_MES);
	$smarty->display("nothing_selected.htm");
}
else
{
	$smarty->assign("PREV_URL",$_SERVER['REQUEST_URI']);
	include_once("include_file_for_login_layer.php");
        $smarty->display("login_layer.htm");
	die;
}
?>
