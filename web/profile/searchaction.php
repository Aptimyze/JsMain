<?php
	global $_SERVER;
        $http_msg=print_r($_SERVER,true);
        $str="echo \"$http_msg\" >> /tmp/test/log_photoModule.txt";
        passthru($str);
?>
