<?php
if("http://w4.naukri.com/stats/select.php" != $_SERVER["HTTP_REFERER"])
        die("Unauthorized");
header('Content-Type: text/plain; charset=utf-8');
exec("ls /var/log/sa/sa?? | cut -c 15-16",$dates);
$date = $_GET["date"];
$type = $_GET["type"];
if(!in_array($date,$dates))
	die("Invalid date");
if(!in_array($type,array("B","c","d","q","r","u","w","W")))
	die("Invalid type");
$command = "sar -$type -f /var/log/sa/sa$date";
//echo "$command\n";
exec($command,$output);
foreach($output as $line)
	echo $line."\n";
?>
