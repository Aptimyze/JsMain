<?php
        $zipIt = 0;
        if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
                $zipIt = 1;
        if($zipIt)
                ob_start("ob_gzhandler");
        //end of it
        include_once("connect.inc");
        $db=connect_db();
        include_once("main.php");
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("GOOG",$smarty->fetch("goog.htm"));
	$smarty->display("index.htm");
        // flush the buffer
        if($zipIt)
                ob_end_flush();
?>

