<?php
include("connect.inc");
$db_slave=connect_slave();
$sql="SELECT PROFILEID FROM newjs.JP_MUSLIM WHERE MATHTHAB<>0";
$res=mysql_query($sql,$db_slave) or die(mysql_error($db_slave));
while($row=mysql_fetch_array($res))
{
	$pid_arr[]=$row['PROFILEID'];
}
$pids=implode(',',$pid_arr);
unset($pid_arr);
$sql_jp="SELECT PROFILEID FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID IN ($pids) AND CASTE='151'";
$res_jp=mysql_query($sql_jp,$db_slave) or die(mysql_error($db_slave));
while($row_jp=mysql_fetch_array($res_jp))
{
	$pid_arr[]=$row_jp['PROFILEID'];
}
$pids=implode(',',$pid_arr);

$db=connect_db();
$sql_up="UPDATE newjs.JP_MUSLIM SET  MATHTHAB=(MATHTHAB+4) WHERE PROFILEID IN ($pids)";
mysql_query($sql_up,$db) or die(mysql_error());

?>
