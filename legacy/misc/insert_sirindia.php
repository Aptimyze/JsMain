<?php
include("../profile/connect.inc");
$db=connect_db();
$temp="SIR0000";
for($i=1;$i<=9;$i++)
{
	$number=$temp."$i";
	$sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('SIR01','$number','E','2008-02-10')";
	mysql_query_decide($sql) or die("$sql".mysql_error_js());	
}
$temp="SIR000";
for($i=10;$i<=99;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('SIR01','$number','E','2008-02-10')";     
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="SIR00";
for($i=100;$i<=999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('SIR01','$number','E','2008-02-10')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="SIR0";
for($i=1000;$i<=9999;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('SIR01','$number','E','2008-02-10')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
$temp="SIR";
for($i=10000;$i<=30000;$i++)
{
        $number=$temp."$i";
        $sql="INSERT INTO billing.VOUCHER_NUMBER(CLIENTID,VOUCHER_NO,TYPE,EXPIRY_DATE) VALUES('SIR01','$number','E','2008-02-10')";
        mysql_query_decide($sql) or die("$sql".mysql_error_js());
}
?>
