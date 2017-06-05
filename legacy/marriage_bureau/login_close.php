<?php

include_once("connectmb.inc");

$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
$smarty->display("close_banner.htm");

?>
