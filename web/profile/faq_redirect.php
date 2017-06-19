<?php
	include("connect.inc");
	$smarty->assign("FAQ_BODY",file_get_contents("$SITE_URL/profile/faq_other.php"));
	$smarty->display("faq_redirect.htm");
?>
