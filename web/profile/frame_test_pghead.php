<?php
include("connect.inc");
connect_db();
$data=authenticated($checksum);
$smarty->assign("PROFILEID",$profileid);
$smarty->display("frame_test_pghead.htm");
?>
