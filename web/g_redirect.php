<?php

/*********************************************************************************************
* FILE NAME	: g_redirect.php
* DESCRIPTION	: Redirects the users to their desired destinations
* CREATION DATE	: 19 May, 2005
* CREATEDED BY	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
include("profile/hits.php");
include("profile/connect.inc");
$db=connect_db();
if($_SERVER['REQUEST_METHOD']=="POST")
{	
	if($source)
	{
		setcookie("JS_SOURCE",$source,time()+2592000,"/");
		$pagename=$_SERVER['PHP_SELF'];
	        savehit($source,$pagename);
	}

	echo "<html><body onLoad=\"javascript:document.forms[0].submit()\"><form action=\"$url\" method=\"post\">";
	foreach($_POST as $name => $value)
	{
		echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">";
	}
	echo "<noscript><br><br>We have taken your details. Kindly press the button below to complete the process<br><br><input type=\"submit\"></noscript></form></body></html>";
}

elseif($_SERVER['REQUEST_METHOD']=="GET")
{
	if($source)
	{
		setcookie("JS_SOURCE",$source,time()+2592000,"/");
		$pagename=$_SERVER['PHP_SELF'];
                savehit($source,$pagename);
	}

	if($url)
	{
		header("Location:".$url);
		exit;
	}
}
?>
