<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
connect_db();

$sql ="select * from incentive.DEALLOCATION_TRACK WHERE ALLOTED_TO!='' ORDER BY ID ASC";
$res = mysql_query($sql) or logError($sql);
while($row =mysql_fetch_array($res))
{
        $profileid 		=$row['PROFILEID'];
	$allotedTo 		=$row['ALLOTED_TO'];
	$source                 =$row['PROCESS_NAME'];	
	$deAllocationTime 	=$row['DEALLOCATION_DT'];

	$deAllocationTimeArr 	=@explode(" ",$deAllocationTime);;
	$deAllocationDt		=$deAllocationTimeArr[0];

	if($source=='RELEASE_PROFILE' || $source=='NO_LONGER_WORKING')
	{
		$sql1 ="UPDATE incentive.CRM_DAILY_ALLOT_TRACK SET REAL_DE_ALLOCATION_DT='$deAllocationTime' where PROFILEID='$profileid' AND ALLOTED_TO='$allotedTo' AND ALLOT_TIME<='$deAllocationTime' AND DE_ALLOCATION_DT>='$deAllocationDt' AND REAL_DE_ALLOCATION_DT='0000-00-00 00:00:00' ORDER BY ID ASC LIMIT 1";
	}
	else
	{	
	        $sql1 ="UPDATE incentive.CRM_DAILY_ALLOT SET REAL_DE_ALLOCATION_DT='$deAllocationTime' where PROFILEID='$profileid' AND ALLOTED_TO='$allotedTo' AND ALLOT_TIME<='$deAllocationTime' AND DE_ALLOCATION_DT>='$deAllocationDt' AND REAL_DE_ALLOCATION_DT='0000-00-00 00:00:00' ORDER BY ID ASC LIMIT 1";
	}
        mysql_query($sql1) or logError($sql1);

}
?>
