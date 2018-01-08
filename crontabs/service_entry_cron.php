<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");

include("connect.inc");
$db =connect_db();
$db_slave =connect_db();

$ts=time();
$hour = date("H",$ts);
if($hour == "00"){
    $ts-=24*60*60;

    $today=date("Y-m-d",$ts);
	//$today ="2017-04-27";

    $st_date=$today." 00:00:00";
    $end_date=$today." 23:59:59";
} else{
    $today=date("Y-m-d",$ts);
    //$today ="2017-04-28";
	$st_date = $today." 00:00:00";
	$end_date = $today." ".date("H:i:s",$ts);
}

//Delete entry from table MIS.SERVICE_DETAIL
$deleteSQL = "DELETE FROM MIS.SERVICE_DETAILS WHERE ENTRY_DT = '$today'";
$deleteRES = mysql_query_decide($deleteSQL,$db) or die("$deleteSQL".mysql_error_js($db));

/* Section for newly purchased services */
$sql ="SELECT COUNT(*) cnt,A.SERVICEID,B.CENTER, MEM_UPGRADE AS UPGRADE FROM billing.SERVICE_STATUS AS A JOIN billing.PURCHASES AS B ON A.BILLID=B.BILLID WHERE B.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND B.STATUS='DONE' GROUP BY A.SERVICEID,CENTER,MEM_UPGRADE";
$res=mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js($db_slave));
while($row=mysql_fetch_array($res))
{
        if($row['UPGRADE'] ==NULL){

	$count		=$row["cnt"];
	$serviceid	=$row["SERVICEID"];
	$center		=$row["CENTER"];

	$sql1 ="SELECT BILLID,ENTRY_DT,SERVICEID FROM billing.PURCHASES WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND STATUS='DONE' AND CENTER='$center' AND MEM_UPGRADE IS NULL";
	$res1=mysql_query_decide($sql1,$db_slave) or die("$sql1".mysql_error_js($db_slave));
	while($row1=mysql_fetch_array($res1))
	{
		//select serviceid for billid
		$billid 	=$row1['BILLID'];
		$entryDate 	=$row1['ENTRY_DT'];
		$serviceid1	=$row1['SERVICEID'];
		$serviceid1Arr	=@explode(",",$serviceid1);
		$serviceid1	=$serviceid1Arr[0];
		
		$sql2 = "SELECT AMOUNT FROM billing.PAYMENT_DETAIL WHERE BILLID=$billid";
		$res2=mysql_query_decide($sql2,$db_slave) or die("$sql2".mysql_error_js($db_slave));
		$row2=mysql_fetch_array($res2);
		$amount = $row2['AMOUNT'];
		//print "billid- ".$billid.", amount- ".$amount.", service_det- ".$serviceid.", purchases-".$serviceid1.PHP_EOL;

		if(strstr($serviceid1,'ES')){
			$sql3 ="select SERVICEID from billing.SERVICE_STATUS where BILLID='$billid' AND SERVICEID='$serviceid'";
			$res3=mysql_query_decide($sql3,$db_slave) or die("$sql3".mysql_error_js($db_slave));
			if($row3=mysql_fetch_array($res3)){
				$count--;
				if(strstr($serviceid,'P')){
					$dataSet[$center][$serviceid1]++;
					if(!empty($amount) && $amount>0){
						$paidComboCount[$center][$serviceid1]++;
					}
				}
			}
		} else if(strstr($serviceid1,'NCP')){
			$sql3 ="select SERVICEID from billing.SERVICE_STATUS where BILLID='$billid' AND SERVICEID='$serviceid'";
			$res3=mysql_query_decide($sql3,$db_slave) or die("$sql3".mysql_error_js($db_slave));
			
			if($row3=mysql_fetch_array($res3)){
				
				if(strstr($serviceid,'C')){
					$count--;
					$dataSet[$center][$serviceid1]++;
					if(!empty($amount) && $amount>0){
						$paidComboCount[$center][$serviceid1]++;
					}
				}
				else if(strstr($serviceid, 'A')){
					$dataSet[$center][$serviceid]++;
					if(!empty($amount) && $amount>0){
						$paidCount[$center][$serviceid]++;
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
		$sqlIns="insert into MIS.SERVICE_DETAILS(`ENTRY_DT`,`COUNT`,`SERVICE`,`BRANCH`,`FREE_COUNT`,`PAID_COUNT`) VALUES('$today','$count','$serviceid','$center','$freeCnt','$paidCnt')";
                //print_r($sqlIns);
		mysql_query_decide($sqlIns,$db) or die("$sqlIns".mysql_error_js($db));
        }
	unset($paidCount);
        unset($freeCnt);
        unset($count);
        unset($center);
        unset($serviceid);

        }else{
            /* Start: JSC-2558: Section for entry in case of upgrade services */

        $count		=$row["cnt"];
	$serviceid	=$row["SERVICEID"];
	$center		=$row["CENTER"];

	$sql1 ="SELECT BILLID,ENTRY_DT,SERVICEID FROM billing.PURCHASES WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND STATUS='DONE' AND CENTER='$center' AND MEM_UPGRADE='MAIN'";
	$res1=mysql_query_decide($sql1,$db_slave) or die("$sql1".mysql_error_js($db_slave));
	while($row1=mysql_fetch_array($res1))
	{
		//select serviceid for billid
		$billid 	=$row1['BILLID'];
		$entryDate 	=$row1['ENTRY_DT'];
		$serviceid1	=$row1['SERVICEID'];
		$serviceid1Arr	=@explode(",",$serviceid1);
		$serviceid1	=$serviceid1Arr[0];

		$sql2 = "SELECT AMOUNT FROM billing.PAYMENT_DETAIL WHERE BILLID=$billid";
		$res2=mysql_query_decide($sql2,$db_slave) or die("$sql2".mysql_error_js($db_slave));
		$row2=mysql_fetch_array($res2);
		$amount = $row2['AMOUNT'];
		//print "billid- ".$billid.", amount- ".$amount.", service_det- ".$serviceid.", purchases-".$serviceid1.PHP_EOL;

		if(strstr($serviceid1,'ES')){
			$sql3 ="select SERVICEID from billing.SERVICE_STATUS where BILLID='$billid' AND SERVICEID='$serviceid'";
			$res3=mysql_query_decide($sql3,$db_slave) or die("$sql3".mysql_error_js($db_slave));
			if($row3=mysql_fetch_array($res3)){
				$count--;
				if(strstr($serviceid,'P')){
                                        $serviceidwithUG = $serviceid1."-UG";
					$dataSet[$center][$serviceidwithUG]++;
					if(!empty($amount) && $amount>0){
						$paidComboCount[$center][$serviceidwithUG]++;
					}
				}
			}
		} else if(strstr($serviceid1,'NCP')){
			$sql3 ="select SERVICEID from billing.SERVICE_STATUS where BILLID='$billid' AND SERVICEID='$serviceid'";
			$res3=mysql_query_decide($sql3,$db_slave) or die("$sql3".mysql_error_js($db_slave));
			if($row3=mysql_fetch_array($res3)){
				$count--;
				if(strstr($serviceid,'C')){
                                        $serviceidwithUG = $serviceid1."-UG";
					$dataSet[$center][$serviceidwithUG]++;
					if(!empty($amount) && $amount>0){
						$paidComboCount[$center][$serviceidwithUG]++;
					}
				}
			}
		}else {
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
		$sqlIns="insert into MIS.SERVICE_DETAILS(`ENTRY_DT`,`COUNT`,`SERVICE`,`BRANCH`,`FREE_COUNT`,`PAID_COUNT`) VALUES('$today','$count','$serviceid-UG','$center','$freeCnt','$paidCnt')";
                //print_r($sqlIns);
		mysql_query_decide($sqlIns,$db) or die("$sqlIns".mysql_error_js($db));
	}
        unset($paidCount);
        unset($freeCnt);
        unset($count);
        unset($center);
        unset($serviceid);
        }

        /* End: JSC-2558: Section for entry in case of upgrade services */
}

// entry for e-Sathi service purchased
if(count($dataSet)>0){
	foreach($dataSet as $key=>$branchVal){
		foreach($branchVal as $key1=>$sidVal){
			if(strstr($key1, "A")==false){
				$newCnt =$dataSet[$key][$key1];
				$paidCnt = $paidComboCount[$key][$key1];
				$freeCnt = $newCnt - $paidCnt;
				$sql_insert="insert into MIS.SERVICE_DETAILS(`ENTRY_DT`,`COUNT`,`SERVICE`,`BRANCH`,`FREE_COUNT`,`PAID_COUNT`) VALUES('$today','$newCnt','$key1','$key','$freeCnt','$paidCnt')";
				
				mysql_query_decide($sql_insert,$db) or die("$sql_insert".mysql_error_js($db));
			}
		}
	}
}
unset($dataSet);
unset($paidCount);
unset($paidComboCount);
/* Newly purchased service section ends */

//*  Section for cancelled transaction  */
/*$sql ="select BILLID from billing.EDIT_DETAILS_LOG where ENTRY_DT>='$st_date' AND ENTRY_DT<='$end_date' AND CHANGES LIKE 'TRANSACTION CANCELLED%'";
$res=mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js($db_slave));
while($row=mysql_fetch_array($res))
{
	$billid =$row['BILLID'];
	$sql1 ="select SERVICEID,ENTRY_DT,CENTER from billing.PURCHASES WHERE BILLID='$billid' AND STATUS='CANCEL'";
	$res1=mysql_query_decide($sql1,$db_slave) or die("$sql1".mysql_error_js($db_slave));

	$sql2 = "SELECT AMOUNT FROM billing.PAYMENT_DETAIL WHERE BILLID=$billid";
	$res2=mysql_query_decide($sql2,$db_slave) or die("$sql2".mysql_error_js($db_slave));
	$row2=mysql_fetch_array($res2);
	$amount = $row2['AMOUNT'];

	if($row1=mysql_fetch_array($res1)){
		$center	   	=$row1['CENTER'];
		$serviceidArr 	=@explode(",",$row1['SERVICEID']);
		$entryDtArr 	=@explode(" ",$row1['ENTRY_DT']);
		$entryDtSet 	=$entryDtArr[0];
		if(!$center)
			$center ='ONLINE_P';
		foreach($serviceidArr as $key=>$val){
			$dataSetArr[$entryDtSet][$center][$val] +=1;
			if(!empty($amount) && $amount>0){
				$paidCount[$entryDtSet][$center][$val] +=1;
			} elseif(!empty($amount) && $amount<0){
				$freeCount[$entryDtSet][$center][$val] +=1;
			}
		}
	}
}*/

/*Section for today's negative entries in billing.PAYMENT_DETAIL_NEW for billings done earlier*/
//confirm status cases
$sql ="select AMOUNT,BILLID,STATUS from billing.PAYMENT_DETAIL_NEW where ENTRY_DT>='$st_date' AND ENTRY_DT<='$end_date' AND STATUS IN('BOUNCE','CANCEL', 'REFUND', 'CHARGE_BACK')";
$res=mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js($db_slave));

while($row=mysql_fetch_array($res))
{

	$billid =$row['BILLID'];
	$amount = $row['AMOUNT'];
	$sql1 ="select SERVICEID,CENTER,MEM_UPGRADE,SERVEFOR from billing.PURCHASES WHERE BILLID='$billid' AND ENTRY_DT<='$end_date'";

	$res1=mysql_query_decide($sql1,$db_slave) or die("$sql1".mysql_error_js($db_slave));
	if($row1=mysql_fetch_array($res1)){
		$center	   	=$row1['CENTER'];
		$serviceidArr =@explode(",",$row1['SERVICEID']);
		if(!$center)
			$center ='ONLINE_P';
		$sql2 ="select SUM(NET_AMOUNT) AS NET_AMOUNT from billing.PURCHASE_DETAIL WHERE BILLID='$billid'";
		$res2=mysql_query_decide($sql2,$db_slave) or die("$sql2".mysql_error_js($db_slave));
		if($row2=mysql_fetch_array($res2)){
			$netAmount = $row2["NET_AMOUNT"];
			$refundAmount = -1 * $amount;
			if($refundAmount >= 0.5*$netAmount){
				foreach($serviceidArr as $key=>$val){
					if($row1['MEM_UPGRADE']=="MAIN" && strpos($val, "A")==false){
						$val .= "-UG";
					}
					$dataSetArr[$today][$center][$val] -= 1;
					
					if(!empty($amount)){
						$paidNegCount[$today][$center][$val] -= 1;
					} elseif(!empty($amount) && $amount<0){
						$freeNegCount[$today][$center][$val] -= 1;
					}
				}
			}
		}
	}
}
if(count($dataSetArr)>0){
	foreach($dataSetArr as $date=>$valArr){
		foreach($valArr as $center=>$valArr1){
			foreach($valArr1 as $service=>$count){
				if(isset($paidNegCount[$date][$center][$service])){
					$paidCnt = $paidNegCount[$date][$center][$service];
				} 
				else { 
					$paidCnt = 0;
				}
				if(isset($freeNegCount[$date][$center][$service])){
					$freeCnt = $freeNegCount[$date][$center][$service];
				}
				else {
					$freeCnt = 0;
				}
				if($center=='ONLINE_P')
					$sqlCenter ='';
				else{
					$sqlCenter = $center;
				}
				
				$sqlUp="insert into MIS.SERVICE_DETAILS(`ENTRY_DT`,`COUNT`,`SERVICE`,`BRANCH`,`FREE_COUNT`,`PAID_COUNT`) VALUES('$today','$count','$service','$sqlCenter','$freeCnt','$paidCnt')";
				mysql_query_decide($sqlUp,$db) or die("$sqlUp".mysql_error_js($db));
			}
		}
	}
}

unset($dataSetArr);
$sqlDel ="delete from MIS.SERVICE_DETAILS where COUNT=0";
mysql_query_decide($sqlDel,$db) or die("$sqlDel".mysql_error_js($db));
/*  service cancelled section ends */

?>
