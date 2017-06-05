<?php
include("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	$flag = 0;
	$scorearr=array("600","550","500","450","400","350","300","250","200","150","100","50","0");

	if($CMDGo)
	{
		$flag = 1;

		if ($month!='ALL' && $year!='ALL')
		{
			if ($month <= 9)
                        	$month = "0".$month;
			$sql="SELECT COUNT(*) as cnt, SCORE, p.CITY_RES  FROM incentive.MAIN_ADMIN_POOL p , incentive.MAIN_ADMIN m WHERE p.PROFILEID = m.PROFILEID  AND p.ENTRY_DT BETWEEN '$year-$month-01' AND '$year-$month-31' GROUP BY p.CITY_RES , SCORE";
                        $sql1 = "SELECT COUNT(DISTINCT p.PROFILEID) as cnt, SCORE, m.CITY_RES FROM incentive.MAIN_ADMIN_POOL m, incentive.MAIN_ADMIN a , billing.PURCHASES p WHERE m.PROFILEID = a.PROFILEID AND m.PROFILEID = p.PROFILEID AND p.STATUS='DONE' AND m.ENTRY_DT BETWEEN '$year-$month-01' AND '$year-$month-31' GROUP BY m.CITY_RES  , m.SCORE";
		}
		else
		{
			$sql="SELECT COUNT(*) as cnt, SCORE, p.CITY_RES  FROM incentive.MAIN_ADMIN_POOL p , incentive.MAIN_ADMIN m WHERE p.PROFILEID = m.PROFILEID GROUP BY p.CITY_RES , SCORE";
                        $sql1 = "SELECT COUNT(DISTINCT p.PROFILEID) as cnt, SCORE, m.CITY_RES FROM incentive.MAIN_ADMIN_POOL m, incentive.MAIN_ADMIN a , billing.PURCHASES p WHERE m.PROFILEID = a.PROFILEID AND m.PROFILEID = p.PROFILEID AND p.STATUS='DONE' GROUP BY m.CITY_RES  , m.SCORE";
		}
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_array($res))
		{
			$score=$row['SCORE'];
			$city_res = $row['CITY_RES'];
			if($city_res != "")
			{
				$city = $CITY_INDIA_DROP["$city_res"];
				if($city == "")
					$city = $CITY_USA_DROP["$city_res"];
			}
			else
				$city = "Others";

			if(is_array($cityarr))
                        {
                                if(!in_array($city,$cityarr))
					$cityarr[]=$city;
                        }
                        else
                                        $cityarr[]=$city;

			$score=get_round_score($score);
			$j=array_search($score,$scorearr);
			$k=array_search($city,$cityarr);

			$cnt[$k][$j] += $row['cnt'];
			$tot_city[$k] += $row['cnt'];
			$tot_scr[$j] += $row['cnt'];
			$tot_all += $row['cnt'];
		}
		for ($k = 0;$k < count($cityarr);$k++)
		{
			$cum_total=0;
			for ($j =0;$j < count($scorearr); $j++)
			{
				$cum_total+= $cnt[$k][$j];
				if ($tot_city[$k] && $cnt[$k][$j])
				{
					$per = ($cum_total/$tot_city[$k]) * 100;
					$percent[$k][$j] = round($per,2);
				}
			}
		}

		unset($score);
		unset($city_res);

		$res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js($db));
		while($row1=mysql_fetch_array($res1))
		{
			$score=$row1['SCORE'];
			$city_res = $row1['CITY_RES'];

			if($city_res != "")
                        {
                                $city = $CITY_INDIA_DROP["$city_res"];
                                if($city == "")
                                        $city = $CITY_USA_DROP["$city_res"];
                        }
                        else
                                $center = "Others";

			$score=get_round_score($score);
			$j=array_search($score,$scorearr);
                        $k= array_search($city,$cityarr);

			$paid_cnt[$k][$j]+=$row1['cnt'];
                        $paid_tot_city[$k]+=$row1['cnt'];
                        $paid_tot_scr[$j]+=$row1['cnt'];
			$paid_tot_all += $row1['cnt'];
		}
		$cum_total=0;
		for ($j =0;$j < count($scorearr); $j++)
		{
			$cum_total+= $tot_scr[$j];
			if ($tot_scr[$j] && $tot_all)
			{
				$per = ($cum_total/$tot_all) * 100;
				$percent_all[$j] = round($per,2);
			}
		}
	}
	for($i=0;$i<12;$i++)
	{
		$mmarr[$i]=$i+1;
	}
	for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}
	if($cityarr)
		$smarty->assign("CITY","Y");
	$smarty->assign("month",$month);
	$smarty->assign("myear",$year);
	$smarty->assign("mmarr",$mmarr);
	$smarty->assign("yyarr",$yyarr);

	$smarty->assign("scorearr",$scorearr);
	$smarty->assign("cityarr",$cityarr);
	$smarty->assign("cnt",$cnt);
	$smarty->assign("paid_cnt",$paid_cnt);
	$smarty->assign("tot_city",$tot_city);
	$smarty->assign("tot_scr",$tot_scr);
	$smarty->assign("tot_all",$tot_all);
	$smarty->assign("paid_tot_city",$paid_tot_city);
	$smarty->assign("paid_tot_scr",$paid_tot_scr);
	$smarty->assign("paid_tot_all",$paid_tot_all);
	$smarty->assign("percent",$percent);
	$smarty->assign("percent_all",$percent_all);
	$smarty->assign("cid",$cid);
	$smarty->assign("flag",$flag);
	$smarty->display("score_citywise_mis.htm");
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
