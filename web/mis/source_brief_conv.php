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
		if($day!="")
		{
			$st_date=$year."-".$month."-".$day;
			$end_date=$st_date;
		}
		else
		{
			$st_date=$year."-".$month."-"."01";
			$end_date=$year."-".$month."-"."31";
		}
		$sql1=" SOURCEID ";//from Source_HITS
		$sql_h="SELECT $sql1 as src, SUM(COUNT) as cnt FROM MIS.SOURCE_HITS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND SOURCEGP='$sourcegp' GROUP BY src ORDER BY cnt DESC";
		if($sec_sources)
			$sql_m="SELECT $sql1 as src, SUM(COUNT) as cnt FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date'  AND SOURCEGP='$sourcegp' AND ENTRY_MODIFY='E' $sec_condition GROUP BY src";
		else
			$sql_m="SELECT $sql1 as src, SUM(COUNT) as cnt FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date'  AND SOURCEGP='$sourcegp' AND ENTRY_MODIFY='E' GROUP BY src";
		if($sec_sources)
			$sql_a="SELECT $sql1 as src, SUM(COUNT) as cnt FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date'  AND SOURCEGP='$sourcegp'  AND ACTIVATED IN ('Y','H') AND ENTRY_MODIFY='E' $sec_condition GROUP BY src";
		else
			$sql_a="SELECT $sql1 as src, SUM(COUNT) as cnt FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date'  AND SOURCEGP='$sourcegp'  AND ACTIVATED IN ('Y','H') AND ENTRY_MODIFY='E' GROUP BY src";

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
		$res_a=mysql_query_decide($sql_a,$db) or die("$sql_a".mysql_error_js());
		while($row_a=mysql_fetch_array($res_a))
		{
			$src=$row_a['src'];
			$counter=$row_a['cnt'];
			$i=array_search($src,$srcarr);
			$cnt[$i]["a"]+=$counter;
			$totalla+=$counter;
		}
		$src_str="'".implode("','",$srcarr)."'";
        if($sec_sources)	
		$sql_r1="SELECT SOURCEID as src, COUNT(*) as cnt FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date'  AND SOURCEGP='$sourcegp' and  GENDER='F' and COUNTRY_RES <> 88  AND MTONGUE <> '1' AND ENTRY_MODIFY='E' AND ACTIVATED IN ('Y','H') $sec_condition GROUP BY src";
		else
		$sql_r1="SELECT SOURCEID as src, COUNT(*) as cnt FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date'  AND SOURCEGP='$sourcegp' and  GENDER='F' and COUNTRY_RES <> 88  AND MTONGUE <> '1' AND ENTRY_MODIFY='E' AND ACTIVATED IN ('Y','H') GROUP BY src";
        if($sec_sources)	
		$sql_r2="SELECT SOURCEID as src, COUNT(*) as cnt FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date'  AND SOURCEGP='$sourcegp' and  GENDER='M' and AGE>=25 and COUNTRY_RES <> 88  AND MTONGUE <> '1' AND ENTRY_MODIFY='E' AND ACTIVATED IN ('Y','H') $sec_condition GROUP BY src";
		else
		$sql_r2="SELECT SOURCEID as src, COUNT(*) as cnt FROM MIS.SOURCE_MEMBERS WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date'  AND SOURCEGP='$sourcegp' and  GENDER='M' and AGE>=25 and COUNTRY_RES <> 88  AND MTONGUE <> '1' AND ENTRY_MODIFY='E' AND ACTIVATED IN ('Y','H') GROUP BY src";
		$res_r=mysql_query_decide($sql_r1,$db) or die("$sql_r1".mysql_error_js());
                while($row_r=mysql_fetch_array($res_r))
                {
                        $src=$row_r['src'];
                        $counter=$row_r['cnt'];
                        $i=array_search($src,$srcarr);
                        $cnt[$i]["r"]+=$counter;
                        $totallr+=$counter;
                }
		$res_r2=mysql_query_decide($sql_r2,$db) or die("$sql_r2".mysql_error_js());
                while($row_r=mysql_fetch_array($res_r2))
                {
                        $src=$row_r['src'];
                        $counter=$row_r['cnt'];
                        $i=array_search($src,$srcarr);
                        $cnt[$i]["r"]+=$counter;
                        $totallr+=$counter;
                }
if($sec_sources)
	$sql_p="SELECT jp.SOURCE as src, COUNT(distinct(jp.PROFILEID)) as cnt FROM newjs.JPROFILE as jp,billing.PURCHASES as pur WHERE jp.ENTRY_DT BETWEEN '$st_date' AND '$end_date'  AND jp.SOURCE IN ($src_str) $sec_condition  and jp.PROFILEID=pur.PROFILEID GROUP BY src";
else
	$sql_p="SELECT jp.SOURCE as src, COUNT(distinct(jp.PROFILEID)) as cnt FROM newjs.JPROFILE as jp,billing.PURCHASES as pur WHERE jp.ENTRY_DT BETWEEN '$st_date' AND '$end_date'  AND jp.SOURCE IN ($src_str) and jp.PROFILEID=pur.PROFILEID GROUP BY src";
                $res_p=mysql_query_decide($sql_p,$db) or die("$sql_p".mysql_error_js());
                while($row_p=mysql_fetch_array($res_p))
                {
                        $src=$row_p['src'];
                        $counter=$row_p['cnt'];
                        $i=array_search($src,$srcarr);
                        $cnt[$i]["pd"]+=$counter;
                        $totallpd+=$counter;
                }
                for($i=0;$i<count($srcarr);$i++)
                {
                        if($cnt[$i]["h"])
                        {
                                $cnt[$i]["cp"]=$cnt[$i]["a"]/$cnt[$i]["h"] * 100;
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
		$smarty->assign("totalla",$totalla);
		$smarty->assign("totallr",$totallr);
		$smarty->assign("totallpd",$totallpd);
		$smarty->assign("totallcp",$totallcp);
		$smarty->assign("source",$sourcegp);
		$smarty->assign("srcarr",$srcarr);
		$smarty->assign("checksum",$checksum);
		$smarty->assign("sec_sources",$sec_sources);
		
		$smarty->assign("dt_from",$dt_from);
		$smarty->assign("dt_to",$dt_to);
		$smarty->display("source_brief_conv.htm");
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
		$smarty->display("srcinit_brief_conv.htm");
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
