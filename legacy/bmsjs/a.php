<?php

/** 
* Banner(s) Is served from this file
* @author Lavesh Rawat
* @copyright Copyright 2008, Infoedge India Ltd.
*/
include_once("classes/Mysql.class.php");
include_once("classes/Banner.class.php");
include_once("classes/Zone.class.php");
include_once("classes/Memcache.class.php");
include_once("classes/UserSmarty.class.php");
include_once("display_include.php");
include_once("/usr/local/scripts/bms_config.php");

$db=connect_737();
        function connect_737()
        {
		$db=mysql_connect("10.208.65.134:3307","user_sel","CLDLRTa9",MYSQL_CLIENT_COMPRESS) or die(mysql_error());//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate","YES","737");
                @mysql_select_db("newjs",$db);
                return $db;
        }
?>
