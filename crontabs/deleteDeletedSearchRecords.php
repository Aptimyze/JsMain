<?php
$curFilePath = dirname(__FILE__)."/";
include_once(JsConstants::$cronDocRoot."/crontabs/connect.inc");
$dbM = connect_db();
$dbS = connect_slave();

mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

$table_name = array("SEARCH_FEMALE","SEARCH_MALE");

foreach($table_name as $k=>$v)
{
        $sql = "SELECT A.PROFILEID AS PID FROM newjs.$v A LEFT JOIN JPROFILE B ON A.PROFILEID = B.PROFILEID WHERE B.ACTIVATED='D'";
        $res = mysql_query($sql,$dbS) or die(mysql_error($dbS).$sql);
        while($row = mysql_fetch_array($res))
        {
                $pidArr[] = $row["PID"];
        }
        if($pidArr)
        {
                $str = implode(",",$pidArr);
	        $sql = "DELETE FROM newjs.$v WHERE PROFILEID IN ($str)";
                $res = mysql_query($sql,$dbM) or die(mysql_error($dbM).$sql);
                unset($pidArr);
        }
}
