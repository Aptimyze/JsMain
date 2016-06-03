<?php
include("../profile/connect.inc");
$db=connect_db();
$temp="M000";
for($i=1;$i<=9;$i++)
{
	$number=$temp."$i";
	$sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('FGB01','$number','E','2007-10-10')";
	mysql_query_decide($sql) or die("$sql".mysql_error_js());	
}
$temp="M00";
for($i=10;$i<=99;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('FGB01','$number','E','2007-10-10')";     
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="M0";
for($i=100;$i<=999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('FGB01','$number','E','2007-10-10')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="M";
for($i=1000;$i<=2000;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('FGB01','$number','E','2007-10-10')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
?>
