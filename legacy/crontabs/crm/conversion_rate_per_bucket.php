<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


// this script calculates conversion rate for each bucket after 20th of every month using previous month.
// to be used in source revenue mis.

ini_set('max_execution_time','0');

include("../connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");

$last_year_month = date("Y-m",mktime(0, 0, 0, date("m"), date("d")-21,   date("Y")));

$db2 = connect_slave();

$sql_login = "SELECT PROFILEID,SCORE,PAID_ALLTIME FROM incentive.MAIN_ADMIN_POOL_INTO_SYSTEM_20_DAYS WHERE ENTRY_DT BETWEEN '$last_year_month-01' AND '$last_year_month-31' ";
$res_login = mysql_query($sql_login,$db2) or logError($sql_login,$db2);

while($row_login = mysql_fetch_array($res_login))
{
	@mysql_ping($db2);
	
	$pid =  $row_login['PROFILEID'];
	
	$score=get_round_score($row_login['SCORE']);

	$total_profiles[$score]++;
	
	if($row_login['PAID_ALLTIME']=='Y')
		$paid_profiles[$score]++;
		
	unset($pid);
	unset($score);
}

print_r($total_profiles);
print_r($paid_profiles);
// conv_rate to be calculated here.


$conv_rate[50]=0.2;
$conv_rate[100]=0.2;
$conv_rate[150]=0.2;
$conv_rate[200]=0.2;
$conv_rate[250]=0.2;
$conv_rate[300]=0.2;
$conv_rate[350]=2.5;
$conv_rate[400]=7.5;
$conv_rate[450]=15;
$conv_rate[500]=25;
$conv_rate[550]=35;
$conv_rate[600]=40;

//echo $sql=" UPDATE incentive.MAIN_ADMIN_POOL_INTO_SYSTEM_20_DAYS SET CONV_RATE = IF(SCORE>564,$conv_rate[600], IF(SCORE>516,$conv_rate[550], IF(SCORE>456,$conv_rate[500], IF(SCORE>420,$conv_rate[450], IF(SCORE>360,$conv_rate[400], IF(SCORE>312,$conv_rate[350], $conv_rate[50] )))))) ";
//mysql_query($sql,$db) or logError($sql,$db);



?>
