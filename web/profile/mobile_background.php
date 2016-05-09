<?php
// directory is being changed as the script is run through command line and it does not include the include files in PHP compiled as cli if we do not change the directory
$chdir= dirname(__FILE__);
chdir("$chdir");
include("connect.inc");
include("mobile_verification_sms.php");
$db=connect_db();
$profileid=$_SERVER['argv'][1];
$Mobile=$_SERVER['argv'][2];
SEND_MOBSMS($profileid,$Mobile);//function from profile/mobile_verification_sms.php
?>
