<?php

include("connect.inc");

$db=connect_misdb();

$flag=0;

if(authenticated($cid))
{
	if($CMDGo)
	{
		$sec_src=array();
		if($self){
			$sec_src[]="'S'";
		}
		if($sug_mailer)
			$sec_src[]="'M'";
		if($js_mailer)
			$sec_src[]="'I'";
		if($sug_called)
			$sec_src[]="'C'";
		$sec_sources=implode(",",$sec_src);
if($self)
	$sec_condition=" AND ( SEC_SOURCE IS NULL or SEC_SOURCE IN ( $sec_sources )) ";
else 
	$sec_condition=" AND SEC_SOURCE IN ( $sec_sources ) ";
		$srcarr[]="Unknown";
		$sql="SELECT DISTINCT GROUPNAME FROM MIS.SOURCE";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$srcarr[]=$row['GROUPNAME'];
		}

		$yrp1=$yr+1;
		$smarty->assign("dt",$yr);
		$smarty->assign("dt1",$yrp1);

		$mmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');

		$sql_h="SELECT SOURCEGP as src, SUM(COUNT) as cnt,MONTH(ENTRY_DT) as mm FROM MIS.SOURCE_HITS WHERE ENTRY_DT BETWEEN '$yr-04-01 00:00:00' AND '$yrp1-03-31 23:59:59' GROUP BY src,mm";

		if($sec_sources)
			$sql_m="SELECT SOURCEGP as src, SUM(COUNT) as cnt,MONTH(ENTRY_DT) as mm FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$yr-04-01 00:00:00' AND '$yrp1-03-31 23:59:59' AND INCOMPLETE<>'Y' AND ACTIVATED<>'D' AND ENTRY_MODIFY='E' $sec_condition GROUP BY src,mm";
		else
			$sql_m="SELECT SOURCEGP as src, SUM(COUNT) as cnt,MONTH(ENTRY_DT) as mm FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$yr-04-01 00:00:00' AND '$yrp1-03-31 23:59:59' AND INCOMPLETE<>'Y' AND ACTIVATED<>'D' AND ENTRY_MODIFY='E' GROUP BY src,mm";

		$res_h=mysql_query_decide($sql_h,$db) or die(mysql_error_js());
		if($row_h=mysql_fetch_array($res_h))
		{
			do
			{
				$src=$row_h['src'];
				$counter=$row_h['cnt'];
				$mm=$row_h['mm'];
				$i=array_search($src,$srcarr);
				if($mm<=3)
				{
					$mm+=8;
				}
				else
				{
					$mm-=4;
				}
				$cnt[$i][$mm]["h"]=$counter;
				$cnt1[$mm][$i]["h"]=$counter;
				$tota[$i]["h"]+=$cnt[$i][$mm]["h"];
				$totb[$mm]["h"]+=$cnt1[$mm][$i]["h"];
				$totallh+=$counter;
			}while($row_h=mysql_fetch_array($res_h));
		}

		$res_m=mysql_query_decide($sql_m,$db) or die(mysql_error_js());
		if($row_m=mysql_fetch_array($res_m))
		{
			do
			{
				$src=$row_m['src'];
				$counter=$row_m['cnt'];
				$i=array_search($src,$srcarr);
				if($i===NULL)
					$i=array_search('Unknown',$srcarr);
				$mm=$row_m['mm'];
				if($mm<=3)
				{
					$mm+=8;
				}
				else
				{
					$mm-=4;
				}
				$cnt[$i][$mm]["m"]=$counter;
				$cnt1[$mm][$i]["m"]=$counter;
				$tota[$i]["m"]+=$cnt[$i][$mm]["m"];
				$totb[$mm]["m"]+=$cnt1[$mm][$i]["m"];
				$totallm+=$counter;
			}while($row_m=mysql_fetch_array($res_m));
		}

		$num=count($mmarr);

		for($i=0;$i<count($srcarr);$i++)
		{
			for($j=0;$j<$num;$j++)
			{
				if($totb[$j]["h"])
				{
					$cnt[$i][$j]["hp"]=$cnt[$i][$j]["h"]/$totb[$j]["h"] * 100;
					$cnt[$i][$j]["hp"]=round($cnt[$i][$j]["hp"],2);
				}
				if($totb[$j]["m"])
				{
					$cnt[$i][$j]["mp"]=$cnt[$i][$j]["m"]/$totb[$j]["m"] * 100;
					$cnt[$i][$j]["mp"]=round($cnt[$i][$j]["mp"],2);
				}
			}

			if($totallh)
			{
				$tota[$i]["hp"]=$tota[$i]["h"]/$totallh * 100;
				$tota[$i]["hp"]=round($tota[$i]["hp"],1);
			}
			if($totallm)
			{
				$tota[$i]["mp"]=$tota[$i]["m"]/$totallm * 100;
				$tota[$i]["mp"]=round($tota[$i]["mp"],1);
			}
		}

		$smarty->assign("cnt",$cnt);
		$smarty->assign("tota",$tota);
		$smarty->assign("totb",$totb);
		$smarty->assign("totallh",$totallh);
		$smarty->assign("totallm",$totallm);

		$smarty->assign("srcarr",$srcarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("sec_sources",$sec_sources);

		$smarty->assign("flag","1");
		$smarty->display("sourcehitspercent.htm");
	}
	else
	{	
		for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}

		$smarty->assign("flag","0");
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("cid",$cid);
		$smarty->display("sourcehitspercent.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
