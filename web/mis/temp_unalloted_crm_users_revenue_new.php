<?php

include("connect.inc");
include_once("../profile/pg/functions.php");
$db=connect_misdb();
//$db2=connect_master();

// dol conv rate changed to 43.5 - overwitten current 45.8 - so that mis does not change
//$DOL_CONV_RATE=43.5;

$opsarr=array();

$sql="SELECT PID FROM test.CRM_PID WHERE 1";
$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
while($row=mysql_fetch_assoc($res))
{
	$pid=$row['PID'];
	$sql="SELECT ID, ALLOTED_TO, ALLOT_TIME, RELAX_DAYS FROM incentive.CRM_DAILY_ALLOT WHERE PROFILEID='$pid' AND ALLOT_TIME>='2007-10-09' ORDER BY ID DESC LIMIT 1";
	$res1=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	if($row1=mysql_fetch_array($res1))
	{
		$alloted_to=$row1['ALLOTED_TO'];
		if($alloted_to=="abhishek.bhatia")
		{
		$allot_time=$row1['ALLOT_TIME'];
		$relax_days=30+$row1['RELAX_DAYS'];

		if(!in_array($alloted_to, $opsarr))
		{
			$opsarr[]=$alloted_to;
		}

		$i=array_search($alloted_to, $opsarr);
		$arr[$i]["ALLOTED"]++;


		$sql="SELECT PROFILEID, STATUS, if(TYPE='DOL',AMOUNT*$DOL_CONV_RATE,AMOUNT) AS AMOUNT FROM billing.PAYMENT_DETAIL WHERE PROFILEID='$pid' AND ENTRY_DT BETWEEN '$allot_time' AND DATE_ADD('$allot_time', INTERVAL $relax_days DAY) AND STATUS IN ('DONE','REFUND')";
		$res1=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row1=mysql_fetch_array($res1))
		{
			if($row1['STATUS']=="DONE")
			{
echo $row1['PROFILEID'].", ".$row1['AMOUNT']."\n";
//				$arr[$i]["CONV"]++;
//				$arr[$i]["AMOUNT"]+=$row1['AMOUNT'];
			}
			else
			{
				$arr[$i]["CONV"]--;
				$arr[$i]["AMOUNT"]-=$row1['AMOUNT'];
			}
		}
		}
	}
}

echo "AGENT, COUNT OF ALLOTED PROFILES, CONVERTED, REVENUE, REVENUE(NET OFF TAX)\n";
for($i=0;$i<count($arr);$i++)
{
	$net_off_tax = net_off_tax_calculation($arr[$i]["AMOUNT"]);
	echo "$opsarr[$i], ".$arr[$i]["ALLOTED"].", ".$arr[$i]["CONV"].", ".$arr[$i]["AMOUNT"].",$net_off_tax\n";
}
print_r($opsarr);
print_r($arr);
?>
