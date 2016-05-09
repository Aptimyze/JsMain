<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	include_once("connect.inc");
	connect_db();
	$query = "DELETE FROM newjs.shortURL where entryDate <=date_sub(curdate(),interval 1 month)";
	$query_res = mysql_query($query) or die ("Error to delete the Expired URL");
?>
