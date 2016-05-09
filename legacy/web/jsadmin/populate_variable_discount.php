<?php
include('connect.inc');
$db = connect_db();
$entryDate =date("Y-m-d");
$profileArr =array();

$sql="INSERT INTO billing.VARIABLE_DISCOUNT_LOG SELECT * FROM billing.VARIABLE_DISCOUNT WHERE EDATE < CURDATE()";
$res= mysql_query($sql,$db) or die(mysql_error1($sql,$db));

$sql="INSERT INTO billing.VARIABLE_DISCOUNT_OFFER_DURATION_LOG SELECT billing.VARIABLE_DISCOUNT_OFFER_DURATION.*, billing.VARIABLE_DISCOUNT.EDATE FROM billing.VARIABLE_DISCOUNT_OFFER_DURATION left join billing.VARIABLE_DISCOUNT on billing.VARIABLE_DISCOUNT_OFFER_DURATION.PROFILEID=billing.VARIABLE_DISCOUNT.PROFILEID WHERE billing.VARIABLE_DISCOUNT.EDATE < CURDATE()";
$res= mysql_query($sql,$db) or die(mysql_error1($sql,$db));

$sql="DELETE FROM billing.VARIABLE_DISCOUNT WHERE EDATE < CURDATE()";
$res= mysql_query($sql,$db) or die(mysql_error1($sql,$db));

$sql="DELETE billing.VARIABLE_DISCOUNT_OFFER_DURATION.* FROM billing.VARIABLE_DISCOUNT_OFFER_DURATION left join billing.VARIABLE_DISCOUNT on billing.VARIABLE_DISCOUNT_OFFER_DURATION.PROFILEID=billing.VARIABLE_DISCOUNT.PROFILEID WHERE billing.VARIABLE_DISCOUNT.PROFILEID IS NULL";
$res= mysql_query($sql,$db) or die(mysql_error1($sql,$db));

$sql="SELECT * FROM billing.VARIABLE_DISCOUNT_TEMP";
$res= mysql_query($sql,$db) or die(mysql_error1($sql,$db));
while($row= mysql_fetch_array($res))
{
	$pid =trim($row['PROFILEID'],'\r');
	if($pid){
		$service 	=trim($row['SERVICE'],'\r');
		$discL		=trim($row['12'],'\r');
		if(!isset($dateArr)){
			$sdate	=trim($row['SDATE'],'\r');
			$edate	=trim($row['EDATE'],'\r');
			$dateArr=array("$sdate","$edate");
		}
		$discMax 	=max(trim($row['3'],'\r'),trim($row['6'],'\r'),trim($row['12'],'\r'),$discL);

		$prevDiscount   =$dataArr[$pid];
		if($discMax>$prevDiscount)
			$profileArr[$pid] =$discMax;
		else
			$profileArr[$pid] =$prevDiscount; 

		$sqlIns="INSERT IGNORE INTO billing.VARIABLE_DISCOUNT_OFFER_DURATION values ('$pid','$service','" . trim($row['3'],'\r') . "','" . trim($row['6'],'\r') . "','". trim($row['12'],'\r') . "','". $discL . "')";
		mysql_query($sqlIns,$db) or die(mysql_error1($sqlIns,$db));
	}
}
if(count($dateArr)>0){
	$sdate =$dateArr[0];
	$edate =$dateArr[1];
}	
foreach($profileArr as $profileid=>$discount){

	$sqlAddVd ="INSERT IGNORE INTO billing.VARIABLE_DISCOUNT (PROFILEID,DISCOUNT,SDATE,EDATE,ENTRY_DT) VALUES('$profileid','$discount','$sdate','$edate','$entryDate')";
	mysql_query($sqlAddVd,$db) or die(mysql_error1($sqlAddVd,$db));
}

$sql="TRUNCATE TABLE billing.VARIABLE_DISCOUNT_TEMP";
mysql_query($sql,$db) or die(mysql_error1($sql,$db));

function mysql_error1($sql,$db)
{
        mail("manoj.rana@naukri.com","Error in populating variable discount csv from Manual Allot",mysql_error($db));
}

?>
