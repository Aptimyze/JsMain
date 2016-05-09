<?php
//SEARCH_POINTS this scirpt calculates SEARCH_POINTS for the table SEARCH_MALE and SEARCH_FEMALE
ini_set("max_execution_time","0");
include("connect.inc");
connect_db();
                                                                                                        
for($i=0;$i<=1;$i++)
{
	if($i==0)
		$table="SEARCH_MALE";
	else
		$table="SEARCH_FEMALE";
	$sql="SELECT PROFILEID,ENTRY_DT,LAST_LOGIN_DT,HAVEPHOTO FROM ".$table;
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql);
	$today=date("Y-m-d");
	while($myrow=mysql_fetch_array($result))
	{	$search_points=0;
		$sql_j="SELECT SUBSCRIPTION FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$myrow[PROFILEID]'";
		$result_j=mysql_query_decide($sql_j) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_j);
		$myrow_j=mysql_fetch_array($result_j);
		if(strstr($myrow_j['SUBSCRIPTION'],'D') || strstr($myrow_j['SUBSCRIPTION'],'E') ||  strstr($myrow_j['SUBSCRIPTION'],'F'))
			$search_points=1000;
		if($myrow['HAVEPHOTO']=='Y')
			$search_points+=500;
		$diff=DayDiff($myrow['LAST_LOGIN_DT'],$today);
		if($diff<=7)
			$search_points+=2000;
		elseif($diff>=400)
			$diff=400;
		$search_points+=(400-$diff);
		$sql2="UPDATE ".$table." SET SEARCH_POINTS='$search_points' where PROFILEID='$myrow[PROFILEID]'";
		$result2=mysql_query_decide($sql2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql2);
		//echo " sub= ".$myrow['SUBSCRIPTION']."  havephoto= ".$myrow['HAVEPHOTO']."  DIFF= ".$diff."  points =".$search_points."<br>";
	}
}		
function DayDiff($StartDate, $StopDate)
{
   // converting the dates to epoch and dividing the difference
   // to the approriate days using 86400 seconds for a day
   //
   return (date('U', JSstrToTime($StopDate)) - date('U', JSstrToTime($StartDate))) / 86400; //seconds a day
}
	
?>
