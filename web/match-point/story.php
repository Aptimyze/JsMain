<?php
        $zipIt = 0;
        if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
                $zipIt = 1;
        if($zipIt)
                ob_start("ob_gzhandler");
        include_once("connect.inc");
        $db=connect_db();
        include_once("main.php");
	if($page!=2&&$page!=3)
		$page=1;
	$url="story$page.htm";
	$smarty->assign("page",$page);
	$smarty->assign("STORY",$smarty->fetch($url));
	$smarty->assign("BETWEEN",$smarty->fetch("between.htm"));
        $smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("GOOG",$smarty->fetch("goog.htm"));
        $smarty->display("story.htm");
        // flush the buffer
        if($zipIt)
                ob_end_flush();
?>

