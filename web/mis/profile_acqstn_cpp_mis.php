<?php
include("connect.inc");
$db=connect_misdb();

$jmmarr=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
$curdate=date("Y-m-d");

$srcarr=array("google","NONE","jeevansathi","IndiaTimes","overture","Rediff06","Sify","yahoo","Yahoo_Tgt","Google_NRI","Rediff_tgt","rediff_pilot_july","MSN_group","mediaturf","yahoo_cpm06","rediff_new_06","yahoosrch","rediff_march_07","yahoo_amj_07");
$srccnt=count($srcarr);

$yrarr=array("2005","2006","2007");
$yrcnt=count($yrarr);

for($ind=0;$ind<$srccnt;$ind++)
{
	$source=$srcarr[$ind];
	if($source)
	{
		$sql="SELECT SourceID FROM MIS.SOURCE WHERE GROUPNAME='$source'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$srcidarr[]=$row['SourceID'];
		}

		$srcstr="'".implode("','",$srcidarr)."'";
		unset($srcidarr);
	}

	for($ind2=0;$ind2<$yrcnt;$ind2++)
	{
		$year=$yrarr[$ind2];

		list($cur_year,$cur_mm,$cur_day) = explode("-",$curdate);

		if ($cur_mm < 10)
		{
			$cur_mm = substr($cur_mm,1);
		}

		if ($cur_year == $year)
		{
			for ($i=0;$i<$cur_mm;$i++)
				$pmmarr[$i] = $jmmarr[$i];
		}
		else
		{
			for ($i=0;$i<count($jmmarr);$i++)
				$pmmarr[$i] = $jmmarr[$i];

			$j= count($jmmarr);
			for ($x=0;$x<$cur_mm;$j++,$x++)
			{
				$pmmarr[$j] = $jmmarr[$x];
			}
		}

		$sql_cost="SELECT COST_MONTH , COST FROM MIS.PROFILE_ACQUISTION_COST WHERE SOURCE = '$source' AND COST_MONTH LIKE '%$year%'";
		$res_cost = mysql_query_decide($sql_cost,$db) or die(mysql_error_js());
		while($row_cost=mysql_fetch_array($res_cost))
		{
			$month = substr($row_cost['COST_MONTH'],5);
			if ($month < 10)
			{
				$month = substr($month,1);
			}
			$mm = $month - 1;
			$prof_acqtot[$mm] = $row_cost['COST'];
			$prof_acq_cost_tot+=$row_cost['COST'];
		}
		
		$sql="SELECT COUNT(*) as cnt,ACTIVATED,MONTH(ENTRY_DT) as mm, YEAR(ENTRY_DT) as yy FROM newjs.JPROFILE WHERE ACTIVATED IN ('Y','H','D') AND INCOMPLETE='N' ";
		if($source)
		{
			$sql.=" AND SOURCE IN ($srcstr) ";
		}
		if($year)
		{
			$sql.=" AND ENTRY_DT BETWEEN '$year-01-01 00:00:00' AND '$year-12-31 23:59:59'";
		}
		$sql.=" GROUP BY mm,ACTIVATED";

		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			do
			{
				$cnt=$row['cnt'];
				
				$mm=$row['mm']-1;

				if ($row['ACTIVATED']=='Y')
					$activetot[$mm]+=$cnt;
				$regtot[$mm]+=$cnt;

				$reg_mem_tot+=$cnt;
			}while($row=mysql_fetch_array($res));
		}
		for ($i=0;$i<count($activetot);$i++)
			$active_mem_tot+=$activetot[$i];

		$sql="SELECT COUNT(DISTINCT p.PROFILEID) as cnt, SUM(AMOUNT) AS sum , month(j.ENTRY_DT) as jmm,year(j.ENTRY_DT) as jyy,month(p.ENTRY_DT) as pmm,year(p.ENTRY_DT) as pyy FROM billing.PAYMENT_DETAIL p LEFT JOIN newjs.JPROFILE j ON j.PROFILEID=p.PROFILEID WHERE INCOMPLETE<>'Y' AND j.ENTRY_DT BETWEEN '$year-01-01 00:00:00' AND '$year-12-31 23:59:59' AND p.STATUS='DONE' AND j.SOURCE IN ($srcstr) GROUP BY jyy,jmm,pyy,pmm";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			do
			{
				$cnt=$row['cnt'];
				$sum = $row['sum'];
				$jmm=$row['jmm']-1;
				$jyy=$row['jyy'];
				$pmm=$row['pmm']-1;
				$pyy=$row['pyy'];

				if($pyy==$year+1)
				{
					$pmm=$pmm+12;
				}

				$tot[$jmm][$pmm]=$cnt;

				$totamt[$jmm][$pmm]+=$sum;

				$jtot[$jmm]+=$cnt;
				$jtotamt[$jmm]+=$sum;

				$totall+=$cnt;
				$totalamt+=$sum;
			}while($row=mysql_fetch_array($res));
		}

		for($i=0;$i<count($jmmarr);$i++)
		{
			$total_amt = 0;
			if($activetot[$i])
			{
				$jtotpercent[$i]=$jtotamt[$i]/$activetot[$i];
				$jtotpercent[$i]=round($jtotpercent[$i],2);

				$active_profile_cost[$i] = $prof_acqtot[$i]/$activetot[$i];
				$active_profile_cost[$i] =round($active_profile_cost[$i],2);
			}
			for($j=0;$j<count($pmmarr);$j++)
			{
				$total_amt+=$totamt[$i][$j];
				if ($total_amt && $activetot[$i] && $tot[$i][$j])
				{
					$percent[$i][$j]=$total_amt/$activetot[$i];
					$percent[$i][$j]=round($percent[$i][$j],2);
				}
				if ($active_profile_cost[$i])
				{
					$active_profile_cost_per[$i][$j] = ($percent[$i][$j]/$active_profile_cost[$i]) * 100;
					$active_profile_cost_per[$i][$j] = round($active_profile_cost_per[$i][$j],2);
				}
			}
			if ($regtot[$i])
			{
				$active_per[$i]=($activetot[$i]/$regtot[$i]) * 100;
				$active_per[$i]=round($active_per[$i],2);
			}
			if ($prof_acqtot[$i])
			{
				$jtot_active_cost_per[$i]=($total_amt/$prof_acqtot[$i]) * 100;
				$jtot_active_cost_per[$i]=round($jtot_active_cost_per[$i],2);
			}
		}

		$smarty->assign("jmmarr",$jmmarr);
		$smarty->assign("pmmarr",$pmmarr);
		$smarty->assign("tot",$tot);
		$smarty->assign("totamt",$totamt);
		$smarty->assign("totall",$totall);
		$smarty->assign("active_per",$active_per);
		$smarty->assign("reg",$reg);
		$smarty->assign("regtot",$regtot);
		$smarty->assign("reg_mem_tot",$reg_mem_tot);
		$smarty->assign("activetot",$activetot);
		$smarty->assign("active_profile_cost",$active_profile_cost);
		$smarty->assign("active_profile_cost_per",$active_profile_cost_per);
		$smarty->assign("active_mem_tot",$active_mem_tot);
		$smarty->assign("prof_acqtot",$prof_acqtot);
		$smarty->assign("prof_acq_cost_tot",$prof_acq_cost_tot);
		$smarty->assign("ptot",$ptot);
		$smarty->assign("jtot",$jtot);
		$smarty->assign("jtot_active_cost_per",$jtot_active_cost_per);
		$smarty->assign("jtotamt",$jtotamt);
		$smarty->assign("percent",$percent);
		$smarty->assign("jtotpercent",$jtotpercent);
		$smarty->assign("year",$year);
		$smarty->assign("source",$source);

		$mis_content = $smarty->fetch("profile_acqstn_cpp_mis.htm");
		$file_name="/usr/local/indicators/cpp_".$source."_".$year."_mis.htm";
		//$file_name="/usr/local/apache/sites/jeevansathi.com/htdocs/mis/indicators/cpp_".$source."_".$year."_mis.htm";
		$fd=fopen($file_name,"w");
		fwrite($fd,$mis_content);
		fclose($fd);
		passthru("chmod 664 ".$file_name);

		unset($pmmarr);
		unset($prof_acqtot);
		unset($prof_acq_cost_tot);
		unset($activetot);
		unset($regtot);
		unset($reg_mem_tot);
		unset($active_mem_tot);
		unset($tot);
		unset($totamt);
		unset($jtot);
		unset($jtotamt);
		unset($totall);
		unset($totalamt);
		unset($jtotpercent);
		unset($active_profile_cost);
		unset($percent);
		unset($active_profile_cost_per);
		unset($active_per);
		unset($jtot_active_cost_per);
	}
}
?>
