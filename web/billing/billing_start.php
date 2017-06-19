<?php
include('../jsadmin/connect.inc');
include('comfunc_sums.php');

$data=authenticated($cid);
if(isset($data))
{
$cri = populate_search_criteria();
$smarty->assign("cid",$cid);
$smarty->assign("CID",$cid);
$smarty->assign("user",$user);
$smarty->assign("cri",$cri);
$smarty->assign("offline_billing",$offline_billing);
$smarty->display('billing_start.htm');
}
else
{
        $smarty->assign("HEAD",$smarty->fetch("head.htm"));
        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
        $smarty->assign("username","$username");
        $smarty->assign("CID",$cid);
        $smarty->display("jsconnectError.tpl");
}


?>
