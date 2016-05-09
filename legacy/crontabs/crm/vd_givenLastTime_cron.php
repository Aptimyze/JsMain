<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include ("$docRoot/crontabs/connect.inc");

$myDb 	=connect_db();		// master connection
$slaveDb=connect_737();		// slave connection
$lastVdDate ='2013-01-01';

$sql ="select PROFILEID from billing.VARIABLE_DISCOUNT";
$res =mysql_query_decide($sql,$slaveDb) or die($sql.mysql_error($slaveDb));
while($row = mysql_fetch_array($res)){

	$profileid =$row['PROFILEID'];
	$sqlLog ="insert ignore into billing.VD_GIVEN_LASTTIME(PROFILEID) VALUES('$profileid')";
	mysql_query_decide($sqlLog,$myDb) or die($sqlLog.mysql_error($myDb));             

}	


$sql1 ="select PROFILEID from billing.VARIABLE_DISCOUNT_LOG where SDATE>='$lastVdDate'";
$res1 =mysql_query_decide($sql1,$slaveDb) or die($sql1.mysql_error($slaveDb));
while($row1 = mysql_fetch_array($res1)){
	$profileid =$row1['PROFILEID'];
        $sqlLog ="insert ignore into billing.VD_GIVEN_LASTTIME(PROFILEID) VALUES('$profileid')";
        mysql_query_decide($sqlLog,$myDb) or die($sqlLog.mysql_error($myDb));                  

}

?>
