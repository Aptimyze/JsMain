<?php
include("connect.inc");
$db=connect_db();
$temp="BSB0000";
for($i=1;$i<=9;$i++)
{
	$number=$temp."$i";
	$sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE) VALUES('BSB01','$number','E')";
	mysql_query_decide($sql) or die("$sql".mysql_error_js());	
}
$temp="BSB000";
for($i=10;$i<=99;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE) VALUES('BSB01','$number','E')";     
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="BSB00";
for($i=100;$i<=999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE) VALUES('BSB01','$number','E')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="BSB0";
for($i=1000;$i<=9999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE) VALUES('BSB01','$number','E')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="BSB";
for($i=10000;$i<=12000;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE) VALUES('BSB01','$number','E')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
?>
