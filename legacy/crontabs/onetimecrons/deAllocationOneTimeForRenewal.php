<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
connect_db();

$dateSet 	='2016-02-11';
$todayDate 	=date("Y-m-d H:i:s");
$processName 	='HOTFIX_1001';
$deAllocatedBy	='SYSTEM';
$pidDialerStr	='';
$pidDialer	=array();

$agentArr =array('arti.panwar','ashok.kumar','nayanika.jetly','neeraj.singh','neha.kushwah','neha.pandey','nikhil.mishra','rajul.sharma','ravi.shekhar','satyam.tomar','swati.s','swati.srivastava','vigya.mittal','shikha.mishra','amrita.kumari','anmol.kashyap','chetendra','chinki.garg','ila.batham','jyoti.chauhan','neha.kumari','priya.b','supriya.agrawal','urvashi.s');

$sqlDialer ="select PROFILEID from incentive.RENEWAL_IN_DIALER";
$resDialer =mysql_query($sqlDialer) or logError($sqlDialer);
while($rowDialer =mysql_fetch_array($resDialer))
	$pidDialer[]  =$rowDialer['PROFILEID'];

$pidDialerStr =implode(",",$pidDialer);

foreach($agentArr as $key=>$agentName){

	$agentName 	=trim($agentName);
	$sqlMain 	="select PROFILEID,ALLOTED_TO,ALLOT_TIME from incentive.MAIN_ADMIN WHERE ALLOTED_TO='$agentName' and PROFILEID NOT IN($pidDialerStr)";
        $resMain 	=mysql_query($sqlMain) or logError($sqlMain);
        while($rowMain	=mysql_fetch_array($resMain))
	{
		$profileid	=$rowMain['PROFILEID'];
	        $allotedTo      =$rowMain['ALLOTED_TO'];
	        $allotTime      =$rowMain['ALLOT_TIME'];
	
		$sqlP ="SELECT PROFILEID,MAX(EXPIRY_DT) EXPIRY_DT FROM billing.SERVICE_STATUS WHERE (SERVEFOR LIKE '%F%' OR SERVEFOR='X') AND ACTIVE IN('Y') AND PROFILEID='$profileid' HAVING EXPIRY_DT>='$dateSet'";
		$resP=mysql_query($sqlP) or logError($sqlP);
		$rowP=mysql_fetch_array($resP);
		$pid =$rowP['PROFILEID'];
		$expDate =$rowP['EXPIRY_DT'];
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
			       	echo $profileid."\n";
			}
		}
	}			
}

?>
