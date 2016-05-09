<?php
include("connect.inc");
include("time.php");
echo "\nargv[1] : ".$t_argv = $argv[1]." ".$argv[2];
echo "\n".newtime($t_argv,0,$screen_time,0);
echo "\n";
?>
