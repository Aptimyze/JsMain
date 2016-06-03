<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	chdir(dirname(__FILE__));
	include("../connect.inc");
	//include_once("/usr/local/scripts/connect_db.php");	//for testing

	$db_master = connect_db();
	$db = connect_slave();
	$sqlj="SELECT USER FROM jsadmin.RENEWAL_AGENT";
        $resj=mysql_query($sqlj,$db) or die(mysql_error($db));
        while($rowj = mysql_fetch_array($resj))
                $allot_to_array[] = $rowj['USER'];
	for($i=0;$i<count($allot_to_array);$i++)
	{
		$sql_pid = "SELECT PROFILEID,ALLOT_TIME,ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO='$allot_to_array[$i]'";
		$res_pid = mysql_query($sql_pid,$db) or die("$sql_pid".mysql_error($db));
		while($row_pid = mysql_fetch_array($res_pid))
		{
			$profileid=$row_pid['PROFILEID'];
			$allot_time=$row_pid['ALLOT_TIME'];
			$allot_to=$row_pid['ALLOTED_TO'];
			$ok=0;
		        $ts = strtotime($allot_time);
        		$check_dt = date("Y-m-d",$ts+10*86400);
        		$sqlc = "SELECT EXPIRY_DT FROM billing.SERVICE_STATUS WHERE PROFILEID='$profileid' AND ACTIVE IN ('Y','E')";
        		$resc = mysql_query_decide($sqlc,$db) or die($sqlc.mysql_error($db));
		        while($rowc = mysql_fetch_array($resc))
        		{
		                $exp_dt = $rowc['EXPIRY_DT'];
                		if("$exp_dt"=="$check_dt")
			                $ok=1;
        		}
			echo $ok;
			if($ok)
			{
				$sql2="UPDATE incentive.CRM_DAILY_ALLOT SET RELAX_DAYS=RELAX_DAYS+20 where PROFILEID='$profileid' AND ALLOTED_TO='$allot_to' ORDER BY ALLOT_TIME DESC LIMIT 1";
				mysql_query($sql2,$db_master) or die("$sql2".mysql_error($db_master));
			}
			/*else
			{
				echo $profileid."*"."$exp_dt"."*"."$check_dt";die;
			}*/
		}
        }
?>
