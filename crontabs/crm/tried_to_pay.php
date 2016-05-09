<?php 
/*
 Cron to mark followups for the allocated handled profiles
 Case Handled: Failed payment
             : Membership page hit excluding failed payments
*/

$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
connect_db();

$processId ='9';
$sqlLast ="select DATE from incentive.LAST_HANDLED_DATE WHERE SOURCE_ID='$processId'";
$resLast=mysql_query($sqlLast) or logError($sqlLast);
if($rowLast=mysql_fetch_array($resLast))
        $startDate =$rowLast['DATE'];
//$startDate ="2013-10-16 00:00:00";
$endDate =date("Y-m-d H:i:s");

// Handling of Membership Page clicks logic (except failed payments and paid profiles) 
$profileArr =array();
$sql="SELECT PROFILEID,ENTRY_DT FROM billing.PAYMENT_HITS WHERE ENTRY_DT>'$startDate' AND ENTRY_DT<='$endDate' ORDER BY ENTRY_DT DESC";
$res=mysql_query($sql) or logError($sql);
while($row=mysql_fetch_array($res))
{
	$profileid 	=$row['PROFILEID'];
	if(!in_array("$profileid",$profileArr)){
		$profileArr[]   =$profileid;
		$entryDt =$row['ENTRY_DT'];
		setFollowUpStatus($profileid, $entryDt,'Y');
	}
}

// Handling of Failed Payments logic (except paid profiles) 
$profileArr =array();
$sql="SELECT PROFILEID,ENTRY_DT FROM billing.ORDERS WHERE STATUS='' AND ENTRY_DT>'$startDate' AND ENTRY_DT<='$endDate' ORDER BY ENTRY_DT DESC";
$res=mysql_query($sql) or logError($sql);
while($row=mysql_fetch_array($res))
{
	$profileid =$row['PROFILEID'];
	if(!in_array("$profileid",$profileArr)){
		$profileArr[]   =$profileid;
		$entryDt =$row['ENTRY_DT'];
		setFollowUpStatus($profileid, $entryDt,'N');
	}
}
unset($profileArr);
$sqlUp="update incentive.LAST_HANDLED_DATE set DATE='$endDate' WHERE SOURCE_ID='$processId'";
mysql_query($sqlUp) or logError($sqlUp);

function setFollowUpStatus($profileid,$entryDt,$orders)
{
	$sqlAdmin="SELECT PROFILEID,ALLOTED_TO,STATUS,ALLOT_TIME FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND STATUS IN('C','F')";
        $resAdmin=mysql_query($sqlAdmin) or logError($sqlAdmin);
        $rowAdmin=mysql_fetch_array($resAdmin);
	if($rowAdmin['PROFILEID']){

		$allotedTo =$rowAdmin['ALLOTED_TO'];
		$status =$rowAdmin['STATUS'];
		$allotTime =$rowAdmin['ALLOT_TIME'];
		$sqlDisp ="SELECT PROFILEID,ENTRY_DT FROM incentive.HISTORY WHERE ENTRYBY='$allotedTo' AND PROFILEID='$profileid' AND ENTRY_DT>='$allotTime' ORDER BY ENTRY_DT DESC LIMIT 1";	
		$resDisp=mysql_query($sqlDisp) or logError($sqlDisp);
		$rowDisp=mysql_fetch_array($resDisp);
		$lastDispDt =$rowDisp['ENTRY_DT'];
		$pid =$rowDisp['PROFILEID'];		
		if((strtotime($entryDt)>strtotime($lastDispDt)) && $pid){
		
				//$updateQuery =",FOLLOWUP_TIME=NOW(),STATUS='F' ";
				if($orders=='N')
					$updateQuery .=",TIMES_TRIED=TIMES_TRIED+1 ";
				$sql="UPDATE incentive.MAIN_ADMIN set ORDERS='$orders'".$updateQuery." WHERE PROFILEID='$profileid'";
				mysql_query($sql) or logError($sql);
				unset($updateQuery);
		}
	}	
}
?>
