<?php 
ini_set('max_execution_time','0');
include("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");

connect_db();

$today=date("Y-m-d");

$sql="SELECT PROFILEID ,ENTRY_DT FROM SEARCH_MALE";
$result=mysql_query_decide($sql) or die("calculate_score.php 1 ".mysql_error1());
calculate_score($result,'SEARCH_MALE');


$sql="SELECT PROFILEID ,ENTRY_DT FROM SEARCH_FEMALE";
$result=mysql_query_decide($sql) or die("calculate_score.php 2 ".mysql_error1());
calculate_score($result,'SEARCH_FEMALE'); 

@//mysql_close();
connect_737();


$sql="SELECT PROFILEID FROM SEARCH_MALE_FULL1";
$result=mysql_query_decide($sql) or die("calculate_score.php 1.1 ".mysql_error1());
calculate_score($result,'SEARCH_MALE_FULL1');

$sql="SELECT PROFILEID FROM SEARCH_FEMALE_FULL1";
$result=mysql_query_decide($sql) or die("calculate_score.php 2.1 ".mysql_error1());
calculate_score($result,'SEARCH_FEMALE_FULL1');

function calculate_score($result,$tablename)
{
	global $today;
		
	while($myrow=mysql_fetch_array($result))
	{
		$pid=$myrow['PROFILEID'];
	  
		$sql_pid = "SELECT SCORE FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID ='$pid'";
		$res_pid = mysql_query_decide($sql_pid) or die("calculate_score.php 3 ".mysql_error1());
																	     
		if ($row_pid = mysql_fetch_array($res_pid))
		{
			$entry_dt=$myrow["ENTRY_DT"];
			$score = $row_pid['SCORE'];
			
			$score_points=0;
			if($score<=150)
				$score_points=-50;
			elseif($score>150 && $score<326)
				$score_points=150;
			else
				$score_points=300;
			
			$diff=DayDiff($entry_dt,$today);
			
			$freshness_points=0;
			if($diff<16)
				$freshness_points=300;
			elseif($diff>15 && $diff<46)
				$freshness_points=150;
			else
				$freshness_points=100;
			
			$total_points=$score_points+$freshness_points;
			$sql_update="UPDATE $tablename SET SCORE_POINTS='$score_points',FRESHNESS_POINTS='$freshness_points' ,TOTAL_POINTS='$total_points' where PROFILEID='$pid'";
			$result_update=mysql_query_decide($sql_update) or die("calculate_score.php 8 ".mysql_error1());
		}
	}
}

function DayDiff($StartDate, $StopDate)
{
   // converting the dates to epoch and dividing the difference
   // to the approriate days using 86400 seconds for a day
   //
   return (date('U', JSstrToTime($StopDate)) - date('U', JSstrToTime($StartDate))) / 86400; //seconds a day
}

function mysql_error1()
{
	//mail("vikas@jeevansathi.com,puneet.makkar@jeevansathi.com","Jeevansathi Error in adding total_points in search_male,search_female",mysql_error_js());
	return;
}
?>
