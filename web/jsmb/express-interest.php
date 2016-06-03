<?php
	include_once("connect.inc");	
	$db=connect_db();
	$data=authenticated($checksum);
	//Assign headers and footers
	
	$header=$smarty->fetch("mobilejs/jsmb_header.html");
	$footer=$smarty->fetch("mobilejs/jsmb_footer.html");
	$smarty->assign("HEADER",$header);
	$smarty->assign("FOOTER",$footer);
if($data){

	$smarty->display("mobilejs/jsmb_express-interest.html");
}
else
{
			$smarty->assign("PREV_URL",$_SERVER['REQUEST_URI']);
			$smarty->display("mobilejs/jsmb_login.html");
}

?>
