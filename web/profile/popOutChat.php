<?php
	ini_set('memory_limit', '64M');
	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt && !$dont_zip_now)
		ob_start("ob_gzhandler");
	//end of it

	// common include file
	include_once("connect.inc");
	$smarty->display("popOutChat.html");
	// flush the buffer
	if($zipIt && !$dont_zip_now)
	        ob_end_flush();
?>
