<?php
include(JsConstants::$docRoot."/profile/connect.inc");
$db_master = connect_db();

$sql = "SELECT max(ID) id from jsadmin.VIEW_CONTACTS_LOG";
$res = mysql_query($sql, $db_master) or die(mysql_error());
$row       = mysql_fetch_array($res);
echo $count = $row['id'];
$chunk       = 100;
$totalChunks = ceil($count / $chunk);
for ($i = 0; $i < $totalChunks; $i++) {
$start=$i*$chunk;
$end=$i*$chunk+$chunk;

echo $sql = "UPDATE jsadmin.VIEW_CONTACTS_LOG set SOURCE='D' WHERE ID between $start and $end"."\n";
mysql_query($sql, $db_master) or die(mysql_error());
}
	
