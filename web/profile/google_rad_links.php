<?
include('connect.inc');
$google_kw=$kw;
$smarty->assign("google_kw",$google_kw);
$smarty->assign("google_page_url",$google_page_url);
$smarty->assign("foot",$smarty->fetch("foot.htm"));
$smarty->display('google_rad_links.htm');

?>
