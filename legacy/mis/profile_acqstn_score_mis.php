<?php
include("connect.inc");
/**************************************************************************************************************************
*       FILE NAME        : profile_acqstn_score_mis.php
*       CREATED BY       : Shobha Kumari
*       MODIFIED ON      : 08.02.2006
*       FILE DESCRIPTION : This file shows a mis of score for various sources
*       FILES INCLUDED   : connect.inc
**************************************************************************************************************************/
$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	$scorearr=array("400","375","350","325","300","275","250","225","200","175","150","125","100");

	$sql="SELECT SourceID,GROUPNAME FROM MIS.SOURCE";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	while($row=mysql_fetch_array($res))
	{
		$source=strtolower($row['SourceID']);
		$grparr[$source]=$row['GROUPNAME'];
	}

	$srcarr[0]="No Record";

	if($CMDGo && $month!='ALL' && $year!='ALL')
	{
		if ($month <= 9)
			$month = "0".$month;
		$sql="SELECT COUNT(*) as cnt, SCORE, SOURCE FROM MIS.PROFILE_ACQUISTION_SCORE WHERE ENTRY_DT BETWEEN '$year-$month-01' AND '$year-$month-31' GROUP BY SOURCE, SCORE";
		$sql1 = "SELECT COUNT(DISTINCT p.PROFILEID) as cnt, SCORE, SOURCE FROM MIS.PROFILE_ACQUISTION_SCORE m, billing.PURCHASES p WHERE m.PROFILEID = p.PROFILEID AND m.ENTRY_DT BETWEEN '$year-$month-01' AND '$year-$month-31'  AND p.STATUS='DONE' GROUP BY m.SOURCE, m.SCORE";
	}
	else
	{
		$sql="SELECT COUNT(*) as cnt, SCORE, SOURCE FROM MIS.PROFILE_ACQUISTION_SCORE GROUP BY SOURCE, SCORE";
		$sql1 = "SELECT COUNT(DISTINCT p.PROFILEID) as cnt, SCORE, SOURCE FROM MIS.PROFILE_ACQUISTION_SCORE m, billing.PURCHASES p WHERE m.PROFILEID = p.PROFILEID AND p.STATUS='DONE' GROUP BY m.SOURCE, m.SCORE";
	}
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	while($row=mysql_fetch_array($res))
	{
		$score=$row['SCORE'];
		$source=strtolower($row['SOURCE']);

		$j=array_search($score,$scorearr);

		$gpname=$grparr[$source];

		if($gpname)
		{
			if(!in_array($gpname,$srcarr))
			{
				$srcarr[]=$gpname;
			}
			$i=array_search($gpname,$srcarr);
		}
		else
		{
			$i=0;
		}

		$cnt[$i][$j]+=$row['cnt'];
		$tota[$i]+=$row['cnt'];
		$totb[$j]+=$row['cnt'];
		$totall+=$row['cnt'];
	}
	for ($i = 0;$i < count($srcarr);$i++)
	{
		$cum_total=0;
		for ($j =0;$j < count($scorearr); $j++)
		{
			$cum_total+= $cnt[$i][$j];
			if ($tota[$i] && $cnt[$i][$j])
			{
				$per = ($cum_total/$tota[$i]) * 100;
				$percent[$i][$j] = round($per,2);
			}
		}
	}
	$cum_total=0;
	for ($j =0;$j < count($scorearr); $j++)
	{
		$cum_total+= $totb[$j];
		if ($totall && $totb[$j])
		{
			$per = ($cum_total/$totall) * 100;
			$totpercent[$j] = round($per,2);
		}
	}
	
	unset($score);
	unset($source);
	$res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js($db));
	while($row1=mysql_fetch_array($res1))
        {
                $score=$row1['SCORE'];
                $source=strtolower($row1['SOURCE']);

                $j=array_search($score,$scorearr);
                                                                                                                            
                $gpname=$grparr[$source];
                                                                                                                            
                if($gpname)
                {
                        $i=array_search($gpname,$srcarr);
                }
                else
                {
                        $i=0;
                }
                                                                                                                            
                $paid_cnt[$i][$j]+=$row1['cnt'];
                $paid_tota[$i]+=$row1['cnt'];
                $paid_totb[$j]+=$row1['cnt'];
                $paid_totall+=$row1['cnt'];
        }
	for($i=0;$i<12;$i++)
	{
		$mmarr[$i]=$i+1;
	}
	for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}
	$smarty->assign("month",$month);
	$smarty->assign("myear",$year);
	$smarty->assign("mmarr",$mmarr);
	$smarty->assign("yyarr",$yyarr);

	$smarty->assign("scorearr",$scorearr);
	$smarty->assign("srcarr",$srcarr);
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
	$smarty->display("profile_acqstn_score_mis.htm");
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
