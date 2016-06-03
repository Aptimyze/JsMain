<?
$msg="echo \"$os:$message :$location :$line :$browser :$version\n";
$msg.="\" >> errorajaxlog";
passthru($msg);
?>
