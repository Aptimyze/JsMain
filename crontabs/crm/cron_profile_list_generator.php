<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");
chdir(dirname(__FILE__));
include("../connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");

// today's date
$today_dt	=date('Y-m-d',time()-86400);
$db2 		=connect_slave();
$db		=connect_db();

$sql_pid = "SELECT  PROFILEID, ENTRY_DT, CITY_RES, SOURCE, MTONGUE FROM newjs.JPROFILE WHERE ENTRY_DT BETWEEN '$today_dt 00:00:00' AND '$today_dt 23:59:59'";
$res_pid = mysql_query($sql_pid,$db2) or logError($sql_pid,$db2);
while($row_pid = mysql_fetch_array($res_pid))
{
	$pid=$row_pid['PROFILEID'];
	$source=$row_pid['SOURCE'];
	$entry_dt=$row_pid["ENTRY_DT"];
	$mtongue = $row_pid["MTONGUE"];
	$city_res=$row_pid['CITY_RES'];

	$sql_rec_exists = "SELECT COUNT(*) as CNT FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID ='$pid'";
	$res_rec_exists = mysql_query($sql_rec_exists,$db2) or logError($sql_rec_exists,$db2);
	if ($row_rec_exists = mysql_fetch_array($res_rec_exists))
	{
		$entry_dtArr 	=@explode(" ",$entry_dt);
		$entry_dt 	=$entry_dtArr[0];
		if ($row_rec_exists['CNT'] > 0)
		{
			$sql_update_pool = "UPDATE incentive.MAIN_ADMIN_POOL SET CITY_RES='$city_res',MTONGUE='$mtongue',ENTRY_DT='$entry_dt' WHERE PROFILEID ='$pid'";
			mysql_query($sql_update_pool,$db) or logError($sql_update_pool,$db);

		}
		else
		{
			$sql_insert = "INSERT INTO incentive.MAIN_ADMIN_POOL (PROFILEID,ALLOTMENT_AVAIL,TIMES_TRIED,SOURCE,ENTRY_DT,CITY_RES,MTONGUE) VALUES ('$pid','Y','0','".addslashes($source)."','$entry_dt','$city_res','$mtongue')";
			mysql_query($sql_insert,$db) or logError($sql_insert,$db);
			$countArr[] =$pid;
		}
	}
}

// Mail 
$totProfiles =count($countArr);
if($totProfiles<1000){
	$to             ="rohan.mathur@jeevansathi.com,manoj.rana@naukri.com";
	$latest_date    =date("Y-m-d");
	$subject        ="Total Fresh Profiles added for Date: ".date("jS F Y", strtotime($today_dt));
	$fromEmail      ="From:JeevansathiCrm@jeevansathi.com";
	$msg            ="Total fresh profiles added: $totProfiles";
	mail($to,$subject,$msg,$fromEmail);
}

?>
