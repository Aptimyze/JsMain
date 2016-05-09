<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");

$mysqlObj = new Mysql;
$dbM = $mysqlObj->connect("master") or die("Unable to connect to master");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$sql = "DELETE FROM newjs.FEATURED_PROFILE_LOG WHERE DATEDIFF(NOW( ),DATE)>45";
$mysqlObj->executeQuery($sql,$dbM) or $mysqlObj->logError($sql);

mysql_close($dbM);
?>
