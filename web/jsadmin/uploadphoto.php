<?php
global $_SERVER;
$http_msg=print_r($_SERVER,true);
$str="echo \"$http_msg\" >> /tmp/test/log_jsadmin.txt";
passthru($str);
die("This Link should not be called.Please report this problem to lavesh.rawat@jeevansathi.com");
//header("Location:/social/addPhotos");
?>
