<?php
$curFilePath = dirname(__FILE__)."/";
include_once(JsConstants::$cronDocRoot."/crontabs/connect.inc");
$dbM = connect_db();
$dbS = connect_slave();

mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

$sql = "SELECT PROFILEID AS PID FROM newjs.JPROFILE_EDUCATION WHERE UG_DEGREE in ('9','23','24')";
$res = mysql_query($sql,$dbS) or die(mysql_error($dbS).$sql);
while($row = mysql_fetch_array($res))
{
        $pidArr[] = $row["PID"];
}
if($pidArr)
{
        $str = implode(",",$pidArr);
        $sql = "UPDATE newjs.JPROFILE_EDUCATION SET UG_DEGREE = '' WHERE PROFILEID IN ($str)";
        $res = mysql_query($sql,$dbM) or die(mysql_error($dbM).$sql);
        unset($pidArr);
}
