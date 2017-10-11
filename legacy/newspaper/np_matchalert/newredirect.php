<?php

// The script handles a URL, parses it, captures the data and finally re-directs the user to desired location
//$vararr is an array that stores the various name,value pairs passed in the querystring. E.g: $vararr[0]==>var1=1
echo "HI";
die("Died");
$vararr=explode("&~",$_SERVER['argv'][0]);

if($url)
{
	$strt=strpos($vararr[0],"=");
	$url1=strstr($vararr[0],$vararr[0]{$strt+1});
	$tarurl=urldecode($url1);
}

if($_SERVER['REQUEST_METHOD']=="POST")
{	
	$url2=parse_url($tarurl);
	parse_str($url2[query]);
	if($source)
	{
		if(!$_COOKIE["JS_SOURCE"])
		{
			setcookie("JS_SOURCE",$source,time()+2592000,"/");
		}
	}

	echo "<html><body onLoad=\"javascript:document.forms[0].submit()\"><form action=\"$url1\" method=\"post\">";
	foreach($_POST as $name => $value)
	{
		echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">";
	}
	echo "</form></body></html>";
	
}

elseif($_SERVER['REQUEST_METHOD']=="GET")
{
	$url2=parse_url($tarurl);
        parse_str($url2[query]);
	if($source)
	{
		if(!$_COOKIE["JS_SOURCE"])
		{
			setcookie("JS_SOURCE",$source,time()+2592000,"/");
		}
	}

	if($url)
	{
		header("Location:".$url1);
		exit;
	}
	
}


?>
