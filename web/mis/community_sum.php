<?php
include("connect.inc");
$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	$sql="SELECT VALUE,SMALL_LABEL FROM newjs.MTONGUE";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	while($row=mysql_fetch_array($res))
	{
		$tvalarr[]=$row['VALUE'];
		$tcommarr[]=$row['SMALL_LABEL'];
	}

	for($i=0;$i<31;$i++)
	{
		$ddarr[$i]=$i+1;
	}

        if($mm<10)
                $mm="0".$mm;
	$st_date=$yy."-".$mm."-01 00:00:00";
	$end_date=$yy."-".$mm."-31 23:59:59";
/*
        $curdate=date("Y-m-d");
        $end_date1=substr($end_date,0,10);
        $diff=strcmp($end_date1,$curdate);
        $t1=mktime(0,0,0,$mm,01,$yy);
        if($diff>=0)
        {
                $t2=gettimeofday();
                $t2=$t2['sec'];
        }
        else
        {
                $last_day=getlastdayofmonth($mm,$yy);
                $t2=mktime(23,59,59,$mm,$last_day,$yy);
        }
        $t=$t2-$t1;
        $d=$t/(60*60*24);
        $d=round($d,2);
*/
	if($get_today)
	{
		$ts=time();
		$ts-=2*24*60*60;
		$today=date("Y-m-d",$ts);

		$sql="SELECT COUNT(*) as cnt,MTONGUE FROM newjs.JPROFILE WHERE ACTIVATED<>'D' AND INCOMPLETE<>'Y' AND ENTRY_DT>='$today' GROUP BY MTONGUE ORDER BY cnt DESC";
	}
	else
	{
		$sql="SELECT SUM(COUNT) as cnt,MTONGUE FROM MIS.SOURCE_MEMBERS WHERE ACTIVATED<>'D' AND INCOMPLETE<>'Y' AND ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND ENTRY_MODIFY='E' GROUP BY MTONGUE ORDER BY cnt DESC";
	}
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	while($row=mysql_fetch_array($res))
	{
		$k=array_search($row['MTONGUE'],$tvalarr);
		$commarr[]=$tcommarr[$k];
		$valarr[]=$tvalarr[$k];
		$i=array_search($row['MTONGUE'],$valarr);

		$cnt[$i]+=$row['cnt'];
		$totall+=$row['cnt'];
	}

	for($i=0;$i<count($commarr);$i++)
	{
		if($totall)
		{
			$pertot[$i]=$cnt[$i]/$totall * 100;
			$pertot[$i]=round($pertot[$i],1);
		}
/*
                if($d)
                {
                        $avg[$i]=$cnt[$i]/$d;
                        $avg[$i]=round($avg[$i],2);
                }
*/
	}
/*
        if($d)
        {
                $avgtot=$totall/$d;
                $avgtot=round($avgtot,2);
        }
*/
	$smarty->assign("cnt",$cnt);
        $smarty->assign("avg",$avg);
        $smarty->assign("avgtot",$avgtot);
	$smarty->assign("totall",$totall);
	$smarty->assign("pertot",$pertot);
	$smarty->assign("get_today",$get_today);
	$smarty->assign("ddarr",$ddarr);
	$smarty->assign("commarr",$commarr);

	$smarty->assign("mm",$mm);
	$smarty->assign("yy",$yy);

	$smarty->display("community_sum.htm");
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
