<?php
$server = $_GET["server"];
$date = $_GET["date"];
$type = $_GET["type"];
if(!in_array($server,array("linuxcp10258","linuxcp10305","linuxcp10078","linuxcp10273","linuxcp10056","linuxcp10057","linuxcp10070","linuxcp10084","linuxcp10079","linuxcp10210","linuxcp10236","linuxcp10237","linuxcp10198","linuxcp10064","linuxcp10067","linuxcp10068","linuxcp10069","linuxcp10073","linuxcp10307","linuxcp10327","lfvscp10016","lfvscp10017")))
	die("invalid server");
$url = "http://$server.dn.net/stats/sar.php?date=$date&type=$type";
header("Location: $url");
?>
