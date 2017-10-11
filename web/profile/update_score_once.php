<?php

/***********************************************************************************************************************
* FILE NAME     : live_profile_count.php
* DESCRIPTION   : Update score in 4 tables based on new freshness value.(ONE TIME SCRIPT)
* INCLUDES      : connect.inc,comfunc1.inc.
* CREATION DATE : 13 July 2006
* CREATED BY    : Lavesh Rawat
************************************************************************************************************************/

ini_set("max_execution_time","0");
                                                                                                                             
//Comment needs to be change
                                                                                                                             
include_once("connect.inc");
                                                                                                                             
$db=connect_db();
$db2 = connect_737_lan();

$today=date("Y-m-d");
$ts = time();
$ts-=31*24*60*60;
$start_dt=date("Y-m-d",$ts);


call_update_all_records();

function call_update_all_records()
{
        update_all_records("SEARCH_FEMALE");
        update_all_records("SEARCH_MALE");
        update_all_records("SEARCH_FEMALE_FULL1");
        update_all_records("SEARCH_MALE_FULL1");
}

function update_all_records($table)
{
	global $start_dt,$db,$db2;
                                                                                                                             
        if($table=="SEARCH_FEMALE" || $table=="SEARCH_MALE")
                $db_flag=0;
        else
                $db_flag=1;
                                                                                                                             
        if($db_flag==1)
                @mysql_ping_js($db2);
        else
                @mysql_ping_js($db);


	$sql="SELECT PROFILEID,SCORE_POINTS,TOTAL_POINTS FROM newjs.$table ";
	
	if($db_flag==1)
                $res=mysql_query_decide($sql,$db2) or logError($sql,$db2);
        else
                $res=mysql_query_decide($sql,$db) or logError($sql,$db);
                                                                                                                             
        while($row=mysql_fetch_array($res))
        {
                $profileid=$row['PROFILEID'];

		$score_points=$row['SCORE_POINTS'];

		if($db_flag==0)
                        @mysql_ping_js($db2);

		$sql1="SELECT HAVEPHOTO,PHOTODATE,ENTRY_DT FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
		$res1=mysql_query_decide($sql1,$db2) or logError($sql1,$db2);
                $row1=mysql_fetch_array($res1);
		
		$today=date("Y-m-d");
                                                                                                                             
		$entry_dt=$row1["ENTRY_DT"];
		$photo_dt=$row1["PHOTODATE"];
														     
		if($row1["HAVEPHOTO"]=='Y')
			$diff=DayDiff($photo_dt,$today);
		else
			$diff=DayDiff($entry_dt,$today);
														     
		$freshness_points=0;
														     
		if($diff<16)
			$freshness_points=300;
		elseif($diff>15 && $diff<46)
			$freshness_points=150;
		else
			$freshness_points=100;

		$total_points=$score_points+$freshness_points;
		
		if($db_flag==0)
			@mysql_ping_js($db);
														     
		$sql1="UPDATE newjs.$table set SCORE_POINTS='$score_points',FRESHNESS_POINTS='$freshness_points' ,TOTAL_POINTS='$total_points' WHERE PROFILEID='$profileid'";
		if($db_flag==1)
			$res1=mysql_query_decide($sql1,$db2) or logError($sql1,$db2);
		else
			$res1=mysql_query_decide($sql1,$db) or logError($sql1,$db);	

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

