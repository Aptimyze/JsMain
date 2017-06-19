<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");
include("../connect.inc");
$db2=connect_db();

		$sql ="SELECT PROFILEID,FOLLOWUP_TIME from incentive.MAIN_ADMIN WHERE STATUS='F' AND ALLOT_TIME BETWEEN '2012-12-01 00:00:00' AND '2012-12-31 23:59:59' AND FOLLOWUP_TIME BETWEEN '2012-01-01 00:00:00' AND '2012-01-31 23:59:59'";
		$res =mysql_query($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$profileid   	=$row['PROFILEID'];
			$follow_time 	=$row['FOLLOWUP_TIME'];
			$timeArr        =explode(" ",$follow_time);

			$newTime 	=date("Y-m-d", strtotime("$timeArr[0] +1 year"));
			$newTime1 	=$newTime." $timeArr[1]";

			if($profileid){
				$sql1="update incentive.MAIN_ADMIN set FOLLOWUP_TIME='$newTime1' WHERE PROFILEID='$profileid'";
				mysql_query($sql1) or die("$sql1".mysql_error_js());
			}
			
			echo $profileid."-".$follow_time.",";	

		}		

?>
