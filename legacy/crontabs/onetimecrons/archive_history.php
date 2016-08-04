<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
chdir(dirname(__FILE__));

include("../connect.inc");
$db_slave =connect_slave();
$master =connect_db();

$sqlc = "USE incentive";
mysql_query_decide($sqlc,$master) or die("$sqlc".mysql_error_js($master));

$sqlc = "CREATE TABLE incentive.HISTORY_buffer LIKE incentive.HISTORY";
mysql_query_decide($sqlc,$master) or die("$sqlc".mysql_error_js($master));

$sql="select * from incentive.HISTORY WHERE ENTRY_DT>='2014-09-01 00:00:00'";
$res=mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js($slave));
while($row = mysql_fetch_array($res))
{
        $sql1 ="INSERT INTO incentive.HISTORY_buffer (ID,PROFILEID,USERNAME,ENTRYBY,MODE,DISPOSITION,VALIDATION,COMMENT,ENTRY_DT) VALUES ('$row[ID]','$row[PROFILEID]','$row[USERNAME]','$row[ENTRYBY]','$row[MODE]','$row[DISPOSITION]','$row[VALIDATION]','$row[COMMENT]','$row[ENTRY_DT]')";
        mysql_query_decide($sql1,$master) or die("$sql1".mysql_error_js($master));
}

$sqlr1 = "Rename table incentive.HISTORY to incentive.HISTORY_31Aug2015";
mysql_query_decide($sqlr1,$master) or die("$sqlr1".mysql_error_js($master));

$sqlr2 = "Rename table incentive.HISTORY_buffer to incentive.HISTORY";
mysql_query_decide($sqlr2,$master) or die("$sqlr2".mysql_error_js($master));

?>
