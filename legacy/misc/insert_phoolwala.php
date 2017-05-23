<?php
include("connect.inc");
$db=connect_db();
$temp="PH0000";
for($i=1;$i<=9;$i++)
{
	$number=$temp."$i";
	$sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE) VALUES('PH01','$number','E')";
	mysql_query_decide($sql) or die("$sql".mysql_error_js());	
}
$temp="PH000";
for($i=10;$i<=99;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE) VALUES('PH01','$number','E')";     
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="PH00";
for($i=100;$i<=999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE) VALUES('PH01','$number','E')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="PH0";
for($i=1000;$i<=9999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE) VALUES('PH01','$number','E')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="PH";
for($i=10000;$i<=18000;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE) VALUES('PH01','$number','E')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
?>
