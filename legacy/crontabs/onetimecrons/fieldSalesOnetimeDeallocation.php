<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
connect_db();

$dateSet 	='2014-10-01';
$todayDate 	=date("Y-m-d H:i:s");
$processName 	='HOTFIX_4841';
$deAllocatedBy	='SYSTEM';

$sql ="SELECT USERNAME from jsadmin.PSWRDS where PRIVILAGE LIKE '%ExcFld%'";
$res = mysql_query($sql) or logError($sql);
while($row = mysql_fetch_array($res))
{
	$agentName	=$row['USERNAME'];
	$sqlMain 	="select PROFILEID,ALLOTED_TO,ALLOT_TIME from incentive.MAIN_ADMIN WHERE ALLOTED_TO='$agentName'";
        $resMain 	=mysql_query($sqlMain) or logError($sqlMain);
        while($rowMain	=mysql_fetch_array($resMain))
	{
		$profileid	=$rowMain['PROFILEID'];
	        $allotedTo      =$rowMain['ALLOTED_TO'];
	        $allotTime      =$rowMain['ALLOT_TIME'];
	
		$sqlP ="SELECT PROFILEID FROM billing.SERVICE_STATUS WHERE (SERVEFOR LIKE '%F%' OR SERVEFOR='X') AND ACTIVE IN('Y') AND PROFILEID='$profileid' AND EXPIRY_DT>='$dateSet'";
		$resP=mysql_query($sqlP) or logError($sqlP);
		$rowP=mysql_fetch_array($resP);
		$pid =$rowP['PROFILEID'];
		if($pid)
		{
			// MAIN_ADMIN_LOG
			$sql1= "INSERT INTO incentive.MAIN_ADMIN_LOG (PROFILEID,ALLOT_TIME,CLAIM_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,FOLLOWUP_TIME,MODE,CONVINCE_TIME,COMMENTS,RES_NO,MOB_NO,EMAIL,WILL_PAY,TIMES_TRIED,ORDERS,REASON) SELECT PROFILEID,ALLOT_TIME,CLAIM_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,FOLLOWUP_TIME,MODE,CONVINCE_TIME,COMMENTS,RES_NO,MOB_NO,EMAIL,WILL_PAY,TIMES_TRIED,ORDERS,REASON FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
			$res1 =mysql_query($sql1) or logError($sql1);
			if($res1){
				// CRM_DAILY_ALLOT
				$sql2 ="update incentive.CRM_DAILY_ALLOT SET REAL_DE_ALLOCATION_DT='$todayDate' where PROFILEID='$profileid' AND ALLOTED_TO='$allotedTo' AND ALLOT_TIME='$allotTime' ORDER BY ID DESC LIMIT 1";
				mysql_query($sql2) or logError($sql2);

				// MAIN_ADMIN_POOL 	
       	                        $sql3 ="update incentive.MAIN_ADMIN_POOL set ALLOTMENT_AVAIL='Y' where PROFILEID='$profileid'";
       	                        mysql_query($sql3) or logError($sql3);

				// DEALLOCATION_TRACK
				$sql4 ="INSERT INTO incentive.DEALLOCATION_TRACK (`PROFILEID`,`PROCESS_NAME`,`DEALLOCATION_DT`,`ALLOTED_TO`,`DEALLOCATED_BY`) VALUES('$profileid','$processName','$todayDate','$allotedTo','$deAllocatedBy')";
				mysql_query($sql4) or logError($sql4);

				// MAIN_ADMIN
       	                       $sql5 ="delete from incentive.MAIN_ADMIN where PROFILEID='$profileid'";
       	                       mysql_query($sql5) or logError($sql5);
			}
		}
	}			
}

?>
