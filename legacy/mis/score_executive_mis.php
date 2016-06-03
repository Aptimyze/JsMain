<?php
include("connect.inc");
/**************************************************************************************************************************
*       FILE NAME        : score_executive_mis.php
*       CREATED BY       : Shobha Kumari
*       MODIFIED ON      : 03.03.2006
*       FILE DESCRIPTION : This file shows a mis of score for various sources
*       FILES INCLUDED   : connect.inc
**************************************************************************************************************************/
$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	$flag = 0;
	$scorearr=array("600","550","500","450","400","350","300","250","200","150","100","50","0");

	//if($CMDGo && $month!='ALL' && $year!='ALL')
	if($CMDGo)
	{
		$flag = 1;

		if ($month!='ALL' && $year!='ALL')
		{
			if ($month <= 9)
                        	$month = "0".$month;
			$sql="SELECT COUNT(*) as cnt, SCORE, m.ALLOTED_TO  FROM incentive.MAIN_ADMIN_POOL p , incentive.MAIN_ADMIN m WHERE p.PROFILEID = m.PROFILEID  AND p.ENTRY_DT BETWEEN '$year-$month-01' AND '$year-$month-31' GROUP BY m.ALLOTED_TO , SCORE";
                        $sql1 = "SELECT COUNT(DISTINCT p.PROFILEID) as cnt, SCORE, a.ALLOTED_TO FROM incentive.MAIN_ADMIN_POOL m, incentive.MAIN_ADMIN a , billing.PURCHASES p WHERE m.PROFILEID = a.PROFILEID AND m.PROFILEID = p.PROFILEID AND p.STATUS='DONE' AND m.ENTRY_DT BETWEEN '$year-$month-01' AND '$year-$month-31' GROUP BY a.ALLOTED_TO  , m.SCORE";
		}
		else
		{
			$sql="SELECT COUNT(*) as cnt, SCORE, m.ALLOTED_TO  FROM incentive.MAIN_ADMIN_POOL p , incentive.MAIN_ADMIN m WHERE p.PROFILEID = m.PROFILEID GROUP BY m.ALLOTED_TO , SCORE";
                        $sql1 = "SELECT COUNT(DISTINCT p.PROFILEID) as cnt, SCORE, a.ALLOTED_TO FROM incentive.MAIN_ADMIN_POOL m, incentive.MAIN_ADMIN a , billing.PURCHASES p WHERE m.PROFILEID = a.PROFILEID AND m.PROFILEID = p.PROFILEID AND p.STATUS='DONE' GROUP BY a.ALLOTED_TO  , m.SCORE";
		}
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_array($res))
		{
			$score=$row['SCORE'];
			$alloted_to = $row['ALLOTED_TO'];
			$center=strtoupper(getcenter_for_operator($alloted_to));

			if(is_array($brancharr))
                        {
                                if(!in_array($center,$brancharr))
                                {
					$brancharr[]=$center;
                                }
                        }
                        else
                        {
                                        $brancharr[]=$center;
                        }

			$score=get_round_score($score);
			$j=array_search($score,$scorearr);
			$k=array_search($center,$brancharr);

			if(is_array($operatorarr[$k]))
			{
				if(!in_array($alloted_to,$operatorarr[$k]))
					$operatorarr[$k][]=$alloted_to;
			}
			else
			{
				$operatorarr[$k][]=$alloted_to;
			}

			//$j=array_search($score,$scorearr);

			/*if(is_array($operatorarr))
			{
				if(!in_array($alloted_to,$operatorarr))
					$operatorarr[]=$alloted_to;
			}
			else
			{
				$operatorarr[]=$alloted_to;
			}*/

			$i = array_search($alloted_to,$operatorarr[$k]);
			/*$cnt[$i][$j]+=$row['cnt'];
			$tota[$i]+=$row['cnt'];
			$totb[$j]+=$row['cnt'];
			$totall+=$row['cnt'];*/

			$cnt[$k][$i][$j]+=$row['cnt'];
                        $tota[$k][$i]+=$row['cnt'];
                        $totb[$k][$j]+=$row['cnt'];
                        $totall[$k]+=$row['cnt'];
		}
		for ($k = 0;$k < count($brancharr);$k++)
		{
			for ($i = 0;$i < count($operatorarr[$k]);$i++)
			{
				$cum_total=0;
				for ($j =0;$j < count($scorearr); $j++)
				{
					$cum_total+= $cnt[$k][$i][$j];
					if ($tota[$k][$i] && $cnt[$k][$i][$j])
					{
						$per = ($cum_total/$tota[$k][$i]) * 100;
						$percent[$k][$i][$j] = round($per,2);
					}
				}
			}
		}
		$cum_total=0;
		for ($k = 0;$k < count($brancharr);$k++)
                {
			$cum_total=0;
			for ($j =0;$j < count($scorearr); $j++)
			{
				$cum_total+= $totb[$k][$j];
				if ($totall[$k] && $totb[$k][$j])
				{
					$per = ($cum_total/$totall[$k]) * 100;
					$totpercent[$k][$j] = round($per,2);
				}
			}
		}
	
		unset($score);
		unset($alloted_to);
		$res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js($db));
		while($row1=mysql_fetch_array($res1))
		{
			$score=$row1['SCORE'];

			$alloted_to = $row1['ALLOTED_TO'];
			$center=strtoupper(getcenter_for_operator($alloted_to));
			$score=get_round_score($score);
			$j=array_search($score,$scorearr);
                        $k= array_search($center,$brancharr);

			if(is_array($operatorarr))
			{
				if(!in_array($alloted_to,$operatorarr[$k]))
					$operatorarr[$k][]=$alloted_to;
			}
			else
			{
				$operatorarr[$k][]=$alloted_to;
			}                                                                                                                            
			$i = array_search($alloted_to,$operatorarr[$k]);
			$paid_cnt[$k][$i][$j]+=$row1['cnt'];
                        $paid_tota[$k][$i]+=$row1['cnt'];
                        $paid_totb[$k][$j]+=$row1['cnt'];
                        $paid_totall[$k]+=$row1['cnt'];

			/*$paid_cnt[$i][$j]+=$row1['cnt'];
			$paid_tota[$i]+=$row1['cnt'];
			$paid_totb[$j]+=$row1['cnt'];
			$paid_totall+=$row1['cnt'];*/
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
	if($brancharr)
		$smarty->assign("BRANCH","Y");
	$smarty->assign("month",$month);
	$smarty->assign("myear",$year);
	$smarty->assign("mmarr",$mmarr);
	$smarty->assign("yyarr",$yyarr);

	$smarty->assign("scorearr",$scorearr);
	//$smarty->assign("srcarr",$srcarr);
	$smarty->assign("brancharr",$brancharr);
	$smarty->assign("operatorarr",$operatorarr);
	$smarty->assign("percent",$percent);
	$smarty->assign("totpercent",$totpercent);
	$smarty->assign("cnt",$cnt);
	$smarty->assign("tota",$tota);
	$smarty->assign("totb",$totb);
	$smarty->assign("totall",$totall);
	$smarty->assign("paid_cnt",$paid_cnt);
        $smarty->assign("paid_tota",$paid_tota);
        $smarty->assign("paid_totb",$paid_totb);
        $smarty->assign("paid_totall",$paid_totall);
	$smarty->assign("cid",$cid);
	$smarty->assign("flag",$flag);
	$smarty->display("score_executive_mis.htm");
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
