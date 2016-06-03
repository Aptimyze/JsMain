<?php 
include_once(jsConstants::$cronDocRoot."/crontabs/connect.inc");
include_once(jsConstants::$docRoot."/classes/Mysql.class.php");

$mysqlObj=new Mysql;

$db_211 = connect_211();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_211);

$db_211_slave = $mysqlObj->connect("211Slave");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_211_slave);

$ts = time();
$ts-=15*24*60*60;
$dt=date("Y-m-d",$ts);


$sql="SELECT VIEWER,VIEWED FROM newjs.VIEW_LOG_TRIGGER  WHERE DATE<'$dt'";
$res=mysql_query($sql,$db_211_slave) or die(mysql_error($db_211_slave).$sql);
while($row=mysql_fetch_assoc($res))
{
        $viewer=$row["VIEWER"];
        $viewed=$row["VIEWED"];

	$sql="DELETE FROM newjs.VIEW_LOG_TRIGGER WHERE DATE<'$dt' AND VIEWER='$viewer' and VIEWED='$viewed'";
	mysql_query($sql,$db_211)  or die(mysql_error($db_211));
}
?>
