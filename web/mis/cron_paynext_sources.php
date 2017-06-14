<?php
ini_set("max_execution_time","0");
/*****
	Modified on Jun 09, 2005 by shiv. City dropdown added.
*****/

/*****
	Modified on Jun 28, 2005 by shiv. Community dropdown added.
*****/
//die("This module is temporarily down.");
include("connect.inc");
$db=connect_misdb();

$jmmarr=array('Before Oct 2004','Oct','Nov','Dec');
$pmmarr=array('Oct','Nov','Dec');

$curryear = date("Y");
$temp_arr=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

$yrarr=array("2005","2006","2007");
if (!in_array($curryear,$yrarr))
	$yrarr[] = $curryear;
$yrcnt=count($yrarr);

$curdate = date("Y-m-d");
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
		{
			$pmmarr[] = $temp_arr[$i];
			$jmmarr[] = $temp_arr[$i];
		}
	}
	else
	{
		for ($i=0;$i<count($temp_arr);$i++)
		{
			$pmmarr[] = $temp_arr[$i];
			$jmmarr[] = $temp_arr[$i];
		}
	}
}

$sourcearr = array('','google','overture','Rediff_tgt','Yahoo_Tgt','Google_NRI','MSN_group','rediff_pilot_july','jeevansathi','yahoo_US_06','yahoo_cpm06','Rediff06','rediff_new_06','yahoo','NONE','rediff','yahoosrch','Sify','mediaturf','yahoo_amj_07','rediff_march_07','New Google','123greetings','Komli','yahoo_jas_07','rediff_july_07','naukri','rediff_affiliate','yahoo_Del_Mum','rediff_jas_tgt','tyroo','yahoo_ond_07','sify_ond_07','aol_ond_07','rediff_ond_07','Yahoo_branding_ond','Tyroo_NRI_JFM08','Tyroo_India_JFM08','Komli_JFM_08','Integrid_CPA_08','rediff_jfm_08','Google NRI US','Rediff_us_fm','Yahoo_nri','Sulekha_us_fm','yahoo_amj_08_cpm','komli_amj_08_cpa','sify_amj_08_cpa','DGM_amj_08_cpa','Vdopia_april08','rediff_amj_08','rediff_branding_june08','rediff_branding_JAS08','rediff_jas_2008_cpc','mediaturf_aug_2008','yahoosearch_2008','ozonemedia_aug_2008','yahooCPA@90_Sep08','Yahoo_US_CPA@200_Sep08','yahooNRI_amj_08_cpm','YAHOO_BrandingJune08','Indiatimes_BrandingJune08','Sify_BrandingJune08','MSN_BrandingJune08');
//$sourcearr = array('','google','overture','Rediff_tgt','Yahoo_Tgt');
$cntarr = count($sourcearr);
for ($x=0;$x<$cntarr;$x++)
{
	$source = $sourcearr[$x];

	unset($srcidarr);
	unset($srcstr);
	unset($srcarr);

	if(!mysql_ping_js($db))
		$db=connect_misdb();

	if($source)
	{
		$sql="SELECT SourceID FROM MIS.SOURCE WHERE GROUPNAME='$source'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$srcidarr[]=$row['SourceID'];
		}	
		if (is_array($srcidarr))
			$srcstr="'".implode("','",$srcidarr)."'";
	}

	if(!mysql_ping_js($db))
		$db=connect_misdb();

	$sql="SELECT COUNT(*) as cnt,LEFT(ENTRY_DT,7) as entry_dt FROM newjs.JPROFILE WHERE ACTIVATED IN ('Y','H','D') AND INCOMPLETE IN ('','N')";
	if ($source)
		$sql.=" AND SOURCE IN ($srcstr)";
	$sql.=" GROUP BY entry_dt";
	$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			list($yy,$mm)=explode("-",$row['entry_dt']);
			$cnt=$row['cnt'];

			if($yy<2004)
				$mm=0;
			elseif($yy==2004)
			{
				if($mm<10)
					$mm=0;
				else
					$mm-=9;
			}
			else
			{
				$num=$yy-2004-1;
				$mm+=3;
				$mm+=($num*12);
			}
			$regtot[$mm]+=$cnt;
			$reg_mem_tot+=$cnt;
		}while($row=mysql_fetch_array($res));
	}

	if(!mysql_ping_js($db))
		$db=connect_misdb();

	$sql="SELECT COUNT(DISTINCT p.PROFILEID) as cnt,LEFT(j.ENTRY_DT,7) as reg_dt,LEFT(p.ENTRY_DT,7) as paid_dt FROM billing.PURCHASES p ,newjs.JPROFILE j WHERE j.PROFILEID=p.PROFILEID AND p.STATUS='DONE' ";
	if($source)
	{
		$sql.=" AND j.SOURCE IN ($srcstr) ";
	}

	$sql.=" GROUP BY reg_dt,paid_dt ";

	$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			list($pyy,$pmm)=explode("-",$row['paid_dt']);
			list($jyy,$jmm)=explode("-",$row['reg_dt']);
			$cnt=$row['cnt'];

			if($jyy<2004)
				$jmm=0;
			elseif($jyy==2004)
			{
				if($jmm<10)
					$jmm=0;
				else
					$jmm-=9;
			}
			else
			{
				$num=$jyy-2004-1;
				$jmm+=3;
				$jmm+=($num*12);
			}

			if($pyy==2004)
				$pmm-=10;
			else
			{
				$pnum=$pyy-2004-1;
				$pmm+=2;
				$pmm+=($pnum*12);
			}
			$tot[$jmm][$pmm]+=$cnt;
			$ptot[$pmm]+=$cnt;
			$jtot[$jmm]+=$cnt;
			$totall+=$cnt;
		}while($row=mysql_fetch_array($res));
	}

	for($i=0;$i<count($jmmarr);$i++)
	{
		for($j=0;$j<count($pmmarr);$j++)
		{
			if($regtot[$i])
			{
				$percent[$i][$j]=$tot[$i][$j]/$regtot[$i] * 100;
				$percent[$i][$j]=round($percent[$i][$j],1);
			}
		}
		if($regtot[$i])
		{
			$jtotpercent[$i]=$jtot[$i]/$regtot[$i] * 100;
			$jtotpercent[$i]=round($jtotpercent[$i],1);
		}
	}

	$smarty->assign("yrarr",$yrarr);
	$smarty->assign("year",$year);
	$smarty->assign("jmmarr",$jmmarr);
	$smarty->assign("pmmarr",$pmmarr);
	$smarty->assign("regearlier",$regearlier);
	$smarty->assign("tot",$tot);
	$smarty->assign("totall",$totall);
	$smarty->assign("reg",$reg);
	$smarty->assign("regtot",$regtot);
	$smarty->assign("reg_mem_tot",$reg_mem_tot);
	$smarty->assign("ptot",$ptot);
	$smarty->assign("jtot",$jtot);
	$smarty->assign("percent",$percent);
	$smarty->assign("jtotpercent",$jtotpercent);
	$smarty->assign("source",$source);
	$smarty->assign("srcarr",$srcarr);

	$mis_content = $smarty->fetch("cron_paynext_sources.htm");

	unset($jtot);
	unset($regtot);
	unset($percent);
	unset($jtotpercent);
	unset($reg_mem_tot);
	unset($tot);
	unset($ptot);
	unset($jtot);
	unset($totall);

	if ($source)
	{
		$file_name="/usr/local/indicators/".$source."_paynext_mis.htm";
		//$file_name="/usr/local/apache/sites/jeevansathi.com/htdocs/mis/indicators/".$source."_paynext_mis.htm";
	}
	else
	{
		$file_name="/usr/local/indicators/paynext_mis.htm";
		//$file_name="/usr/local/apache/sites/jeevansathi.com/htdocs/mis/indicators/paynext_mis.htm";
	}
		
	$fd=fopen($file_name,"w");
	fwrite($fd,$mis_content);
	fclose($fd);
	passthru("chmod 664 ".$file_name);

	//mail("shiv.narayan@jeevansathi.com","Payment MIS","MIS calculated successfully");
}
if(!mysql_ping_js($db))
	$db=connect_misdb();

$sql="SELECT VALUE FROM newjs.MTONGUE WHERE 1";
$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
while($row=mysql_fetch_array($res))
{
	$arr[]=$row['VALUE'];
}
$cntarr = count($arr);
for ($x=0;$x<$cntarr;$x++)
{
	$mtongue = $arr[$x];

	unset($srcidarr);
	unset($srcstr);
	unset($srcarr);

	if(!mysql_ping_js($db))
		$db=connect_misdb();

	$sql="SELECT COUNT(*) as cnt,LEFT(ENTRY_DT,7) as entry_dt FROM newjs.JPROFILE WHERE ACTIVATED IN ('Y','H','D') AND INCOMPLETE IN ('','N') AND MTONGUE='$mtongue' GROUP BY entry_dt";
	$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			list($yy,$mm)=explode("-",$row['entry_dt']);
			$cnt=$row['cnt'];

			if($yy<2004)
				$mm=0;
			elseif($yy==2004)
			{
				if($mm<10)
					$mm=0;
				else
					$mm-=9;
			}
			else
			{
				$num=$yy-2004-1;
				$mm+=3;
				$mm+=($num*12);
			}
			$regtot[$mm]+=$cnt;
			$reg_mem_tot+=$cnt;
		}while($row=mysql_fetch_array($res));
	}

	if(!mysql_ping_js($db))
		$db=connect_misdb();

	$sql="SELECT COUNT(DISTINCT p.PROFILEID) as cnt,LEFT(j.ENTRY_DT,7) as reg_dt,LEFT(p.ENTRY_DT,7) as paid_dt FROM billing.PURCHASES p ,newjs.JPROFILE j WHERE j.PROFILEID=p.PROFILEID AND p.STATUS='DONE' AND MTONGUE='$mtongue' GROUP BY reg_dt,paid_dt ";

	$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			list($pyy,$pmm)=explode("-",$row['paid_dt']);
			list($jyy,$jmm)=explode("-",$row['reg_dt']);
			$cnt=$row['cnt'];

			if($jyy<2004)
				$jmm=0;
			elseif($jyy==2004)
			{
				if($jmm<10)
					$jmm=0;
				else
					$jmm-=9;
			}
			else
			{
				$num=$jyy-2004-1;
				$jmm+=3;
				$jmm+=($num*12);
			}

			if($pyy==2004)
				$pmm-=10;
			else
			{
				$pnum=$pyy-2004-1;
				$pmm+=2;
				$pmm+=($pnum*12);
			}
			$tot[$jmm][$pmm]+=$cnt;
			$ptot[$pmm]+=$cnt;
			$jtot[$jmm]+=$cnt;
			$totall+=$cnt;
		}while($row=mysql_fetch_array($res));
	}

	for($i=0;$i<count($jmmarr);$i++)
	{
		for($j=0;$j<count($pmmarr);$j++)
		{
			if($regtot[$i])
			{
				$percent[$i][$j]=$tot[$i][$j]/$regtot[$i] * 100;
				$percent[$i][$j]=round($percent[$i][$j],1);
			}
		}
		if($regtot[$i])
		{
			$jtotpercent[$i]=$jtot[$i]/$regtot[$i] * 100;
			$jtotpercent[$i]=round($jtotpercent[$i],1);
		}
	}

	$smarty->assign("yrarr",$yrarr);
	$smarty->assign("year",$year);
	$smarty->assign("jmmarr",$jmmarr);
	$smarty->assign("pmmarr",$pmmarr);
	$smarty->assign("regearlier",$regearlier);
	$smarty->assign("tot",$tot);
	$smarty->assign("totall",$totall);
	$smarty->assign("reg",$reg);
	$smarty->assign("regtot",$regtot);
	$smarty->assign("reg_mem_tot",$reg_mem_tot);
	$smarty->assign("ptot",$ptot);
	$smarty->assign("jtot",$jtot);
	$smarty->assign("percent",$percent);
	$smarty->assign("jtotpercent",$jtotpercent);
	$smarty->assign("source",$source);
	$smarty->assign("srcarr",$srcarr);

	$mis_content = $smarty->fetch("cron_paynext_sources.htm");

	unset($jtot);
	unset($regtot);
	unset($percent);
	unset($jtotpercent);
	unset($reg_mem_tot);
	unset($tot);
	unset($ptot);
	unset($jtot);
	unset($totall);

	$file_name="/usr/local/indicators/comm_".$mtongue."_paynext_mis.htm";
		
	$fd=fopen($file_name,"w");
	fwrite($fd,$mis_content);
	fclose($fd);
	passthru("chmod 664 ".$file_name);

	//mail("shiv.narayan@jeevansathi.com","Payment MIS - Community - $mtongue","MIS calculated successfully");
}
mail("shiv.narayan@jeevansathi.com","Payment MIS","MIS calculated successfully");

?>
