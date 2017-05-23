<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
$db =connect_db();

$sql ="SELECT distinct PROFILEID FROM incentive.DEALLOCATION_TRACK WHERE DATE(DEALLOCATION_DT)='2014-08-21' AND PROCESS_NAME IN('SALES_OTHERS','UPSELL')";
$res_2 = mysql_query($sql,$db) or die(mysql_error($sql,$db));
while($row =mysql_fetch_array($res_2))
{
	$profileid =$row['PROFILEID'];

	$sql1 ="select * from incentive.MAIN_ADMIN_LOG where PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
	$res1 = mysql_query($sql1) or logError($sql1);
	if($row1 = mysql_fetch_array($res1))
	{

		$id 		=$row1['ID'];
		$profileid 	=$row1['PROFILEID'];
		$allotTime 	=$row1['ALLOT_TIME'];
		$claim 		=$row1['CLAIM_TIME'];
		$allotedTo 	=$row1['ALLOTED_TO'];
		$status 	=$row1['STATUS'];
		$alternate 	=$row1['ALTERNATE_NO'];
		$followupTime 	=$row1['FOLLOWUP_TIME'];
		$mode		=$row1['MODE'];			
		$convince 	=$row1['CONVINCE_TIME'];
		$comments 	=$row1['COMMENTS'];
		$res 		=$row1['RES_NO'];
		$mob 		=$row1['MOB_NO'];
		$email 		=$row1['EMAIL'];
		$willPay 	=$row1['WILL_PAY'];
		$tries 		=$row1['TIMES_TRIED'];	
		$order		=$row1['ORDERS'];
		$reason 	=$row1['REASON'];	

		$sql_c ="select DE_ALLOCATION_DT from incentive.CRM_DAILY_ALLOT where PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
		$res_c =mysql_query($sql_c) or logError($sql_c);
		if($row_c = mysql_fetch_array($res_c))	
		{
			$deAllDt =$row_c['DE_ALLOCATION_DT'];
			if($deAllDt=='2014-08-21'){
				$sql3 ="INSERT IGNORE INTO incentive.MAIN_ADMIN (PROFILEID,ALLOT_TIME,CLAIM_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,FOLLOWUP_TIME,MODE,CONVINCE_TIME,COMMENTS,RES_NO,MOB_NO,EMAIL,WILL_PAY,TIMES_TRIED,ORDERS,REASON) VALUES('$profileid','$allotTime','$claim','$allotedTo','$status','$alternate','$followupTime','$mode','$convince','$comments','$res','$mob','$email','$willPay','$tries','$order','$reason')";
				mysql_query($sql3) or logError($sql3);
	
				$sql4 ="DELETE FROM incentive.MAIN_ADMIN_LOG WHERE ID='$id'";
				mysql_query($sql4) or logError($sql4);
				
				echo $profileid."\n";
			}
		}
	}
}
?>
