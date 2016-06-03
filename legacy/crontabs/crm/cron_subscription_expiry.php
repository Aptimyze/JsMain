<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
ini_set("max_execution_time","0");
include "$docRoot/crontabs/connect.inc";

$ts = time();
$start_time = date("Y-m-d H:i:s");
$date_before10days = date('Y-m-d',time()-10*86400); 
$ts += 24*60*60;
$start_date = date("Y-m-d",$ts);
$ts1 = $ts + 24*60*60*30;
$end_date = date("Y-m-d",$ts1);

$msg="";

$db=connect_db();

//select profile ids which expire.
//$sql = "SELECT PROFILEID, EXPIRY_DT FROM billing.SERVICE_STATUS WHERE PROFILEID IN ( SELECT DISTINCT(PROFILEID) FROM billing.SERVICE_STATUS WHERE EXPIRY_DT BETWEEN '$start_date' AND '$end_date' ) AND PROFILEID NOT IN (SELECT DISTINCT(PROFILEID) FROM billing.SERVICE_STATUS WHERE EXPIRY_DT > '$end_date')";

$sql ="SELECT PROFILEID,MAX(EXPIRY_DT) AS EXPIRY_DT FROM billing.SERVICE_STATUS WHERE EXPIRY_DT >='$start_date' AND SERVEFOR LIKE '%F%' GROUP BY PROFILEID"; 
if($res = mysql_query($sql))
{
	while($row = mysql_fetch_array($res))
	{
		$expirytDate		=$row['EXPIRY_DT'];
		if($expirytDate<=$end_date){
			$profile_arr[]		= $row['PROFILEID'];
			$expiry_dt_arr[]	= $row['EXPIRY_DT'];
		}
	}
}
else
{
	$msg .= "\n$sql \nError :".mysql_error();
}
if($profile_arr)
{
	unset($db);
        $db = connect_slave();
	$str1	= implode("','",$profile_arr);
	$sql	= "SELECT PROFILEID,USERNAME FROM newjs.JPROFILE WHERE PROFILEID IN ('$str1') AND SUBSCRIPTION LIKE '%F%'";
	if($res = mysql_query($sql))
	{
		while($row = mysql_fetch_array($res))
		{
			//if(!in_array($row['PROFILEID'],$arr_airex))  //exclude if Profile is activated by airex
				$proid[]	= $row['PROFILEID'];
		}
	}
	else
	{
		$msg .= "\n$sql \nError :".mysql_error();
	}
	unset($db);
        $db = connect_db();
	if($proid)
	{
		$sql_del 	= "DELETE FROM incentive.SUBSCRIPTION_EXPIRY_PROFILES WHERE EXPIRY_DT < '$date_before10days'";
		mysql_query($sql_del) or  $msg .= "\n$sql_del \nError :".mysql_error();

		$proid_str=implode("','",$proid);

		$sql1 	= "SELECT ALLOTED_TO , PROFILEID FROM incentive.MAIN_ADMIN where PROFILEID in ('$proid_str')";
		$res1 	= mysql_query($sql1) or $msg .= "\n$sql1 \nError :".mysql_error();

		while ($row1 = mysql_fetch_array($res1))
		{

			$profileid[]	= $row1['PROFILEID'];
			$ind		= array_search($row1['PROFILEID'],$profile_arr);
			$expiry_dt	= $expiry_dt_arr[$ind];

			$sql_dt  = "SELECT EXPIRY_DT FROM incentive.SUBSCRIPTION_EXPIRY_PROFILES WHERE PROFILEID='$row1[PROFILEID]'";
			$res_dt   = mysql_query($sql_dt) or $msg .= "\n$sql_dt \nError :".mysql_error();
			if($row_dt = mysql_fetch_array($res_dt))
			{
				if ($expiry_dt > $row_dt['EXPIRY_DT'])	
				{
					$sql2 = "REPLACE INTO incentive.SUBSCRIPTION_EXPIRY_PROFILES (PROFILEID,ALLOTED_TO,EXPIRY_DT) VALUES ('$row1[PROFILEID]','$row1[ALLOTED_TO]','$expiry_dt')";
					mysql_query($sql2) or $msg .= "\n$sql2 \nError :".mysql_error();
				}
			}
			else
			{
				$sql2 = "REPLACE INTO incentive.SUBSCRIPTION_EXPIRY_PROFILES (PROFILEID,ALLOTED_TO,EXPIRY_DT) VALUES ('$row1[PROFILEID]','$row1[ALLOTED_TO]','$expiry_dt')";
				mysql_query($sql2) or $msg .= "\n$sql2 \nError :".mysql_error();
			}
		}
	}
	unset($proid);
}
unset($profile_arr);
unset($expiry_dt_arr);

if ($profileid)
	$profile_str=implode(",",$profileid);

$end_time=date("Y-m-d H:i:s");

$msg.="\n List of Profile Ids whose subscription expires in next 30 days\n\n".$profile_str;
$msg.="\n Start time : $start_time";
$msg.="\n End time : $end_time";
mail("manoj.rana@naukri.com,vibhor.garg@jeevansathi.com","Subscription expiring records in 30 days, deleted from CRM","$msg");
?>
