<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

include("connect.inc");
$db = connect_db();
$db_slave = connect_slave();

$ts=time();
$ts-=24*60*60;
$today=date("Y-m-d",$ts);
$st_date=$today." 00:00:00";
$end_date=$today." 23:59:59";

$sql1 = "SELECT a.ENTRY_DT,COUNT(*),'T',b.CENTER FROM billing.PAYMENT_DETAIL a, billing.PURCHASES b WHERE a.MODE='CHEQUE' AND a.BILLID=b.BILLID AND a.ENTRY_DT BETWEEN '$st_date' AND '$end_date' GROUP BY a.ENTRY_DT,b.CENTER";
$result1=mysql_query($sql1,$db_slave) or $msg .= "\n$sql1 \nError :".mysql_error();
while($myrow1=mysql_fetch_array($result1))
{
	$sqlI1="INSERT INTO MIS.CHEQUE_DETAILS(ENTRY_DT,COUNT,STATUS,BRANCH) VALUES ('$myrow1[ENTRY_DT]','$myrow1[COUNT]','$myrow1[STATUS]','$myrow1[BRANCH]')";
	mysql_query($sqlI1,$db) or logError($sql1);//die(mysql_error());
}

$sql2 = "SELECT a.BOUNCE_DT,COUNT(*),'B',b.CENTER FROM billing.PAYMENT_DETAIL a, billing.PURCHASES b WHERE a.MODE='CHEQUE' AND a.STATUS='BOUNCE' AND a.BILLID=b.BILLID AND a.BOUNCE_DT='$today' GROUP BY a.BOUNCE_DT,b.CENTER";
$result2=mysql_query($sql2,$db_slave) or $msg .= "\n$sql2 \nError :".mysql_error();
while($myrow2=mysql_fetch_array($result2))
{
        $sqlI2="INSERT INTO MIS.CHEQUE_DETAILS(ENTRY_DT,COUNT,STATUS,BRANCH) VALUES ('$myrow2[BOUNCE_DT]','$myrow2[COUNT]','$myrow2[STATUS]','$myrow2[BRANCH]')";
        mysql_query($sqlI2,$db) or logError($sql2);//die(mysql_error());
}
?>
