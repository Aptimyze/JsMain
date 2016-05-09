<?php
include("/usr/local/lib/smarty/Smarty.class.php");
$smarty=new Smarty;

$smarty->template_dir="/usr/local/apache/htdocs/mis_beta/templates/";
$SITE_URL="http://devjs.infoedge.com/";
$smarty->assign("SITE_URL",$SITE_URL);
?>
