<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
chdir(dirname(__FILE__));

include("../connect.inc");
$db_slave =connect_slave();
$master =connect_db();

$sqlc = "USE billing";
mysql_query_decide($sqlc,$master) or die("$sqlc".mysql_error_js($master));

$sqlc = "CREATE TABLE billing.PAYMENT_HITS_buffer LIKE billing.PAYMENT_HITS";
mysql_query_decide($sqlc,$master) or die("$sqlc".mysql_error_js($master));

$sql="select * from billing.PAYMENT_HITS WHERE ENTRY_DT>='2015-08-01 00:00:00'";
$res=mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js($slave));
while($row = mysql_fetch_array($res))
{
        $sql1 ="INSERT INTO billing.PAYMENT_HITS_buffer (ID,PROFILEID,PAGE,ENTRY_DT,TAB_BUTTON,USER_AGENT) VALUES ('$row[ID]','$row[PROFILEID]','$row[PAGE]','$row[ENTRY_DT]','$row[TAB_BUTTON]','".addslashes(htmlentities($row[USER_AGENT], ENT_QUOTES | ENT_IGNORE, 'UTF-8'))."')";
        mysql_query_decide($sql1,$master) or die("$sql1".mysql_error_js($master));
}

$sqlr1 = "Rename table billing.PAYMENT_HITS to billing.PAYMENT_HITS_till_31July2015";
mysql_query_decide($sqlr1,$master) or die("$sqlr1".mysql_error_js($master));

$sqlr2 = "Rename table billing.PAYMENT_HITS_buffer to billing.PAYMENT_HITS";
mysql_query_decide($sqlr2,$master) or die("$sqlr2".mysql_error_js($master));

?>
