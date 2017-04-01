<?php
include("connect.inc");
$db=connect_misdb();

if(authenticated($cid))
{
	if($CMDGo)
	{
		$smarty->assign("flag","1");

		$genderarr=array('Male','Female');
		$agearr=array('18-21','22-25','26-29','30-33','34+');

		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		$st_date=$yy."-".$mm."-01 00:00:00";
		$end_date=$yy."-".$mm."-31 23:59:59";

		if($get_today)
		{
			$ts=time();
			$ts-=2*24*60*60;
			$today=date("Y-m-d",$ts);

			$sql="SELECT COUNT(*) as cnt,GENDER,DAYOFMONTH(ENTRY_DT) as dd,AGE FROM newjs.JPROFILE WHERE ACTIVATED<>'D' AND INCOMPLETE<>'Y' AND ENTRY_DT>='$today' GROUP BY AGE,GENDER,dd";
		}
		else
		{
			$sql="SELECT SUM(COUNT) as cnt,GENDER,DAYOFMONTH(ENTRY_DT) as dd,AGE FROM MIS.SOURCE_MEMBERS WHERE ACTIVATED<>'D' AND INCOMPLETE<>'Y' AND ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND ENTRY_MODIFY='E' GROUP BY AGE,GENDER,dd";
		}
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$dd=$row['dd']-1;

			if($row['AGE']<=21)
				$k=0;
			elseif($row['AGE']>21 && $row['AGE']<=25)
				$k=1;
			elseif($row['AGE']>25 && $row['AGE']<=29)
				$k=2;
			elseif($row['AGE']>29 && $row['AGE']<=33)
				$k=3;
			else
				$k=4;

			if($row['GENDER']=='M')
				$i=0;
			elseif($row['GENDER']=='F')
				$i=1;

			$cnt[$i][$dd]+=$row['cnt'];
			$tota[$i]+=$row['cnt'];
			$totb[$dd]+=$row['cnt'];
			$totall+=$row['cnt'];

			$cnt2[$i][$k][$dd]+=$row['cnt'];
			$tot2a[$i][$k]+=$row['cnt'];
			$tot2b[$i][$dd]+=$row['cnt'];
			$totall2[$i]+=$row['cnt'];
		}

		for($i=0;$i<count($genderarr);$i++)
		{
			for($j=0;$j<count($ddarr);$j++)
			{
				if($totb[$j])
				{
					$per[$i][$j]=$cnt[$i][$j]/$totb[$j] * 100;
					$per[$i][$j]=round($per[$i][$j],1);
				}
			}
			if($totall)
			{
				$pertot[$i]=$tota[$i]/$totall * 100;
				$pertot[$i]=round($pertot[$i],1);
			}
		}

		for($i=0;$i<count($genderarr);$i++)
		{
			for($k=0;$k<count($agearr);$k++)
			{
				for($j=0;$j<count($ddarr);$j++)
				{
					if($tot2b[$i][$j])
					{
						$per2[$i][$k][$j]=$cnt2[$i][$k][$j]/$tot2b[$i][$j] * 100;
						$per2[$i][$k][$j]=round($per2[$i][$k][$j],1);
					}
				}
				if($totall2[$i])
				{
					$pertot2[$i][$k]=$tot2a[$i][$k]/$totall2[$i] * 100;
					$pertot2[$i][$k]=round($pertot2[$i][$k],1);
				}
			}
		}

		$smarty->assign("cnt",$cnt);
		$smarty->assign("tota",$tota);
		$smarty->assign("totb",$totb);
		$smarty->assign("totall",$totall);
		$smarty->assign("per",$per);
		$smarty->assign("pertot",$pertot);

		$smarty->assign("cnt2",$cnt2);
		$smarty->assign("tot2a",$tot2a);
		$smarty->assign("tot2b",$tot2b);
		$smarty->assign("totall2",$totall2);
		$smarty->assign("per2",$per2);
		$smarty->assign("pertot2",$pertot2);

		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("genderarr",$genderarr);
		$smarty->assign("agearr",$agearr);

		$smarty->assign("mm",$mm);
		$smarty->assign("yy",$yy);

		$smarty->display("male_female_count.htm");
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

		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("flag","0");
		$smarty->assign("cid",$cid);
		$smarty->display("male_female_count.htm");
	}
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
