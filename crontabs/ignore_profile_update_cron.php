<?php

/**
*       Filename        :       ignore_profiles_list.php
*       Description     :       To update UPDATED='Y' in newjs.IGNORE_PROFILE table
*       Created by      :       Gaurav Arora
**/

$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include("$docRoot/crontabs/connect.inc");
														     
$db=connect_db();
/*$days=1;
$dt=date('Y-m-d',strtotime('now -'.$days.' days'));*/

$seconds=2*60*60; //for 2 hours
$dt=date('Y-m-d H:i:s',time()-$seconds);

$sql="UPDATE IGNORE_PROFILE SET UPDATED = 'Y' WHERE DATE<='$dt' and UPDATED = 'N'";
mysql_query_decide($sql) or logError($sql);
?>
