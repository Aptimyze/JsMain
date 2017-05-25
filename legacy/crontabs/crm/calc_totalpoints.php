<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

ini_set("max_execution_time","0");
ini_set("memory_limit","32M");

include("../connect.inc");
include_once("comfunc.inc");

$start_time=date("Y-m-d H:i:s");

$icnt=0;
$ucnt=0;

$db2 = connect_737();
//$db2 = connect_737_lan();

$sql_login = "SELECT ENTRY_DT , PROFILEID , SCORE FROM incentive.MAIN_ADMIN_POOL";

$res_login = mysql_query($sql_login,$db2) or die("$sql_login".mysql_error());

mysql_close($db2);
$db = connect_db();

while($row_login = mysql_fetch_array($res_login))
{
	//@mysql_ping($db2);
	$pid =  $row_login['PROFILEID'];
	$entry_dt=$row_login["ENTRY_DT"];
	$score = $row_login['SCORE'];

	if($score<=150)
		$newscore=-50;
	elseif($score<326)
		$newscore=150;
	else
		$newscore=300;

	$today=date("Y-m-d");
	$diff=DayDiff($entry_dt,$today);
	$freshness_points=0;
	if($diff<16)
		$freshness_points=300;
	elseif($diff>15 && $diff<46)
		$freshness_points=150;
	else
		$freshness_points=100;
	$total_points=$newscore+$freshness_points;

	$sql_update_pool = "UPDATE incentive.MAIN_ADMIN_POOL SET TOTAL_POINTS='$total_points' WHERE PROFILEID ='$pid'";
	mysql_query($sql_update_pool,$db) or logError($sql_update_pool,$db);
}

mail("shiv.narayan@jeevansathi.com","one time total points","Updated\nStart time : $start_time\nEnd Time : $end_time");

function DayDiff($StartDate, $StopDate)
{
   // converting the dates to epoch and dividing the difference
   // to the approriate days using 86400 seconds for a day
   //
   return (date('U', strtotime($StopDate)) - date('U', strtotime($StartDate))) / 86400; //seconds a day
}
?>
