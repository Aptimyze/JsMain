<?php
	include_once("profile/connect.inc");
	include_once ($_SERVER['DOCUMENT_ROOT']."/classes/ShortURL.class.php");
	$shortURL = $_GET[id];
	$getURL = new ShortURL();
	$URL = $getURL->getLongURL($shortURL);
	if($URL)
        header("Location:$URL");
    else
	{
		header ("$_SERVER[SERVER_PROTOCOL] 301 Moved Permanently");
	        header("Location:$SITE_URL");
	}
?>
