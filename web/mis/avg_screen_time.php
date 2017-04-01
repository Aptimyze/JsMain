<?php
include("connect.inc");
$db=connect_misdb();

if(authenticated($cid))
{
	if($outside)
        {
                $CMDGo='Y';
                $today=date("Y-m-d");
                list($year,$month,$d)=explode("-",$today);
        }

	if($CMDGo)
	{
		$smarty->assign("flag",1);
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		$st_date=$year."-".$month."-01 00:00:00";
		$end_date=$year."-".$month."-31 23:59:59";

		$sql="SELECT ROUND( AVG( TIMESTAMPDIFF(HOUR , RECEIVE_TIME, SUBMITED_TIME) ) , 2 ) AS td, DAYOFMONTH( RECEIVE_TIME ) AS dd FROM jsadmin.MAIN_ADMIN_LOG WHERE RECEIVE_TIME BETWEEN '$st_date' AND '$end_date' AND SCREENING_TYPE='O' AND SCREENING_VAL=0 GROUP BY dd";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_array($res))
		{
			$dd=$row['dd']-1;
			$tot[$dd]+=$row['td'];
		}

		$sql="SELECT ROUND( AVG( TIMESTAMPDIFF(HOUR , RECEIVE_TIME, SUBMITED_TIME) ) , 2 ) AS td,IF (HOUR (RECEIVE_TIME) > '9' AND RECEIVE_TIME < '21', '7p', '7a' ) AS tz, DAYOFMONTH( RECEIVE_TIME ) AS dd FROM jsadmin.MAIN_ADMIN_LOG WHERE RECEIVE_TIME BETWEEN '$st_date' AND '$end_date' AND SCREENING_TYPE='O' AND SCREENING_VAL=0 GROUP BY dd, tz";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_array($res))
		{
			$dd=$row['dd']-1;
			if($row['tz']=='7a')
				$i=0;
			else
				$i=1;
			$cnt[$i][$dd]+=$row['td'];
		}

		$smarty->assign("cnt",$cnt);
		$smarty->assign("tot",$tot);

		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("year",$year);
		$smarty->assign("month",$month);
		$smarty->assign("cid",$cid);
		$smarty->display("avg_screen_time.htm");
	}
	else
	{
		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }

                for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}

		$smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("cid",$cid);
		$smarty->display("avg_screen_time.htm");
	}
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
