<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include ("$docRoot/crontabs/connect.inc");

$myDb 	=connect_db();		// master connection

$setDt 	='2013-12-08';
$checkDt='2013-11-25';

$sql ="update billing.VARIABLE_DISCOUNT vd, billing.VD_GIVEN_LASTTIME l SET vd.EDATE='$setDt' where vd.PROFILEID=l.PROFILEID and vd.SDATE='$checkDt'";
mysql_query_decide($sql,$myDb) or die($sql.mysql_error($myDb));



?>
