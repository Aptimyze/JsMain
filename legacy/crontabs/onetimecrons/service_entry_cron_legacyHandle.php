<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include("../connect.inc");
$db =connect_db();
$db_slave =connect_slave();

for($x=2011;$x<=2014;$x++){
	for($y=1;$y<=12;$y++){

		if($y<10)
			$z="0".$y;
		else
			$z=$y;
		if($x=='2014' && $z=='06')
			die('END');

		$st_date=$x."-".$z."-01 00:00:00";
		$end_date=$x."-".$z."-31 23:59:59";

/* Section for newly purchased services */
$sql ="SELECT COUNT(*) cnt,A.SERVICEID,B.CENTER, LEFT( B.ENTRY_DT, 10 ) AS ENTRY_DT FROM billing.SERVICE_STATUS AS A JOIN billing.PURCHASES AS B ON A.BILLID=B.BILLID WHERE B.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND B.STATUS='DONE' GROUP BY A.SERVICEID,CENTER,LEFT( B.ENTRY_DT, 10 )";
$res=mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js($db_slave));
while($row=mysql_fetch_array($res))
{
	$count		=$row["cnt"];
	$serviceid	=$row["SERVICEID"];
	$center		=$row["CENTER"];
	$entryDate      =$row['ENTRY_DT'];	

	$sql1 ="SELECT BILLID,ENTRY_DT,SERVICEID FROM billing.PURCHASES WHERE ENTRY_DT BETWEEN '$entryDate 00:00:00' AND '$entryDate 23:59:59' AND STATUS='DONE' AND CENTER='$center'";
	$res1=mysql_query_decide($sql1,$db_slave) or die("$sql1".mysql_error_js($db_slave));
	while($row1=mysql_fetch_array($res1))
	{
		//select serviceid for billid
		$billid 	=$row1['BILLID'];
		//$entryDate 	=$row1['ENTRY_DT'];
		$serviceid1	=$row1['SERVICEID'];
		$serviceid1Arr	=@explode(",",$serviceid1);
		$serviceid1	=$serviceid1Arr[0];

		$sql2 = "SELECT AMOUNT FROM billing.PAYMENT_DETAIL WHERE BILLID=$billid";
		$res2=mysql_query_decide($sql2,$db_slave) or die("$sql2".mysql_error_js($db_slave));
		$row2=mysql_fetch_array($res2);
		$amount = $row2['AMOUNT'];

		if(strstr($serviceid1,'ES')){
			$sql3 ="select SERVICEID from billing.SERVICE_STATUS where BILLID='$billid' AND SERVICEID='$serviceid'";
			$res3=mysql_query_decide($sql3,$db_slave) or die("$sql3".mysql_error_js($db_slave));
			if($row3=mysql_fetch_array($res3)){
				$count--;
				if(strstr($serviceid,'P')){
					$dataSet[$entryDate][$center][$serviceid1]++;
					if(!empty($amount) && $amount>0){
						$paidComboCount[$entryDate][$center][$serviceid1]++;
					}
				}
			}
		} else {
			$sql3 ="select SERVICEID from billing.SERVICE_STATUS where BILLID='$billid' AND SERVICEID='$serviceid'";
			$res3=mysql_query_decide($sql3,$db_slave) or die("$sql3".mysql_error_js($db_slave));
			if($row3=mysql_fetch_array($res3)){
				if(!empty($amount) && $amount>0){
					$paidCount[$center][$serviceid]++;
				}
			}
		}

	}

	if($count>0){
		$paidCnt = $paidCount[$center][$serviceid];
		$freeCnt = $count - $paidCnt;
		//$sql_insert="insert into MIS.SERVICE_DETAILS_NEW(`ENTRY_DT`,`COUNT`,`SERVICE`,`BRANCH`,`FREE_COUNT`,`PAID_COUNT`) VALUES('$entryDate','$count','$serviceid','$center','$freeCnt','$paidCnt')";
		$sql_insert ="update MIS.SERVICE_DETAILS SET FREE_COUNT='$freeCnt',PAID_COUNT='$paidCnt' WHERE ENTRY_DT='$entryDate' AND SERVICE='$serviceid' AND BRANCH='$center'";
		mysql_query_decide($sql_insert,$db) or die("$sql_insert".mysql_error_js($db));
	}
	unset($paidCount);
}

// entry for e-Sathi service purchased
if(count($dataSet)>0){
	foreach($dataSet as $dateKey=>$dataSetNew){
		mysql_ping();
		foreach($dataSetNew as $key=>$branchVal){
			foreach($branchVal as $key1=>$sidVal){
				$newCnt =$dataSet[$dateKey][$key][$key1];
				$paidCnt = $paidComboCount[$dateKey][$key][$key1];
				$freeCnt = $newCnt - $paidCnt;
				//$sql_insert="insert into MIS.SERVICE_DETAILS_NEW(`ENTRY_DT`,`COUNT`,`SERVICE`,`BRANCH`,`FREE_COUNT`,`PAID_COUNT`) VALUES('$dateKey','$newCnt','$key1','$key','$freeCnt','$paidCnt')";
				$sql_insert ="update MIS.SERVICE_DETAILS SET FREE_COUNT='$freeCnt',PAID_COUNT='$paidCnt' WHERE ENTRY_DT='$dateKey' AND SERVICE='$key1' AND BRANCH='$key'";
				mysql_query_decide($sql_insert,$db) or die("$sql_insert".mysql_error_js($db));
			}
		}
	}
}

unset($dataSet);
unset($paidCount);
unset($paidComboCount);
unset($serviceid1Arr);
/* Newly purchased service section ends */

}
}

?>
