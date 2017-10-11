<?php
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObj = new Mysql;
$dbM = $mysqlObj->connect("master") or die("Unable to connect to master");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$sql = "UPDATE newjs.SMS_TYPE SET MESSAGE = REPLACE(MESSAGE, 'TOLLNO', 'AMOBILE') WHERE ID IN (19,20,21,22,23)";
$mysqlObj->executeQuery($sql,$dbM);
mysql_close($dbM);
?>
