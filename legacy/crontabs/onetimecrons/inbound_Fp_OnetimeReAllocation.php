<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
connect_db();

$processName 	='HOTFIX_4841';
$deAllocatedBy	='SYSTEM';
$agentStr	="'richa.k','sonali.gharat','sheetal.s','nutan.panigrahy','pranjal.raut'";

$sql ="SELECT ID,PROFILEID,ALLOTED_TO from incentive.DEALLOCATION_TRACK where ALLOTED_TO IN($agentStr) AND PROCESS_NAME='$processName' AND DEALLOCATED_BY='$deAllocatedBy' AND DEALLOCATION_DT>='2014-09-19 00:00:00' AND DEALLOCATION_DT<='2014-09-19 23:59:59'";
$res = mysql_query($sql) or logError($sql);
while($row = mysql_fetch_array($res))
{
        $deAllocId      =$row['ID'];
        $profileid      =$row['PROFILEID'];
        $allotedTo      =$row['ALLOTED_TO'];

	$sqlMain 	="select PROFILEID from incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
        $resMain 	=mysql_query($sqlMain) or logError($sqlMain);
        $rowMain	=mysql_fetch_array($resMain);
	$pid            =$rowMain['PROFILEID'];
	if($pid)
		continue;
	
        $sqlTrk ="select * from incentive.CRM_DAILY_ALLOT where PROFILEID='$profileid' AND ALLOTED_TO='$allotedTo' ORDER BY ID DESC LIMIT 1";
        $resTrk=mysql_query($sqlTrk) or logError($sqlTrk);
        if($rowTrk=mysql_fetch_array($resTrk))
	{

                $trackId   =$rowTrk['ID'];
                $profileid =$rowTrk['PROFILEID'];
                $allotedTo =$rowTrk['ALLOTED_TO'];
                $allotTime =$rowTrk['ALLOT_TIME'];

                $sql2 ="insert ignore into incentive.MAIN_ADMIN (PROFILEID,ALLOT_TIME,CLAIM_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,FOLLOWUP_TIME,MODE,CONVINCE_TIME,COMMENTS,RES_NO,MOB_NO,EMAIL,WILL_PAY,TIMES_TRIED,ORDERS,REASON) SELECT PROFILEID,ALLOT_TIME,CLAIM_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,FOLLOWUP_TIME,MODE,CONVINCE_TIME,COMMENTS,RES_NO,MOB_NO,EMAIL,WILL_PAY,TIMES_TRIED,ORDERS,REASON FROM incentive.MAIN_ADMIN_LOG WHERE PROFILEID='$profileid' AND ALLOTED_TO='$allotedTo' and ALLOT_TIME='$allotTime' ORDER BY ID DESC limit 1";
                $res2 =mysql_query($sql2) or logError($sql2);
                if($res2){

                        $sql3 ="update incentive.CRM_DAILY_ALLOT SET REAL_DE_ALLOCATION_DT='0000-00-00 00:00:00' where ID='$trackId'";
                        mysql_query($sql3) or logError($sql3);

                        $sql4 ="update incentive.MAIN_ADMIN_POOL set ALLOTMENT_AVAIL='N' where PROFILEID='$profileid'";
                        mysql_query($sql4) or logError($sql4);

                        $sql5 ="DELETE from incentive.DEALLOCATION_TRACK where ID='$deAllocId'";
                        mysql_query($sql5) or logError($sql5);
	
			$sql6 ="DELETE FROM incentive.MAIN_ADMIN_LOG WHERE PROFILEID='$profileid' AND ALLOTED_TO='$allotedTo' and ALLOT_TIME='$allotTime' ORDER BY ID DESC limit 1";
			mysql_query($sql6) or logError($sql6);
		}
	}			
}

?>
