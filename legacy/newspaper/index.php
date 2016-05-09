<?php
	
	include("connect.inc");
                                                                                                                             
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->display("index.htm");

?>
