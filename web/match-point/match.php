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
	if($m==4||$m==3||$m==2) 
		$crook=$m;
	else
		$crook=1;
	$smarty->assign("crook",$crook);
        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
        $smarty->assign("BETWEEN",$smarty->fetch("between.htm"));
	$smarty->assign("GOOG",$smarty->fetch("goog.htm"));
        $smarty->display("match.htm");
        // flush the buffer
        if($zipIt)
                ob_end_flush();
?>

