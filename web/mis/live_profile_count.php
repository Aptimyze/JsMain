<?php
/***********************************************************************************************************************
* FILE NAME     	: live_profile_count.php 
* DESCRIPTION   	: Create MIS forDisplays No.of user Registered on a particular day and when did they get live.
* INCLUDES      	: connect.inc
* CREATION DATE 	: 23 may 2006
* CREATED BY    	: Lavesh Rawat
* MODIFICATION DATE	: 25 June 2007
* MODIFIED BY		: Sriram Viswanathan
************************************************************************************************************************/
include_once("connect.inc");
include_once("../jsadmin/time1.php");
$db=connect_rep();

if(authenticated($cid) || $JSIndicator==1)
{
	 if($outside)
        {
                $CMDGo='Y';
		if(!$today)					
	                $today=date("Y-m-d");
                list($year,$month,$d)=explode("-",$today);
		$type="new";
        }

	if($CMDGo)
	{
		$no_entry_in_main_admin_log = 0;
		$smarty->assign("flag",1);

		$mmarr = array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');

		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
			$ddarr1[$i]=$i+1;
		}
		$j=1;
		// 5 extra days are provided for taking care of end-month days.
		for($i=31;$i<36;$i++)
		{
			$ddarr1[$i]=$j;	
			$j++;
		}
		$st_date = $year."-".$month."-01 00:00:00";
		$end_date = $year."-".$month."-31 23:59:59";

		if($type == "new")
		{
			//finding count of those profiles which are yet to be screened.
			//$sql_new_unscreened = "SELECT COUNT(*) AS COUNT,DAYOFMONTH(MOD_DT) AS DAY FROM newjs.JPROFILE WHERE ACTIVATED='N' AND INCOMPLETE='N' AND MOD_DT BETWEEN '$st_date' AND '$end_date' and activatedKey=1  and MOD_DT < date_sub(now(), interval 10 minute) GROUP BY DAY";
      $sql_new_unscreened = <<<SQL
        SELECT COUNT(*),DAY FROM (
        SELECT J.PROFILEID,DAYOFMONTH(MOD_DT) AS DAY 
        FROM newjs.JPROFILE J
        LEFT JOIN newjs.JPROFILE_CONTACT C
        ON J.PROFILEID=C.PROFILEID
        WHERE 
        ACTIVATED='N'
        AND INCOMPLETE='N' 
        AND MOD_DT BETWEEN '$st_date' AND '$end_date' 
        AND (J.MOB_STATUS='Y' OR J.LANDL_STATUS='Y' OR C.ALT_MOB_STATUS='Y')
        UNION
        SELECT J.PROFILEID,DAYOFMONTH(MOD_DT) AS DAY 
        FROM newjs.JPROFILE J
        LEFT JOIN newjs.JPROFILE_CONTACT C
        ON J.PROFILEID=C.PROFILEID
        LEFT JOIN jsadmin.ACTIVATED_WITHOUT_YOURINFO A
        ON J.PROFILEID=A.PROFILEID
        WHERE 
        A.PROFILEID IS NOT NULL
        AND INCOMPLETE='N' 
        AND MOD_DT BETWEEN '$st_date' AND '$end_date' 
        AND (J.MOB_STATUS='Y' OR J.LANDL_STATUS='Y' OR C.ALT_MOB_STATUS='Y')
        AND activatedKey=1
        )
        AS TEMP GROUP BY DAY
SQL;
			$res_new_unscreened = mysql_query_decide($sql_new_unscreened) or die("$sql_new_unscreened".mysql_error_js());
			while($row_new_unscreened = mysql_fetch_array($res_new_unscreened))
			{
				$day = $row_new_unscreened['DAY'] - 1;
				$total_to_screen_temp[$day] = $row_new_unscreened['COUNT'];
			}

			//finding count of those profiles which are under screening.
			$sql_under_screening = "SELECT COUNT(*) AS COUNT, DAYOFMONTH(RECEIVE_TIME) AS DAY FROM jsadmin.MAIN_ADMIN mad, newjs.JPROFILE jp WHERE mad.PROFILEID = jp.PROFILEID AND RECEIVE_TIME BETWEEN '$st_date' AND '$end_date' AND jp.ACTIVATED = 'U' GROUP BY DAY"; 
			$res_under_screening = mysql_query_decide($sql_under_screening) or die("$sql_under_screening".mysql_error_js());
			while($row_under_screening = mysql_fetch_array($res_under_screening))
			{
				$day = $row_under_screening['DAY'] -1;
        $total_to_screen_temp[$day] += $row_under_screening['COUNT'];
			}
		}
		elseif($type=="edit")
		{
			//finding count of those profiles which are edited today (i.e needs to be screened.)
			//$sql_edit_unscreened = "SELECT COUNT(*) AS COUNT, DAYOFMONTH(MOD_DT) AS DAY FROM newjs.JPROFILE WHERE ACTIVATED='Y' AND SCREENING < '4094303' AND MOD_DT BETWEEN '$st_date' AND '$end_date' GROUP BY DAY";
      
      $sql_edit_unscreened = <<<SQL
        SELECT COUNT(J.PROFILEID) AS COUNT, DAYOFMONTH(J.MOD_DT) AS DAY 
        FROM newjs.JPROFILE J
        LEFT JOIN newjs.JPROFILE_CONTACT C
        ON J.PROFILEID=C.PROFILEID 
        LEFT JOIN jsadmin.ACTIVATED_WITHOUT_YOURINFO A
        ON A.PROFILEID=J.PROFILEID
        WHERE 
        J.ACTIVATED='Y' 
        AND A.PROFILEID IS NULL
        AND J.SCREENING < '1099511627775' 
        AND J.MOD_DT BETWEEN '$st_date' AND '$end_date' 
        AND (J.MOB_STATUS='Y' OR J.LANDL_STATUS='Y' OR C.ALT_MOB_STATUS='Y')
        GROUP BY DAY
SQL;
      
			$res_edit_unscreened = mysql_query_decide($sql_edit_unscreened) or die("$sql_edit_unscreened".mysql_error_js());
			while($row_edit_unscreened = mysql_fetch_array($res_edit_unscreened))
			{
				$day = $row_edit_unscreened['DAY'] - 1;
				$total_to_screen_temp[$day] = $row_edit_unscreened['COUNT'];
			}

		}

		//finding the count of those profiles which has been screened already.
		if($type=="new") {
			$sql = "SELECT STATUS,DAYOFMONTH(RECEIVE_TIME) AS RDAY, MONTH(RECEIVE_TIME) AS RMONTH, DAYOFMONTH(SUBMITED_TIME) SDAY, MONTH(SUBMITED_TIME) AS SMONTH,PROFILEID  FROM jsadmin.MAIN_ADMIN_LOG WHERE SCREENING_TYPE='O' AND SCREENING_VAL='0' AND RECEIVE_TIME BETWEEN '$st_date' AND '$end_date' ORDER BY RDAY";
    }
		elseif($type=="edit"){
			$sql="SELECT STATUS,DAYOFMONTH(RECEIVE_TIME) AS RDAY, MONTH(RECEIVE_TIME) AS RMONTH, DAYOFMONTH(SUBMITED_TIME) AS SDAY, MONTH(SUBMITED_TIME) AS SMONTH,PROFILEID FROM jsadmin.MAIN_ADMIN_LOG WHERE SCREENING_TYPE='O' AND SCREENING_VAL > '0' AND RECEIVE_TIME BETWEEN '$st_date' AND '$end_date' ORDER BY RDAY";
    }
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row = mysql_fetch_array($res))
		{
			$no_entry_in_main_admin_log = 1;

			$rday = $row['RDAY'] - 1;		
			$total_screened[$rday]++;
			$sday = $row['SDAY'] - 1;				
			if($row['RMONTH'] == $row['SMONTH'])
				$total_screened_day[$row['STATUS']][$rday][$sday]++;
			//need to select values(1-5) from the end of table
			else
				$total_screened_day[$row['STATUS']][$rday][$sday+31]++;
		}
		for($rday=0;$rday<31;$rday++)
		{
			$total_to_screen[$rday] = $total_to_screen_temp[$rday] + $total_screened[$rday];
			$total_to_screen_perc[$rday] = round(((($total_screened[$rday])/$total_to_screen[$rday]) * 100),2);
		}
		//if no entry exists in MAIN_ADMIN_LOG satisfying the above condition.
		if(!$no_entry_in_main_admin_log)
			$total_to_screen = $total_to_screen_temp;

		//finding unassigned profiles and showing them zone-wise(comfort,rush etc.)
		if($type == "new"){
			//$sql_unassigned = "SELECT MOD_DT FROM newjs.JPROFILE WHERE ACTIVATED='N' AND INCOMPLETE='N' AND MOD_DT BETWEEN '$st_date' AND '$end_date'";
      $sql_unassigned = <<< SQL
        SELECT J.MOD_DT 
        FROM newjs.JPROFILE J
        LEFT JOIN newjs.JPROFILE_CONTACT C
        ON J.PROFILEID=C.PROFILEID 
        WHERE 
        J.ACTIVATED='N' AND J.INCOMPLETE='N' 
        AND MOD_DT BETWEEN '$st_date' AND '$end_date'
        AND (J.MOB_STATUS='Y' OR J.LANDL_STATUS='Y' OR C.ALT_MOB_STATUS='Y')
        UNION
        SELECT J.MOD_DT 
        FROM newjs.JPROFILE J
        LEFT JOIN newjs.JPROFILE_CONTACT C
        ON J.PROFILEID=C.PROFILEID
        LEFT JOIN jsadmin.ACTIVATED_WITHOUT_YOURINFO A
        ON A.PROFILEID = J.PROFILEID
        WHERE 
        A.PROFILEID IS NOT NULL AND J.INCOMPLETE='N' 
        AND MOD_DT BETWEEN '$st_date' AND '$end_date'
        AND (J.MOB_STATUS='Y' OR J.LANDL_STATUS='Y' OR C.ALT_MOB_STATUS='Y')
        AND activatedKey=1
SQL;
    }
		elseif($type == "edit"){
			//$sql_unassigned = "SELECT MOD_DT FROM newjs.JPROFILE jp USE INDEX(SCREENING) LEFT JOIN jsadmin.MAIN_ADMIN mad ON mad.PROFILEID = jp.PROFILEID WHERE (mad.PROFILEID IS NULL) AND (ACTIVATED = 'Y' AND SCREENING < '4094303') AND jp.MOD_DT BETWEEN '$st_date' AND '$end_date'";
      $sql_unassigned = <<< SQL
        SELECT MOD_DT 
        FROM newjs.JPROFILE J 
        LEFT JOIN newjs.JPROFILE_CONTACT C
        ON J.PROFILEID=C.PROFILEID 
        LEFT JOIN jsadmin.MAIN_ADMIN mad 
        ON mad.PROFILEID = J.PROFILEID
        LEFT JOIN jsadmin.ACTIVATED_WITHOUT_YOURINFO A
        ON A.PROFILEID=J.PROFILEID
        WHERE 
        (mad.PROFILEID IS NULL) 
        AND (ACTIVATED = 'Y' AND A.PROFILEID IS NULL AND SCREENING < '4094303') 
        AND J.MOD_DT BETWEEN '$st_date' AND '$end_date'
        AND (J.MOB_STATUS='Y' OR J.LANDL_STATUS='Y' OR C.ALT_MOB_STATUS='Y')
SQL;
      
      
    }

		$res_unassigned = mysql_query_decide($sql_unassigned) or die("$sql_unassigned".mysql_error_js());
		while($row_unassigned = mysql_fetch_array($res_unassigned))
		{
			$mod_dt = $row_unassigned['MOD_DT'];
			$submit_dt = newtime($mod_dt,0,$screen_time,0);
			$mod_dt = getIST($mod_dt);
			$submit_dt = getIST($submit_dt);
			$submit_dt_arr = explode(" ",$submit_dt);

			$now = getIST(date("Y-m-d H:i:s"));

			list($submit_year,$submit_month,$submit_day) = explode("-",$submit_dt_arr[0]);
			list($hour,$minute,$second) = explode(":",$submit_dt_arr[1]);

			$timestamp = mktime($hour -10,$minute,$second,$submit_month,$submit_day,$submit_year);
			$comfort_time = getIST(date("Y-m-d H:i:s",$timestamp));

			$timestamp = mktime($hour - 8,$minute,$second,$submit_month,$submit_day,$submit_year);
			$rush_time = getIST(date("Y-m-d H:i:s",$timestamp));

			//Comfort zone: 0 to 2 hrs
			if($now < $comfort_time)
				$count['COMFORT']++;
			//Rush zone:    2 to 4 hrs
			elseif($now >= $comfort_time && $now < $rush_time)
				$count['RUSH']++;
			//Red Alert:    4 to 12 hrs
			elseif($now >= $rush_time && $now < $submit_dt)
				$count['RED_ALERT']++;
			//Expired zone: After 12 hours
			else
				$count['EXPIRED']++;

			$count['TOTAL']++;
		}

                if($JSIndicator==1)
                {
                        return;
                }

		$smarty->assign("total_screened",$total_screened);
		$smarty->assign("total_screened_day_approve",$total_screened_day[APPROVED]);
		$smarty->assign("total_screened_day_disapprove",$total_screened_day[DELETED]);
		$smarty->assign("total_to_screen",$total_to_screen);
		$smarty->assign("total_to_screen_perc",$total_to_screen_perc);
		$smarty->assign("count",$count);
		$smarty->assign("type",$type);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("ddarr1",$ddarr1);
		$smarty->assign("year",$year);
		$smarty->assign("month",$month);
		$smarty->assign("cid",$cid);
                $smarty->display("live_profiles_mis.htm");
	}
	else
	{
		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }

                for($i=2005;$i<=date('Y')+1;$i++)
                {
                        $yyarr[] = $i;
                }

		$smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("cid",$cid);
		$smarty->assign("type",$type);
		$smarty->display("live_profiles_mis.htm");
	}
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
