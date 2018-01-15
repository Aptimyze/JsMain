<?php
	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it
	
	$file="/usr/local/apache/sites/jeevansathi.com/htdocs";
	
	header("Content-type: text/css");
	//if($zipIt)
	//	header('Content-Encoding: gzip');

	header("Cache-Control: public");
	
	$if_modified_since = preg_replace('/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE']);

	if($zipIt)
		$mtime = filemtime("$file/profile/images/styles_zip.css.gz");
	else
		$mtime = filemtime("$file/profile/images/styles.css");

	$gmdate_mod = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';
	
	// if both the dates are same send not modified header and exit. No need to send further output
	if ($if_modified_since == $gmdate_mod)
	{
		header("HTTP/1.0 304 Not Modified");
		header("Expires: " . gmdate('D, d M Y H:i:s', time()+(3600*24)) . " GMT");
		exit;
	}
	// tell the browser the last modified time so that next time when the same file is requested we get to know the modified time
	else 
	{
		header("Last-Modified: $gmdate_mod");
		header("Expires: " . gmdate('D, d M Y H:i:s', time()+(3600*24)) . " GMT");
	}

	if($zipIt)	
		readfile("$file/profile/images/styles_zip.css.gz");
	else
		readfile("$file/profile/images/styles.css");

	// flush the buffer
	//if($zipIt)
	//	ob_end_flush();
?>
