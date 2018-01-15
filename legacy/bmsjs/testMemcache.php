<?php
include_once("classes/Mysql.class.php");
include_once("classes/Banner.class.php");
include_once("classes/Zone.class.php");
include_once("classes/Memcache.class.test.php");
include_once("classes/UserSmarty.class.php");
include_once("display_include.php");
include_once("/usr/local/scripts/bms_config.php");


$memcacheObj=new UserMemcache;
$data=742;
if($data)
{
        $memcacheObj->userDetails($data);
        $userData=$memcacheObj->getuserData();
}
print_r($userData);
?>

