<?php
chdir(dirname(__FILE__));

include("connect.inc");

$today=date('Y-m-d');
$db=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
mysql_select_db("newjs",$db) or die(mysql_error1());

$sql="INSERT INTO VARIABLE_DISCOUNT_LOG SELECT * FROM VARIABLE_DISCOUNT WHERE EDATE<'$today'";
$res=mysql_query($sql) or die(mysql_error1($sql));

$sql_del="DELETE FROM VARIABLE_DISCOUNT WHERE EDATE<'$today'";
$res_del=mysql_query($sql_del) or die(mysql_error1($sql_del));

function mysql_error1($sql)
{
        $msg=$sql."::".mysql_error();
        //mail("neha.verma@jeevansathi.com,nehaverma.dce@gmail.com","Jeevansathi Error in dump_special_discount",$msg);
}

?>
