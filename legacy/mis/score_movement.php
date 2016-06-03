<?php

include_once("connect.inc");

$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{

	if($CMDGo || $CMDGo1)
	{
		$select_date=$year.'-'.$month.'-'.$day;

		for($i=0;$i<17;$i++)
		{
			$ddarr[$i]=$i;
			$ddarr1[$i]=$i;
		}

		$ddarr[17]="TOTAL";


		if($CMDGo)
		{
			$sql="SELECT BAND1,BAND2,COUNTF,COUNTM FROM MIS.BAND_MOVEMENT WHERE CHECK_DATE='$select_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			while($row=mysql_fetch_array($res))
			{
				$band1=$row['BAND1'];
				$band2=$row['BAND2'];			
				$countf=$row['COUNTF'];
				$countm=$row['COUNTM'];
				
				$cnt[0][$band1][$band2]=$countf;
				$cnt[1][$band1][$band2]=$countm;
			}
		}
		else
		{
			$sql="SELECT BAND1,BAND2,COUNTF,COUNTM FROM MIS.BAND_MOVEMENT_WEEKLY WHERE CHECK_DATE='$select_date'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			while($row=mysql_fetch_array($res))
			{
				$band1=$row['BAND1'];
				$band2=$row['BAND2'];
				$countf=$row['COUNTF'];
				$countm=$row['COUNTM'];
																     
				$cnt[0][$band1][$band2]=$countf;
				$cnt[1][$band1][$band2]=$countm;
			}
		}

		for($j=0;$j<17;$j++)
		{
			if(is_array($cnt[0][$j]))
				$total[0][$j]=array_sum($cnt[0][$j]);
			else	
				$total[0][$j]=0;
			
			if(is_array($cnt[1][$j]))
				$total[1][$j]=array_sum($cnt[1][$j]);
			else
				$total[1][$j]=0;
		}

		for($j=0;$j<17;$j++)
		{
			for($k=0;$k<17;$k++)
			{
				$total1[0][$j]+=$cnt[0][$k][$j];
				$total1[1][$j]+=$cnt[1][$k][$j];
			}
		}

		$total1[3]=array_sum($total1[0]);
		$total1[4]=array_sum($total1[1]);

		$smarty->assign("flag",1);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("ddarr1",$ddarr1);
		$smarty->assign("cnt",$cnt);
		$smarty->assign("total",$total);
		$smarty->assign("total1",$total1);
		$smarty->assign("year",$year);
		$smarty->assign("month",$month);	
		$smarty->assign("day",$day);
		$smarty->display("score_movement.htm");

	}

	else
	{
		for($i=1;$i<13;$i++)
		{
			$mmarr[]=$i;
		}

		for($i=1;$i<32;$i++)
		{
			$ddarr[]=$i;
		}
															     
		for($i=0;$i<10;$i++)
		{
			$yyarr[]=$i+2006;
		}
															     
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("cid",$cid);
		$smarty->display("score_movement.htm");
	}
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}


?>
