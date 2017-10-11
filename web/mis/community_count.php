<?php
//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
        $zipIt = 1;
if($zipIt)
        ob_start("ob_gzhandler");
//end of it

include("connect.inc");
$db=connect_misdb();

if(authenticated($cid))
{
	if($CMDGo)
	{
		$smarty->assign("flag","1");

		$sql="SELECT DISTINCT VALUE,SMALL_LABEL FROM newjs.MTONGUE";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$valarr[]=$row['VALUE'];
			$commarr[]=$row['SMALL_LABEL'];
		}

		if($mm || $get_today)
		{
			for($i=0;$i<31;$i++)
			{
				$ddarr[$i]=$i+1;
			}

			$st_date=$yy."-".$mm."-01 00:00:00";
			$end_date=$yy."-".$mm."-31 23:59:59";

			$smarty->assign("DT",$mm." - ".$yy);
		}
		else
		{
			$ddarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');

			$yyp1=$yy+1;

			$st_date=$yy."-04-01 00:00:00";
			$end_date=$yyp1."-03-31 23:59:59";

			$smarty->assign("DT","Apr $yy - Mar $yyp1");
		}

		if($get_today)
		{
			$ts=time();
			$ts-=2*24*60*60;
			$today=date("Y-m-d",$ts);

			$smarty->assign("DT","Current Records");
		
			$sql="SELECT COUNT(*) as cnt,MTONGUE,DAYOFMONTH(ENTRY_DT) as dd FROM newjs.JPROFILE WHERE ACTIVATED<>'D' AND INCOMPLETE<>'Y' AND ENTRY_DT>='$today' GROUP BY MTONGUE,dd";
		}
		else
		{
			$sql="SELECT SUM(COUNT) as cnt,MTONGUE,";
			if($mm)
			{
				$sql.="DAYOFMONTH(ENTRY_DT)";
			}
			else
			{
				$sql.="MONTH(ENTRY_DT)";
			}
			$sql.=" as dd FROM MIS.SOURCE_MEMBERS WHERE ACTIVATED<>'D' AND INCOMPLETE<>'Y' AND ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND ENTRY_MODIFY='E' GROUP BY MTONGUE,dd";
		}
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$dd=$row['dd'];

			if($mm || $get_today)
			{
				$dd=$dd-1;
			}
			else
			{
				if($dd<=3)
				{
					$dd+=8;
				}
				else
				{
					$dd-=4;
				}
			}

			$i=array_search($row['MTONGUE'],$valarr);

			$cnt[$i][$dd]+=$row['cnt'];
			$tota[$i]+=$row['cnt'];
			$totb[$dd]+=$row['cnt'];
			$totall+=$row['cnt'];
		}

		for($i=0;$i<count($commarr);$i++)
		{
			for($j=0;$j<count($ddarr);$j++)
			{
				if($totb[$j])
				{
					$per[$i][$j]=$cnt[$i][$j]/$totb[$j] * 100;
					$per[$i][$j]=round($per[$i][$j],1);

					if(!$mm && !$get_today)
					{
						if($j>=9)
						{
							$ddate_yy=$yyp1;
							$temp-=8;
						}
						else
						{
							$temp+=4;
							$ddate_yy=$yy;
						}

						$curdate=date("Y-m-d");
						$temp=$j;
						if($temp>=9)
						{
							$temp-=9;
						}
						else
						{
							$temp+=3;
						}
						$temp=$temp+1;

						$ddate_mon=$temp;

						if($ddate_mon<10)
							$ddate_mon="0".$ddate_mon;
						$last_day=getlastdayofmonth($ddate_mon,$ddate_yy);
						$end_date=$ddate_yy."-".$ddate_mon."-".$last_day;

						$diff=strcmp($end_date,$curdate);
						$t1=mktime(0,0,0,$ddate_mon,01,$ddate_yy);
						if($diff>=0)
						{
							$t2=gettimeofday();
							$t2=$t2['sec'];
						}
						else
						{
							$t2=mktime(23,59,59,$ddate_mon,$last_day,$ddate_yy);
						}

						$t=$t2-$t1;
						$d=$t/(60*60*24);
						$d=round($d,2);
echo "<!--comm : $commarr[$i] --- mon : $ddarr[$j] --- d : $d --- t : $t --- last : $last_day --- mm : $ddate_mon --- yy $ddate_yy --- diff : $diff --- end : $end_date --- cur : $curdate\n-->";
						if($d)
						{
							$avg[$i][$j]=$cnt[$i][$j]/$d;
							$avg[$i][$j]=round($avg[$i][$j],1);
						}
					}
				}
			}
			if($totall)
			{
				$pertot[$i]=$tota[$i]/$totall * 100;
				$pertot[$i]=round($pertot[$i],1);
			}
		}

		$smarty->assign("cnt",$cnt);
		$smarty->assign("tota",$tota);
		$smarty->assign("totb",$totb);
		$smarty->assign("totall",$totall);
		$smarty->assign("per",$per);
		$smarty->assign("avg",$avg);
		$smarty->assign("pertot",$pertot);
		$smarty->assign("get_today",$get_today);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("commarr",$commarr);

		$smarty->assign("mm",$mm);
		$smarty->assign("yy",$yy);

		$smarty->assign("cid",$cid);
		$smarty->display("community_count.htm");
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
		$smarty->display("community_count.htm");
	}
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

// flush the buffer
if($zipIt)
        ob_end_flush();
?>
