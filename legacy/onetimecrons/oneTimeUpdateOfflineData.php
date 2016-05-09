<?php
include("connect.inc");
$db=connect_db();
$db_slave=connect_737();
$sql="SELECT PROFILEID,ENTRY_DT FROM newjs.JPROFILE WHERE ENTRY_DT>'2009-09-01' AND SOURCE='onoffreg'";
$res=mysql_query($sql,$db_slave) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	$pid=$row["PROFILEID"];
	$edate=$row["ENTRY_DT"];
	$sql_e="SELECT ENTRYBY FROM billing.PURCHASES WHERE PROFILEID='$pid' ORDER BY ENTRY_DT DESC LIMIT 1";
	$res_e=mysql_query($sql_e,$db_slave) or die(mysql_error());
	$row_e=mysql_fetch_array($res_e);
	$exe=$rowe["ENTRYBY"];
	$sql1="INSERT IGNORE INTO newjs.OFFLINE_REGISTRATION VALUES ('$pid','$exe','onoffreg','$edate')";
	$res1=mysql_query($sql1,$db) or die(mysql_error());
}
$sql="SELECT PROFILEID,ENTRY_DATE FROM newjs.OFFLINE_BILLING";
$res=mysql_query($sql,$db_slave) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
        $pid=$row["PROFILEID"];
        $edate=$row["ENTRY_DATE"];
        $sql_e="SELECT ENTRYBY FROM billing.PURCHASES WHERE PROFILEID='$pid' ORDER BY ENTRY_DT DESC LIMIT 1";
        $res_e=mysql_query($sql_e,$db_slave) or die(mysql_error());
        $row_e=mysql_fetch_array($res_e);
        $exe=$row["ENTRYBY"];
        $sql1="INSERT IGNORE INTO newjs.OFFLINE_REGISTRATION VALUES ('$pid','$exe','ofl_prof','$edate')";
        $res1=mysql_query($sql1,$db) or die(mysql_error());
}
$sql="SELECT * FROM OFFLINE_ASSIGNLOG";
$res=mysql_query($sql,$db_slave) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	$pid=$row["PROFILEID"];
	$exe=$row["OPERATOR"];
	$edate=$row["ASSIGN_DATE"];
	$sql1="INSERT IGNORE INTO newjs.OFFLINE_REGISTRATION VALUES ('$pid','$exe','ofl_prof','$edate')";
        $res1=mysql_query($sql1,$db) or die(mysql_error());
}
?>

