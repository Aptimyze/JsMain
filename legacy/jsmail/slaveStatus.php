<?php
$db = mysql_connect("localhost:/tmp/mysql.sock","root","HGpZD141") or die(mysql_error());
$sql = "SHOW SLAVE STATUS";
$res = mysql_query($sql, $db) or die(mysql_error().$sql);
$row = mysql_fetch_assoc($res);
$timeDelay = $row["Seconds_Behind_Master"];
$currentTime = date("Y-m-d g:i a"); 
$str = $currentTime."(delay:".$timeDelay.")\n";
$str="echo \"$str\" >> /home/developer/slaveStatus.txt";
passthru($str);
