<?php

require_once("connect.inc");
$db=connect_db();

if($source)
	$smarty->assign('BANNERSOURCE',$source);
$smarty->display("banner.htm");

?>


