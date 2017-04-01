<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include("connect.inc");

$db2=connect_slave81();

$ts=time();
$ts-=24*60*60;
$today=date("Y-m-d",$ts);
$sql="SELECT COUNT(*) as cnt FROM newjs.JPROFILE WHERE DATE(LAST_LOGIN_DT)='$today'";
$res=mysql_query($sql,$db2) or logError($sql,$db2);
$row=mysql_fetch_array($res);
$cnt=$row['cnt'];

mysql_close($db2);

$db=connect_db();
$db_ddl = connect_ddl();
$sql="select count(*) from newjs.AUTOLOGIN_LOGIN";
$res=mysql_query($sql) or logError($sql,$db);
$row=mysql_fetch_row($res);
$auto_cnt=$row[0];

$sql="INSERT INTO MIS.DAY_LOGIN_COUNT(LAST_LOGIN_DT,COUNT,AUTO_LOGIN) VALUES('$today','$cnt','$auto_cnt')";
mysql_query($sql,$db) or logError($sql,$db);

$sql="truncate table newjs.AUTOLOGIN_LOGIN";
mysql_query($sql,$db_ddl) or logError($sql,$db_ddl);

?>
