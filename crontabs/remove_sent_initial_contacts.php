<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include("connect.inc");

$db=connect_db();

$sql="DELETE FROM CONTACTS_ONCE WHERE SENT='Y'";
mysql_query($sql) or logError($sql);

?>
