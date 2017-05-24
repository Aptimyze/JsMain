<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	include("connect.inc");
	connect_db();

	$sql="delete from SEARCH_MALE where LAST_LOGIN_DT < DATE_SUB(CURDATE(), INTERVAL 4 MONTH)";
	mysql_query($sql);

	$sql="delete from SEARCH_FEMALE where LAST_LOGIN_DT < DATE_SUB(CURDATE(),
INTERVAL 4 MONTH)";
        mysql_query($sql);
?>
