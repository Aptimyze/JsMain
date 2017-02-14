<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");
chdir(dirname(__FILE__));

include("../connect.inc");
$db_slave =connect_slave();
$master =connect_db();

$sql="select * from MIS.LOGIN_TRACKING_15July2015 WHERE DATE>='2015-07-21 00:00:00' AND DATE<'2015-07-25 00:00:00'";
$res=mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js($slave));
while($row = mysql_fetch_array($res))
{
        $sql1 ="INSERT INTO MIS.LOGIN_TRACKING (`PROFILEID`,`URL`,`DATE`,`CHANNEL`,`WEBSITE_VERSION`,`STYPE`) VALUES('$row[PROFILEID]','$row[URL]','$row[DATE]','$row[CHANNEL]','$row[WEBSITE_VERSION]','$row[STYPE]')";
	mysql_query_decide($sql1,$master) or die("$sql1".mysql_error_js($master));
}
?>
