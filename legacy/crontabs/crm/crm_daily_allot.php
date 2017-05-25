<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include("../connect.inc");

$db = connect_db();

$ts=time();
$today = date("Y-m-d",$ts);
$ts-=24*60*60;
$yday = date("Y-m-d",$ts);
$start_time = $yday." 14:15:00";
$end_time =  $today." 14:14:59";

//$start_time = "2006-05-12 14:15:00";
//$end_time = "2006-05-13 14:14:59";

/*//$sql = "SELECT USERNAME FROM jsadmin.PSWRDS WHERE UPPER(CENTER)='NOIDA' AND PRIVILAGE LIKE '%IUO%'";
$sql = "SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%IUO%'";
$res = mysql_query($sql) or logError($sql);
while($row = mysql_fetch_array($res))
{
        $userarr[] = $row['USERNAME'];
}
*/                                                                                                                            
//if($userarr)
//{
//        $allotedto = implode("','", $userarr);

	//$sql_ins = "INSERT INTO incentive.CRM_DAILY_ALLOT (PROFILEID,ALLOTED_TO,ALLOT_TIME) SELECT PROFILEID , ALLOTED_TO , ALLOT_TIME FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO IN ('$allotedto') AND ALLOT_TIME BETWEEN '$start_time' AND '$end_time'";
	$sql_ins = "INSERT INTO incentive.CRM_DAILY_ALLOT (PROFILEID,ALLOTED_TO,ALLOT_TIME) SELECT PROFILEID , ALLOTED_TO , ALLOT_TIME FROM incentive.MAIN_ADMIN WHERE ALLOT_TIME BETWEEN '$start_time' AND '$end_time'";
	mysql_query($sql_ins) or  logError($sql_ins);

	$str="came in insert query loop - ideally shud be here daily\nmysql insert id : ".mysql_insert_id();
//}

	$sql="UPDATE incentive.SUBSCRIPTION_EXPIRY_PROFILES a , incentive.CRM_DAILY_ALLOT set b.RELAX_DAYS = b.RELAX_DAYS+'30' where a.PROFILEID=b.PROFILEID and a.HANDLE_DT=CURDATE() and b.ALLOT_TIME BETWEEN '$start_time' AND '$end_time'";
	mysql_query($sql) or logError($sql);

mysql_close($db);

mail("shiv.narayan@jeevansathi.com","crm daily allot",$str);
?>
