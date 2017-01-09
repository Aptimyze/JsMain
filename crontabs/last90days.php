<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

ini_set("max_execution_time",0);

$flag_using_php5=1;
include("connect.inc");
$db = connect_737();

//header("Content-Type: application/vnd.ms-excel");
//header("Content-Disposition:attachment; filename=deferral.xls");
//header("Pragma: no-cache");
//header("Expires: 0");

$header = "PROFILEID"."\t"."LOGIN COUNT"."\t"."CONTACTS INITIATED"."\t"."CONTACTS RECEIVED"."\t"."TOTAL ACCEPTANCE"."\t"."REGISTRATION DATE"."\t"."PAYMENT DATE"."\t"."PHOTODATE";
echo $header."\n";

$ts=time();
$ts-=90*24*60*60;
$date=date("Y-m-d H:i:s",$ts);

$sql_pid = "SELECT PROFILEID,HAVEPHOTO,PHOTODATE, ENTRY_DT FROM newjs.JPROFILE WHERE LAST_LOGIN_DT >=  '$date'  AND ACTIVATED IN ('Y','H')";
$res = mysql_query($sql_pid,$db) or logError($sql_pid,$db);
while($row = mysql_fetch_array($res))
{
	$pid = $row['PROFILEID'];
	if($row['HAVEPHOTO']!='N')
		$PHOTODATE=$row['PHOTODATE'];

	$sql1 = "SELECT COUNT(*) AS CNT FROM newjs.LOGIN_HISTORY WHERE PROFILEID = '$pid' AND LOGIN_DT >= '$date'";
	$res1 = mysql_query($sql1) or logError($sql1,$db);
	$row1 = mysql_fetch_array($res1);
	$LOGINCNT=$row1['CNT'];

	$sendersIn=$pid;
	$typeIn="'I'";
	$timeClause="TIME>='$date'";
	$contactResult=getResultSet("COUNT(*) AS CNT2",$sendersIn,'','','',$typeIn,'',$timeClause,'','','','','',"Y");
	$INITIATE_CNT=$contactResult[0]["CNT2"];
	unset($contactResult);

	$receiversIn=$pid;
	$timeClause="TIME>='$date'";
	$contactResult=getResultSet("COUNT(*) AS CNT3",'','',$receiversIn,'',$typeIn,'',$timeClause,'','','','','',"Y");
	$RECEIVE_CNT=$contactResult[0]["CNT3"];
	unset($contactResult);

	$receiversIn=$pid;
	$typeIn="'A'";
	$timeClause="TIME>='$date'";
	$contactResult=getResultSet("COUNT(*) AS CNT",'','',$receiversIn,'',$typeIn,'',$timeClause,'','','','','',"Y");
	$cnt1=$contactResult[0]["CNT"];
	unset($contactResult);

	$sendersIn=$pid;
	$typeIn="'A'";
	$timeClause="TIME>='$date'";
	$contactResult=getResultSet("COUNT(*) AS CNT",$sendersIn,'','','',$typeIn,'',$timeClause,'','','','','',"Y");
	$cnt2=$contactResult[0]["CNT"];
	unset($contactResult);

        $total =  $cnt1+$cnt2;

	$ACCEPTANCE_CNT = $total;

	$REG_DT=$row['ENTRY_DT'];

	$sql6 = "SELECT ENTRY_DT FROM billing.PAYMENT_DETAIL WHERE STATUS IN('DONE','ADJUST') AND PROFILEID='$pid' order by ENTRY_DT desc limit 1";
	$res6 = mysql_query($sql6,$db) or logError($sql6,$db);
	$row6 = mysql_fetch_array($res6);
	$PAYMENT_DT= $row6['ENTRY_DT'];

	$line=$pid."\t".$LOGINCNT."\t".$INITIATE_CNT."\t".$RECEIVE_CNT."\t".$ACCEPTANCE_CNT."\t".$REG_DT."\t".$PAYMENT_DT."\t".$PHOTODATE;

	$data = trim($line)."\t \n";	

	echo $data;

	unset($data);
	unset($PROFILEID);
	unset($PHOTODATE);
	unset($LOGINCNT);
	unset($INITIATE_CNT);
	unset($RECEIVE_CNT);
	unset($ACCEPTANCE_CNT);
	unset($REG_DT);
	unset($PAYMENT_DT);
}
?>
