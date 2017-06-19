<?php
//to zip the file before sending it

$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it

include_once("connect.inc");

include("../profile/arrays.php");
 
$db=connect_misdb();
$db2=connect_master();

if(authenticated($checksum) || $JSIndicator)
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
		$st_date=$year."-".$month."-".$day;
		$end_date=$year2."-".$month2."-".$day2;
		$sql1=" SOURCEID ";//from Source_HITS
		$sql_h="SELECT $sql1 as src, SUM(COUNT) as cnt FROM MIS.SOURCE_HITS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND SOURCEGP='$sourcegp' GROUP BY src ORDER BY cnt DESC";
		if($sec_sources)
		$sql_m="SELECT $sql1 as src, SUM(COUNT) as cnt FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND ENTRY_MODIFY='E' AND ACTIVATED IN ('Y','H') AND INCOMPLETE='N' AND SOURCEGP='$sourcegp' $sec_condition GROUP BY src";
		else
		$sql_m="SELECT $sql1 as src, SUM(COUNT) as cnt FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND ENTRY_MODIFY='E' AND ACTIVATED IN ('Y','H') AND INCOMPLETE='N' AND SOURCEGP='$sourcegp' GROUP BY src";
		$res_h=mysql_query_decide($sql_h,$db) or die("$sql_h".mysql_error_js());
		while($row_h=mysql_fetch_array($res_h))
		{
			$src=$row_h['src'];
			$counter=$row_h['cnt'];
			if(is_array($srcarr))
			{
				if(!in_array($src,$srcarr))
				{
					$srcarr[]=$src;
				}
			}
			else
			{
				$srcarr[]=$src;
			}

			$i=array_search($src,$srcarr);
			$cnt[$i]["h"]+=$counter;
			$totallh+=$counter;
		}
		$res_m=mysql_query_decide($sql_m,$db) or die("$sql_m".mysql_error_js());
		while($row_m=mysql_fetch_array($res_m))
		{
			$src=$row_m['src'];
			$counter=$row_m['cnt'];
			$i=array_search($src,$srcarr);
			$cnt[$i]["m"]+=$counter;
			$totallm+=$counter;
		}
		for($i=0;$i<count($srcarr);$i++)
		{
			if($cnt[$i]["h"])
			{
				$cnt[$i]["cp"]=$cnt[$i]["m"]/$cnt[$i]["h"] * 100;
				$cnt[$i]["cp"]=round($cnt[$i]["cp"],2);
			}
		}
		if($totallh)
		{
			for($i=0;$i<count($srcarr);$i++)
			{
				$cnt[$i]["p"]=$cnt[$i]["h"]/$totallh * 100;
				$cnt[$i]["p"]=round($cnt[$i]["p"],2);
			}
			$totallcp=$totallm/$totallh * 100;
			$totallcp=round($totallcp,2);
		}

		$dt_from=$day."-".$month."-".$year;
		$dt_to=$day2."-".$month2."-".$year2;
		$smarty->assign("cnt",$cnt);
		$smarty->assign("tota",$tota);
		$smarty->assign("totb",$totb);
		$smarty->assign("totallh",$totallh);
		$smarty->assign("totallm",$totallm);
		$smarty->assign("totallcp",$totallcp);
		$smarty->assign("source",$sourcegp);
		$smarty->assign("srcarr",$srcarr);
		$smarty->assign("checksum",$checksum);
		$smarty->assign("sec_sources",$sec_sources);
		
		$smarty->assign("dt_from",$dt_from);
		$smarty->assign("dt_to",$dt_to);
		$smarty->display("sourcehits_brief.htm");
	}
	else	
	{	
		$sql="SELECT DISTINCT GROUPNAME FROM MIS.SOURCE";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$i=0;
		while($row=mysql_fetch_array($res))
		{
			$srcgrp[$i]=$row['GROUPNAME'];
			$i++;
		}

		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}
		for($i=0;$i<12;$i++)
		{
			$mmarr[$i]=$i+1;
		}
		for($i=0;$i<10;$i++)
		{
			$yyarr[$i]=2005+$i;
		}
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("commarr",$commarr);
		$smarty->assign("ctryarr",$ctryarr);
		$smarty->assign("mstatusarr",$MSTATUS);
		$smarty->assign("srcgrp",$srcgrp);
		$smarty->assign("checksum",$checksum);
		//$smarty->assign("total",$total);
		$smarty->display("srcinit_brief.htm");
	}
}
else
{
	$smarty->display("jsconnectError.tpl");
}

// flush the buffer
if($zipIt)
	ob_end_flush();
?>
