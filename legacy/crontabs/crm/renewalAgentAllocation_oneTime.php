<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
connect_db();

$startDt ='2013-09-01 00:00:00';
$endDt ='2013-09-30 23:59:59';

$sqlP ="select distinct USERNAME from jsadmin.PSWRDS where PRIVILAGE like '%ExcRnw%' AND ACTIVE='Y'";
$resP = mysql_query($sqlP) or logError($sqlP);
while($rowP = mysql_fetch_array($resP))
	$agentName[] =$rowP['USERNAME'];
$renewalAgentsStr ="'".implode("','",$agentName)."'";	

// Renewal Agent Allocation
$sql_crm ="select distinct PROFILEID,ALLOTED_TO from incentive.MANUAL_ALLOT where ENTRY_DT>='$startDt' AND ENTRY_DT<='$endDt' AND ALLOTED_TO IN($renewalAgentsStr)";
$res_crm = mysql_query($sql_crm) or logError($sql_crm);
while($row_crm = mysql_fetch_array($res_crm))
{
        $profileid =$row_crm['PROFILEID'];
	$allotedTo =$row_crm['ALLOTED_TO'];	
	
	$sql ="select PROFILEID from billing.PURCHASES where PROFILEID='$profileid' AND ENTRY_DT>='$startDt' AND STATUS='DONE' AND MEMBERSHIP='Y' ORDER BY ENTRY_DT DESC limit 1";
	$res = mysql_query($sql) or logError($sql);
	if($row = mysql_fetch_array($res))
	{
        	$sql1 ="SELECT PROFILEID from incentive.MAIN_ADMIN where PROFILEID='$profileid'";
        	$res1 = mysql_query($sql1) or logError($sql1);
        	$rowsCnt = mysql_num_rows($res1);
		if($rowsCnt<1)
		{		
			$sqlLog ="SELECT * from incentive.MAIN_ADMIN_LOG where PROFILEID='$profileid' AND ALLOTED_TO='$allotedTo' AND ALLOT_TIME>='$startDt' AND ALLOT_TIME<='$endDt' ORDER BY ID DESC limit 1";
			$resLog = mysql_query($sqlLog) or logError($sqlLog);
			if($rowLog = mysql_fetch_array($resLog))
			{
				$sql2="insert ignore into incentive.MAIN_ADMIN(`PROFILEID`,`ALLOT_TIME`,`CLAIM_TIME`,`ALLOTED_TO`,`STATUS`,`ALTERNATE_NO`,`FOLLOWUP_TIME`,`MODE`,`CONVINCE_TIME`,`COMMENTS`,`RES_NO`,`MOB_NO`,`EMAIL`,`WILL_PAY`,`TIMES_TRIED`,`ORDERS`,`REASON`) VALUES('$profileid','$rowLog[ALLOT_TIME]','$rowLog[CLAIM_TIME]','$rowLog[ALLOTED_TO]','C','$rowLog[ALTERNATE_NO]','','$rowLog[MODE]','$rowLog[CONVINCE_TIME]','$rowLog[COMMENTS]','$rowLog[RES_NO]','$rowLog[MOB_NO]','$rowLog[EMAIL]','$rowLog[WILL_PAY]','$rowLog[TIMES_TRIED]','$rowLog[ORDERS]','$rowLog[REASON]')";
				$res2=mysql_query($sql2) or logError($sql2);
				echo "\n".$sql2;		
			}
		}
	}
}
?>
