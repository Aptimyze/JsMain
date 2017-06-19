<?php
$path ="/usr/local/source_banners/";

//global $image_type , $source;

if ($image_type == 'htm')
{
	$gif = $path.$source.".htm";
	if (@file_exists($gif))
		readfile("$gif");
	else
		echo "<br><center><font size=1 color=\"brown\">No banner for this source.</font></center>";
}
elseif ($image_type == 'swf')
{
	$gif = $path.$source.".swf";
	if (@file_exists($gif))
	{
		header("Content-Type:application/x-shockwave-flash");
		$banner = readfile("$gif");
	}
	else
		echo "<br><center><font size=1 color=\"brown\">No banner for this source.</font></center>";
}
elseif (!$image_type)
{
	echo "<br>";
	echo "<center><font size=1 color=\"brown\">No banner for this source.</font></center>";
}
else
{
	if (@file_exists($path.$source.".jpg"))
		$gif = "/usr/local/source_banners/".$source.".jpg";
	elseif (@file_exists($path.$source.".gif"))
		$gif = "/usr/local/source_banners/".$source.".gif";
	else
		$noimage = 1;
	if ($noimage == 1)
		echo "<br><center><font size=1 color=\"brown\">No banner for this source.</font></center>";
	else
	{
		header('Content-type: image/jpeg');
		$banner = @readfile("$gif");
	}
}

?>
