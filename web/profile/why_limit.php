<?
include("connect.inc");
$db=connect_db();
$data=authenticated();
$smarty->assign("CONT_LEFT",500-200);

$smarty->assign("PROFILECHECKSUM",createChecksumForSearch($data['PROFILEID']));
$smarty->display("why_limit.htm");
?>

