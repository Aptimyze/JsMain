<?php
include("connect.inc");
include("../profile/pg/functions.php");

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

	$sql="SELECT COUNT(*) as cnt,MTONGUE FROM billing.PURCHASES b LEFT JOIN newjs.JPROFILE j ON b.PROFILEID=j.PROFILEID WHERE INCOMPLETE<>'Y' AND b.ENTRY_DT BETWEEN '$st_date' AND '$end_date'  AND b.STATUS='DONE' GROUP BY MTONGUE ORDER BY cnt DESC";
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

	$sql="SELECT SUM(if(b.TYPE='DOL',$DOL_CONV_RATE*b.AMOUNT,b.AMOUNT)) as amt,MTONGUE FROM billing.PAYMENT_DETAIL b LEFT JOIN newjs.JPROFILE j ON b.PROFILEID=j.PROFILEID WHERE INCOMPLETE<>'Y' AND b.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND b.STATUS='DONE' GROUP BY MTONGUE ";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	while($row=mysql_fetch_array($res))
	{
		/*$k=array_search($row['MTONGUE'],$tvalarr);
		$commarr[]=$tcommarr[$k];
		$valarr[]=$tvalarr[$k];*/
		$i=array_search($row['MTONGUE'],$valarr);

		$amt[$i]+=$row['amt'];
		$totall_amt+=$row['amt'];
	}

	for($i=0;$i<count($commarr);$i++)
	{
		if($totall)
		{
			$pertot[$i]=$cnt[$i]/$totall * 100;
			$pertot[$i]=round($pertot[$i],1);
		}

		if($d)
		{
			$avg[$i]=$cnt[$i]/$d;
			$avg[$i]=round($avg[$i],2);
		}
	}

	if($d)
	{
		$avgtot=$totall/$d;
		$avgtot=round($avgtot,2);
	}

	$smarty->assign("cnt",$cnt);
	$smarty->assign("amt",$amt);
	$smarty->assign("avg",$avg);
	$smarty->assign("avgtot",$avgtot);
	$smarty->assign("totall",$totall);
	$smarty->assign("totall_amt",$totall_amt);
	$smarty->assign("pertot",$pertot);

	$smarty->assign("ddarr",$ddarr);
	$smarty->assign("commarr",$commarr);

	$smarty->assign("mm",$mm);
	$smarty->assign("yy",$yy);

	$smarty->display("community_payments_sum.htm");
}
else
{
	$smarty->display("jsconnectError.tpl");
}

function getlastdayofmonth($mm,$yy)
{
        if($mm<10)
                $mm="0".$mm;

        switch($mm)
        {
                case '01' : $ret='31';
                        break;
                case '02' :
                        $check=date("L",mktime(0,0,0,$mm,31,$yy));
                        if($check)
                                $ret='29';
                        else
                                $ret='28';
                        break;
                case '03' : $ret='31';
                        break;
                case '04' : $ret='30';
                        break;
                case '05' : $ret='31';
                        break;
                case '06' : $ret='30';
                        break;
                case '07' : $ret='31';
                        break;
                case '08' : $ret='31';
                        break;
                case '09' : $ret='30';
                        break;
                case '10' : $ret='31';
                        break;
                case '11' : $ret='30';
                        break;
                case '12' : $ret='31';
                        break;
        }
        return $ret;
}
?>
