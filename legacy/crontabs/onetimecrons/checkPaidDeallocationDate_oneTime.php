<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
connect_db();
$dbyday='2013-01-01 00:00:00';

$sql_crm ="select m.PROFILEID from incentive.MAIN_ADMIN m,billing.PURCHASES p where p.ENTRY_DT >='$dbyday' AND p.STATUS='DONE' AND p.PROFILEID=m.PROFILEID";
$res_crm = mysql_query($sql_crm) or logError($sql_crm);
while($row_crm = mysql_fetch_array($res_crm))
{

        $profileid =$row_crm['PROFILEID'];
        $sql1 ="SELECT EXPIRY_DT from billing.SERVICE_STATUS where PROFILEID='$profileid' AND SERVEFOR LIKE '%F%' order by ID DESC LIMIT 1";
        $res1 = mysql_query($sql1) or logError($sql1);
        if($row1 = mysql_fetch_array($res1))
	{
        	$exp_dt =$row1['EXPIRY_DT']; 		       
	
		$sql2="SELECT ID,DE_ALLOCATION_DT from incentive.CRM_DAILY_ALLOT WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
		$res2=mysql_query($sql2) or logError($sql2);
		if($row2=mysql_fetch_array($res2)){
			$id 		=$row2['ID'];
			$oldDeAllocDate =$row2['DE_ALLOCATION_DT'];	
		
			if(strtotime($oldDeAllocDate)<strtotime($exp_dt))
			{
				$sql3 ="update incentive.CRM_DAILY_ALLOT set DE_ALLOCATION_DT='$exp_dt' where ID='$id'";
				echo $sql3."\n";
				mysql_query($sql3) or logError($sql3);
			}
		}
	}
}
$mailmsg="Set DeAllocation Date Onetime";
mail("manoj.rana@naukri.com","Set De-Allocation date",$mailmsg);
?>
