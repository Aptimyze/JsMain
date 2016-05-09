<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
connect_db();

$startDt	="2014-05-22 06:40:00";
$endDt		="2014-05-22 06:40:59";

$sql ="select ID,PROFILEID,ALLOTED_TO from incentive.DEALLOCATION_TRACK WHERE ALLOTED_TO IN('Mayank.gupta','Bokaro_1') AND PROCESS_NAME='NO_LONGER_WORKING' AND DEALLOCATION_DT>='$startDt' AND DEALLOCATION_DT<='$endDt'";
$res = mysql_query($sql) or logError($sql);
while($row = mysql_fetch_array($res))
{

	$deAllocId	=$row['ID'];
        $profileid 	=$row['PROFILEID'];
	$allotedTo	=$row['ALLOTED_TO'];

	$sqlMain 	="select PROFILEID from incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
        $resMain 	=mysql_query($sqlMain) or logError($sqlMain);
        $rowMain 	=mysql_fetch_array($resMain);
	$pid 		=$rowMain['PROFILEID'];	
	if($pid)
		continue;
	
	//$sqlTrk ="select * from incentive.CRM_DAILY_ALLOT_TRACK where PROFILEID='$profileid' AND ALLOTED_TO='$allotedTo' ORDER BY ID DESC LIMIT 1";
	$sqlTrk ="select * from incentive.CRM_DAILY_ALLOT where PROFILEID='$profileid' AND ALLOTED_TO='$allotedTo' ORDER BY ID DESC LIMIT 1";
	$resTrk=mysql_query($sqlTrk) or logError($sqlTrk);
	if($rowTrk=mysql_fetch_array($resTrk)){
	
		$trackId   =$rowTrk['ID'];
		$profileid =$rowTrk['PROFILEID']; 
		$allotedTo =$rowTrk['ALLOTED_TO'];
		$allotTime =$rowTrk['ALLOT_TIME'];
		$relaxDays =$rowTrk['RELAX_DAYS'];
		$deAllocDt =$rowTrk['DE_ALLOCATION_DT'];
		$allocDays =$rowTrk['ALLOCATION_DAYS'];
			
		/*$sqlIns ="insert into incentive.CRM_DAILY_ALLOT (`PROFILEID`,`ALLOTED_TO`,`ALLOT_TIME`,`RELAX_DAYS`,`DE_ALLOCATION_DT`,`ALLOCATION_DAYS`) VALUES('$profileid','$allotedTo','$allotTime','$relaxDays','$deAllocDt','$allocDays')";
		$resIns =mysql_query($sqlIns) or logError($sqlIns);
		*/
			
		$sql2 ="insert ignore into incentive.MAIN_ADMIN (PROFILEID,ALLOT_TIME,CLAIM_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,FOLLOWUP_TIME,MODE,CONVINCE_TIME,COMMENTS,RES_NO,MOB_NO,EMAIL,WILL_PAY,TIMES_TRIED,ORDERS,REASON) SELECT PROFILEID,ALLOT_TIME,CLAIM_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,FOLLOWUP_TIME,MODE,CONVINCE_TIME,COMMENTS,RES_NO,MOB_NO,EMAIL,WILL_PAY,TIMES_TRIED,ORDERS,REASON FROM incentive.MAIN_ADMIN_LOG WHERE PROFILEID='$profileid' AND ALLOTED_TO='$allotedTo' and ALLOT_TIME='$allotTime' ORDER BY ID DESC limit 1";	
		$res2 =mysql_query($sql2) or logError($sql2);
		if($res2){
			$sql3 ="update incentive.MAIN_ADMIN_POOL set ALLOTMENT_AVAIL='N' where PROFILEID='$profileid'";
			mysql_query($sql3) or logError($sql3);

			$entryDt =date("Y-m-d H:i:s");	
			$sql4 ="insert into incentive.MANUAL_ALLOT_TEMP(PROFILEID,ENTRY_DT) VALUES('$profileid','$entryDt')";
			mysql_query($sql4) or logError($sql4);

        	        $sql5 ="DELETE from incentive.DEALLOCATION_TRACK where ID='$deAllocId'";
        	        mysql_query($sql5) or logError($sql5);
			echo $sql5."\n";
		}
	}
}
?>
