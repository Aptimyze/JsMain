<?php
include("connect.inc");
$smarty->assign("MtongueDropdownForTemplate",generateMtongueDropdownForTemplate());
$smarty->display("widget.htm");
?>
