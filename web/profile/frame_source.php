<?php
include("connect.inc");
if($flag=='u')
	$smarty->display("upload.htm");
elseif($flag=='ru')
	$smarty->display("replace_upload.htm");
if($flag=='u1')
        $smarty->display("upload1.htm");
elseif($flag=='ru1')
        $smarty->display("replace_upload1.htm");
if($flag=='u2')
        $smarty->display("upload2.htm");
elseif($flag=='ru2')
        $smarty->display("replace_upload2.htm");
if($flag=='su')
	$smarty->display("sugarcrm_upload.htm");
?>
