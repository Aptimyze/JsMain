<?php
$log_file_path = JsConstants::$cronDocRoot."/log/jeevansathi_dev.log";
$mailLimit = 1000;

$file=fopen($log_file_path,"r")  or die("Unable to open file!");
$count = 0;
while(!feof($file))
{
	$temp = trim(fgets($file));
	if(strstr($temp, 'Validation Error:'))
	{
		$count++;
	}
	if($count>$mailLimit)
	{
		mail("lavesh.rawat@gmail.com,reshu.rajput@gmail.com","More than 1000 Validation Errors","More than 1000 Validation Errors are logged in ".$log_file_path);
		break;
	}
}
fclose($file);
?>
